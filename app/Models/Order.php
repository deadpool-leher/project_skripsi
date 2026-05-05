<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'produk',
        'subtotal',
        'total',
        'discount_code',
        'discount_amount',
        'status',
        'metode',
        'waktu',
        'alamat',
        'latitude',
        'longitude',
        'payment_proof',
        'created_at',
    ];

    protected $casts = [
        'produk' => 'array',
        'created_at' => 'datetime',
    ];

    public function getStatusLabelAttribute()
{
    return [
        'baru' => 'Menunggu Konfirmasi',
        'diproses' => 'Sedang Diproses',
        'siap' => 'Siap Diambil',
        'selesai' => 'Sudah Diambil'
    ][$this->status] ?? $this->status;
}
}
