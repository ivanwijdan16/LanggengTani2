<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSizeImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_stock_id',
        'size',
        'image',
    ];

    public function masterStock()
    {
        return $this->belongsTo(MasterStock::class, 'master_stock_id');
    }
}
