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
        'waktu'
    ];
}