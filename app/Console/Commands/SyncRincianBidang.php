<?php

namespace App\Console\Commands;

use App\Models\SPD;
use App\Models\RincianBidang;
use Illuminate\Console\Command;

class SyncRincianBidang extends Command
{
    protected $signature = 'rincian:sync';
    protected $description = 'Sync semua RincianBidang dari data SPD';

    public function handle()
    {
        $this->info('Mulai sync RincianBidang...');
        
        $spds = SPD::with(['tempatTujuan', 'pelaksanaPerjadin'])->get();
        $count = 0;
        $errors = [];
        
        foreach ($spds as $spd) {
            try {
                $this->info("Processing SPD ID: {$spd->id_spd} - {$spd->nomor_surat}");
                RincianBidang::syncFromSpd($spd);
                $count++;
            } catch (\Exception $e) {
                $errors[] = "SPD ID {$spd->id_spd}: " . $e->getMessage();
                $this->error("Error: " . $e->getMessage());
            }
        }
        
        $this->info("Selesai! Total {$count} RincianBidang berhasil disync.");
        
        if (count($errors) > 0) {
            $this->warn("Error terjadi pada: " . implode(', ', $errors));
        }
        
        return Command::SUCCESS;
    }
}