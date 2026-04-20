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
        'pegawai_snapshot',     // TAMBAHKAN
        'tujuan',
        'tanggal',
        'lokasi',
        'penanda_tangan',
        'penanda_tangan_nama',   // TAMBAHKAN
        'penanda_tangan_nip',    // TAMBAHKAN
        'penanda_tangan_jabatan' // TAMBAHKAN
    ];

    // Casting tipe data
    protected $casts = [
        'dasar' => 'array',
        'pegawai' => 'array',
        'pegawai_snapshot' => 'array',  // TAMBAHKAN
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONS ==========
    
    // Relasi ke penanda tangan (untuk referensi ID saja, bukan untuk tampilan)
    public function penandaTangan()
    {
        return $this->belongsTo(Pegawai::class, 'penanda_tangan', 'id_pegawai');
    }

    
// TAMBAHKAN INI
/**
 * Relasi ke LHPD (one-to-one)
 */
public function lhpd()
{
    return $this->hasOne(Lhpd::class, 'spt_id', 'id_spt');
}

    // ========== ACCESSORS (MENGGUNAKAN SNAPSHOT) ==========
    
    /**
     * Mendapatkan daftar pegawai dari snapshot (data saat SPT dibuat)
     * Gunakan ini untuk tampilan, bukan dari master
     */
    public function getPegawaiListFromSnapshotAttribute()
    {
        if (empty($this->pegawai_snapshot)) {
            return collect([]);
        }
        
        // Konversi array ke collection of objects
        return collect($this->pegawai_snapshot)->map(function ($item) {
            return (object) $item;
        });
    }
    
    /**
     * Mendapatkan daftar pegawai (ambil dari master - HATI-HATI! Bisa berubah)
     * @deprecated Gunakan getPegawaiListFromSnapshotAttribute untuk tampilan
     */
    public function getPegawaiListAttribute()
    {
        if (empty($this->pegawai)) {
            return collect([]);
        }
        
        return Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
    }

    /**
     * Mendapatkan nama pegawai dari snapshot (data saat SPT dibuat)
     */
    public function getNamaPegawaiFromSnapshotAttribute()
    {
        if (empty($this->pegawai_snapshot)) {
            return '-';
        }
        
        $namaList = array_column($this->pegawai_snapshot, 'nama');
        return implode(', ', $namaList);
    }
    
    /**
     * Mendapatkan nama pegawai (ambil dari master - HATI-HATI! Bisa berubah)
     * @deprecated Gunakan getNamaPegawaiFromSnapshotAttribute untuk tampilan
     */
    public function getNamaPegawaiAttribute()
    {
        if (empty($this->pegawai)) {
            return '-';
        }
        
        $pegawaiList = Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
        return $pegawaiList->pluck('nama')->implode(', ');
    }

    /**
     * Mendapatkan data lengkap penanda tangan dari snapshot (data saat SPT dibuat)
     */
    public function getPenandaTanganSnapshotAttribute()
    {
        if (empty($this->penanda_tangan_nama)) {
            return null;
        }
        
        return (object) [
            'nama' => $this->penanda_tangan_nama,
            'nip' => $this->penanda_tangan_nip,
            'jabatan' => $this->penanda_tangan_jabatan,
        ];
    }
    
    /**
     * Mendapatkan nama penanda tangan dari snapshot
     */
    public function getPenandaTanganNamaSnapshotAttribute()
    {
        return $this->penanda_tangan_nama ?? '-';
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Membuat snapshot pegawai dari array ID pegawai
     * Simpan data lengkap pegawai saat SPT dibuat
     */
    public function createPegawaiSnapshot()
    {
        if (empty($this->pegawai)) {
            $this->pegawai_snapshot = [];
            return;
        }
        
        $pegawaiList = Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
        
        $snapshot = [];
        foreach ($pegawaiList as $pegawai) {
            $snapshot[] = [
                'id_pegawai' => $pegawai->id_pegawai,
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'pangkat' => $pegawai->pangkat ?? '-',
                'gol' => $pegawai->gol ?? '-',
            ];
        }
        
        $this->pegawai_snapshot = $snapshot;
    }
    
    /**
     * Membuat snapshot penanda tangan dari ID penanda tangan
     * Simpan data lengkap penanda tangan saat SPT dibuat
     */
    public function createPenandaTanganSnapshot()
    {
        if (empty($this->penanda_tangan)) {
            $this->penanda_tangan_nama = null;
            $this->penanda_tangan_nip = null;
            $this->penanda_tangan_jabatan = null;
            return;
        }
        
        $pegawai = Pegawai::find($this->penanda_tangan);
        if ($pegawai) {
            $this->penanda_tangan_nama = $pegawai->nama;
            $this->penanda_tangan_nip = $pegawai->nip;
            $this->penanda_tangan_jabatan = $pegawai->jabatan;
        }
    }
    
    /**
     * Update snapshot (dipanggil saat SPT dibuat atau diupdate)
     */
    public function updateSnapshots()
    {
        $this->createPegawaiSnapshot();
        $this->createPenandaTanganSnapshot();
    }

    // ========== MUTATORS ==========
    
    public function getDasarListAttribute()
    {
        return $this->dasar ?? [];
    }

    public function setDasarAttribute($value)
    {
        $this->attributes['dasar'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setPegawaiAttribute($value)
    {
        $this->attributes['pegawai'] = is_array($value) ? json_encode($value) : $value;
    }
    
    public function setPegawaiSnapshotAttribute($value)
    {
        $this->attributes['pegawai_snapshot'] = is_array($value) ? json_encode($value) : $value;
    }

    // ========== BOOTED ==========
    
    protected static function booted()
    {
        // Auto create snapshot saat SPT dibuat
        static::creating(function ($spt) {
            $spt->updateSnapshots();
        });
        
        // Auto update snapshot saat SPT diupdate
        static::updating(function ($spt) {
            // Cek apakah ada perubahan pada pegawai atau penanda_tangan
            if ($spt->isDirty('pegawai') || $spt->isDirty('penanda_tangan')) {
                $spt->updateSnapshots();
            }
        });
    }
}