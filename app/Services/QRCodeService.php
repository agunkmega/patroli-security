<?php

namespace App\Services;

use App\Models\Checkpoint;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    public function generate(Checkpoint $checkpoint): void
    {
        $qrData = json_encode([
            'type' => 'checkpoint',
            'code' => $checkpoint->code,
            'id'   => $checkpoint->id,
            'name' => $checkpoint->name,
        ]);

        $filename = "qr-checkpoint-{$checkpoint->code}.png";
        $path = "qr-codes/{$filename}";

        if (!Storage::disk('public')->exists('qr-codes')) {
            Storage::disk('public')->makeDirectory('qr-codes');
        }

        QrCode::format('png')
            ->size(400)
            ->margin(2)
            ->color(0, 0, 0)
            ->generate($qrData, Storage::disk('public')->path($path));

        $checkpoint->update([
            'qr_code' => $qrData,
            'qr_path' => $path,
        ]);
    }
}
