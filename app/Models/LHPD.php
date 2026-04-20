<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Lhpd extends Model
{
    use HasFactory;

    protected $table = 'tb_lhpd';
    protected $primaryKey = 'id_lhpd';

    protected $fillable = [
        'spt_id',  // TAMBAHKAN INI
        'dasar',
        'tujuan',
        'id_pegawai',
        'pegawai_snapshot',
        'tanggal_berangkat',
        'id_daerah',
        'tempat_tujuan_snapshot',
        'hasil',
        'tempat_dikeluarkan',
        'tempat_dikeluarkan_snapshot',
        'tanggal_lhpd',
        'foto',
        'uang_harian_snapshot',
        'uang_transport_snapshot',
        'total_biaya_snapshot'
    ];

    protected $casts = [
        'dasar' => 'array',
        'id_pegawai' => 'array',
        'pegawai_snapshot' => 'array',
        'foto' => 'array',
        'tanggal_berangkat' => 'date',
        'tanggal_lhpd' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONS ==========
    
    /**
     * Relasi ke SPT (source)
     */
    public function spt(): BelongsTo
    {
        return $this->belongsTo(SPT::class, 'spt_id', 'id_spt');
    }
    
    /**
     * Relasi ke tabel tb_daerah (daerah tujuan dari SPD)
     * Hanya untuk referensi, jangan gunakan untuk tampilan
     */
    public function daerahTujuan()
    {
        return $this->belongsTo(Daerah::class, 'id_daerah', 'id');
    }

    /**
     * Relasi ke tabel tb_daerah (tempat LHPD dikeluarkan)
     * Hanya untuk referensi, jangan gunakan untuk tampilan
     */
    public function tempatDikeluarkan()
    {
        return $this->belongsTo(Daerah::class, 'tempat_dikeluarkan', 'id');
    }

    // ========== ACCESSORS (MENGGUNAKAN SNAPSHOT) ==========
    
    /**
     * Mendapatkan data pegawai dari snapshot (data saat LHPD dibuat)
     * Gunakan: $lhpd->pegawai_list_from_snapshot
     */
    public function getPegawaiListFromSnapshotAttribute()
    {
        if (empty($this->pegawai_snapshot)) {
            return collect();
        }
        
        return collect($this->pegawai_snapshot)->map(function ($item) {
            return (object) $item;
        });
    }
    
    /**
     * Mendapatkan data pegawai (dari relasi - HATI-HATI bisa berubah)
     * @deprecated Gunakan getPegawaiListFromSnapshotAttribute untuk tampilan
     */
    public function getPegawaiListAttribute()
    {
        if (empty($this->id_pegawai)) {
            return collect();
        }
        
        $pegawaiIds = is_array($this->id_pegawai) ? $this->id_pegawai : json_decode($this->id_pegawai, true);
        
        if (empty($pegawaiIds)) {
            return collect();
        }
        
        return Pegawai::whereIn('id_pegawai', $pegawaiIds)->get();
    }

    /**
     * Mendapatkan data dasar dari JSON
     */
    public function getDasarListAttribute()
    {
        if (empty($this->dasar)) {
            return collect();
        }
        
        return is_array($this->dasar) ? collect($this->dasar) : collect(json_decode($this->dasar, true));
    }
    
    /**
     * Mendapatkan nama tempat tujuan dari snapshot
     */
    public function getNamaTempatTujuanAttribute()
    {
        return $this->tempat_tujuan_snapshot ?? ($this->daerahTujuan?->nama ?? '-');
    }
    
    /**
     * Mendapatkan nama tempat dikeluarkan dari snapshot
     */
    public function getNamaTempatDikeluarkanAttribute()
    {
        return $this->tempat_dikeluarkan_snapshot ?? ($this->tempatDikeluarkan?->nama ?? '-');
    }

    // ========== ACCESSORS FOTO ==========
    
    /**
     * Accessor untuk mendapatkan URL foto lengkap
     */
    public function getFotoUrlsAttribute()
    {
        if (empty($this->foto)) {
            return collect();
        }
        
        $fotos = is_array($this->foto) ? $this->foto : json_decode($this->foto, true);
        
        if (empty($fotos)) {
            return collect();
        }
        
        return collect($fotos)->map(function ($foto) {
            return Storage::url($foto);
        });
    }

    /**
     * Accessor untuk mendapatkan foto pertama (thumbnail)
     */
    public function getFirstFotoUrlAttribute()
    {
        if (empty($this->foto)) {
            return null;
        }
        
        $fotos = is_array($this->foto) ? $this->foto : json_decode($this->foto, true);
        
        if (empty($fotos)) {
            return null;
        }
        
        return Storage::url($fotos[0]);
    }

    /**
     * Accessor untuk mendapatkan jumlah foto
     */
    public function getFotoCountAttribute()
    {
        if (empty($this->foto)) {
            return 0;
        }
        
        $fotos = is_array($this->foto) ? $this->foto : json_decode($this->foto, true);
        
        return count($fotos);
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Membuat snapshot pegawai dari array ID pegawai
     */
    public function createPegawaiSnapshot()
    {
        if (empty($this->id_pegawai)) {
            $this->pegawai_snapshot = [];
            return;
        }
        
        $pegawaiIds = is_array($this->id_pegawai) ? $this->id_pegawai : json_decode($this->id_pegawai, true);
        
        if (empty($pegawaiIds)) {
            $this->pegawai_snapshot = [];
            return;
        }
        
        $pegawaiList = Pegawai::whereIn('id_pegawai', $pegawaiIds)->get();
        
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
     * Membuat snapshot tempat tujuan dari ID daerah
     */
    public function createTempatTujuanSnapshot()
    {
        if (empty($this->id_daerah)) {
            $this->tempat_tujuan_snapshot = null;
            return;
        }
        
        $daerah = Daerah::find($this->id_daerah);
        if ($daerah) {
            $this->tempat_tujuan_snapshot = $daerah->nama;
        }
    }
    
    /**
     * Membuat snapshot tempat dikeluarkan dari ID daerah
     */
    public function createTempatDikeluarkanSnapshot()
    {
        if (empty($this->tempat_dikeluarkan)) {
            $this->tempat_dikeluarkan_snapshot = null;
            return;
        }
        
        $daerah = Daerah::find($this->tempat_dikeluarkan);
        if ($daerah) {
            $this->tempat_dikeluarkan_snapshot = $daerah->nama;
        }
    }
    
    /**
     * Update semua snapshot (dipanggil saat create/update)
     */
    public function updateSnapshots()
    {
        $this->createPegawaiSnapshot();
        $this->createTempatTujuanSnapshot();
        $this->createTempatDikeluarkanSnapshot();
    }

    // ========== SCOPES ==========
    
    /**
     * Scope filter berdasarkan SPT ID
     */
    public function scopeBySptId($query, $sptId)
    {
        return $query->where('spt_id', $sptId);
    }
    
    /**
     * Scope filter berdasarkan tanggal LHPD
     */
    public function scopeByTanggalLhpd($query, $tanggal)
    {
        return $query->whereDate('tanggal_lhpd', $tanggal);
    }

    /**
     * Scope filter berdasarkan rentang tanggal berangkat
     */
    public function scopeByTanggalBerangkat($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
        }
        return $query->whereDate('tanggal_berangkat', $startDate);
    }

    /**
     * Scope filter berdasarkan daerah tujuan
     */
    public function scopeByDaerahTujuan($query, $daerahId)
    {
        return $query->where('id_daerah', $daerahId);
    }

    /**
     * Scope filter berdasarkan tempat dikeluarkan
     */
    public function scopeByTempatDikeluarkan($query, $tempatId)
    {
        return $query->where('tempat_dikeluarkan', $tempatId);
    }
    
    // ========== BOOTED ==========
    
    protected static function booted()
    {
        // Auto create snapshot saat LHPD dibuat
        static::creating(function ($lhpd) {
            $lhpd->updateSnapshots();
        });
        
        // Auto update snapshot saat LHPD diupdate
        static::updating(function ($lhpd) {
            if ($lhpd->isDirty('id_pegawai') || 
                $lhpd->isDirty('id_daerah') || 
                $lhpd->isDirty('tempat_dikeluarkan')) {
                $lhpd->updateSnapshots();
            }
        });
    }
}