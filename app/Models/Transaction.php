<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'total_paid',
        'change',
        'id_penjualan',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
