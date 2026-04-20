<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    
    // ========== RELATIONS ==========
    
    // Relasi ke pegawai (pejabat teknis)
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
    
    // Relasi ke SPD (sebagai pejabat teknis)
    public function spd(): HasMany
    {
        return $this->hasMany(SPD::class, 'pejabat_teknis_id', 'id_program');
    }
    
    // ========== ACCESSORS ==========
    
    // Accessor untuk mendapatkan nama pegawai
    public function getNamaPegawaiAttribute()
    {
        return $this->pegawai?->nama ?? '-';
    }
    
    // Accessor untuk mendapatkan NIP pegawai
    public function getNipPegawaiAttribute()
    {
        return $this->pegawai?->nip ?? '-';
    }
    
    // Accessor untuk mendapatkan jabatan pegawai
    public function getJabatanPegawaiAttribute()
    {
        return $this->pegawai?->jabatan ?? '-';
    }
    
    // Accessor untuk mendapatkan informasi lengkap program
    public function getInformasiLengkapAttribute()
    {
        return "{$this->program} - {$this->kegiatan} - {$this->sub_kegiatan}";
    }
    
    // Accessor untuk mendapatkan kode rekening dengan label
    public function getKodeRekeningLabelAttribute()
    {
        $rekeningOptions = self::getRekeningOptions();
        $label = array_search($this->kode_rekening, $rekeningOptions);
        
        if ($label) {
            return str_replace('REKENING_', '', $label);
        }
        
        return $this->kode_rekening;
    }
    
    // ========== SCOPES ==========
    
    // Scope untuk filter berdasarkan program
    public function scopeByProgram($query, $program)
    {
        if ($program) {
            return $query->where('program', 'like', "%{$program}%");
        }
        return $query;
    }
    
    // Scope untuk filter berdasarkan kegiatan
    public function scopeByKegiatan($query, $kegiatan)
    {
        if ($kegiatan) {
            return $query->where('kegiatan', 'like', "%{$kegiatan}%");
        }
        return $query;
    }
    
    // Scope untuk filter berdasarkan kode rekening
    public function scopeByKodeRekening($query, $kodeRekening)
    {
        if ($kodeRekening) {
            return $query->where('kode_rekening', $kodeRekening);
        }
        return $query;
    }
    
    // Scope untuk filter berdasarkan pegawai
    public function scopeByPegawai($query, $pegawaiId)
    {
        if ($pegawaiId) {
            return $query->where('id_pegawai', $pegawaiId);
        }
        return $query;
    }
    
    // ========== HELPER METHODS ==========
    
    /**
     * Cek apakah program memiliki SPD terkait
     */
    public function hasSpd(): bool
    {
        return $this->spd()->count() > 0;
    }
    
    /**
     * Get total SPD yang menggunakan program ini
     */
    public function getTotalSpdAttribute()
    {
        return $this->spd()->count();
    }
}