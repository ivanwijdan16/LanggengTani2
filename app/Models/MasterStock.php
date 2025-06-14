<?php

namespace App\Models;

use App\Helpers\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_stocks';

    protected $fillable = [
        'name',
        'sku',
        'image',
        'description',
        'type',
        'sub_type',
    ];

    protected $dates = ['deleted_at'];

    // Boot method to set up model event listeners
    protected static function boot()
    {
        parent::boot();

        // Generate SKU before creating a new master stock
        static::creating(function ($masterStock) {
            if (!$masterStock->sku) {
                $masterStock->sku = IdGenerator::generateSku($masterStock->name, $masterStock->type, $masterStock->sub_type);
            }
        });
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'master_stock_id');
    }

    public function sizeImages()
    {
        return $this->hasMany(StockSizeImage::class, 'master_stock_id');
    }

    public function getSizeImage($size)
    {
        $sizeImage = $this->sizeImages()->where('size', $size)->first();
        if ($sizeImage && $sizeImage->image) {
            return $sizeImage->image;
        }

        // Return master stock image as a fallback
        return $this->image;
    }
}
