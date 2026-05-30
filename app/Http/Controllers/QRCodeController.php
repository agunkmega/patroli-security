<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function print(Checkpoint $checkpoint)
    {
        $checkpoint->load('area');
        return view('admin.qrcode.print', compact('checkpoint'));
    }

    public function printAll()
    {
        $checkpoints = Checkpoint::with('area')->where('is_active', true)->get();
        return view('admin.qrcode.print-all', compact('checkpoints'));
    }

    public function download(Checkpoint $checkpoint)
    {
        if ($checkpoint->qr_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($checkpoint->qr_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download(
                $checkpoint->qr_path,
                "QR-{$checkpoint->code}.png"
            );
        }

        return back()->with('error', 'QR Code belum tersedia. Generate terlebih dahulu.');
    }
}
