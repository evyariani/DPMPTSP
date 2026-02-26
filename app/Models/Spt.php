<?php
// app/Models/Spt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Spt extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_spt';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_spt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomor_surat',
        'dasar_surat',
        'pegawai_yang_diperintahkan',
        'untuk_keperluan',
        'tanggal_surat_dibuat',
        'kota',
        'penandatangan_surat',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pegawai_yang_diperintahkan' => 'array',
        'tanggal_surat_dibuat' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the penandatangan (pegawai) that signs the document.
     */
    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'penandatangan_surat', 'id_pegawai');
    }

    /**
     * Get the list of pegawai names for the yang diperintahkan.
     */
    public function getPegawaiDiperintahkanList()
    {
        $pegawaiIds = $this->pegawai_yang_diperintahkan ?? [];
        return Pegawai::whereIn('id_pegawai', $pegawaiIds)->get();
    }

    /**
     * Accessor untuk menampilkan dasar_surat dalam format yang lebih rapi
     */
    public function getDasarSuratFormattedAttribute(): string
    {
        $poins = explode("\n", $this->dasar_surat);
        $formatted = '';
        foreach ($poins as $poin) {
            if (trim($poin)) {
                $formatted .= '<li>' . htmlspecialchars($poin) . '</li>';
            }
        }
        return $formatted ? '<ul>' . $formatted . '</ul>' : $this->dasar_surat;
    }

    /**
     * 🔥 ACCESSOR AMAN: Mendapatkan nama penandatangan
     */
    public function getPenandatanganNamaAttribute(): string
    {
        try {
            // Cek dari relasi dulu
            if ($this->relationLoaded('penandatangan') && $this->penandatangan) {
                return $this->penandatangan->nama ?? '-';
            }
            
            // Relasi belum di-load, coba load manual
            if ($this->penandatangan_surat) {
                $pegawai = Pegawai::find($this->penandatangan_surat);
                return $pegawai ? ($pegawai->nama ?? '-') : '-';
            }
            
            return '-';
        } catch (\Exception $e) {
            Log::warning('Error getPenandatanganNama: ' . $e->getMessage(), [
                'id_spt' => $this->id_spt,
                'penandatangan_surat' => $this->penandatangan_surat
            ]);
            return '-';
        }
    }

    /**
     * 🔥 ACCESSOR AMAN: Mendapatkan jabatan penandatangan (AMAN DARI NIP NULL)
     */
    public function getPenandatanganJabatanAttribute(): string
    {
        try {
            // Cek dari relasi dulu
            if ($this->relationLoaded('penandatangan') && $this->penandatangan) {
                $jabatan = $this->penandatangan->jabatan ?? '-';
                $nip = $this->penandatangan->nip;
                
                // Tampilkan NIP hanya jika ada dan tidak null
                if (!is_null($nip) && $nip !== '') {
                    return $jabatan . ' (NIP: ' . $nip . ')';
                }
                return $jabatan;
            }
            
            // Relasi belum di-load, coba load manual
            if ($this->penandatangan_surat) {
                $pegawai = Pegawai::find($this->penandatangan_surat);
                if ($pegawai) {
                    $jabatan = $pegawai->jabatan ?? '-';
                    $nip = $pegawai->nip;
                    
                    if (!is_null($nip) && $nip !== '') {
                        return $jabatan . ' (NIP: ' . $nip . ')';
                    }
                    return $jabatan;
                }
            }
            
            return '-';
        } catch (\Exception $e) {
            Log::warning('Error getPenandatanganJabatan: ' . $e->getMessage(), [
                'id_spt' => $this->id_spt,
                'penandatangan_surat' => $this->penandatangan_surat
            ]);
            return '-';
        }
    }

    /**
     * 🔥 ACCESSOR AMAN: Mendapatkan NIP penandatangan
     */
    public function getPenandatanganNipAttribute(): string
    {
        try {
            if ($this->relationLoaded('penandatangan') && $this->penandatangan) {
                return $this->penandatangan->nip ?? '-';
            }
            
            if ($this->penandatangan_surat) {
                $pegawai = Pegawai::find($this->penandatangan_surat);
                return $pegawai ? ($pegawai->nip ?? '-') : '-';
            }
            
            return '-';
        } catch (\Exception $e) {
            return '-';
        }
    }

    /**
     * 🔥 ACCESSOR AMAN: Mendapatkan data lengkap penandatangan
     */
    public function getPenandatanganDataAttribute()
    {
        try {
            if ($this->relationLoaded('penandatangan') && $this->penandatangan) {
                return $this->penandatangan;
            }
            
            if ($this->penandatangan_surat) {
                return Pegawai::find($this->penandatangan_surat);
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 🔥 FALLBACK METHOD: Pengecekan apakah penandatangan valid
     */
    public function hasValidPenandatangan(): bool
    {
        try {
            if ($this->relationLoaded('penandatangan') && $this->penandatangan) {
                return true;
            }
            
            if ($this->penandatangan_surat) {
                return Pegawai::where('id_pegawai', $this->penandatangan_surat)->exists();
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}