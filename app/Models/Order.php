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
        'total',
        'status',
        'metode',
        'waktu',
        'alamat',
        'latitude',
        'longitude'
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