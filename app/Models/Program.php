<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    
    protected $table = 'tb_program';
    protected $primaryKey = 'id_program';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'program',
        'kegiatan',
        'sub_kegiatan',
        'kode_rekening',
        'id_pegawai'
    ];
    
    // Konstanta untuk pilihan rekening
    const REKENING_0001 = '5.1.02.04.01.0001';
    const REKENING_0003 = '5.1.02.04.01.0003';
    
    // Array pilihan rekening
    public static function getRekeningOptions()
    {
        return [
            self::REKENING_0001 => '5.1.02.04.01.0001',
            self::REKENING_0003 => '5.1.02.04.01.0003',
        ];
    }
    
    // Relasi ke pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}