<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\UangHarian;
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b

class RincianBidang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'tb_rincianbidang';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
<<<<<<< HEAD
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
=======
        'spd_id',
        'nomor_sppd',
        'tanggal_berangkat',
        'tanggal_kembali',
        'lama_perjadin',
        'tempat_tujuan_id',
        'tempat_tujuan',
        'bendahara_pengeluaran_id',
        'uang_harian_id',
        'uang_harian',
        'uang_transport',
        'transport',
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
    
    public function getTotalUangHarianKeseluruhanAttribute()
    {
        $jumlahPegawai = is_array($this->pegawai) ? count($this->pegawai) : 0;
        return ($this->uang_harian ?? 0) * ($this->lama_perjadin ?? 0) * $jumlahPegawai;
    }
    
    public function getTotalUangTransportKeseluruhanAttribute()
    {
        $jumlahPegawai = is_array($this->pegawai) ? count($this->pegawai) : 0;
        return ($this->uang_transport ?? 0) * $jumlahPegawai;
    }

    public function getTotalKeseluruhanAttribute()
    {
        return $this->total_uang_harian_keseluruhan + 
               $this->total_uang_transport_keseluruhan + 
               ($this->transport ?? 0);
    }

>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    public function getDaftarNamaPegawaiAttribute()
    {
        if (!$this->pegawai) return [];
        return array_column($this->pegawai, 'nama');
    }
<<<<<<< HEAD

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
=======
    
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
    
    /**
     * Get default bendahara pengeluaran (pegawai dengan jabatan Bendahara Pengeluaran)
     */
    public static function getDefaultBendahara()
    {
        return Pegawai::where('jabatan', 'like', '%Bendahara%Pengeluaran%')
            ->orWhere('jabatan', 'like', '%Bendahara%')
            ->orWhere('jabatan', 'like', '%bendahara%')
            ->first();
    }
    
    public function hitungTotal(): float
    {
        $jumlahPegawai = is_array($this->pegawai) ? count($this->pegawai) : 0;
        $totalUangHarian = ($this->uang_harian ?? 0) * ($this->lama_perjadin ?? 0) * $jumlahPegawai;
        $totalUangTransport = ($this->uang_transport ?? 0) * $jumlahPegawai;
        
        return $totalUangHarian + $totalUangTransport + ($this->transport ?? 0);
    }
    
    public function updateTotal(): self
    {
        $this->total = $this->hitungTotal();
        return $this;
    }
    
    /**
     * Generate terbilang dari total
     */
    public function generateTerbilang(): string
    {
        // Pastikan total adalah integer
        $total = (int) $this->total;
        return $this->terbilang($total);
    }
    
    /**
     * Fungsi terbilang (angka ke kata) - DIPERBAIKI
     * 
     * @param int|string $angka
     * @return string
     */
    private function terbilang($angka): string
    {
        // Jika parameter adalah array, return default
        if (is_array($angka)) {
            $angka = 0;
        }
        
        // Konversi ke integer
        $angka = (int) $angka;
        
        if ($angka == 0) {
            return 'Nol Rupiah';
        }
        
        $satuan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
        
        // Fungsi rekursif untuk konversi
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

    /**
     * Sync dari data SPD - DIPERBAIKI dengan default bendahara
     */
    public static function syncFromSpd(SPD $spd, array $additionalData = [])
    {
        \Illuminate\Support\Facades\Log::info('=== SYNC FROM SPD ===', [
            'spd_id' => $spd->id_spd,
            'nomor_surat' => $spd->nomor_surat,
            'lama_perjadin' => $spd->lama_perjadin,
        ]);
        
        // Cari atau buat RincianBidang berdasarkan spd_id
        $rincianBidang = self::firstOrNew(['spd_id' => $spd->id_spd]);
        
        // Data dasar dari SPD
        $rincianBidang->spd_id = $spd->id_spd;
        $rincianBidang->nomor_sppd = $spd->nomor_surat;
        $rincianBidang->tanggal_berangkat = $spd->tanggal_berangkat;
        $rincianBidang->tanggal_kembali = $spd->tanggal_kembali;
        $rincianBidang->lama_perjadin = $spd->lama_perjadin ?? 1;
        $rincianBidang->tempat_tujuan_id = $spd->tempat_tujuan;
        
        // Ambil nama tempat tujuan
        if ($spd->tempatTujuan) {
            $rincianBidang->tempat_tujuan = $spd->tempatTujuan->nama;
        }
        
        // Ambil data uang harian berdasarkan daerah tujuan
        $uangHarianData = null;
        if ($spd->tempat_tujuan) {
            $uangHarianData = UangHarian::where('daerah_id', $spd->tempat_tujuan)->first();
            if ($uangHarianData) {
                $rincianBidang->uang_harian_id = $uangHarianData->id_uang_harian;
                $rincianBidang->uang_harian = $uangHarianData->uang_harian;
                $rincianBidang->uang_transport = $uangHarianData->uang_transport;
            } else {
                // Reset ke 0 jika tidak ditemukan
                $rincianBidang->uang_harian_id = null;
                $rincianBidang->uang_harian = 0;
                $rincianBidang->uang_transport = 0;
            }
        }
        
        // Ambil data pegawai dari spd_pelaksana
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
        
        // ========== SET BENDAHARA PENGELUARAN ==========
        // Prioritas:
        // 1. Dari parameter $additionalData
        // 2. Cari pegawai dengan jabatan "Bendahara Pengeluaran"
        // 3. Pegawai pertama dari daftar pelaksana
        // 4. Null
        
        if (isset($additionalData['bendahara_pengeluaran_id']) && !empty($additionalData['bendahara_pengeluaran_id'])) {
            $rincianBidang->bendahara_pengeluaran_id = $additionalData['bendahara_pengeluaran_id'];
        } else {
            // Cari bendahara default dari database
            $defaultBendahara = self::getDefaultBendahara();
            
            if ($defaultBendahara) {
                $rincianBidang->bendahara_pengeluaran_id = $defaultBendahara->id_pegawai;
                \Illuminate\Support\Facades\Log::info('Menggunakan bendahara default', [
                    'id' => $defaultBendahara->id_pegawai,
                    'nama' => $defaultBendahara->nama,
                    'jabatan' => $defaultBendahara->jabatan,
                ]);
            } elseif (!empty($pegawaiList)) {
                $rincianBidang->bendahara_pengeluaran_id = $pegawaiList[0]['id_pegawai'];
                \Illuminate\Support\Facades\Log::info('Menggunakan pegawai pertama sebagai bendahara', [
                    'id' => $pegawaiList[0]['id_pegawai'],
                    'nama' => $pegawaiList[0]['nama'],
                ]);
            } else {
                $rincianBidang->bendahara_pengeluaran_id = null;
                \Illuminate\Support\Facades\Log::warning('Tidak ada bendahara yang dipilih');
            }
        }
        
        // Set transport
        $rincianBidang->transport = $additionalData['transport'] ?? 0;
        
        // Hitung total
        $rincianBidang->total = $rincianBidang->hitungTotal();
        
        // Generate terbilang
        $rincianBidang->terbilang = $rincianBidang->generateTerbilang();
        
        // Merge additional data
        foreach ($additionalData as $key => $value) {
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
>>>>>>> db0c50f6a0cf3864408bbf4a141a91bc52fa8d2b
    }
}