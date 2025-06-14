<?php

namespace App\Models;

use App\Helpers\IdGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'size',
        'purchase_price',
        'selling_price',
        'quantity',
        'expiration_date',
        'stock_id',
        'retail_price',
        'retail_quantity',
        'master_stock_id',
    ];

    protected $dates = ['expiration_date', 'deleted_at'];

    // Boot method to set up model event listeners
    protected static function boot()
    {
        parent::boot();

        // Generate stock_id before creating a new stock
        static::creating(function ($stock) {
            if (!$stock->stock_id) {
                $masterStock = MasterStock::find($stock->master_stock_id);
                if ($masterStock) {
                    // FIXED: Get batch number including soft deleted stocks
                    $batchNumber = Stock::withTrashed()
                        ->where('master_stock_id', $stock->master_stock_id)
                        ->where('size', $stock->size)
                        ->count() + 1;

                    $stock->stock_id = IdGenerator::generateStockId(
                        $masterStock->sku,
                        $stock->size,
                        $stock->expiration_date,
                        $batchNumber
                    );
                }
            }
        });
    }

    public function masterStock()
    {
        return $this->belongsTo(MasterStock::class, 'master_stock_id')->withTrashed();
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    // Method to create a notification if stock is low, out of stock, or expiring
    public function createNotification($message)
    {
        Notification::create([
            'stock_id' => $this->id,
            'message' => $message,
            'read' => false, // Newly created notification is unread
        ]);
    }

    // Method to check if stock is low
    public function isLowStock()
    {
        return $this->quantity <= 3 && $this->quantity > 0;
    }

    // Method to check if stock is out
    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    // Method to check if stock is expiring soon (within 3 days)
    public function isExpiringSoon()
    {
        return Carbon::parse($this->expiration_date)->diffInDays(Carbon::now()) <= 3;
    }

    // Method to check if stock is expired
    public function isExpired()
    {
        return Carbon::parse($this->expiration_date)->isPast();
    }

    // Method to check stock status and create notifications accordingly
    public function checkAndCreateNotifications()
    {
        if ($this->isLowStock()) {
            $this->createNotification("Stok barang '{$this->stock_id}' hampir habis! Sisa {$this->quantity} unit. Segera restock!");
        }

        if ($this->isOutOfStock()) {
            $this->createNotification("Stok barang '{$this->stock_id}' Habis. Segera restock!");
        }

        if ($this->isExpiringSoon()) {
            $this->createNotification("{$this->stock_id} akan kadaluwarsa dalam " . Carbon::parse($this->expiration_date)->diffInDays(Carbon::now()) . " hari! (Exp: {$this->expiration_date})");
        }

        if ($this->isExpired()) {
            $this->createNotification("{$this->stock_id} sudah kadaluwarsa (Exp: {$this->expiration_date})");
        }
    }
}
