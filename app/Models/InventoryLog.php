<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'description',
    ];

    // Mendefinisikan Relasi: 1 Jejak hanya dimiliki 1 Barang
    public function product() {
        return $this->belongsTo(Product::class);
    }

    // Mendefinisikan Relasi: 1 Jejak hanya dimiliki 1 User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
