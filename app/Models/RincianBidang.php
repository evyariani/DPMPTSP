<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UangHarian;

class RincianBidang extends Model
{
    use HasFactory;

    protected $table = 'tb_rincianbidang';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'spd_id',
        'nomor_sppd',
        'tanggal_berangkat',
        'tanggal_kembali',
        'lama_perjadin',
        'tempat_tujuan_id',
        'tempat_tujuan',
        'bendahara_pengeluaran_id',
        'bendahara_nama',
        'bendahara_nip',
        'bendahara_jabatan',
        'uang_harian_id',
        'uang_harian',
        'total',
        'terbilang',
        'pegawai'
    ];

    protected $casts = [
        'pegawai' => 'array',
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONS ==========
    
    public function spd(): BelongsTo
    {
        return $this->belongsTo(SPD::class, 'spd_id', 'id_spd');
    }
    
    public function tempatTujuan(): BelongsTo
    {
        return $this->belongsTo(Daerah::class, 'tempat_tujuan_id', 'id');
    }
    
    public function bendaharaPengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'bendahara_pengeluaran_id', 'id_pegawai');
    }
    
    public function uangHarian(): BelongsTo
    {
        return $this->belongsTo(UangHarian::class, 'uang_harian_id', 'id_uang_harian');
    }

    // ========== ACCESSORS ==========
    
    /**
     * Total uang harian untuk semua pegawai
     * Rumus: uang_harian * lama_perjadin * jumlah_pegawai
     */
    public function getTotalUangHarianKeseluruhanAttribute()
    {
        $jumlahPegawai = is_array($this->pegawai) ? count($this->pegawai) : 0;
        return ($this->uang_harian ?? 0) * ($this->lama_perjadin ?? 0) * $jumlahPegawai;
    }
    
    /**
     * Total keseluruhan (hanya uang harian, karena transport terpisah)
     */
    public function getTotalKeseluruhanAttribute()
    {
        return $this->total_uang_harian_keseluruhan;
    }

    public function getDaftarNamaPegawaiAttribute()
    {
        if (!$this->pegawai) return [];
        return array_column($this->pegawai, 'nama');
    }
    
    public function getDaftarPegawaiLengkapAttribute()
    {
        if (!$this->pegawai) return [];
        
        $result = [];
        foreach ($this->pegawai as $pegawai) {
            $result[] = (object) [
                'id' => $pegawai['id_pegawai'] ?? null,
                'nama' => $pegawai['nama'] ?? '-',
                'nip' => $pegawai['nip'] ?? '-',
                'jabatan' => $pegawai['jabatan'] ?? '-',
                'golongan' => $pegawai['gol'] ?? '-',
                'nominal' => $pegawai['nominal'] ?? 0,
                'hari' => $pegawai['hari'] ?? $this->lama_perjadin,
            ];
        }
        return $result;
    }

    /**
     * Mendapatkan data bendahara dari snapshot (data saat RincianBidang dibuat)
     */
    public function getBendaharaSnapshotAttribute()
    {
        if ($this->bendahara_nama) {
            return (object) [
                'nama' => $this->bendahara_nama,
                'nip' => $this->bendahara_nip,
                'jabatan' => $this->bendahara_jabatan,
            ];
        }
        return $this->bendaharaPengeluaran;
    }

    public function getTanggalBerangkatIndonesiaAttribute()
    {
        if (!$this->tanggal_berangkat) return '-';
        return $this->tanggal_berangkat->translatedFormat('d F Y');
    }
    
    public function getTanggalKembaliIndonesiaAttribute()
    {
        if (!$this->tanggal_kembali) return '-';
        return $this->tanggal_kembali->translatedFormat('d F Y');
    }
    
    public function getRentangTanggalAttribute()
    {
        $berangkat = $this->tanggal_berangkat ? $this->tanggal_berangkat->format('d/m/Y') : '-';
        $kembali = $this->tanggal_kembali ? $this->tanggal_kembali->format('d/m/Y') : '-';
        return $berangkat . ' s/d ' . $kembali;
    }
    
    public function getTotalRupiahAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
    
    public function getTotalKeseluruhanRupiahAttribute()
    {
        return 'Rp ' . number_format($this->total_keseluruhan, 0, ',', '.');
    }

    // ========== SCOPES ==========
    
    public function scopeBySpd($query, $spdId)
    {
        if ($spdId) {
            return $query->where('spd_id', $spdId);
        }
        return $query;
    }
    
    public function scopeByNomorSppd($query, $nomor)
    {
        if ($nomor) {
            return $query->where('nomor_sppd', 'like', "%{$nomor}%");
        }
        return $query;
    }
    
    public function scopeByTempatTujuan($query, $tempatTujuanId)
    {
        if ($tempatTujuanId) {
            return $query->where('tempat_tujuan_id', $tempatTujuanId);
        }
        return $query;
    }
    
    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('tanggal_berangkat', [$startDate, $endDate]);
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
    
    /**
     * Hitung total biaya (HANYA uang harian)
     * Uang transport TIDAK termasuk, akan dikwitansi terpisah
     */
    public function hitungTotal(): float
    {
        $jumlahPegawai = is_array($this->pegawai) ? count($this->pegawai) : 0;
        return ($this->uang_harian ?? 0) * ($this->lama_perjadin ?? 0) * $jumlahPegawai;
    }
    
    public function updateTotal(): self
    {
        $this->total = $this->hitungTotal();
        return $this;
    }
    
    public function generateTerbilang(): string
    {
        $total = (int) $this->total;
        return $this->terbilang($total);
    }
    
    private function terbilang($angka): string
    {
        if (is_array($angka)) {
            $angka = 0;
        }
        
        $angka = (int) $angka;
        
        if ($angka == 0) {
            return 'Nol Rupiah';
        }
        
        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        
        $konversi = function($n) use ($satuan, &$konversi) {
            if ($n < 10) {
                return $satuan[$n];
            }
            if ($n < 20) {
                if ($n == 10) return 'Sepuluh';
                if ($n == 11) return 'Sebelas';
                return $satuan[$n - 10] . ' Belas';
            }
            if ($n < 100) {
                $puluh = floor($n / 10);
                $sisa = $n % 10;
                if ($sisa == 0) {
                    return $satuan[$puluh] . ' Puluh';
                }
                return $satuan[$puluh] . ' Puluh ' . $konversi($sisa);
            }
            if ($n < 200) {
                return 'Seratus ' . $konversi($n - 100);
            }
            if ($n < 1000) {
                $ratus = floor($n / 100);
                $sisa = $n % 100;
                if ($sisa == 0) {
                    return $satuan[$ratus] . ' Ratus';
                }
                return $satuan[$ratus] . ' Ratus ' . $konversi($sisa);
            }
            if ($n < 2000) {
                return 'Seribu ' . $konversi($n - 1000);
            }
            if ($n < 1000000) {
                $ribu = floor($n / 1000);
                $sisa = $n % 1000;
                if ($sisa == 0) {
                    return $konversi($ribu) . ' Ribu';
                }
                return $konversi($ribu) . ' Ribu ' . $konversi($sisa);
            }
            if ($n < 1000000000) {
                $juta = floor($n / 1000000);
                $sisa = $n % 1000000;
                if ($sisa == 0) {
                    return $konversi($juta) . ' Juta';
                }
                return $konversi($juta) . ' Juta ' . $konversi($sisa);
            }
            if ($n < 1000000000000) {
                $miliar = floor($n / 1000000000);
                $sisa = $n % 1000000000;
                if ($sisa == 0) {
                    return $konversi($miliar) . ' Miliar';
                }
                return $konversi($miliar) . ' Miliar ' . $konversi($sisa);
            }
            
            return 'Angka terlalu besar';
        };
        
        $hasil = $konversi($angka);
        return ucfirst($hasil) . ' Rupiah';
    }

    public static function syncFromSpd(SPD $spd, array $additionalData = [])
    {
        \Illuminate\Support\Facades\Log::info('=== SYNC FROM SPD ===', [
            'spd_id' => $spd->id_spd,
            'nomor_surat' => $spd->nomor_surat,
            'lama_perjadin' => $spd->lama_perjadin,
        ]);
        
        $rincianBidang = self::firstOrNew(['spd_id' => $spd->id_spd]);
        
        $rincianBidang->spd_id = $spd->id_spd;
        $rincianBidang->nomor_sppd = $spd->nomor_surat;
        $rincianBidang->tanggal_berangkat = $spd->tanggal_berangkat;
        $rincianBidang->tanggal_kembali = $spd->tanggal_kembali;
        $rincianBidang->lama_perjadin = $spd->lama_perjadin ?? 1;
        $rincianBidang->tempat_tujuan_id = $spd->tempat_tujuan;
        
        if ($spd->tempatTujuan) {
            $rincianBidang->tempat_tujuan = $spd->tempatTujuan->nama;
        }
        
        $uangHarianData = null;
        if ($spd->tempat_tujuan) {
            $uangHarianData = UangHarian::where('daerah_id', $spd->tempat_tujuan)->first();
            if ($uangHarianData) {
                $rincianBidang->uang_harian_id = $uangHarianData->id_uang_harian;
                $rincianBidang->uang_harian = $uangHarianData->uang_harian;
                // TIDAK mengambil uang_transport karena akan dikwitansi terpisah
            } else {
                $rincianBidang->uang_harian_id = null;
                $rincianBidang->uang_harian = 0;
            }
        }
        
        $pegawaiList = [];
        $pelaksana = $spd->pelaksanaPerjadin()->get();
        
        foreach ($pelaksana as $pegawai) {
            $pegawaiList[] = [
                'id_pegawai' => $pegawai->id_pegawai,
                'nama' => $pegawai->nama,
                'nip' => $pegawai->nip ?? '-',
                'jabatan' => $pegawai->jabatan ?? '-',
                'gol' => $pegawai->gol ?? '-',
                'nominal' => $rincianBidang->uang_harian ?? 0,
                'hari' => $rincianBidang->lama_perjadin,
            ];
        }
        
        $rincianBidang->pegawai = $pegawaiList;
        
        // ========== SET BENDAHARA PENGELUARAN DENGAN SNAPSHOT ==========
        $bendaharaData = null;
        
        if (isset($additionalData['bendahara_pengeluaran_id']) && !empty($additionalData['bendahara_pengeluaran_id'])) {
            $bendahara = Pegawai::find($additionalData['bendahara_pengeluaran_id']);
            if ($bendahara) {
                $bendaharaData = $bendahara;
                $rincianBidang->bendahara_pengeluaran_id = $bendahara->id_pegawai;
            }
        } else {
            $defaultBendahara = self::getDefaultBendahara();
            if ($defaultBendahara) {
                $bendaharaData = $defaultBendahara;
                $rincianBidang->bendahara_pengeluaran_id = $defaultBendahara->id_pegawai;
            } elseif (!empty($pegawaiList)) {
                $pegawaiFirst = Pegawai::find($pegawaiList[0]['id_pegawai']);
                if ($pegawaiFirst) {
                    $bendaharaData = $pegawaiFirst;
                    $rincianBidang->bendahara_pengeluaran_id = $pegawaiList[0]['id_pegawai'];
                }
            } else {
                $rincianBidang->bendahara_pengeluaran_id = null;
            }
        }
        
        // Simpan snapshot bendahara
        if ($bendaharaData) {
            $rincianBidang->bendahara_nama = $bendaharaData->nama;
            $rincianBidang->bendahara_nip = $bendaharaData->nip;
            $rincianBidang->bendahara_jabatan = $bendaharaData->jabatan;
        } else {
            $rincianBidang->bendahara_nama = null;
            $rincianBidang->bendahara_nip = null;
            $rincianBidang->bendahara_jabatan = null;
        }
        
        // TIDAK mengambil transport dari additionalData
        $rincianBidang->total = $rincianBidang->hitungTotal();
        $rincianBidang->terbilang = $rincianBidang->generateTerbilang();
        
        // Proses additionalData (kecuali transport yang sudah dihapus)
        foreach ($additionalData as $key => $value) {
            // Skip 'transport' karena tidak ada di fillable lagi
            if ($key === 'transport') continue;
            
            if (in_array($key, $rincianBidang->fillable) && $key !== 'spd_id') {
                $rincianBidang->$key = $value;
            }
        }
        
        $rincianBidang->save();
        
        \Illuminate\Support\Facades\Log::info('SYNC SELESAI', [
            'id' => $rincianBidang->id,
            'pegawai_count' => count($pegawaiList),
            'lama_perjadin' => $rincianBidang->lama_perjadin,
            'total' => $rincianBidang->total,
            'bendahara_id' => $rincianBidang->bendahara_pengeluaran_id,
        ]);
        
        return $rincianBidang;
    }
}