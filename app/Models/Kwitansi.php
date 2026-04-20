<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kwitansi extends Model
{
    use SoftDeletes;
    
    protected $table = 'kwitansi';
    
    protected $fillable = [
        // Hapus 'rincian_biaya_id'
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
    
    protected $dates = ['tanggal_kwitansi', 'deleted_at'];
    
    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_kwitansi' => 'date',
    ];
    
    // Hapus method rincianBiaya() karena tidak ada relasi
    
    public function getFormattedNominalAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
    
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_kwitansi->format('d F Y');
    }
}