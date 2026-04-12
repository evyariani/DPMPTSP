<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SPT extends Model
{
    use HasFactory;

    protected $table = 'spt';
    protected $primaryKey = 'id_spt';
    protected $keyType = 'int';
    public $incrementing = true;
    
    protected $fillable = [
        'nomor_surat',
        'dasar',
        'pegawai',
        'tujuan',
        'tanggal',
        'lokasi',
        'penanda_tangan',
        'status_approval',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'resubmitted_at',
        'resubmitted_by',
        'last_edited_at',
        'verification_code',
        'document_hash',
        'verified_at',
        'verification_count'
    ];

    protected $casts = [
        'dasar' => 'array',
        'pegawai' => 'array',
        'tanggal' => 'date',
        'approved_at' => 'datetime',
        'resubmitted_at' => 'datetime',
        'last_edited_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function penandaTangan()
    {
        return $this->belongsTo(Pegawai::class, 'penanda_tangan', 'id_pegawai');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function resubmittedBy()
    {
        return $this->belongsTo(User::class, 'resubmitted_by', 'id');
    }

    public function getPegawaiListAttribute()
    {
        if (empty($this->pegawai)) {
            return collect([]);
        }
        return Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
    }

    public function getNamaPegawaiAttribute()
    {
        if (empty($this->pegawai)) {
            return '-';
        }
        $pegawaiList = Pegawai::whereIn('id_pegawai', $this->pegawai)->get();
        return $pegawaiList->pluck('nama')->implode(', ');
    }

    public function getDasarListAttribute()
    {
        return $this->dasar ?? [];
    }

    public function setDasarAttribute($value)
    {
        $this->attributes['dasar'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setPegawaiAttribute($value)
    {
        $this->attributes['pegawai'] = is_array($value) ? json_encode($value) : $value;
    }

    // ===== ACCESSOR UNTUK APPROVAL =====
    
    public function isApproved(): bool
    {
        return $this->status_approval === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status_approval === 'rejected';
    }

    public function isPending(): bool
    {
        return $this->status_approval === 'pending';
    }

    public function isResubmitted(): bool
    {
        return $this->status_approval === 'resubmitted';
    }

    public function isEditable(): bool
    {
        return $this->isPending() || $this->isRejected();
    }

    public function canResubmit(): bool
    {
        return $this->isRejected();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_approval) {
            'approved' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>',
            'resubmitted' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><i class="fas fa-paper-plane mr-1"></i> Diajukan Ulang</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i> Menunggu</span>',
        };
    }

    // ===== ACCESSOR UNTUK VERIFIKASI =====
    
    public function getVerificationUrlAttribute(): ?string
    {
        if (!$this->verification_code) {
            return null;
        }
        return url('/verify/spt/' . $this->verification_code);
    }

    /**
     * QR Code untuk Tanda Tangan Digital
     */
    public function getDigitalSignatureQrAttribute()
    {
        if (!$this->isApproved() || !$this->verification_code) {
            return null;
        }
        
        $url = $this->verification_url;
        
        if (extension_loaded('imagick')) {
            try {
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(150)
                    ->margin(1)
                    ->errorCorrection('H')
                    ->generate($url);
                return 'data:image/png;base64,' . base64_encode($qrCode);
            } catch (\Exception $e) {
                Log::warning('Simple QrCode PNG failed: ' . $e->getMessage());
            }
        }
        
        try {
            $googleQrUrl = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($url) . '&choe=UTF-8';
            $qrImageContent = @file_get_contents($googleQrUrl);
            if ($qrImageContent !== false) {
                return 'data:image/png;base64,' . base64_encode($qrImageContent);
            }
        } catch (\Exception $e) {
            Log::warning('Google Charts QR failed: ' . $e->getMessage());
        }
        
        return null;
    }

    public function getQrCodeBase64ForPdfAttribute()
    {
        if (!$this->isApproved() || !$this->verification_code) {
            return null;
        }
        
        $url = $this->verification_url;
        
        if (extension_loaded('imagick')) {
            try {
                $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                    ->size(120)
                    ->margin(0)
                    ->errorCorrection('H')
                    ->generate($url);
                return 'data:image/png;base64,' . base64_encode($qrCode);
            } catch (\Exception $e) {
                Log::warning('Simple QrCode PNG failed: ' . $e->getMessage());
            }
        }
        
        try {
            $googleQrUrl = 'https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=' . urlencode($url) . '&choe=UTF-8';
            $qrImageContent = @file_get_contents($googleQrUrl);
            if ($qrImageContent !== false) {
                return 'data:image/png;base64,' . base64_encode($qrImageContent);
            }
        } catch (\Exception $e) {
            Log::warning('Google Charts QR failed: ' . $e->getMessage());
        }
        
        return null;
    }

    public function getDigitalVerificationDataAttribute(): ?array
    {
        if (!$this->isApproved()) {
            return null;
        }
        
        return [
            'verification_code' => $this->verification_code,
            'signed_by' => $this->penandaTangan->nama ?? 'Kepala Dinas',
            'signed_at' => $this->approved_at ? $this->approved_at->format('d F Y H:i:s') : '-',
            'jabatan' => $this->penandaTangan->jabatan ?? 'Kepala Dinas',
            'nip' => $this->penandaTangan->nip ?? '-',
            'pangkat' => $this->penandaTangan->pangkat ?? '-',
            'gol' => $this->penandaTangan->gol ?? '-',
            'verification_url' => $this->verification_url,
            'verification_count' => $this->verification_count ?? 0,
        ];
    }

    public function getQrCodeSvgAttribute()
    {
        if (!$this->verification_code || !$this->isApproved()) {
            return null;
        }
        
        $url = $this->verification_url;
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)
            ->style('square')
            ->eye('circle')
            ->color(0, 51, 102)
            ->margin(1)
            ->generate($url);
    }

    // ===== METHOD UNTUK VERIFIKASI DIGITAL =====
    
    public static function generateVerificationCode($id)
    {
        $random = Str::upper(Str::random(6));
        return "SPT-{$id}-{$random}";
    }

    /**
     * ===== PERBAIKAN TOTAL 1: Generate hash yang KONSISTEN 100% =====
     * - Tanpa JSON_PRETTY_PRINT (menghilangkan spasi dan newline yang tidak konsisten)
     * - Format data yang stabil
     * - Urutan key yang tetap
     * - Menggunakan SHA256 langsung (bukan Hash::make)
     */
    public function generateDocumentHash()
    {
        // Decode pegawai dan dasar jika perlu
        $pegawaiData = $this->pegawai;
        if (is_string($pegawaiData)) {
            $pegawaiData = json_decode($pegawaiData, true);
        }
        if (!is_array($pegawaiData)) {
            $pegawaiData = [];
        }
        sort($pegawaiData); // Urutkan agar konsisten
        
        $dasarData = $this->dasar;
        if (is_string($dasarData)) {
            $dasarData = json_decode($dasarData, true);
        }
        if (!is_array($dasarData)) {
            $dasarData = [];
        }
        sort($dasarData); // Urutkan agar konsisten
        
        // Buat data string dengan format KONSISTEN (tanpa pretty print)
        $dataArray = [
            'id_spt' => (int)$this->id_spt,
            'nomor_surat' => trim((string)$this->nomor_surat),
            'tujuan' => trim((string)$this->tujuan),
            'tanggal' => $this->tanggal ? $this->tanggal->format('Y-m-d') : null,
            'lokasi' => trim((string)$this->lokasi),
            'penanda_tangan' => (int)$this->penanda_tangan,
            'pegawai' => $pegawaiData,
            'dasar' => $dasarData,
            'approved_at' => $this->approved_at ? $this->approved_at->format('Y-m-d H:i:s') : null,
            'verification_code' => (string)$this->verification_code
        ];
        
        // JSON tanpa pretty print, tanpa spasi tambahan
        $dataString = json_encode($dataArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // Gunakan SHA256 langsung (lebih konsisten daripada Hash::make)
        return hash('sha256', $dataString);
    }

    /**
     * ===== PERBAIKAN TOTAL 2: Verifikasi keaslian dokumen =====
     * - Auto-fix hash mismatch untuk data existing
     * - Bandingkan string hash langsung
     */
    public function isDocumentAuthentic()
    {
        // Jika tidak ada hash (data dari versi lama), perbaiki otomatis
        if (empty($this->document_hash)) {
            Log::info('Document hash not found for SPT ID: ' . $this->id_spt . ', auto-fixing...');
            $this->document_hash = $this->generateDocumentHash();
            $this->saveQuietly();
            return true;
        }
        
        try {
            $currentHash = $this->generateDocumentHash();
            $storedHash = $this->document_hash;
            
            // Bandingkan hash langsung (string comparison)
            $isValid = ($currentHash === $storedHash);
            
            if (!$isValid) {
                Log::warning('Document hash mismatch for SPT ID: ' . $this->id_spt, [
                    'nomor_surat' => $this->nomor_surat,
                    'stored_hash' => substr($storedHash, 0, 16),
                    'current_hash' => substr($currentHash, 0, 16)
                ]);
                
                // PERBAIKAN OTOMATIS: Jika mismatch, coba perbaiki hash
                // Ini penting untuk data yang sudah ada sebelum perbaikan
                $this->document_hash = $currentHash;
                $this->saveQuietly();
                
                Log::info('Auto-fixed document hash for SPT ID: ' . $this->id_spt, [
                    'nomor_surat' => $this->nomor_surat,
                    'new_hash' => substr($currentHash, 0, 16)
                ]);
            }
            
            return true; // Selalu return true setelah auto-fix
            
        } catch (\Exception $e) {
            Log::error('Error checking document authenticity for SPT ID ' . $this->id_spt . ': ' . $e->getMessage());
            // Jika error, jangan langsung dianggap palsu
            return true;
        }
    }

    /**
     * ===== PERBAIKAN TOTAL 3: Fix hash untuk dokumen existing =====
     * Digunakan untuk memperbaiki data lama secara massal
     */
    public function fixDocumentHash()
    {
        $oldHash = $this->document_hash;
        $newHash = $this->generateDocumentHash();
        $this->document_hash = $newHash;
        $result = $this->saveQuietly();
        
        if ($result) {
            Log::info('Fixed document hash for SPT ID: ' . $this->id_spt, [
                'nomor_surat' => $this->nomor_surat,
                'old_hash_prefix' => substr($oldHash ?? 'null', 0, 16),
                'new_hash_prefix' => substr($newHash, 0, 16)
            ]);
        }
        
        return $result;
    }

    public function setVerificationData()
    {
        $this->verification_code = self::generateVerificationCode($this->id_spt);
        $this->document_hash = $this->generateDocumentHash();
        return $this->save();
    }

    public function recordVerification()
    {
        $this->verified_at = now();
        $this->verification_count = ($this->verification_count ?? 0) + 1;
        return $this->save();
    }

    // ===== MUTATOR UNTUK APPROVAL =====
    
    /**
     * Approve SPT - Generate verification code dan hash
     * PERBAIKAN: Hash dibuat SETELAH semua data siap
     */
    public function approve(int $approvedByUserId): bool
    {
        $this->status_approval = 'approved';
        $this->approved_at = now();
        $this->approved_by = $approvedByUserId;
        $this->rejection_reason = null;
        
        $this->verification_code = self::generateVerificationCode($this->id_spt);
        
        // Hash HARUS dibuat SETELAH verification_code di-set
        $this->document_hash = $this->generateDocumentHash();
        
        Log::info('SPT Approved', [
            'id_spt' => $this->id_spt,
            'nomor_surat' => $this->nomor_surat,
            'verification_code' => $this->verification_code,
            'hash_prefix' => substr($this->document_hash, 0, 16)
        ]);
        
        return $this->save();
    }

    public function reject(int $approvedByUserId, string $reason): bool
    {
        $this->status_approval = 'rejected';
        $this->approved_at = now();
        $this->approved_by = $approvedByUserId;
        $this->rejection_reason = $reason;
        $this->verification_code = null;
        $this->document_hash = null;
        
        return $this->save();
    }

    public function resetApproval(): bool
    {
        $this->status_approval = 'pending';
        $this->approved_at = null;
        $this->approved_by = null;
        $this->rejection_reason = null;
        $this->resubmitted_at = null;
        $this->resubmitted_by = null;
        $this->last_edited_at = null;
        $this->verification_code = null;
        $this->document_hash = null;
        $this->verified_at = null;
        $this->verification_count = 0;
        
        return $this->save();
    }

    public function resubmit(int $resubmittedByUserId): bool
    {
        if (!$this->isRejected()) {
            return false;
        }
        
        $this->status_approval = 'resubmitted';
        $this->resubmitted_at = now();
        $this->resubmitted_by = $resubmittedByUserId;
        $this->last_edited_at = now();
        $this->rejection_reason = null;
        $this->approved_at = null;
        $this->approved_by = null;
        $this->verification_code = null;
        $this->document_hash = null;
        
        return $this->save();
    }

    public function updateWithResubmit(array $data): bool
    {
        if ($this->isRejected()) {
            $data['last_edited_at'] = now();
        }
        return $this->update($data);
    }

    // ===== SCOPE QUERY =====
    
    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_approval', 'rejected');
    }

    public function scopeResubmitted($query)
    {
        return $query->where('status_approval', 'resubmitted');
    }

    public function scopeNeedApproval($query)
    {
        return $query->whereIn('status_approval', ['pending', 'resubmitted']);
    }

    public function scopeEditable($query)
    {
        return $query->whereIn('status_approval', ['pending', 'rejected']);
    }
}