<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    
    protected $table = 'tb_pegawai';
    protected $primaryKey = 'id_pegawai';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'nama',
        'nip',
        'pangkat',
        'gol',
        'jabatan',
        'tk_jalan'
    ];
    
    // HAPUS ATAU KOMENTARI CASTING INI:
    // protected $casts = [
    //     'tk_jalan' => 'decimal:0'
    // ];
}