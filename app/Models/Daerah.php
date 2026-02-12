<?php
// app/Models/Daerah.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daerah extends Model
{
    protected $table = 'tb_daerah';
    
    protected $primaryKey = 'id'; // PASTIKAN primary key = 'id'
    
    protected $fillable = [
        'kode',
        'nama',
        'tingkat',
        'parent_id'
    ];

    // Relasi ke parent
    public function parent()
    {
        return $this->belongsTo(Daerah::class, 'parent_id');
    }

    // Relasi ke child
    public function children()
    {
        return $this->hasMany(Daerah::class, 'parent_id');
    }

    // Scopes
    public function scopeProvinsi($query)
    {
        return $query->where('tingkat', 'provinsi');
    }

    public function scopeKabupaten($query)
    {
        return $query->where('tingkat', 'kabupaten');
    }

    public function scopeKecamatan($query)
    {
        return $query->where('tingkat', 'kecamatan');
    }
}