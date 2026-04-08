<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_transaksi',
        'total_harga',
        'uang_bayar',
        'uang_kembali',
        'tanggal_transaksi'
    ];

    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
}
