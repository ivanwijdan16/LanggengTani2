<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel's default convention)
    protected $table = 'notifications';

    // Fillable fields
    protected $fillable = ['stock_id', 'message', 'read'];

    // Define the relationship between Notification and Stock models
    public function stock()
    {
        return $this->belongsTo(Stock::class)->withTrashed();
    }

    // Helper method to get the redirect URL for this notification
    public function getRedirectUrl()
    {
        if (!$this->stock || !$this->stock->masterStock) {
            return route('stocks.index');
        }

        // Generate URL to the batches page for this specific stock
        return route('stocks.batches', [
            'master_id' => $this->stock->masterStock->id,
            'size' => $this->stock->size
        ]);
    }

    // Helper method to check if notification is clickable
    public function isClickable()
    {
        return $this->stock && $this->stock->masterStock;
    }
}
