<?php

namespace App\Console\Commands;

use App\Models\Checkpoint;
use App\Services\QRCodeService;
use Illuminate\Console\Command;

class RegenerateQRCodes extends Command
{
    protected $signature = 'patrol:regenerate-qr';
    protected $description = 'Regenerate all QR code images for checkpoints';

    public function handle(QRCodeService $qrCodeService): void
    {
        $checkpoints = Checkpoint::all();

        if ($checkpoints->isEmpty()) {
            $this->warn('Tidak ada checkpoint ditemukan.');
            return;
        }

        $this->info("Memproses {$checkpoints->count()} checkpoint...");

        foreach ($checkpoints as $checkpoint) {
            $this->line("  Generating QR for {$checkpoint->code}...");

            try {
                $qrCodeService->generate($checkpoint);
                $this->info("  [OK] {$checkpoint->code}");
            } catch (\Exception $e) {
                $this->error("  [FAIL] {$checkpoint->code}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('Semua QR code berhasil diregenerate!');
    }
}
