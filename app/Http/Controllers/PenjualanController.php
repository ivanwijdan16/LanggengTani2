<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    // Menampilkan halaman utama menu penjualan
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $penjualans = Transaction::with(['items.product' => function ($query) {
            $query->withTrashed()->with('masterStock');
        }])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->get();

        // Initialize an array to store total sales per day
        $totalPerHari = [];

        // Loop through each transaction and calculate the total sales per day
        foreach ($penjualans as $penjualan) {
            // Format the date (using Carbon for consistency)
            $tanggal = $penjualan->created_at->format('d-m-Y');

            // If the date is not already in the array, initialize it to 0
            if (!isset($totalPerHari[$tanggal])) {
                $totalPerHari[$tanggal] = 0;
            }

            // Add the total price of the transaction to that date
            $totalPerHari[$tanggal] += $penjualan->total_price;
        }

        // Pass the necessary data to the view
        return view('penjualan.index', compact('penjualans', 'totalPerHari', 'bulan', 'tahun'));
    }

    // Menampilkan riwayat penjualan per bulan
    public function riwayatPenjualanPerBulan($bulan, $tahun)
    {
        $penjualans = Transaction::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->get();

        return view('penjualan.riwayat', compact('penjualans'));
    }

    // Menampilkan grafik penjualan per bulan
    public function grafikPenjualanPerBulan($bulan, $tahun)
    {
        $penjualans = Transaction::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->get();

        $totalPerHari = [];
        foreach ($penjualans as $penjualan) {
            $tanggal = date('d-m-Y', strtotime($penjualan->created_at));
            if (!isset($totalPerHari[$tanggal])) {
                $totalPerHari[$tanggal] = 0;
            }
            $totalPerHari[$tanggal] += $penjualan->total_price;
        }

        return view('penjualan.grafik', compact('totalPerHari', 'bulan', 'tahun'));
    }

    // Cetak laporan penjualan per bulan
    public function cetakLaporan($bulan, $tahun)
    {
        // Get transactions with their items and products
        $transactions = Transaction::with(['items.product' => function ($query) {
            $query->withTrashed()->with('masterStock');
        }])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->get();

        // Calculate total sales
        $totalPenjualan = $transactions->sum('total_price');

        // Calculate total profit
        $totalProfit = 0;

        // Group by category to find sales per category
        $penjualanPerKategori = [];

        // Find the best selling product
        $produkTerlaris = null;
        $maxQuantity = 0;

        // Process items for detailed reporting
        $items = [];

        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                if ($item->product) {
                    // Calculate profit for each item
                    $profit = $item->price - $item->product->purchase_price;

                    // Get product name - check both product and masterStock
                    $namaBarang = $item->product->masterStock ? $item->product->masterStock->name : 'Barang Terhapus';
                    $sku = $item->product->masterStock ? $item->product->masterStock->sku : '-';

                    // Add to items for table display
                    $items[] = [
                        'id_penjualan' => $transaction->id_penjualan,
                        'tanggal' => $transaction->created_at,
                        'nama_barang' => $namaBarang,
                        'sku' => $sku,
                        'ukuran' => $item->product->size,
                        'jumlah' => $item->quantity,
                        'total_harga' => $item->subtotal,
                        'laba' => $profit,
                        'laba_total' => $profit * $item->quantity
                    ];

                    // Add to total profit
                    $totalProfit += $profit * $item->quantity;

                    // Group by category
                    if ($item->product->masterStock) {
                        $kategori = $item->product->masterStock->type;
                        if (!isset($penjualanPerKategori[$kategori])) {
                            $penjualanPerKategori[$kategori] = [
                                'total' => 0,
                                'jumlah' => 0
                            ];
                        }
                        $penjualanPerKategori[$kategori]['total'] += $item->subtotal;
                        $penjualanPerKategori[$kategori]['jumlah'] += $item->quantity;
                    }

                    // Check if this is the best selling product
                    if ($item->quantity > $maxQuantity) {
                        $maxQuantity = $item->quantity;
                        $produkTerlaris = $namaBarang;
                    }
                } else {
                    // Handle case where product is completely deleted
                    $items[] = [
                        'id_penjualan' => $transaction->id_penjualan,
                        'tanggal' => $transaction->created_at,
                        'nama_barang' => 'Barang Terhapus',
                        'sku' => '-',
                        'ukuran' => '-',
                        'jumlah' => $item->quantity,
                        'total_harga' => $item->subtotal,
                        'laba' => 0, // Assume no profit for deleted items
                        'laba_total' => 0
                    ];

                    // Check if this is the best selling product
                    if ($item->quantity > $maxQuantity) {
                        $maxQuantity = $item->quantity;
                        $produkTerlaris = 'Barang Terhapus';
                    }
                }
            }
        }

        $pdf = PDF::loadView('penjualan.laporan', compact('items', 'totalPenjualan', 'totalProfit', 'produkTerlaris', 'penjualanPerKategori', 'bulan', 'tahun'));
        return $pdf->download("laporan_penjualan_{$bulan}_{$tahun}.pdf");
    }
}
