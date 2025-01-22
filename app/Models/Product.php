<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'gambar',
        'kategori',
        'file',
        'format_file',
    ];
    public $timestamps = false; // Nonaktifkan timestamps

    public function users()
    {
        return $this->belongsToMany(User::class, 'purchases');
    }
}