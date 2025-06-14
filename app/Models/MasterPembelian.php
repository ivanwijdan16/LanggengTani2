<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\IdGenerator;
use Illuminate\Support\Facades\DB;

class MasterPembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_pembelians';

    protected $fillable = [
        'purchase_code',
        'total',
        'date',
    ];

    protected $dates = ['deleted_at'];

    // Menambahkan event untuk otomatis mengisi purchase_code saat pembuatan model baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($masterPembelian) {
            if (!$masterPembelian->purchase_code) {
                $purchaseCode = IdGenerator::generatePurchaseCode();

                // Check if purchase_code already exists and append unique identifier if needed
                $attempts = 0;
                while (DB::table('master_pembelians')
                    ->where('purchase_code', $purchaseCode)
                    ->exists() && $attempts < 10
                ) {

                    $attempts++;
                    $purchaseCode = IdGenerator::generatePurchaseCode();

                    // Add a small delay to prevent race condition
                    usleep(100000); // 0.1 second
                }

                if ($attempts >= 10) {
                    // Fallback: append random string if still not unique
                    $purchaseCode .= '-' . substr(uniqid(), -4);
                }

                $masterPembelian->purchase_code = $purchaseCode;
            }
        });
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'master_pembelians_id');
    }
}
