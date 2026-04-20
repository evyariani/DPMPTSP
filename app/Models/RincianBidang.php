<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianBidang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'tb_rincianbidang';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomor',
        'tanggal',
        'tujuan',
        'pegawai',
        'transport',
        'total',
        'terbilang'
    ];

    protected $casts = [
        'pegawai' => 'array',  // JSON otomatis jadi array
        'tanggal' => 'date'     // otomatis jadi Carbon object
    ];

    // Accessor: Total Uang Harian
    public function getTotalUangHarianAttribute()
    {
        $total = 0;
        if ($this->pegawai) {
            foreach ($this->pegawai as $p) {
                $total += ($p['nominal'] ?? 0) * ($p['hari'] ?? 0);
            }
        }
        return $total;
    }

    // Accessor: Total Keseluruhan (Uang Harian + Transport)
    public function getTotalKeseluruhanAttribute()
    {
        return $this->total_uang_harian + ($this->transport ?? 0);
    }

    // ✅ Tambahan opsional: Mendapatkan daftar nama pegawai
    public function getDaftarNamaPegawaiAttribute()
    {
        if (!$this->pegawai) return [];
        return array_column($this->pegawai, 'nama');
    }

    // ✅ Tambahan opsional: Mendapatkan pegawai pertama (bendahara)
    public function getBendaharaAttribute()
    {
        return $this->pegawai[0] ?? null;
    }

    // ✅ Tambahan opsional: Mendapatkan pegawai selain pertama (yang menerima)
    public function getPenerimaAttribute()
    {
        return array_slice($this->pegawai, 1);
    }

    // ✅ Tambahan opsional: Format tanggal Indonesia
    public function getTanggalIndonesiaAttribute()
    {
        if (!$this->tanggal) return '-';
        
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tgl = $this->tanggal->day;
        $bln = $bulan[(int)$this->tanggal->month];
        $thn = $this->tanggal->year;
        
        return "{$tgl} {$bln} {$thn}";
    }
}