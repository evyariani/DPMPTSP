<?php

namespace App\Console\Commands;

use App\Models\SPT;
use Illuminate\Console\Command;

class GenerateQrCodeForExistingSpt extends Command
{
    protected $signature = 'spt:generate-qrcode';
    protected $description = 'Generate QR Code untuk SPT yang sudah disetujui tapi belum punya verification_code';

    public function handle()
    {
        $spts = SPT::where('status_approval', 'approved')
                   ->whereNull('verification_code')
                   ->get();
        
        if ($spts->isEmpty()) {
            $this->info('Tidak ada SPT yang perlu diproses.');
            return;
        }
        
        $count = 0;
        foreach ($spts as $spt) {
            $spt->verification_code = SPT::generateVerificationCode($spt->id_spt);
            $spt->document_hash = $spt->generateDocumentHash();
            $spt->save();
            $count++;
            $this->info("Generated for SPT ID: {$spt->id_spt} - {$spt->verification_code}");
        }
        
        $this->info("Selesai! {$count} SPT telah digenerate QR Code-nya.");
    }
}