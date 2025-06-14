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
        return $this->belongsTo(Stock::class);
    }
}
