<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SPD extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'spd';

    // Tentukan primary key
    protected $primaryKey = 'id_spd';

    // Tipe primary key
    protected $keyType = 'int';

    // Auto increment
    public $incrementing = true;

    // Kolom yang bisa diisi
    protected $fillable = [
        'nomor_surat',
        'pengguna_anggaran',
        'maksud_perjadin',
        'alat_transportasi',
        'tempat_berangkat',
        'tempat_tujuan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'lama_perjadin',
        'skpd',
        'kode_rek',
        'keterangan',
        'tempat_dikeluarkan',
        'tanggal_dikeluarkan',
        // Atribut baru
        'spt_id',
        'pelaksana_snapshot', // TAMBAHKAN: Snapshot pelaksana
        'pejabat_teknis_id',
        'pejabat_teknis_pegawai_id',
        'pejabat_teknis_kode_rekening',
        'pejabat_teknis_program',
        'pejabat_teknis_kegiatan',
        'pejabat_teknis_sub_kegiatan',
        // Penanda Tangan SPD (Eksternal)
        'penanda_tangan_nama',
        'penanda_tangan_nip',
        'penanda_tangan_jabatan',
        'penanda_tangan_instansi',
    ];

    // Casting tipe data
    protected $casts = [
        'pelaksana_snapshot' => 'array', // TAMBAHKAN
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikeluarkan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONS ==========
    
    /**
     * Relasi ke SPT (source - untuk pembuatan otomatis)
     */
    public function spt(): BelongsTo
    {
        return $this->belongsTo(SPT::class, 'spt_id', 'id_spt');
    }
    
    /**
     * Relasi ke pengguna anggaran (tb_pegawai) - Kepala Dinas
     */
    public function penggunaAnggaran(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pengguna_anggaran', 'id_pegawai');
    }

    /**
     * Relasi ke tempat tujuan (tb_daerah)
     */
    public function tempatTujuan(): BelongsTo
    {
        return $this->belongsTo(Daerah::class, 'tempat_tujuan', 'id');
    }
    
    /**
     * Relasi ke pejabat teknis (tb_program)
     */
    public function pejabatTeknis(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'pejabat_teknis_id', 'id_program');
    }
    
    /**
     * Relasi ke pegawai pejabat teknis
     */
    public function pejabatTeknisPegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pejabat_teknis_pegawai_id', 'id_pegawai');
    }
    
    /**
     * Relasi many-to-many ke pelaksana perjalanan dinas
     */
    public function pelaksanaPerjadin(): BelongsToMany
    {
        return $this->belongsToMany(
            Pegawai::class,
            'spd_pelaksana',
            'spd_id',
            'pegawai_id',
            'id_spd',
            'id_pegawai'
        )->withTimestamps();
    }
    
    /**
     * Relasi one-to-one ke RincianBidang
     */
    public function rincianBidang(): HasOne
    {
        return $this->hasOne(RincianBidang::class, 'spd_id', 'id_spd');
    }

    // ========== ACCESSORS PENANDA TANGAN ==========
    
    /**
     * Mendapatkan informasi lengkap penanda tangan dalam satu string
     * Gunakan: $spd->penanda_tangan_lengkap
     */
    public function getPenandaTanganLengkapAttribute()
    {
        $parts = [];
        if ($this->penanda_tangan_nama) $parts[] = $this->penanda_tangan_nama;
        if ($this->penanda_tangan_nip) $parts[] = "NIP: {$this->penanda_tangan_nip}";
        if ($this->penanda_tangan_jabatan) $parts[] = $this->penanda_tangan_jabatan;
        if ($this->penanda_tangan_instansi) $parts[] = $this->penanda_tangan_instansi;
        return implode(' | ', $parts);
    }
    
    /**
     * Mendapatkan format penanda tangan untuk tampilan (dengan baris baru)
     * Gunakan: $spd->penanda_tangan_formatted
     */
    public function getPenandaTanganFormattedAttribute()
    {
        $nama = $this->penanda_tangan_nama ?? '-';
        $nip = $this->penanda_tangan_nip ? "NIP. {$this->penanda_tangan_nip}" : '';
        $jabatan = $this->penanda_tangan_jabatan ?? '';
        $instansi = $this->penanda_tangan_instansi ?? '';
        
        $result = $nama;
        if ($nip) $result .= "\n{$nip}";
        if ($jabatan) $result .= "\n{$jabatan}";
        if ($instansi) $result .= "\n{$instansi}";
        
        return nl2br(e($result));
    }
    
    /**
     * Cek apakah SPD memiliki data penanda tangan
     * Gunakan: $spd->has_penanda_tangan
     */
    public function getHasPenandaTanganAttribute()
    {
        return !empty($this->penanda_tangan_nama);
    }

    // ========== ACCESSORS PELAKSANA (MENGGUNAKAN SNAPSHOT) ==========
    
    /**
     * Mendapatkan daftar pelaksana dari snapshot (data saat SPD dibuat)
     * Gunakan: $spd->pelaksana_dari_snapshot
     */
    public function getPelaksanaDariSnapshotAttribute()
    {
        if (empty($this->pelaksana_snapshot)) {
            return collect([]);
        }
        
        return collect($this->pelaksana_snapshot)->map(function ($item) {
            return (object) $item;
        });
    }
    
    /**
     * Mendapatkan nama-nama pelaksana dari snapshot
     * Gunakan: $spd->nama_pelaksana_dari_snapshot
     */
    public function getNamaPelaksanaDariSnapshotAttribute()
    {
        if (empty($this->pelaksana_snapshot)) {
            return '-';
        }
        
        $namaList = array_column($this->pelaksana_snapshot, 'nama');
        return implode(', ', $namaList);
    }

    // ========== ACCESSORS LAINNYA ==========
    
    /**
     * Accessor untuk mendapatkan nomor surat SPT asal
     * Gunakan: $spd->nomor_surat_spt_asal
     */
    public function getNomorSuratSptAsalAttribute()
    {
        if (empty($this->spt_id)) {
            return '-';
        }
        return $this->spt?->nomor_surat ?? '-';
    }
    
    /**
     * Accessor untuk mendapatkan nama pengguna anggaran
     * Gunakan: $spd->nama_pengguna_anggaran
     */
    public function getNamaPenggunaAnggaranAttribute()
    {
        if (empty($this->pengguna_anggaran)) {
            return '-';
        }

        return $this->penggunaAnggaran?->nama ?? '-';
    }

    /**
     * Accessor untuk mendapatkan nama tempat tujuan
     * Gunakan: $spd->nama_tempat_tujuan
     */
    public function getNamaTempatTujuanAttribute()
    {
        if (empty($this->tempat_tujuan)) {
            return '-';
        }

        return $this->tempatTujuan?->nama ?? '-';
    }
    
    /**
     * Accessor untuk mendapatkan nama pejabat teknis
     * Gunakan: $spd->nama_pejabat_teknis
     */
    public function getNamaPejabatTeknisAttribute()
    {
        if (empty($this->pejabat_teknis_pegawai_id)) {
            return '-';
        }
        
        return $this->pejabatTeknisPegawai?->nama ?? '-';
    }
    
    /**
     * Accessor untuk mendapatkan nip pejabat teknis
     * Gunakan: $spd->nip_pejabat_teknis
     */
    public function getNipPejabatTeknisAttribute()
    {
        if (empty($this->pejabat_teknis_pegawai_id)) {
            return '-';
        }
        
        return $this->pejabatTeknisPegawai?->nip ?? '-';
    }
    
    /**
     * Accessor untuk mendapatkan jabatan pejabat teknis
     * Gunakan: $spd->jabatan_pejabat_teknis
     */
    public function getJabatanPejabatTeknisAttribute()
    {
        if (empty($this->pejabat_teknis_pegawai_id)) {
            return '-';
        }
        
        return $this->pejabatTeknisPegawai?->jabatan ?? '-';
    }
    
    /**
     * Accessor untuk mendapatkan data lengkap pejabat teknis
     * Gunakan: $spd->data_pejabat_teknis
     */
    public function getDataPejabatTeknisAttribute()
    {
        if (empty($this->pejabat_teknis_id)) {
            return null;
        }
        
        return (object) [
            'id_program' => $this->pejabat_teknis_id,
            'program' => $this->pejabat_teknis_program,
            'kegiatan' => $this->pejabat_teknis_kegiatan,
            'sub_kegiatan' => $this->pejabat_teknis_sub_kegiatan,
            'kode_rekening' => $this->pejabat_teknis_kode_rekening,
            'pegawai_id' => $this->pejabat_teknis_pegawai_id,
            'pegawai_nama' => $this->nama_pejabat_teknis,
            'pegawai_nip' => $this->nip_pejabat_teknis,
            'pegawai_jabatan' => $this->jabatan_pejabat_teknis,
        ];
    }
    
    /**
     * Accessor untuk mendapatkan daftar pelaksana perjadin (dari relasi - HATI-HATI bisa berubah)
     * @deprecated Gunakan pelaksana_dari_snapshot untuk tampilan
     */
    public function getDaftarPelaksanaAttribute()
    {
        return $this->pelaksanaPerjadin()->get();
    }
    
    /**
     * Accessor untuk mendapatkan nama-nama pelaksana perjadin (dari relasi - HATI-HATI bisa berubah)
     * @deprecated Gunakan nama_pelaksana_dari_snapshot untuk tampilan
     */
    public function getNamaPelaksanaPerjadinAttribute()
    {
        $pelaksana = $this->pelaksanaPerjadin;
        if ($pelaksana->isEmpty()) {
            return '-';
        }
        
        return $pelaksana->pluck('nama')->implode(', ');
    }
    
    /**
     * Accessor untuk mendapatkan jumlah pelaksana
     * Gunakan: $spd->jumlah_pelaksana
     */
    public function getJumlahPelaksanaAttribute()
    {
        return $this->pelaksanaPerjadin()->count();
    }

    /**
     * Accessor untuk mendapatkan label alat transportasi
     * Gunakan: $spd->label_alat_transportasi
     */
    public function getLabelAlatTransportasiAttribute()
    {
        $labels = [
            'transportasi_darat' => 'Transportasi Darat',
            'transportasi_udara' => 'Transportasi Udara',
            'transportasi_darat_udara' => 'Transportasi Darat & Udara',
            'angkutan_darat' => 'Angkutan Darat',
            'kendaraan_dinas' => 'Kendaraan Dinas',
            'angkutan_umum' => 'Angkutan Umum'
        ];

        return $labels[$this->alat_transportasi] ?? $this->alat_transportasi;
    }

    /**
     * Accessor untuk mendapatkan lama perjadin dalam format hari
     * Gunakan: $spd->lama_perjadin_formatted
     */
    public function getLamaPerjadinFormattedAttribute()
    {
        if (empty($this->lama_perjadin)) {
            return '-';
        }

        return $this->lama_perjadin . ' Hari';
    }

    /**
     * Accessor untuk mendapatkan rentang tanggal perjadin
     * Gunakan: $spd->rentang_tanggal
     */
    public function getRentangTanggalAttribute()
    {
        $berangkat = $this->tanggal_berangkat ? $this->tanggal_berangkat->format('d/m/Y') : '-';
        $kembali = $this->tanggal_kembali ? $this->tanggal_kembali->format('d/m/Y') : '-';

        return $berangkat . ' s/d ' . $kembali;
    }
    
    /**
     * Accessor untuk mendapatkan kode rekening dari pejabat teknis
     * Gunakan: $spd->kode_rekening_teknis
     */
    public function getKodeRekeningTeknisAttribute()
    {
        return $this->pejabat_teknis_kode_rekening ?? $this->kode_rek;
    }
    
    /**
     * Accessor untuk mengecek apakah SPD dibuat dari SPT
     * Gunakan: $spd->is_dari_spt
     */
    public function getIsDariSptAttribute()
    {
        return !empty($this->spt_id);
    }

    // ========== SCOPES ==========
    
    /**
     * Scope untuk filter berdasarkan SKPD
     */
    public function scopeBySkpd($query, $skpd)
    {
        if ($skpd) {
            return $query->where('skpd', $skpd);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan pengguna anggaran
     */
    public function scopeByPenggunaAnggaran($query, $penggunaAnggaranId)
    {
        if ($penggunaAnggaranId) {
            return $query->where('pengguna_anggaran', $penggunaAnggaranId);
        }
        return $query;
    }
    
    /**
     * Scope untuk filter berdasarkan pejabat teknis
     */
    public function scopeByPejabatTeknis($query, $pejabatTeknisId)
    {
        if ($pejabatTeknisId) {
            return $query->where('pejabat_teknis_pegawai_id', $pejabatTeknisId);
        }
        return $query;
    }
    
    /**
     * Scope untuk filter berdasarkan program
     */
    public function scopeByProgram($query, $programId)
    {
        if ($programId) {
            return $query->where('pejabat_teknis_id', $programId);
        }
        return $query;
    }
    
    /**
     * Scope untuk filter berdasarkan pelaksana
     */
    public function scopeByPelaksana($query, $pelaksanaId)
    {
        if ($pelaksanaId) {
            return $query->whereHas('pelaksanaPerjadin', function ($q) use ($pelaksanaId) {
                $q->where('pegawai_id', $pelaksanaId);
            });
        }
        return $query;
    }
    
    /**
     * Scope untuk filter berdasarkan SPT asal
     */
    public function scopeBySpt($query, $sptId)
    {
        if ($sptId) {
            return $query->where('spt_id', $sptId);
        }
        return $query;
    }
    
    /**
     * Scope untuk mengambil SPD yang dibuat dari SPT
     */
    public function scopeDariSpt($query)
    {
        return $query->whereNotNull('spt_id');
    }
    
    /**
     * Scope untuk mengambil SPD yang dibuat manual (bukan dari SPT)
     */
    public function scopeManual($query)
    {
        return $query->whereNull('spt_id');
    }
    
    /**
     * Scope untuk filter berdasarkan penanda tangan
     */
    public function scopeByPenandaTangan($query, $nama)
    {
        if ($nama) {
            return $query->where('penanda_tangan_nama', 'like', "%{$nama}%");
        }
        return $query;
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Sync pelaksana perjalanan dinas dan update snapshot
     */
    public function syncPelaksana(array $pegawaiIds)
    {
        $result = $this->pelaksanaPerjadin()->sync($pegawaiIds);
        
        // Update snapshot setelah sync pelaksana
        $this->createPelaksanaSnapshot();
        $this->saveQuietly();
        
        return $result;
    }
    
    /**
     * Membuat snapshot pelaksana dari data pegawai yang terkait
     * Simpan data lengkap pegawai saat SPD dibuat/diupdate
     */
    public function createPelaksanaSnapshot()
    {
        $pelaksana = $this->pelaksanaPerjadin()->get();
        $snapshot = [];
        
        foreach ($pelaksana as $pegawai) {
            $snapshot[] = [
                'id_pegawai' => $pegawai->id_pegawai,
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'pangkat' => $pegawai->pangkat ?? '-',
                'gol' => $pegawai->gol ?? '-',
            ];
        }
        
        $this->pelaksana_snapshot = $snapshot;
        
        return $this;
    }
    
    /**
     * Set pejabat teknis dari program
     */
    public function setPejabatTeknisFromProgram(Program $program)
    {
        $this->pejabat_teknis_id = $program->id_program;
        $this->pejabat_teknis_pegawai_id = $program->id_pegawai;
        $this->pejabat_teknis_kode_rekening = $program->kode_rekening;
        $this->pejabat_teknis_program = $program->program;
        $this->pejabat_teknis_kegiatan = $program->kegiatan;
        $this->pejabat_teknis_sub_kegiatan = $program->sub_kegiatan;
        
        return $this;
    }
    
    /**
     * Set penanda tangan (eksternal)
     */
    public function setPenandaTangan($nama, $nip = null, $jabatan = null, $instansi = null)
    {
        $this->penanda_tangan_nama = $nama;
        $this->penanda_tangan_nip = $nip;
        $this->penanda_tangan_jabatan = $jabatan;
        $this->penanda_tangan_instansi = $instansi;
        
        return $this;
    }
    
    /**
     * Cek apakah SPD memiliki pelaksana
     */
    public function hasPelaksana(): bool
    {
        return $this->pelaksanaPerjadin()->count() > 0;
    }
    
    /**
     * Cek apakah SPD memiliki pejabat teknis
     */
    public function hasPejabatTeknis(): bool
    {
        return !empty($this->pejabat_teknis_id);
    }
    
    /**
     * Cek apakah SPD memiliki penanda tangan
     */
    public function hasPenandaTangan(): bool
    {
        return !empty($this->penanda_tangan_nama);
    }
    
    /**
     * Cek apakah SPD dibuat dari SPT
     */
    public function isFromSpt(): bool
    {
        return !empty($this->spt_id);
    }
    
    /**
     * Sync/Update RincianBidang otomatis
     */
    public function syncRincianBidang(array $additionalData = []): RincianBidang
    {
        return RincianBidang::syncFromSpd($this, $additionalData);
    }
    
    /**
     * Override booted method untuk auto sync RincianBidang dan snapshot
     */
    protected static function booted()
    {
        // Event saat SPD dibuat
        static::created(function ($spd) {
            // Buat snapshot pelaksana
            $spd->createPelaksanaSnapshot();
            $spd->saveQuietly();
            
            // Sync RincianBidang
            $spd->syncRincianBidang();
        });
        
        // Event saat SPD diupdate
        static::updated(function ($spd) {
            // Cek apakah ada perubahan pada pelaksana
            if ($spd->isDirty('pelaksana_perjadin')) {
                $spd->createPelaksanaSnapshot();
            }
            
            // Cek apakah ada perubahan pada data yang mempengaruhi RincianBidang
            $dirtyFields = ['nomor_surat', 'tempat_tujuan', 'tanggal_berangkat', 'tanggal_kembali', 'lama_perjadin'];
            $isRelatedChanged = false;
            
            foreach ($dirtyFields as $field) {
                if ($spd->isDirty($field)) {
                    $isRelatedChanged = true;
                    break;
                }
            }
            
            if ($spd->isDirty('pelaksana_perjadin') || $isRelatedChanged) {
                $spd->syncRincianBidang();
            }
        });
    }
    
    /**
     * Ambil point pertama dari tujuan SPT untuk dijadikan maksud perjadin
     */
    public static function extractMaksudPerjadinFromSptTujuan($tujuan)
    {
        if (empty($tujuan)) {
            return '';
        }
        
        // Pisahkan berdasarkan baris baru
        if (strpos($tujuan, "\n") !== false) {
            $lines = explode("\n", $tujuan);
            return trim($lines[0]);
        }
        
        // Pisahkan berdasarkan titik
        if (strpos($tujuan, ".") !== false) {
            $firstPoint = substr($tujuan, 0, strpos($tujuan, "."));
            return trim($firstPoint);
        }
        
        return trim($tujuan);
    }
    
    /**
     * Buat SPD dari data SPT
     */
    public static function createFromSpt(SPT $spt, array $additionalData = [])
    {
        // Ekstrak maksud perjadin dari tujuan SPT
        $maksudPerjadin = self::extractMaksudPerjadinFromSptTujuan($spt->tujuan);
        
        // Ambil daftar pegawai dari SPT untuk dijadikan pelaksana (gunakan snapshot SPT)
        $pelaksanaIds = [];
        $pegawaiList = $spt->pegawai_list_from_snapshot;
        if ($pegawaiList && count($pegawaiList) > 0) {
            foreach ($pegawaiList as $pegawai) {
                $pelaksanaIds[] = $pegawai->id_pegawai;
            }
        }
        
        // Data dasar SPD
        $data = array_merge([
            'spt_id' => $spt->id_spt,
            'maksud_perjadin' => $maksudPerjadin,
            'tanggal_berangkat' => $spt->tanggal,
            'tanggal_kembali' => $spt->tanggal,
            'tempat_berangkat' => $spt->lokasi ?? 'Pelaihari',
            'tempat_dikeluarkan' => 'Pelaihari',
            'tanggal_dikeluarkan' => now(),
            'skpd' => 'Dinas Penanaman Modal dan PTSP Kabupaten Tanah Laut',
            'lama_perjadin' => 1,
            'keterangan' => "Dibuat otomatis dari SPT Nomor: {$spt->nomor_surat}",
        ], $additionalData);
        
        // Buat SPD
        $spd = self::create($data);
        
        // Sync pelaksana
        if (!empty($pelaksanaIds)) {
            $spd->syncPelaksana($pelaksanaIds);
        }
        
        // Snapshot pelaksana akan otomatis dibuat via booted created event
        // RincianBidang akan otomatis dibuat via booted created event
        return $spd;
    }
}