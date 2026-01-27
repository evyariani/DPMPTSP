<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    use HasFactory;
    
    protected $table = 'tb_transportasi';
    protected $primaryKey = 'id_transportasi';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'jenis_transportasi',
        'lama_perjalanan'
    ];
    
    // HAPUS ATAU KOMENTARI CASTING INI:
    // protected $casts = [
    //     'tk_jalan' => 'decimal:0'
    // ];
}