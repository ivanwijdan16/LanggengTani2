<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pembelian;
use App\Models\MasterStock;
use Illuminate\Http\Request;
use App\Models\MasterPembelian;
use App\Helpers\IdGenerator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\StockSizeImage;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterStock::query();

        // Pencarian berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('sku', 'LIKE', '%' . $request->search . '%');
        }

        // Pengurutan
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');

        // Memastikan field sort valid untuk menghindari SQL injection
        $allowedSortFields = [
            'name',
            'type',
            'created_at'
        ];

        if (in_array($sort, $allowedSortFields)) {
            $query->orderBy($sort, $direction);
        } else {
            // Default fallback ke name asc
            $query->orderBy('name', 'asc');
        }

        $stocks = $query->paginate(12);

        // Calculate total stock quantities for each master stock
        $stockQuantities = [];
        foreach ($stocks as $stock) {
            $stockQuantities[$stock->id] = $this->getTotalStockQuantity($stock->id);
        }

        return view('stocks.index', compact('stocks', 'stockQuantities'));
    }

    public function sizes($masterId, Request $request)
    {
        $masterStock = MasterStock::findOrFail($masterId);

        // Get all stocks for this master stock (excluding soft-deleted)
        $stocks = Stock::where('master_stock_id', $masterId)->whereNull('deleted_at')->get();

        // Get sort parameters
        $sort = $request->input('sort', '');
        $direction = $request->input('direction', 'asc');

        // Group stocks by size
        $sizeGroups = $stocks->groupBy('size');

        // Apply sorting to each size group if sorting by price
        if ($sort === 'price') {
            // Sort each size group by selling_price
            foreach ($sizeGroups as $size => $stocksInSize) {
                if ($direction === 'asc') {
                    $sizeGroups[$size] = $stocksInSize->sortBy('selling_price');
                } else {
                    $sizeGroups[$size] = $stocksInSize->sortByDesc('selling_price');
                }
            }

            // Sort the size groups based on the first item's selling_price
            $sizeGroups = $direction === 'asc'
                ? $sizeGroups->sortBy(function ($stocks) {
                    return $stocks->first()->selling_price;
                })
                : $sizeGroups->sortByDesc(function ($stocks) {
                    return $stocks->first()->selling_price;
                });
        }

        // Get all size images for this master stock
        $sizeImages = StockSizeImage::where('master_stock_id', $masterId)->get()->keyBy('size');

        return view('stocks.sizes', compact('masterStock', 'sizeGroups', 'sizeImages', 'sort', 'direction'));
    }

    public function batches($masterId, $size, Request $request)
    {
        $masterStock = MasterStock::findOrFail($masterId);

        // Get sort parameters
        $sort = $request->input('sort', 'expiration_date');
        $direction = $request->input('direction', 'asc');

        // Get all stocks for this master stock and size with sorting (exclude soft-deleted)
        $query = Stock::where('master_stock_id', $masterId)
            ->where('size', $size)
            ->whereNull('deleted_at');

        // Apply sorting
        if ($sort === 'expiration_date') {
            $query->orderBy('expiration_date', $direction);
        }

        $stocks = $query->get();

        // Get the size image if it exists
        $sizeImage = StockSizeImage::where('master_stock_id', $masterId)
            ->where('size', $size)
            ->first();

        return view('stocks.batches', compact('masterStock', 'stocks', 'size', 'sizeImage', 'sort', 'direction'));
    }

    public function create()
    {
        return view('stocks.create');
    }

    public function createSize($masterId, $size = null)
    {
        $masterStock = MasterStock::findOrFail($masterId);

        // Get a representative stock for this size to pre-fill values
        $sizeStock = Stock::where('master_stock_id', $masterId)
            ->where('size', $size)
            ->first();

        return view('stocks.create_size', compact('masterStock', 'size', 'sizeStock'));
    }

    public function editMaster($id)
    {
        $masterStock = MasterStock::findOrFail($id);
        return view('stocks.edit_master', compact('masterStock'));
    }

    public function editSize($masterId, $size)
    {
        $masterStock = MasterStock::findOrFail($masterId);

        // Get a representative stock for this size
        $sizeStock = Stock::where('master_stock_id', $masterId)
            ->where('size', $size)
            ->first();

        // Get the size image if it exists
        $sizeImage = StockSizeImage::where('master_stock_id', $masterId)
            ->where('size', $size)
            ->first();

        return view('stocks.edit_size', compact('masterStock', 'size', 'sizeStock', 'sizeImage'));
    }

    public function show($id)
    {
        $stock = Stock::with('masterStock')->findOrFail($id);

        $expired = \Carbon\Carbon::parse($stock->expiration_date)->isPast();
        $almostExpired = !$expired && \Carbon\Carbon::parse($stock->expiration_date)->diffInDays(now()) < 30;

        return view('stocks.show', compact('stock', 'expired', 'almostExpired'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.*' => 'required|string|max:255',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description.*' => 'nullable|string',
            'type.*' => 'required|in:Obat,Pupuk,Bibit',
            'size.*' => 'required',
            'purchase_price.*' => 'required|numeric|min:0',
            'selling_price.*' => 'required|numeric|min:0',
            'quantity.*' => 'required|integer|min:1', // Pastikan min:1
            'expiration_date.*' => 'required|date',
            'retail_price.*' => 'nullable|numeric|min:0',
            'retail_quantity.*' => 'nullable|integer|min:0',
            'sub_type.*' => 'nullable',
        ]);

        // Debug: Log data yang diterima
        Log::info('Stock creation data received:', [
            'quantities' => $validated['quantity'],
            'names' => $validated['name']
        ]);

        // Initialize total variable
        $total = 0;

        // Initialize arrays to store all product information for the session
        $productNames = [];
        $productTypes = [];
        $productSizes = [];
        $productQuantities = [];
        $productPrices = [];

        // Prepare log data array
        $logData = [];

        // Loop through the validated data to calculate total purchase_price * quantity
        for ($i = 0; $i < count($validated['purchase_price']); $i++) {
            $purchasePrice = $validated['purchase_price'][$i];
            $quantity = $validated['quantity'][$i];

            // Validasi tambahan untuk memastikan quantity > 0
            if ($quantity <= 0) {
                return back()->withErrors([
                    "quantity.$i" => "Jumlah stok harus lebih dari 0"
                ])->withInput();
            }

            // Calculate total for the current item and add it to the total
            $total += $purchasePrice * $quantity;
        }

        $master_pembelian = MasterPembelian::create([
            'total' => $total,
            'date' => date('Y-m-d'),
        ]);

        for ($i = 0; $i < count($validated['name']); $i++) {
            // Access the validated data for each item in the array
            $name = $validated['name'][$i];
            $image = $validated['image'][$i] ?? null;
            $description = $validated['description'][$i] ?? null;
            $type = $validated['type'][$i];
            $sub_type = $validated['sub_type'][$i] ?? null;
            $size = $validated['size'][$i];
            $purchase_price = $validated['purchase_price'][$i];
            $selling_price = $validated['selling_price'][$i];
            $quantity = $validated['quantity'][$i];
            $expiration_date = $validated['expiration_date'][$i];
            $retail_price = $validated['retail_price'][$i] ?? null;
            $retail_quantity = $validated['retail_quantity'][$i] ?? null;

            // Debug log untuk setiap item
            Log::info("Processing item $i:", [
                'name' => $name,
                'quantity' => $quantity,
                'type' => gettype($quantity),
                'is_numeric' => is_numeric($quantity),
                'intval' => intval($quantity)
            ]);

            // Konversi quantity ke integer untuk memastikan
            $quantity = intval($quantity);

            if ($quantity <= 0) {
                Log::error("Invalid quantity for item $i:", [
                    'original_quantity' => $validated['quantity'][$i],
                    'converted_quantity' => $quantity
                ]);
                continue; // Skip item ini jika quantity invalid
            }

            // Add product info to arrays for session
            $productNames[] = $name;
            $productTypes[] = $type;
            $productSizes[] = $size;
            $productQuantities[] = $quantity;
            $productPrices[] = $selling_price;

            if ($request->hasFile("image.$i")) {
                $imagePath = $request->file("image.$i")->store('stocks', 'public');
                $validated['image'][$i] = $imagePath;
            }

            // Generate SKU using the new format
            $sku = IdGenerator::generateSku($name, $type, $sub_type);

            // Check existing master stock by SKU
            $existingMasterStock = MasterStock::withTrashed()->where('sku', $sku)->first();

            // Create or get master stock
            if ($existingMasterStock) {
                if ($existingMasterStock->trashed()) {
                    $existingMasterStock->restore();
                    Log::info('Master stock restored during creation', [
                        'user_id' => auth()->id() ?? 'system',
                        'master_stock_id' => $existingMasterStock->id,
                        'name' => $name,
                        'sku' => $sku
                    ]);
                }

                $masterStockId = $existingMasterStock->id;

                if ($request->hasFile("image.$i")) {
                    if ($existingMasterStock->image) {
                        Storage::disk('public')->delete($existingMasterStock->image);
                    }
                    $existingMasterStock->image = $request->file("image.$i")->store('stocks', 'public');
                }

                $existingMasterStock->description = $description;
                $existingMasterStock->save();
                $masterStock = $existingMasterStock;
            } else {
                $masterStock = MasterStock::create([
                    'name' => $name,
                    'image' => $validated['image'][$i] ?? null,
                    'description' => $description,
                    'type' => $type,
                    'sub_type' => $sub_type,
                    'sku' => $sku,
                ]);

                $masterStockId = $masterStock->id;
            }

            // Find existing stock
            $existingStock = $this->findExistingStock($masterStockId, $size, $expiration_date);

            if ($existingStock) {
                if ($existingStock->trashed()) {
                    // Restore dan update stok yang telah dihapus
                    $existingStock->restore();

                    $existingStock->purchase_price = $purchase_price;
                    $existingStock->selling_price = $selling_price;
                    $existingStock->retail_price = $retail_price;

                    // Tambah retail_quantity jika ada
                    if ($retail_quantity !== null) {
                        $existingStock->retail_quantity = ($existingStock->retail_quantity ?? 0) + $retail_quantity;
                    }

                    // Update quantity dengan pengecekan ketat
                    $oldQuantity = $existingStock->quantity;
                    $newQuantity = $oldQuantity + $quantity;

                    Log::info('Stock restore and update:', [
                        'stock_id' => $existingStock->stock_id,
                        'old_quantity' => $oldQuantity,
                        'added_quantity' => $quantity,
                        'new_quantity' => $newQuantity
                    ]);

                    $this->updateStockWithNotificationHandling(
                        $existingStock,
                        $newQuantity,
                        $quantity
                    );

                    // Check master stock restore
                    $masterStockCheck = MasterStock::withTrashed()->find($existingStock->master_stock_id);
                    if ($masterStockCheck && $masterStockCheck->trashed()) {
                        $masterStockCheck->restore();
                    }
                } else {
                    // Update stok yang sudah ada
                    $existingStock->purchase_price = $purchase_price;
                    $existingStock->selling_price = $selling_price;

                    if ($retail_price !== null) {
                        $existingStock->retail_price = $retail_price;
                    }
                    if ($retail_quantity !== null) {
                        $existingStock->retail_quantity = ($existingStock->retail_quantity ?? 0) + $retail_quantity;
                    }

                    // Update quantity dengan logging
                    $oldQuantity = $existingStock->quantity;
                    $newQuantity = $oldQuantity + $quantity;

                    Log::info('Stock quantity update:', [
                        'stock_id' => $existingStock->stock_id,
                        'old_quantity' => $oldQuantity,
                        'added_quantity' => $quantity,
                        'new_quantity' => $newQuantity
                    ]);

                    $this->updateStockWithNotificationHandling(
                        $existingStock,
                        $newQuantity,
                        $quantity
                    );
                }

                $this->createPembelian($existingStock, $quantity, $purchase_price, $expiration_date, $master_pembelian);

                $logData[] = [
                    'action' => 'update_stock',
                    'stock_id' => $existingStock->stock_id,
                    'name' => $name,
                    'type' => $type,
                    'size' => $size,
                    'quantity' => $quantity,
                    'purchase_price' => $purchase_price,
                    'selling_price' => $selling_price,
                    'expiration_date' => $expiration_date
                ];
            } else {
                // Create new stock - case yang mungkin terlewat dalam kode asli
                $batchNumber = Stock::withTrashed()
                    ->where('master_stock_id', $masterStockId)
                    ->where('size', $size)
                    ->count() + 1;

                $stockId = IdGenerator::generateStockId(
                    $sku,
                    $size,
                    $expiration_date,
                    $batchNumber
                );

                $newStock = Stock::create([
                    'master_stock_id' => $masterStockId,
                    'size' => $size,
                    'purchase_price' => $purchase_price,
                    'selling_price' => $selling_price,
                    'quantity' => $quantity,
                    'retail_price' => $retail_price,
                    'retail_quantity' => $retail_quantity,
                    'expiration_date' => $expiration_date,
                    'stock_id' => $stockId,
                ]);

                Log::info('New stock created:', [
                    'stock_id' => $newStock->stock_id,
                    'quantity' => $newStock->quantity,
                    'name' => $name
                ]);

                $newStock->checkAndCreateNotifications();
                $this->createPembelian($newStock, $quantity, $purchase_price, $expiration_date, $master_pembelian);

                $logData[] = [
                    'action' => 'create_stock',
                    'stock_id' => $newStock->stock_id,
                    'name' => $name,
                    'type' => $type,
                    'size' => $size,
                    'quantity' => $quantity,
                    'purchase_price' => $purchase_price,
                    'selling_price' => $selling_price,
                    'expiration_date' => $expiration_date
                ];
            }
        }

        // Log all operations
        Log::info('Stock operation completed', [
            'user_id' => auth()->id() ?? 'system',
            'master_pembelian_id' => $master_pembelian->id,
            'total' => $total,
            'items' => $logData
        ]);

        return redirect()->route('stocks.create')->with([
            'success' => 'Stok berhasil ditambahkan!',
            'product_names' => $productNames,
            'product_types' => $productTypes,
            'product_sizes' => $productSizes,
            'product_quantities' => $productQuantities,
            'product_prices' => $productPrices,
            'product_name' => end($productNames),
            'product_type' => end($productTypes),
            'product_size' => end($productSizes),
            'product_quantity' => end($productQuantities),
            'product_selling_price' => end($productPrices)
        ]);
    }

    public function storeSize(Request $request)
    {
        $validated = $request->validate([
            'master_stock_id' => 'required|exists:master_stocks,id',
            'size' => 'required',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $masterStock = MasterStock::findOrFail($validated['master_stock_id']);

        // Handle size-specific image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stocks', 'public');

            // Create or update the size image record
            StockSizeImage::updateOrCreate(
                [
                    'master_stock_id' => $validated['master_stock_id'],
                    'size' => $validated['size']
                ],
                [
                    'image' => $imagePath
                ]
            );
        }

        // Create master pembelian entry
        $master_pembelian = MasterPembelian::create([
            'total' => $validated['purchase_price'] * $validated['quantity'],
            'date' => date('Y-m-d'),
        ]);

        // PERBAIKAN: Gunakan helper method untuk pencarian stok existing
        $existingStock = $this->findExistingStock($validated['master_stock_id'], $validated['size'], $validated['expiration_date']);

        if ($existingStock) {
            if ($existingStock->trashed()) {
                // If stock was soft-deleted, restore it and update its details
                $existingStock->restore();
                $existingStock->purchase_price = $validated['purchase_price'];
                $existingStock->selling_price = $validated['selling_price'];

                // Use the new method for proper notification handling
                $this->updateStockWithNotificationHandling(
                    $existingStock,
                    $existingStock->quantity + $validated['quantity'],
                    $validated['quantity']
                );

                // Also check if master stock is soft-deleted and restore it
                $masterStockCheck = MasterStock::withTrashed()->find($existingStock->master_stock_id);
                if ($masterStockCheck && $masterStockCheck->trashed()) {
                    $masterStockCheck->restore();
                    Log::channel('daily')->info('Master stock restored due to stock size restore', [
                        'user_id' => auth()->id() ?? 'system',
                        'master_stock_id' => $masterStockCheck->id,
                        'name' => $masterStockCheck->name
                    ]);
                }

                // Create pembelian record
                $this->createPembelian($existingStock, $validated['quantity'], $validated['purchase_price'], $validated['expiration_date'], $master_pembelian);

                Log::channel('daily')->info('Stock size restored', [
                    'user_id' => auth()->id() ?? 'system',
                    'master_stock_id' => $masterStock->id,
                    'master_stock_name' => $masterStock->name,
                    'stock_id' => $existingStock->stock_id,
                    'size' => $validated['size'],
                    'quantity' => $validated['quantity'],
                    'purchase_price' => $validated['purchase_price'],
                    'selling_price' => $validated['selling_price'],
                    'expiration_date' => $validated['expiration_date']
                ]);

                $stock = $existingStock;
            } else {
                // If stock exists but isn't deleted, update quantity and prices
                $existingStock->purchase_price = $validated['purchase_price'];
                $existingStock->selling_price = $validated['selling_price'];

                // Use the new method for proper notification handling
                $this->updateStockWithNotificationHandling(
                    $existingStock,
                    $existingStock->quantity + $validated['quantity'],
                    $validated['quantity']
                );

                // Create pembelian record
                $this->createPembelian($existingStock, $validated['quantity'], $validated['purchase_price'], $validated['expiration_date'], $master_pembelian);

                Log::channel('daily')->info('Stock size quantity updated', [
                    'user_id' => auth()->id() ?? 'system',
                    'master_stock_id' => $masterStock->id,
                    'master_stock_name' => $masterStock->name,
                    'stock_id' => $existingStock->stock_id,
                    'size' => $validated['size'],
                    'previous_quantity' => $existingStock->quantity - $validated['quantity'],
                    'added_quantity' => $validated['quantity'],
                    'new_quantity' => $existingStock->quantity,
                    'purchase_price' => $validated['purchase_price'],
                    'selling_price' => $validated['selling_price'],
                    'expiration_date' => $validated['expiration_date']
                ]);

                $stock = $existingStock;
            }
        }

        return redirect()->route('stocks.sizes', $validated['master_stock_id'])->with([
            'success' => 'Stok berhasil ditambahkan!',
            'quantity' => $validated['quantity'],
            'product_name' => $masterStock->name,
            'product_size' => $validated['size']
        ]);
    }

    public function updateMaster(Request $request, $id)
    {
        $masterStock = MasterStock::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Obat,Pupuk,Bibit',
            'sub_type' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($masterStock->image) {
                Storage::disk('public')->delete($masterStock->image);
            }
            $validated['image'] = $request->file('image')->store('stocks', 'public');
        }

        $masterStock->update($validated);

        return redirect()->route('stocks.index')->with([
            'success' => true,
            'message' => 'Produk berhasil diupdate.'
        ]);
    }

    public function updateSize(Request $request)
    {
        $validated = $request->validate([
            'master_stock_id' => 'required|exists:master_stocks,id',
            'size' => 'required',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $masterStock = MasterStock::findOrFail($validated['master_stock_id']);

        // Handle size-specific image
        if ($request->hasFile('image')) {
            // Check if there's an existing size image
            $existingSizeImage = StockSizeImage::where('master_stock_id', $validated['master_stock_id'])
                ->where('size', $validated['size'])
                ->first();

            if ($existingSizeImage && $existingSizeImage->image) {
                // Delete old image if it exists
                Storage::disk('public')->delete($existingSizeImage->image);
            }

            $imagePath = $request->file('image')->store('stocks', 'public');

            // Create or update the size image record
            StockSizeImage::updateOrCreate(
                [
                    'master_stock_id' => $validated['master_stock_id'],
                    'size' => $validated['size']
                ],
                [
                    'image' => $imagePath
                ]
            );
        }

        // Update purchase_price and selling_price for all stocks of this size
        Stock::where('master_stock_id', $validated['master_stock_id'])
            ->where('size', $validated['size'])
            ->update([
                'purchase_price' => $validated['purchase_price'],
                'selling_price' => $validated['selling_price']
            ]);

        return redirect()->route('stocks.sizes', $validated['master_stock_id'])->with([
            'success' => true,
            'message' => 'Stok berhasil diupdate.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($stock->image) {
                Storage::disk('public')->delete($stock->image);
            }
            $validated['image'] = $request->file('image')->store('stocks', 'public');
        }

        if (isset($validated['name']) || isset($validated['size']) || isset($validated['expiration_date'])) {
            $validated['stock_id'] = Stock::generateStockId(
                $validated['name'] ?? $stock->name,
                $validated['size'] ?? $stock->size,
                $validated['expiration_date'] ?? $stock->expiration_date
            );
        }

        $stock->update($validated);
        $stock->checkAndCreateNotifications();
        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diupdate.');
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);

        // Cek apakah barang ini memiliki riwayat penjualan
        $hasTransactions = \App\Models\TransactionItem::where('product_id', $stock->id)->exists();

        // Cek kondisi untuk penghapusan
        $isExpired = \Carbon\Carbon::parse($stock->expiration_date)->isPast();
        $isStockZero = $stock->quantity <= 0;

        // Jika barang tidak memiliki riwayat penjualan, atau stoknya habis, atau sudah kadaluarsa
        if (!$hasTransactions || $isStockZero || $isExpired) {
            // Jika barang memiliki riwayat penjualan atau pembelian, gunakan soft delete
            $hasPembelian = \App\Models\Pembelian::where('stock_id', $stock->id)->exists();

            if ($hasTransactions || $hasPembelian) {
                // Soft delete the stock
                $stock->delete();

                // Log soft delete operation
                Log::channel('daily')->info('Stock soft deleted', [
                    'user_id' => auth()->id() ?? 'system',
                    'stock_id' => $stock->stock_id,
                    'name' => $stock->masterStock->name ?? 'Unknown',
                    'type' => $stock->masterStock->type ?? 'Unknown',
                    'size' => $stock->size,
                    'quantity' => $stock->quantity,
                    'reason' => $isExpired ? 'expired' : ($isStockZero ? 'zero_stock' : 'manual_deletion'),
                    'delete_type' => 'soft_delete'
                ]);

                return redirect()->back()->with([
                    'showSuccessModal' => true,
                    'message' => 'Stok berhasil dihapus. Data historis tetap tersimpan.'
                ]);
            } else {
                // Only if there's no purchase history and no sales history, do force delete
                // Log force delete operation before deletion
                $logData = [
                    'user_id' => auth()->id() ?? 'system',
                    'stock_id' => $stock->stock_id,
                    'name' => $stock->masterStock->name ?? 'Unknown',
                    'type' => $stock->masterStock->type ?? 'Unknown',
                    'size' => $stock->size,
                    'quantity' => $stock->quantity,
                    'reason' => $isExpired ? 'expired' : ($isStockZero ? 'zero_stock' : 'manual_deletion'),
                    'delete_type' => 'force_delete'
                ];

                Log::channel('daily')->info('Stock permanently deleted', $logData);

                $stock->forceDelete();
                return redirect()->back()->with([
                    'showSuccessModal' => true,
                    'message' => 'Stok berhasil dihapus permanen.'
                ]);
            }
        } else {
            // Jika stok masih ada dan belum kadaluarsa, dan pernah dijual, tolak penghapusan
            Log::channel('daily')->info('Stock deletion rejected', [
                'user_id' => auth()->id() ?? 'system',
                'stock_id' => $stock->stock_id,
                'name' => $stock->masterStock->name ?? 'Unknown',
                'type' => $stock->masterStock->type ?? 'Unknown',
                'size' => $stock->size,
                'quantity' => $stock->quantity,
                'reason' => 'active_stock_with_transactions'
            ]);

            return back()->with([
                'showErrorModal' => true,
                'stockId' => $stock->id,
                'error' => 'Stok tidak dapat dihapus. Stok masih tersisa dan belum kadaluarsa.'
            ]);
        }
    }

    public function destroyMaster($id)
    {
        $masterStock = MasterStock::findOrFail($id);

        // Check if any associated stocks have transaction history - include trashed stocks
        $stockIds = Stock::withTrashed()->where('master_stock_id', $id)->pluck('id');
        $hasTransactions = \App\Models\TransactionItem::whereIn('product_id', $stockIds)->exists();

        // Check if any associated stocks have purchase history
        $hasPembelian = \App\Models\Pembelian::whereIn('stock_id', $stockIds)->exists();

        // Collect information about the stocks for logging - include trashed stocks
        $stocks = Stock::withTrashed()->where('master_stock_id', $id)->get();

        // Log the actual count before processing for debugging
        Log::channel('daily')->info('Master stock deletion started', [
            'user_id' => auth()->id() ?? 'system',
            'master_stock_id' => $masterStock->id,
            'name' => $masterStock->name,
            'actual_stocks_count' => $stocks->count(),
            'stocks_ids' => $stocks->pluck('id')->toArray(),
            'stocks_stock_ids' => $stocks->pluck('stock_id')->toArray()
        ]);

        $stocksData = $stocks->map(function ($stock) {
            return [
                'id' => $stock->id,
                'stock_id' => $stock->stock_id,
                'size' => $stock->size,
                'quantity' => $stock->quantity,
                'is_trashed' => $stock->trashed()
            ];
        })->toArray();

        if ($hasTransactions || $hasPembelian) {
            // Use soft delete for stocks
            Stock::where('master_stock_id', $id)->delete();

            // Also soft delete the master stock
            $masterStock->delete();

            // Log master stock soft deletion
            Log::channel('daily')->info('Master stock soft deleted with all associated stocks', [
                'user_id' => auth()->id() ?? 'system',
                'master_stock_id' => $masterStock->id,
                'name' => $masterStock->name,
                'type' => $masterStock->type,
                'delete_type' => 'soft_delete',
                'reason' => $hasTransactions ? 'has_transactions' : 'has_purchases',
                'stocks_count' => count($stocksData),
                'stocks' => $stocksData
            ]);
        } else {
            // Log master stock permanent deletion before the actual deletion
            $logData = [
                'user_id' => auth()->id() ?? 'system',
                'master_stock_id' => $masterStock->id,
                'name' => $masterStock->name,
                'type' => $masterStock->type,
                'delete_type' => 'force_delete',
                'stocks_count' => count($stocksData),
                'stocks' => $stocksData
            ];

            Log::channel('daily')->info('Master stock permanently deleted with all associated stocks', $logData);

            // Only if there's no purchase or sales history, force delete
            // Force delete stocks including soft-deleted ones
            Stock::withTrashed()->where('master_stock_id', $id)->forceDelete();

            // Delete master stock (image and record)
            if ($masterStock->image) {
                Storage::disk('public')->delete($masterStock->image);
            }
            $masterStock->forceDelete();
        }

        return redirect()->route('stocks.index')->with([
            'showSuccessModal' => true,
            'message' => 'Produk dan semua stoknya berhasil dihapus.'
        ]);
    }

    public function destroySize($masterId, $size)
    {
        $masterStock = MasterStock::findOrFail($masterId);

        // Get all stocks for this size including soft-deleted ones
        $stockIds = Stock::withTrashed()->where('master_stock_id', $masterId)
            ->where('size', $size)
            ->pluck('id');

        // Check if any stocks have transaction history
        $hasTransactions = \App\Models\TransactionItem::whereIn('product_id', $stockIds)->exists();

        // Check if any associated stocks have purchase history
        $hasPembelian = \App\Models\Pembelian::whereIn('stock_id', $stockIds)->exists();

        // Collect stocks information for logging including soft-deleted ones
        $stocks = Stock::withTrashed()->where('master_stock_id', $masterId)
            ->where('size', $size)
            ->get();

        // Log the actual count before processing for debugging
        Log::channel('daily')->info('Size deletion started', [
            'user_id' => auth()->id() ?? 'system',
            'master_stock_id' => $masterStock->id,
            'master_stock_name' => $masterStock->name,
            'size' => $size,
            'actual_stocks_count' => $stocks->count(),
            'stocks_ids' => $stocks->pluck('id')->toArray(),
            'stocks_stock_ids' => $stocks->pluck('stock_id')->toArray()
        ]);

        $stocksData = $stocks->map(function ($stock) {
            return [
                'id' => $stock->id,
                'stock_id' => $stock->stock_id,
                'quantity' => $stock->quantity,
                'expiration_date' => $stock->expiration_date,
                'is_trashed' => $stock->trashed()
            ];
        })->toArray();

        if ($hasTransactions || $hasPembelian) {
            // Use soft delete
            Stock::where('master_stock_id', $masterId)
                ->where('size', $size)
                ->delete();

            // Log size deletion (soft delete)
            Log::channel('daily')->info('Stock size soft deleted', [
                'user_id' => auth()->id() ?? 'system',
                'master_stock_id' => $masterStock->id,
                'master_stock_name' => $masterStock->name,
                'size' => $size,
                'delete_type' => 'soft_delete',
                'reason' => $hasTransactions ? 'has_transactions' : 'has_purchases',
                'stocks_count' => count($stocksData),
                'stocks' => $stocksData
            ]);
        } else {
            // Log size deletion (force delete) before the actual deletion
            $logData = [
                'user_id' => auth()->id() ?? 'system',
                'master_stock_id' => $masterStock->id,
                'master_stock_name' => $masterStock->name,
                'size' => $size,
                'delete_type' => 'force_delete',
                'stocks_count' => count($stocksData),
                'stocks' => $stocksData
            ];

            Log::channel('daily')->info('Stock size permanently deleted', $logData);

            // Only if there's no purchase or sales history, force delete
            // Force delete stocks including soft-deleted ones
            Stock::withTrashed()->where('master_stock_id', $masterId)
                ->where('size', $size)
                ->forceDelete();
        }

        return redirect()->route('stocks.sizes', $masterId)->with([
            'showSuccessModal' => true,
            'message' => 'Stok dengan ukuran ' . $size . ' berhasil dihapus.'
        ]);
    }

    // HELPER METHODS - PERBAIKAN UTAMA

    /**
     * Helper method untuk mencari stok yang sudah ada berdasarkan tanggal kadaluwarsa
     */
    private function findExistingStock($masterStockId, $size, $expirationDate)
    {
        // Konversi tanggal kadaluwarsa ke format yang konsisten
        $expirationDateFormatted = \Carbon\Carbon::parse($expirationDate)->format('Y-m-d');

        // Debug: Log untuk melihat tanggal yang dicari
        Log::channel('daily')->debug('Searching for existing stock', [
            'master_stock_id' => $masterStockId,
            'size' => $size,
            'expiration_date_input' => $expirationDate,
            'expiration_date_formatted' => $expirationDateFormatted
        ]);

        $existingStock = Stock::withTrashed()
            ->where('master_stock_id', $masterStockId)
            ->where('size', $size)
            ->whereRaw('DATE(expiration_date) = ?', [$expirationDateFormatted])
            ->first();

        // Debug: Log hasil pencarian
        if ($existingStock) {
            Log::channel('daily')->debug('Found existing stock', [
                'stock_id' => $existingStock->stock_id,
                'existing_expiration_date' => $existingStock->expiration_date,
                'is_trashed' => $existingStock->trashed()
            ]);
        } else {
            Log::channel('daily')->debug('No existing stock found');
        }

        return $existingStock;
    }

    /**
     * Helper method untuk generate batch number yang akurat
     */
    private function generateBatchNumber($masterStockId, $size, $expirationDate)
    {
        // Konversi tanggal kadaluwarsa ke format yang konsisten
        $expirationDateFormatted = \Carbon\Carbon::parse($expirationDate)->format('Y-m-d');

        // Hitung batch berdasarkan tanggal kadaluwarsa yang unik
        $existingBatches = Stock::withTrashed()
            ->where('master_stock_id', $masterStockId)
            ->where('size', $size)
            ->selectRaw('DATE(expiration_date) as exp_date, MIN(id) as first_id')
            ->groupBy(DB::raw('DATE(expiration_date)'))
            ->orderBy('first_id')
            ->get();

        // Debug: Log existing batches
        Log::channel('daily')->debug('Existing batches for batch number generation', [
            'master_stock_id' => $masterStockId,
            'size' => $size,
            'existing_batches_count' => $existingBatches->count(),
            'existing_dates' => $existingBatches->pluck('exp_date')->toArray()
        ]);

        return $existingBatches->count() + 1;
    }

    // Fungsi untuk mencatat pembelian baru
    private function createPembelian($stock, $quantity, $purchasePrice, $expirationDate, $master_pembelian)
    {
        $pembelianData = [
            'stock_id' => $stock->id,
            'purchase_price' => $purchasePrice,
            'quantity' => $quantity,
            'purchase_date' => now(),
            'master_pembelians_id' => $master_pembelian->id,
            'purchase_code' => $master_pembelian->purchase_code, // Gunakan kode pembelian dari master_pembelian
        ];

        Pembelian::create($pembelianData);
    }

    /**
     * Clean up duplicate master stocks with the same SKU
     * This method can be called manually or via a scheduled command
     */
    public function cleanupDuplicateMasterStocks()
    {
        // Get all SKUs with multiple master stocks (including soft-deleted ones)
        $duplicateSKUs = DB::table('master_stocks')
            ->select('sku', DB::raw('COUNT(*) as count'))
            ->groupBy('sku')
            ->having('count', '>', 1)
            ->get();

        $cleaned = 0;
        $errors = [];

        foreach ($duplicateSKUs as $duplicate) {
            try {
                // Get all master stocks with this SKU, ordered by ID (oldest first)
                $masterStocks = MasterStock::withTrashed()
                    ->where('sku', $duplicate->sku)
                    ->orderBy('id')
                    ->get();

                // The first one is the one we'll keep
                $primaryMasterStock = $masterStocks->first();

                // Restore it if it's soft-deleted
                if ($primaryMasterStock->trashed()) {
                    $primaryMasterStock->restore();
                }

                // For each additional master stock, transfer its stocks to the primary one
                foreach ($masterStocks as $index => $masterStock) {
                    // Skip the primary master stock
                    if ($index === 0) continue;

                    // Get all stocks associated with this duplicate master stock
                    $stocks = Stock::withTrashed()
                        ->where('master_stock_id', $masterStock->id)
                        ->get();

                    // Transfer each stock to the primary master stock
                    foreach ($stocks as $stock) {
                        $stock->master_stock_id = $primaryMasterStock->id;
                        $stock->save();
                    }

                    // Delete the now-empty duplicate master stock
                    if ($masterStock->image) {
                        Storage::disk('public')->delete($masterStock->image);
                    }
                    $masterStock->forceDelete();
                }

                $cleaned++;

                Log::channel('daily')->info('Duplicate master stock cleaned up', [
                    'user_id' => auth()->id() ?? 'system',
                    'sku' => $duplicate->sku,
                    'primary_id' => $primaryMasterStock->id,
                    'duplicates_removed' => $duplicate->count - 1
                ]);
            } catch (\Exception $e) {
                $errors[] = [
                    'sku' => $duplicate->sku,
                    'error' => $e->getMessage()
                ];

                Log::channel('daily')->error('Error cleaning up duplicate master stock', [
                    'sku' => $duplicate->sku,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => count($errors) === 0,
            'cleaned' => $cleaned,
            'errors' => $errors
        ];
    }

    /**
     * Admin route to manually trigger cleanup of duplicate master stocks
     */
    public function runCleanupDuplicates()
    {
        $result = $this->cleanupDuplicateMasterStocks();

        if ($result['success']) {
            return redirect()->route('stocks.index')->with([
                'success' => true,
                'message' => "Berhasil membersihkan {$result['cleaned']} duplikat master stock."
            ]);
        } else {
            return redirect()->route('stocks.index')->with([
                'error' => true,
                'message' => "Terjadi {$result['cleaned']} dibersihkan, tapi ada " . count($result['errors']) . " error.",
                'details' => $result['errors']
            ]);
        }
    }

    /**
     * Get total active (non-deleted) stock quantity for a master stock
     */
    public function getTotalStockQuantity($masterStockId)
    {
        return Stock::where('master_stock_id', $masterStockId)
            ->whereNull('deleted_at')  // Only include non-deleted stocks
            ->sum('quantity');
    }

    private function updateStockWithNotificationHandling($stock, $newQuantity, $addedQuantity = 0)
    {
        $oldQuantity = $stock->quantity;
        $stock->quantity = $newQuantity;

        // Check for improved conditions before updating
        if ($oldQuantity <= 0 && $newQuantity > 0) {
            // Stock was out but now has quantity - mark out_of_stock notifications as resolved
            $stock->markNotificationsAsResolved('out_of_stock');
        }

        if ($oldQuantity <= 5 && $newQuantity > 5) {
            // Stock was low but now above threshold - mark low_stock notifications as resolved
            $stock->markNotificationsAsResolved('low_stock');
        }

        $stock->save();

        // Create new notifications based on current conditions
        $stock->checkAndCreateNotifications();

        return $stock;
    }
}
