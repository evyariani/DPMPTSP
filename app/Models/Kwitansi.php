<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Kwitansi extends Model
{
    use SoftDeletes;
    
    protected $table = 'kwitansi';
    
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'spd_id',  // ✅ KEMBALI KE spd_id
        'tahun_anggaran',
        'kode_rekening',
        'sub_kegiatan',
        'no_bku',
        'no_brpp',
        'terbilang',
        'untuk_pembayaran',
        'nominal',
        'tanggal_kwitansi',
        'pengguna_anggaran',
        'nip_pengguna_anggaran',
        'bendahara_pengeluaran',
        'nip_bendahara',
        'penerima',
        'nip_penerima',
    ];
    
    protected $dates = ['tanggal_kwitansi', 'deleted_at', 'created_at', 'updated_at'];
    
    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_kwitansi' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // ========== RELATIONS ==========
    
    public function spd()
    {
        // ✅ KEMBALI KE spd_id
        return $this->belongsTo(SPD::class, 'spd_id', 'id_spd');
    }
    
    // ========== ACCESSORS ==========
    
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
    
    public function getNominalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
    
    public function getTanggalKwitansiIndonesiaAttribute()
    {
        if (!$this->tanggal_kwitansi) return '-';
        return $this->tanggal_kwitansi->translatedFormat('d F Y');
    }
    
    public function getTanggalKwitansiFormattedAttribute()
    {
        if (!$this->tanggal_kwitansi) return '-';
        return $this->tanggal_kwitansi->format('d/m/Y');
    }
    
    // ========== SCOPES ==========
    
    public function scopeBySpd($query, $spdId)
    {
        if ($spdId) {
            return $query->where('spd_id', $spdId);  // ✅ KEMBALI KE spd_id
        }
        return $query;
    }
    
    public function scopeByTahunAnggaran($query, $tahun)
    {
        if ($tahun) {
            return $query->where('tahun_anggaran', $tahun);
        }
        return $query;
    }
    
    public function scopeByNoBku($query, $noBku)
    {
        if ($noBku) {
            return $query->where('no_bku', 'like', "%{$noBku}%");
        }
        return $query;
    }
    
    public function scopeByKodeRekening($query, $kodeRekening)
    {
        if ($kodeRekening) {
            return $query->where('kode_rekening', 'like', "%{$kodeRekening}%");
        }
        return $query;
    }
    
    public function scopeByPenerima($query, $penerima)
    {
        if ($penerima) {
            return $query->where('penerima', 'like', "%{$penerima}%");
        }
        return $query;
    }
    
    // ========== HELPER METHODS ==========
    
    public static function getDefaultBendahara()
    {
        return Pegawai::where('jabatan', 'like', '%Bendahara%Pengeluaran%')
            ->orWhere('jabatan', 'like', '%Bendahara%')
            ->orWhere('jabatan', 'like', '%bendahara%')
            ->first();
    }
    
    private static function generateNoBku($spd)
    {
        $tahun = date('Y', strtotime($spd->tanggal_berangkat));
        $bulan = date('m', strtotime($spd->tanggal_berangkat));
        $count = self::whereYear('tanggal_kwitansi', $tahun)
            ->whereMonth('tanggal_kwitansi', $bulan)
            ->count() + 1;
        return sprintf("%03d/SPJ/GU-%s/%s", $count, $bulan, $tahun);
    }
    
    private static function terbilangStatic($angka): string
    {
        if (!$angka || $angka == 0) return 'Nol Rupiah';
        
        $angka = (int) $angka;
        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        
        $konversi = function($n) use ($satuan, &$konversi) {
            if ($n < 10) return $satuan[$n];
            if ($n < 20) {
                if ($n == 10) return 'Sepuluh';
                if ($n == 11) return 'Sebelas';
                return $satuan[$n - 10] . ' Belas';
            }
            if ($n < 100) {
                $puluh = floor($n / 10);
                $sisa = $n % 10;
                if ($sisa == 0) return $satuan[$puluh] . ' Puluh';
                return $satuan[$puluh] . ' Puluh ' . $konversi($sisa);
            }
            if ($n < 200) return 'Seratus ' . $konversi($n - 100);
            if ($n < 1000) {
                $ratus = floor($n / 100);
                $sisa = $n % 100;
                if ($sisa == 0) return $satuan[$ratus] . ' Ratus';
                return $satuan[$ratus] . ' Ratus ' . $konversi($sisa);
            }
            if ($n < 2000) return 'Seribu ' . $konversi($n - 1000);
            if ($n < 1000000) {
                $ribu = floor($n / 1000);
                $sisa = $n % 1000;
                if ($sisa == 0) return $konversi($ribu) . ' Ribu';
                return $konversi($ribu) . ' Ribu ' . $konversi($sisa);
            }
            if ($n < 1000000000) {
                $juta = floor($n / 1000000);
                $sisa = $n % 1000000;
                if ($sisa == 0) return $konversi($juta) . ' Juta';
                return $konversi($juta) . ' Juta ' . $konversi($sisa);
            }
            return 'Angka terlalu besar';
        };
        
        return ucfirst($konversi($angka)) . ' Rupiah';
    }
    
    public function hitungTotal(): float
    {
        return (float) $this->nominal;
    }
    
    public function generateTerbilang(): string
    {
        return self::terbilangStatic($this->nominal);
    }
    
    public static function syncFromSpd(SPD $spd, array $additionalData = [], $existingKwitansi = null)
    {
        Log::info('=== SYNC KWITANSI FROM SPD ===', [
            'spd_id' => $spd->id_spd,
            'nomor_surat' => $spd->nomor_surat,
        ]);
        
        // ✅ KEMBALI KE spd_id
        $kwitansi = $existingKwitansi ?? self::firstOrNew(['spd_id' => $spd->id_spd]);
        
        $rincianBidang = RincianBidang::where('spd_id', $spd->id_spd)->first();
        
        $nominal = 0;
        if (isset($additionalData['nominal']) && $additionalData['nominal'] > 0) {
            $nominal = (float) $additionalData['nominal'];
        } elseif ($rincianBidang && $rincianBidang->total_keseluruhan > 0) {
            $nominal = (float) $rincianBidang->total_keseluruhan;
        } elseif ($rincianBidang && $rincianBidang->total > 0) {
            $nominal = (float) $rincianBidang->total;
        } elseif ($rincianBidang) {
            $jumlahPegawai = is_array($rincianBidang->pegawai) ? count($rincianBidang->pegawai) : 0;
            $nominal = ($rincianBidang->uang_harian ?? 0) * ($rincianBidang->lama_perjadin ?? 1) * $jumlahPegawai;
            $nominal += ($rincianBidang->uang_transport ?? 0) * $jumlahPegawai;
            $nominal += ($rincianBidang->transport ?? 0);
        }
        
        $pelaksana = $spd->pelaksanaPerjadin->first();
        $defaultBendahara = self::getDefaultBendahara();
        
        // ✅ KEMBALI KE spd_id
        $kwitansi->spd_id = $spd->id_spd;
        $kwitansi->tahun_anggaran = date('Y', strtotime($spd->tanggal_berangkat));
        $kwitansi->kode_rekening = $additionalData['kode_rekening'] ?? $spd->kode_rek ?? $spd->pejabat_teknis_kode_rekening ?? '';
        $kwitansi->sub_kegiatan = $additionalData['sub_kegiatan'] ?? $spd->pejabat_teknis_sub_kegiatan ?? '';
        $kwitansi->no_bku = $additionalData['no_bku'] ?? self::generateNoBku($spd);
        $kwitansi->no_brpp = $additionalData['no_brpp'] ?? null;
        $kwitansi->terbilang = $additionalData['terbilang'] ?? self::terbilangStatic($nominal);
        $kwitansi->untuk_pembayaran = $additionalData['untuk_pembayaran'] ?? $spd->maksud_perjadin ?? '';
        $kwitansi->nominal = $nominal;
        $kwitansi->tanggal_kwitansi = $additionalData['tanggal_kwitansi'] ?? date('Y-m-d');
        $kwitansi->pengguna_anggaran = $additionalData['pengguna_anggaran'] ?? $spd->penggunaAnggaran?->nama ?? '';
        $kwitansi->nip_pengguna_anggaran = $additionalData['nip_pengguna_anggaran'] ?? $spd->penggunaAnggaran?->nip ?? '';
        $kwitansi->bendahara_pengeluaran = $additionalData['bendahara_pengeluaran'] ?? $defaultBendahara?->nama ?? '';
        $kwitansi->nip_bendahara = $additionalData['nip_bendahara'] ?? $defaultBendahara?->nip ?? '';
        $kwitansi->penerima = $additionalData['penerima'] ?? ($pelaksana?->nama ?? '');
        $kwitansi->nip_penerima = $additionalData['nip_penerima'] ?? ($pelaksana?->nip ?? '');
        
        foreach ($additionalData as $key => $value) {
            if (in_array($key, $kwitansi->fillable) && $key !== 'spd_id') {
                $kwitansi->$key = $value;
            }
        }
        
        $kwitansi->save();
        
        Log::info('SYNC KWITANSI SELESAI', [
            'id' => $kwitansi->id,
            'spd_id' => $kwitansi->spd_id,
            'no_bku' => $kwitansi->no_bku,
            'nominal' => $kwitansi->nominal,
        ]);
        
        return $kwitansi;
    }
}