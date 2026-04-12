<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Helper methods untuk cek level
    public function isAdmin()
    {
        return $this->level === 'admin';
    }

    public function isPegawai()
    {
        return $this->level === 'pegawai';
    }

    public function isKadis()
    {
        return $this->level === 'kadis';
    }
}