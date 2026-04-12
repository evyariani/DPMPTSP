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
    
    /**
     * Relasi ke SPT sebagai penanda tangan
     */
    public function sptSebagaiPenandaTangan()
    {
        return $this->hasMany(SPT::class, 'penanda_tangan', 'id_pegawai');
    }

    /**
     * Method untuk mendapatkan SPT yang diikuti
     * (karena pakai JSON, tidak bisa pakai relasi biasa)
     */
    public function getSptDiikuti()
    {
        return SPT::whereJsonContains('pegawai', $this->id_pegawai)->get();
    }

    /**
     * Hitung jumlah SPT yang diikuti
     */
    public function getJumlahSptDiikutiAttribute()
    {
        return SPT::whereJsonContains('pegawai', $this->id_pegawai)->count();
    }

    /**
     * Hitung jumlah SPT sebagai penanda tangan
     */
    public function getJumlahSptPenandaTanganAttribute()
    {
        return $this->sptSebagaiPenandaTangan()->count();
    }
}