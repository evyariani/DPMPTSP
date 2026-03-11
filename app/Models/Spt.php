<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPT extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi plural
    protected $table = 'spt';
    
    // Tentukan primary key
    protected $primaryKey = 'id_spt';
    
    // Tipe primary key
    protected $keyType = 'int';
    
    // Auto increment
    public $incrementing = true;
    
    // Kolom yang bisa diisi
    protected $fillable = [
        'nomor_surat',
        'dasar',
        'pegawai',
        'tujuan',
        'tanggal',
        'lokasi',
        'penanda_tangan'
    ];

    // Casting tipe data
    protected $casts = [
        'dasar' => 'array',
        'pegawai' => 'array',
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke penanda tangan
    public function penandaTangan()
    {
        return $this->belongsTo(Pegawai::class, 'penanda_tangan', 'id_pegawai');
    }

    // Accessor untuk mendapatkan daftar pegawai
    public function getPegawaiListAttribute()
    {
        if (empty($this->pegawai)) {
            return collect([]);
        }
        
        return Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
    }

    // Accessor untuk mendapatkan nama pegawai (string)
    public function getNamaPegawaiAttribute()
    {
        if (empty($this->pegawai)) {
            return '-';
        }
        
        $pegawaiList = Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
        return $pegawaiList->pluck('nama')->implode(', ');
    }

    // Accessor untuk mendapatkan daftar dasar
    public function getDasarListAttribute()
    {
        return $this->dasar ?? [];
    }

    // Mutator untuk dasar
    public function setDasarAttribute($value)
    {
        $this->attributes['dasar'] = is_array($value) ? json_encode($value) : $value;
    }

    // Mutator untuk pegawai
    public function setPegawaiAttribute($value)
    {
        $this->attributes['pegawai'] = is_array($value) ? json_encode($value) : $value;
    }
}