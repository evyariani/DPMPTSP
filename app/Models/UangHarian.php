<?php
// app/Models/UangHarian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangHarian extends Model
{
    protected $table = 'tb_uang_harian';
    
    protected $primaryKey = 'id_uang_harian';
    
    protected $fillable = [
        'daerah_id',
        'tempat_tujuan',
        'uang_harian',
        'uang_transport',
        'total'
    ];

    protected $casts = [
        'uang_harian' => 'decimal:0',
        'uang_transport' => 'decimal:0',
        'total' => 'decimal:0',
    ];

    /**
     * Relasi ke daerah tujuan
     */
    public function daerah()
    {
        return $this->belongsTo(Daerah::class, 'daerah_id');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where(function($q) use ($search) {
                $q->where('tempat_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('daerah', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}