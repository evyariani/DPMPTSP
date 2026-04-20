<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lhpd extends Model
{
    use HasFactory;

    protected $table = 'tb_lhpd';
    protected $primaryKey = 'id_lhpd';

    protected $fillable = [
        'dasar',
        'tujuan',
        'id_pegawai',
        'tanggal_berangkat',
        'id_daerah',
        'hasil',
        'tempat_dikeluarkan',
        'tanggal_lhpd',
        'foto'
    ];

    protected $casts = [
        'dasar' => 'array',
        'id_pegawai' => 'array',
        'foto' => 'array', // CAST FOTO MENJADI ARRAY
        'tanggal_berangkat' => 'date',
        'tanggal_lhpd' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi ke tabel tb_daerah (daerah tujuan dari SPD)
     */
    public function daerahTujuan()
    {
        return $this->belongsTo(Daerah::class, 'id_daerah', 'id');
    }

    /**
     * Relasi ke tabel tb_daerah (tempat LHPD dikeluarkan)
     */
    public function tempatDikeluarkan()
    {
        return $this->belongsTo(Daerah::class, 'tempat_dikeluarkan', 'id');
    }

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

    /**
     * Get data pegawai dari JSON
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
     * Get data dasar dari JSON
     */
    public function getDasarListAttribute()
    {
        if (empty($this->dasar)) {
            return collect();
        }
        
        return is_array($this->dasar) ? collect($this->dasar) : collect(json_decode($this->dasar, true));
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
}