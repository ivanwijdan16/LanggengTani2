<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Helpers\IdGenerator;
use Illuminate\Http\Request;
use App\Models\Stock;
use DB;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        // Ambil semua barang yang ada di keranjang
        $carts = Cart::where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        $total_price = $carts->sum('subtotal'); // Hitung total harga dari keranjang
        $total_paid = $request->total_paid ?? 0; // Uang yang dibayar
        $change = $total_paid - $total_price; // Kembalian

        if ($change < 0) {
            return redirect()->route('cart.index')->with('error', 'Uang tidak mencukupi!');
        }

        // Generate transaction ID using the helper
        $id_penjualan = IdGenerator::generateSaleId();

        // Validate stock availability before processing
        foreach ($carts as $cart) {
            $product = $cart->product;

            // Get all stocks for this product (master_stock_id and size)
            $stocks = Stock::where('master_stock_id', $product->master_stock_id)
                ->where('size', $product->size)
                ->where('quantity', '>', 0)
                ->orderBy('expiration_date', 'asc')
                ->get();

            $totalAvailable = 0;
            if ($cart->type == 'normal') {
                $totalAvailable = $stocks->sum('quantity');
            } else {
                $totalAvailable = $stocks->sum('retail_quantity');
            }

            if ($totalAvailable < $cart->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $product->masterStock->name . ' (' . $product->size . ') tidak mencukupi!');
            }
        }

        // Simpan transaksi baru dengan ID Penjualan
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_price' => $total_price,
            'total_paid' => $total_paid,
            'change' => $change,
            'id_penjualan' => $id_penjualan,
        ]);

        // Loop untuk setiap item di keranjang dan mengurangi stok produk
        foreach ($carts as $cart) {
            $remainingQuantity = $cart->quantity;
            $product = $cart->product;
            $price = $cart->type == 'normal' ? $product->selling_price : $product->retail_price;

            // Get stocks ordered by expiration date (FIFO)
            $stocks = Stock::where('master_stock_id', $product->master_stock_id)
                ->where('size', $product->size)
                ->where('quantity', '>', 0)
                ->orderBy('expiration_date', 'asc')
                ->get();

            foreach ($stocks as $stock) {
                if ($remainingQuantity <= 0) break;

                $oldQuantity = $stock->quantity; // Store old quantity for notification handling

                if ($cart->type == 'normal') {
                    $availableQuantity = $stock->quantity;
                    $quantityToTake = min($availableQuantity, $remainingQuantity);

                    // Simpan detail transaksi untuk setiap batch
                    $transaction->items()->create([
                        'product_id' => $stock->id,
                        'quantity' => $quantityToTake,
                        'price' => $price,
                        'subtotal' => $quantityToTake * $price
                    ]);

                    // Update stock quantity
                    $newQuantity = $stock->quantity - $quantityToTake;
                    $stock->quantity = $newQuantity;
                    $remainingQuantity -= $quantityToTake;
                } else {
                    $availableQuantity = $stock->retail_quantity;
                    $quantityToTake = min($availableQuantity, $remainingQuantity);

                    // Simpan detail transaksi untuk setiap batch
                    $transaction->items()->create([
                        'product_id' => $stock->id,
                        'quantity' => $quantityToTake,
                        'price' => $price,
                        'subtotal' => $quantityToTake * $price
                    ]);

                    // Update retail stock quantity
                    $stock->retail_quantity -= $quantityToTake;
                    $remainingQuantity -= $quantityToTake;
                }

                $stock->save();

                // UPDATED: Handle notifications properly after stock reduction
                $this->handleStockNotificationsAfterSale($stock, $oldQuantity);
            }
        }

        // Hapus barang dari keranjang setelah transaksi selesai
        Cart::where('user_id', auth()->id())->delete();

        // Alihkan ke halaman success dengan ID transaksi di URL
        return redirect()->route('transaction.success', ['id' => $transaction->id]);
    }

    // Method baru untuk menampilkan halaman sukses
    public function showSuccess($id)
    {
        // Cari transaksi berdasarkan ID dan muat relasi items dengan produk (termasuk yang sudah soft delete)
        $transaction = Transaction::with(['items.product' => function ($query) {
            $query->withTrashed(); // Ini akan mengambil produk meskipun sudah dihapus (soft deleted)
        }])->findOrFail($id);

        // Pastikan transaksi ini milik user yang sedang login
        if ($transaction->user_id != auth()->id()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke transaksi ini!');
        }

        // Tampilkan view success dengan data transaksi
        return view('transaction.success', compact('transaction'));
    }

    private function handleStockNotificationsAfterSale($stock, $oldQuantity)
    {
        // If stock quantity changed, check for new notifications
        if ($oldQuantity != $stock->quantity) {
            // Clear old notifications that might no longer be relevant
            if ($oldQuantity > 5 && $stock->quantity <= 5) {
                // Stock just became low, clear any out_of_stock notifications
                $stock->markNotificationsAsResolved('out_of_stock');
            } elseif ($oldQuantity > 0 && $stock->quantity <= 0) {
                // Stock just became out of stock, clear low_stock notifications
                $stock->markNotificationsAsResolved('low_stock');
            }

            // Create new notifications based on current conditions
            $stock->checkAndCreateNotifications();
        }
    }
}
