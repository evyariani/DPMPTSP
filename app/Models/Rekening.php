<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;
    
    protected $table = 'tb_rekening';
    protected $primaryKey = 'id_rekening';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'kode_rek',
        'nomor-rek',
        'uraian'
    ];
    
    // HAPUS ATAU KOMENTARI CASTING INI:
    // protected $casts = [
    //     'tk_jalan' => 'decimal:0'
    // ];
}