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

    // Method to create a notification if it doesn't already exist
    public function createNotification($message, $type = null)
    {
        // Define notification types for better categorization
        $notificationType = $type ?? $this->getNotificationType($message);

        // Create a unique identifier for this notification
        $notificationIdentifier = $this->generateNotificationIdentifier($notificationType);

        // Check if a similar unread notification already exists
        $existingNotification = Notification::where('stock_id', $this->id)
            ->where('read', false)
            ->where(function ($query) use ($notificationType, $message) {
                // Check for same type of notification
                switch ($notificationType) {
                    case 'low_stock':
                        $query->where('message', 'like', '%hampir habis%')
                            ->orWhere('message', 'like', '%stok menipis%');
                        break;
                    case 'out_of_stock':
                        $query->where('message', 'like', '%habis%')
                            ->where('message', 'like', '%segera restock%');
                        break;
                    case 'expiring_soon':
                        $query->where('message', 'like', '%akan kadaluwarsa%');
                        break;
                    case 'expired':
                        $query->where('message', 'like', '%sudah kadaluwarsa%');
                        break;
                    default:
                        $query->where('message', $message);
                }
            })
            ->first();

        // Only create notification if it doesn't exist
        if (!$existingNotification) {
            Notification::create([
                'stock_id' => $this->id,
                'message' => $message,
                'read' => false,
                'notification_type' => $notificationType,
                'created_at' => now(),
            ]);
        }
    }

    // Helper method to determine notification type
    private function getNotificationType($message)
    {
        if (str_contains(strtolower($message), 'hampir habis') || str_contains(strtolower($message), 'stok menipis')) {
            return 'low_stock';
        } elseif (str_contains(strtolower($message), 'habis')) {
            return 'out_of_stock';
        } elseif (str_contains(strtolower($message), 'akan kadaluwarsa')) {
            return 'expiring_soon';
        } elseif (str_contains(strtolower($message), 'sudah kadaluwarsa')) {
            return 'expired';
        }

        return 'general';
    }

    // Generate unique identifier for notification
    private function generateNotificationIdentifier($type)
    {
        return sprintf('%s_%d_%s', $type, $this->id, date('Y-m-d'));
    }

    // Method to check if stock is low
    public function isLowStock()
    {
        return $this->quantity <= 5 && $this->quantity > 0;
    }

    // Method to check if stock is out
    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    // Method to check if stock is expiring soon (within 3 days)
    public function isExpiringSoon()
    {
        return Carbon::parse($this->expiration_date)->diffInDays(Carbon::now()) <= 3
            && !Carbon::parse($this->expiration_date)->isPast();
    }

    // Method to check if stock is expired
    public function isExpired()
    {
        return Carbon::parse($this->expiration_date)->isPast();
    }

    // Method to clear old notifications of the same type
    public function clearOldNotifications($type)
    {
        Notification::where('stock_id', $this->id)
            ->where('notification_type', $type)
            ->where('read', false)
            ->update(['read' => true]);
    }

    // Method to check stock status and create notifications accordingly
    public function checkAndCreateNotifications()
    {
        // Clear any existing unread notifications first to prevent accumulation
        $this->clearOldNotifications('out_of_stock');
        $this->clearOldNotifications('low_stock');
        $this->clearOldNotifications('expiring_soon');
        $this->clearOldNotifications('expired');

        // Check for out of stock first (highest priority)
        if ($this->isOutOfStock()) {
            $this->createNotification(
                "Stok barang '{$this->stock_id}' Habis. Segera restock!",
                'out_of_stock'
            );
            return; // Exit early if out of stock
        }

        // Check for low stock
        if ($this->isLowStock()) {
            $this->createNotification(
                "Stok barang '{$this->stock_id}' hampir habis! Sisa {$this->quantity} unit. Segera restock!",
                'low_stock'
            );
        }

        // Check for expired (highest priority for date-related)
        if ($this->isExpired()) {
            $this->createNotification(
                "{$this->stock_id} sudah kadaluwarsa (Exp: {$this->expiration_date})",
                'expired'
            );
            return; // Exit early if expired
        }

        // Check for expiring soon
        if ($this->isExpiringSoon()) {
            $daysLeft = Carbon::parse($this->expiration_date)->diffInDays(Carbon::now());
            $this->createNotification(
                "{$this->stock_id} akan kadaluwarsa dalam {$daysLeft} hari! (Exp: {$this->expiration_date})",
                'expiring_soon'
            );
        }
    }

    // Method to mark stock-related notifications as resolved
    public function markNotificationsAsResolved($type = null)
    {
        $query = Notification::where('stock_id', $this->id)->where('read', false);

        if ($type) {
            $query->where('notification_type', $type);
        }

        $query->update(['read' => true]);
    }

    // Method to check if stock condition has improved
    public function checkForImprovedConditions()
    {
        // If stock was out of stock but now has quantity, mark out_of_stock notifications as resolved
        if ($this->quantity > 0) {
            $this->markNotificationsAsResolved('out_of_stock');
        }

        // If stock was low but now above threshold, mark low_stock notifications as resolved
        if ($this->quantity > 5) {
            $this->markNotificationsAsResolved('low_stock');
        }

        // Note: We don't auto-resolve expiration notifications as they remain relevant
    }
}
