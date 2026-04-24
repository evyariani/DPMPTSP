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
        'spt_id',
        'dasar',                    // INPUT MANUAL, bukan dari SPT
        'tujuan',                   // snapshot dari SPT
        'pegawai_snapshot',         // snapshot dari SPT
        'tanggal_berangkat',        // snapshot dari SPD
        'id_daerah',                // referensi daerah tujuan
        'tempat_tujuan_snapshot',   // snapshot nama tempat tujuan
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
        'dasar' => 'array',              // JSON untuk multiple dasar
        'pegawai_snapshot' => 'array',
        'foto' => 'array',
        'tanggal_berangkat' => 'date',
        'tanggal_lhpd' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONS ==========
    
    /**
     * Relasi ke SPT (sumber data)
     */
    public function spt(): BelongsTo
    {
        return $this->belongsTo(SPT::class, 'spt_id', 'id_spt');
    }
    
    /**
     * Relasi ke daerah tujuan (referensi, gunakan snapshot untuk tampilan)
     */
    public function daerahTujuan(): BelongsTo
    {
        return $this->belongsTo(Daerah::class, 'id_daerah', 'id');
    }

    /**
     * Relasi ke tempat dikeluarkan (referensi, gunakan snapshot untuk tampilan)
     */
    public function tempatDikeluarkan(): BelongsTo
    {
        return $this->belongsTo(Daerah::class, 'tempat_dikeluarkan', 'id');
    }

    // ========== ACCESSORS - DASAR (INPUT MANUAL) ==========
    
    /**
     * Mendapatkan daftar dasar perjalanan dari input manual LHPD
     * Gunakan: $lhpd->dasar_list
     */
    public function getDasarListAttribute()
    {
        if (empty($this->dasar)) {
            return collect();
        }
        
        $dasar = is_array($this->dasar) ? $this->dasar : json_decode($this->dasar, true);
        
        return collect($dasar);
    }
    
    /**
     * Menampilkan dasar sebagai teks dengan pemisah
     * Gunakan: $lhpd->dasar_text
     */
    public function getDasarTextAttribute(): string
    {
        $dasarList = $this->getDasarListAttribute();
        
        if ($dasarList->isEmpty()) {
            return '-';
        }
        
        return $dasarList->implode(', ');
    }
    
    /**
     * Menampilkan dasar sebagai list HTML
     * Gunakan: {!! $lhpd->dasar_html !!}
     */
    public function getDasarHtmlAttribute(): string
    {
        $dasarList = $this->getDasarListAttribute();
        
        if ($dasarList->isEmpty()) {
            return '-';
        }
        
        $html = '<ul class="list-disc list-inside">';
        foreach ($dasarList as $item) {
            $html .= '<li>' . e($item) . '</li>';
        }
        $html .= '</ul>';
        
        return $html;
    }

    // ========== ACCESSORS - PEGAWAI SNAPSHOT ==========
    
    /**
     * Mendapatkan data pegawai dari snapshot
     * Gunakan: $lhpd->pegawai_list
     */
    public function getPegawaiListAttribute()
    {
        if (empty($this->pegawai_snapshot)) {
            return collect();
        }
        
        $pegawai = is_array($this->pegawai_snapshot) 
            ? $this->pegawai_snapshot 
            : json_decode($this->pegawai_snapshot, true);
        
        return collect($pegawai)->map(function ($item) {
            return (object) $item;
        });
    }
    
    /**
     * Mendapatkan nama-nama pegawai dari snapshot (untuk ditampilkan)
     * Gunakan: $lhpd->nama_pegawai
     */
    public function getNamaPegawaiAttribute(): string
    {
        $pegawaiList = $this->getPegawaiListAttribute();
        
        if ($pegawaiList->isEmpty()) {
            return '-';
        }
        
        return $pegawaiList->map(function ($pegawai) {
            return $pegawai->nama ?? $pegawai['nama'] ?? '-';
        })->implode(', ');
    }

    // ========== ACCESSORS - TEMPAT & TUJUAN ==========
    
    /**
     * Mendapatkan nama tempat tujuan (prioritas snapshot)
     * Gunakan: $lhpd->tempat_tujuan
     */
    public function getTempatTujuanAttribute(): string
    {
        return $this->tempat_tujuan_snapshot ?? ($this->daerahTujuan?->nama ?? '-');
    }
    
    /**
     * Mendapatkan nama tempat dikeluarkan (prioritas snapshot)
     * Gunakan: $lhpd->tempat_dikeluarkan_nama
     */
    public function getTempatDikeluarkanNamaAttribute(): string
    {
        return $this->tempat_dikeluarkan_snapshot ?? ($this->tempatDikeluarkan?->nama ?? '-');
    }

    // ========== ACCESSORS - FOTO ==========
    
    /**
     * Mendapatkan URL foto lengkap
     * Gunakan: $lhpd->foto_urls
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
            // Cek apakah sudah ada storage path atau raw path
            if (str_starts_with($foto, 'http')) {
                return $foto;
            }
            return Storage::url($foto);
        });
    }

    /**
     * Mendapatkan foto pertama (thumbnail)
     * Gunakan: $lhpd->first_foto_url
     */
    public function getFirstFotoUrlAttribute(): ?string
    {
        $urls = $this->getFotoUrlsAttribute();
        
        return $urls->isNotEmpty() ? $urls->first() : null;
    }

    /**
     * Mendapatkan jumlah foto
     * Gunakan: $lhpd->foto_count
     */
    public function getFotoCountAttribute(): int
    {
        if (empty($this->foto)) {
            return 0;
        }
        
        $fotos = is_array($this->foto) ? $this->foto : json_decode($this->foto, true);
        
        return count($fotos);
    }

    // ========== ACCESSORS - BIAYA ==========
    
    /**
     * Format uang harian dengan rupiah
     */
    public function getUangHarianRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->uang_harian_snapshot, 0, ',', '.');
    }
    
    /**
     * Format uang transport dengan rupiah
     */
    public function getUangTransportRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->uang_transport_snapshot, 0, ',', '.');
    }
    
    /**
     * Format total biaya dengan rupiah
     */
    public function getTotalBiayaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya_snapshot, 0, ',', '.');
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
    
    /**
     * Scope untuk pencarian teks (dasar, tujuan, hasil)
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }
        
        return $query->where(function ($q) use ($search) {
            $q->where('tujuan', 'like', "%{$search}%")
              ->orWhere('hasil', 'like', "%{$search}%")
              ->orWhere('tempat_tujuan_snapshot', 'like', "%{$search}%")
              ->orWhere('tempat_dikeluarkan_snapshot', 'like', "%{$search}%")
              ->orWhereJsonContains('dasar', $search);
        });
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Cek apakah LHPD sudah lengkap datanya
     */
    public function isComplete(): bool
    {
        return !empty($this->hasil) 
            && !empty($this->tanggal_lhpd)
            && !empty($this->tempat_dikeluarkan_snapshot);
    }
    
    /**
     * Mendapatkan total hari perjalanan (jika ada tanggal berangkat dan tanggal lhpd)
     */
    public function getTotalHariAttribute(): ?int
    {
        if (empty($this->tanggal_berangkat) || empty($this->tanggal_lhpd)) {
            return null;
        }
        
        return $this->tanggal_berangkat->diffInDays($this->tanggal_lhpd) + 1;
    }
}