<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    // Route untuk semua user yang sudah login
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Stock management (semua user dapat akses)
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/create', [StockController::class, 'create'])->name('create');
        Route::post('/', [StockController::class, 'store'])->name('store');
        Route::get('/cleanup-duplicates', [StockController::class, 'runCleanupDuplicates'])->name('cleanup.duplicates');

        // Size-based management
        Route::get('/sizes/{master_id}', [StockController::class, 'sizes'])->name('sizes');
        Route::get('/batches/{master_id}/{size}', [StockController::class, 'batches'])->name('batches');

        Route::get('/create-size/{master_id}/{size?}', [StockController::class, 'createSize'])->name('create.size');
        Route::post('/store-size', [StockController::class, 'storeSize'])->name('store.size');

        Route::get('/edit-master/{id}', [StockController::class, 'editMaster'])->name('edit.master');
        Route::put('/update-master/{id}', [StockController::class, 'updateMaster'])->name('update.master');

        Route::get('/edit-size/{master_id}/{size}', [StockController::class, 'editSize'])->name('edit.size');
        Route::put('/update-size', [StockController::class, 'updateSize'])->name('update.size');

        Route::delete('/master/{id}', [StockController::class, 'destroyMaster'])->name('destroy.master');
        Route::delete('/size/{master_id}/{size}', [StockController::class, 'destroySize'])->name('destroy.size');

        Route::get('/{id}', [StockController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StockController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StockController::class, 'update'])->name('update');
        Route::delete('/{id}', [StockController::class, 'destroy'])->name('destroy');
    });

    // Cart/Kasir (semua user dapat akses)
    Route::get('/cart/search', [CartController::class, 'search'])->name('cart.search');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/stock-info', [CartController::class, 'getProductStock'])->name('cart.stock-info');
    Route::get('/get-cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart/{id}/quantity', [CartController::class, 'updateCartQuantity'])->name('cart.update.quantity');
    Route::get('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::get('/transaction/success/{id}', [TransactionController::class, 'showSuccess'])->name('transaction.success');
    Route::post('/cart/{id}', [CartController::class, 'removeFromCart']);

    // Notifications (semua user dapat akses)
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{id}/markAsRead', [NotificationController::class, 'markAsRead']);
    Route::get('/notifications/all', [NotificationController::class, 'index'])->name('notifications.all');

    // Dokumentasi
    Route::get('/dokumentasi', [DokumentasiController::class, 'index'])->name('dokumentasi');

    // Route yang hanya bisa diakses owner
    Route::middleware('role:owner')->group(function () {
        // Route Pembelian
        Route::prefix('pembelian')->group(function () {
            Route::get('/', [PembelianController::class, 'index'])->name('pembelian.index');
            Route::get('/riwayat/{bulan}/{tahun}', [PembelianController::class, 'riwayatPembelianPerBulan'])->name('pembelian.riwayat');
            Route::get('/grafik/{bulan}/{tahun}', [PembelianController::class, 'grafikPembelianPerBulan'])->name('pembelian.grafik');
            Route::get('/laporan/{bulan}/{tahun}', [PembelianController::class, 'cetakLaporan'])->name('pembelian.laporan');
            Route::get('/kadaluarsa/{bulan}/{tahun}', [PembelianController::class, 'barangKadaluarsaPerBulan'])->name('pembelian.kadaluarsa');
        });

        // Route Penjualan
        Route::prefix('penjualan')->group(function () {
            Route::get('/', [PenjualanController::class, 'index'])->name('penjualan.index');
            Route::get('/riwayat/{bulan}/{tahun}', [PenjualanController::class, 'riwayatPenjualanPerBulan'])->name('penjualan.riwayat');
            Route::get('/grafik/{bulan}/{tahun}', [PenjualanController::class, 'grafikPenjualanPerBulan'])->name('penjualan.grafik');
            Route::get('/laporan/{bulan}/{tahun}', [PenjualanController::class, 'cetakLaporan'])->name('penjualan.laporan');
        });

        // Profile edit (hanya owner)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // User management
        Route::resource('user', UserController::class);
    });
});

require __DIR__ . '/auth.php';
