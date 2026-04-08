<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'tanggal_dikeluarkan'
    ];

    // Casting tipe data
    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikeluarkan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke pengguna anggaran (tb_pegawai)
    public function penggunaAnggaran()
    {
        return $this->belongsTo(Pegawai::class, 'pengguna_anggaran', 'id_pegawai');
    }

    // Relasi ke tempat tujuan (tb_daerah)
    public function tempatTujuan()
    {
        return $this->belongsTo(Daerah::class, 'tempat_tujuan', 'id');
    }

    // Accessor untuk mendapatkan nama pengguna anggaran
    public function getNamaPenggunaAnggaranAttribute()
    {
        if (empty($this->pengguna_anggaran)) {
            return '-';
        }

        return $this->penggunaAnggaran?->nama ?? '-';
    }

    // Accessor untuk mendapatkan nama tempat tujuan
    public function getNamaTempatTujuanAttribute()
    {
        if (empty($this->tempat_tujuan)) {
            return '-';
        }

        return $this->tempatTujuan?->nama_daerah ?? '-';
    }

    // Accessor untuk mendapatkan label alat transportasi
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

    // Accessor untuk mendapatkan lama perjadin dalam format hari
    public function getLamaPerjadinFormattedAttribute()
    {
        if (empty($this->lama_perjadin)) {
            return '-';
        }

        return $this->lama_perjadin . ' Hari';
    }

    // Accessor untuk mendapatkan rentang tanggal perjadin
    public function getRentangTanggalAttribute()
    {
        $berangkat = $this->tanggal_berangkat ? $this->tanggal_berangkat->format('d/m/Y') : '-';
        $kembali = $this->tanggal_kembali ? $this->tanggal_kembali->format('d/m/Y') : '-';

        return $berangkat . ' s/d ' . $kembali;
    }

    // Scope untuk filter berdasarkan SKPD
    public function scopeBySkpd($query, $skpd)
    {
        if ($skpd) {
            return $query->where('skpd', $skpd);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan rentang tanggal
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan pengguna anggaran
    public function scopeByPenggunaAnggaran($query, $penggunaAnggaranId)
    {
        if ($penggunaAnggaranId) {
            return $query->where('pengguna_anggaran', $penggunaAnggaranId);
        }
        return $query;
    }
}
