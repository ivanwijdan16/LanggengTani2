<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relasi ke transaksi (Transaction)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relasi ke produk (Product)
    public function product()
    {
        return $this->belongsTo(Stock::class)->withTrashed();
    }
}
