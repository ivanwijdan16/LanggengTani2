<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Stock;
use App\Models\StockSizeImage;
use App\Models\MasterStock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');
        $sort = $request->input('sort', 'newest');
        $direction = $request->input('direction', 'desc');

        // Start with master stocks, possibly filtered by search
        $masterStocksQuery = MasterStock::query();

        if ($searchQuery) {
            $masterStocksQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('type', 'like', "%{$searchQuery}%")
                    ->orWhere('sku', 'like', "%{$searchQuery}%");
            });
        }

        $masterStocks = $masterStocksQuery->get();
        $products = collect();

        foreach ($masterStocks as $masterStock) {
            // Get all unique sizes for this master stock
            $sizes = Stock::where('master_stock_id', $masterStock->id)
                ->select('size')
                ->distinct()
                ->get()
                ->pluck('size');

            // For each size, get the product with the closest non-expired expiration date and available stock
            foreach ($sizes as $size) {
                $product = Stock::where('master_stock_id', $masterStock->id)
                    ->where('size', $size)
                    ->where('quantity', '>', 0)
                    ->whereDate('expiration_date', '>', Carbon::today())
                    ->orderBy('expiration_date', 'asc')
                    ->first();

                // If we found a valid product, add it to our collection
                if ($product) {
                    // Add custom properties
                    $product->expired = Carbon::parse($product->expiration_date)->isPast();
                    $product->expiration_date_formatted = Carbon::parse($product->expiration_date)->format('d M Y');

                    // Get the image (either size-specific or master stock image)
                    $sizeImage = StockSizeImage::where('master_stock_id', $masterStock->id)
                        ->where('size', $size)
                        ->first();

                    if ($sizeImage && $sizeImage->image) {
                        $product->image = $sizeImage->image;
                    } else {
                        $product->image = $masterStock->image;
                    }

                    // Load the master stock relationship
                    $product->load('masterStock');

                    // Get total quantity for this SKU and size combination
                    $totalQuantity = Stock::where('master_stock_id', $masterStock->id)
                        ->where('size', $size)
                        ->sum('quantity');

                    $product->total_quantity = $totalQuantity;

                    // Get all batch info for this SKU and size
                    $batches = Stock::where('master_stock_id', $masterStock->id)
                        ->where('size', $size)
                        ->where('quantity', '>', 0)
                        ->whereDate('expiration_date', '>', Carbon::today())
                        ->orderBy('expiration_date', 'asc')
                        ->get();

                    $product->batches = $batches;

                    // Add to our collection
                    $products->push($product);
                }
            }
        }

        // Sort the products based on the request
        if ($sort === 'name') {
            $products = $direction === 'asc'
                ? $products->sortBy(function ($p) {
                    return $p->masterStock->name;
                })
                : $products->sortByDesc(function ($p) {
                    return $p->masterStock->name;
                });
        } elseif ($sort === 'price') {
            $products = $direction === 'asc'
                ? $products->sortBy('selling_price')
                : $products->sortByDesc('selling_price');
        } elseif ($sort === 'expiry') {
            $products = $direction === 'asc'
                ? $products->sortBy('expiration_date')
                : $products->sortByDesc('expiration_date');
        } elseif ($sort === 'newest') {
            $products = $direction === 'desc'
                ? $products->sortByDesc('created_at')
                : $products->sortBy('created_at');
        }

        // Get the cart items
        $carts = Cart::where('user_id', auth()->id())->get();

        return view('cart.index', compact('carts', 'products', 'searchQuery', 'sort', 'direction'));
    }

    public function getProductStock(Request $request)
    {
        $masterStockId = $request->input('master_stock_id');
        $size = $request->input('size');

        // Get all stocks for this master stock and size with details
        $stocks = Stock::where('master_stock_id', $masterStockId)
            ->where('size', $size)
            ->where('quantity', '>', 0)
            ->whereDate('expiration_date', '>', Carbon::today())
            ->orderBy('expiration_date', 'asc')
            ->get()
            ->map(function ($stock) {
                return [
                    'stock_id' => $stock->stock_id,
                    'expiration_date' => Carbon::parse($stock->expiration_date)->format('d M Y'),
                    'quantity' => $stock->quantity,
                    'expired' => Carbon::parse($stock->expiration_date)->isPast(),
                    'almost_expired' => !Carbon::parse($stock->expiration_date)->isPast() &&
                        Carbon::parse($stock->expiration_date)->diffInDays(now()) < 30
                ];
            });

        return response()->json([
            'stocks' => $stocks,
            'total_quantity' => $stocks->sum('quantity')
        ]);
    }

    public function getCart()
    {
        // Ambil data keranjang berdasarkan user yang sedang login
        $carts = Cart::with('product.masterStock')->where('user_id', auth()->id())->get();

        // Add the size-specific image to each cart item
        $carts->map(function ($cart) {
            if ($cart->product && $cart->product->masterStock) {
                // Get size-specific image if available
                $sizeImage = StockSizeImage::where('master_stock_id', $cart->product->master_stock_id)
                    ->where('size', $cart->product->size)
                    ->first();

                if ($sizeImage && $sizeImage->image) {
                    $cart->product->image = $sizeImage->image;
                } else {
                    $cart->product->image = $cart->product->masterStock->image;
                }
            }
            return $cart;
        });

        // Mengembalikan data ke view dengan AJAX
        return response()->json([
            'carts' => $carts
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('query', '');
        $sortBy = $request->input('sort_by', 'created_at');
        $direction = $request->input('direction', 'desc');

        // Create a collection for our unique products
        $uniqueProducts = collect();

        // First, find all master stocks that match the search query
        $masterStocksQuery = MasterStock::query();

        if ($searchQuery) {
            $masterStocksQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('type', 'like', "%{$searchQuery}%")
                    ->orWhere('sku', 'like', "%{$searchQuery}%");
            });
        }

        $masterStocks = $masterStocksQuery->get();

        // For each master stock, find the product with the nearest expiration date for each size
        foreach ($masterStocks as $masterStock) {
            // Get distinct sizes for this master stock
            $sizes = Stock::where('master_stock_id', $masterStock->id)
                ->select('size')
                ->distinct()
                ->get()
                ->pluck('size');

            // For each size, get the stock with the closest expiration date that's not expired and has quantity
            foreach ($sizes as $size) {
                $product = Stock::where('master_stock_id', $masterStock->id)
                    ->where('size', $size)
                    ->where('quantity', '>', 0)
                    ->whereDate('expiration_date', '>', Carbon::today())
                    ->orderBy('expiration_date', 'asc')
                    ->first();

                if ($product) {
                    // Add a custom expired field
                    $product->expired = Carbon::parse($product->expiration_date)->isPast();
                    // Format expiration_date
                    $product->expiration_date = Carbon::parse($product->expiration_date)->format('d M Y');

                    // Add master stock details to the product
                    $product->load('masterStock');
                    $product->master_stock = $product->masterStock; // Including related master stock data

                    // Get size-specific image if available
                    $sizeImage = StockSizeImage::where('master_stock_id', $product->master_stock_id)
                        ->where('size', $product->size)
                        ->first();

                    if ($sizeImage && $sizeImage->image) {
                        $product->image = $sizeImage->image;
                    } else {
                        $product->image = $product->masterStock->image;
                    }

                    $uniqueProducts->push($product);
                }
            }
        }

        // Apply final sorting based on user's sort preference
        if ($sortBy === 'name') {
            $uniqueProducts = $direction === 'asc'
                ? $uniqueProducts->sortBy(function ($p) {
                    return $p->masterStock->name;
                })
                : $uniqueProducts->sortByDesc(function ($p) {
                    return $p->masterStock->name;
                });
        } elseif ($sortBy === 'price') {
            $uniqueProducts = $direction === 'asc'
                ? $uniqueProducts->sortBy('selling_price')
                : $uniqueProducts->sortByDesc('selling_price');
        } elseif ($sortBy === 'expiry') {
            $uniqueProducts = $direction === 'asc'
                ? $uniqueProducts->sortBy('expiration_date')
                : $uniqueProducts->sortByDesc('expiration_date');
        } elseif ($sortBy === 'newest') {
            $uniqueProducts = $direction === 'desc'
                ? $uniqueProducts->sortByDesc('created_at')
                : $uniqueProducts->sortBy('created_at');
        }

        return response()->json([
            'products' => $uniqueProducts->values()->all()
        ]);
    }

    // Menambahkan produk ke keranjang
    public function addToCart(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        // Ambil produk berdasarkan ID
        $product = Stock::findOrFail($request->product_id);

        // Cek apakah produk sudah ada di keranjang
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('type', $request->type)
            ->first();

        $price = $request->type == 'normal' ? $product->selling_price : $product->retail_price;

        // Jika produk sudah ada di keranjang, update jumlahnya
        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->subtotal = $cart->quantity * $price;
            $cart->save();
        } else {
            // Jika produk belum ada, buat entri baru di keranjang
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $price,
                'type' => $request->type
            ]);
        }

        // Kembalikan respons sukses
        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang']);
    }

    // Menghapus produk dari keranjang
    public function removeFromCart($id)
    {
        // Hapus produk berdasarkan ID keranjang
        $cart = Cart::findOrFail($id);

        // Pastikan hanya menghapus milik user yang sedang login
        if ($cart->user_id === auth()->id()) {
            $cart->delete();
        }

        // Mengembalikan respons setelah penghapusan
        return response()->json(['message' => 'Produk berhasil dihapus dari keranjang']);
    }

    // Update quantity in cart
    public function updateCartQuantity(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item
        $cart = Cart::findOrFail($id);

        // Make sure it belongs to the current user
        if ($cart->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Get the product
        $product = Stock::findOrFail($cart->product_id);

        // Check if quantity is available
        $totalAvailable = 0;
        if ($cart->type == 'normal') {
            $totalAvailable = Stock::where('master_stock_id', $product->master_stock_id)
                ->where('size', $product->size)
                ->where('quantity', '>', 0)
                ->whereDate('expiration_date', '>', Carbon::today())
                ->sum('quantity');
        } else {
            $totalAvailable = Stock::where('master_stock_id', $product->master_stock_id)
                ->where('size', $product->size)
                ->where('retail_quantity', '>', 0)
                ->whereDate('expiration_date', '>', Carbon::today())
                ->sum('retail_quantity');
        }

        // If requested quantity exceeds available, return error
        if ($request->quantity > $totalAvailable) {
            return response()->json([
                'message' => 'Stok tidak mencukupi',
                'available' => $totalAvailable
            ], 400);
        }

        // Update the cart item
        $price = $cart->type == 'normal' ? $product->selling_price : $product->retail_price;
        $cart->quantity = $request->quantity;
        $cart->subtotal = $cart->quantity * $price;
        $cart->save();

        // Return success response
        return response()->json([
            'message' => 'Quantity updated successfully',
            'cart' => $cart
        ]);
    }
}
