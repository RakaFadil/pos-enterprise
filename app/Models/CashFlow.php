<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description'
    ];

    // Relasi Pemilik Jejak
    public function user() {
        return $this->belongsTo(User::class);
    }
}
