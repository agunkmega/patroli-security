<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Checkpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckpointController extends Controller
{
    public function index()
    {
        $checkpoints = Checkpoint::with('area')->latest()->paginate(15);
        return view('admin.checkpoints.index', compact('checkpoints'));
    }

    public function create()
    {
        $areas = Area::where('is_active', true)->get();
        return view('admin.checkpoints.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:checkpoints,code|max:20',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
            'order' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'instruction' => 'nullable|string',
            'require_photo' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['require_photo'] = $request->boolean('require_photo');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['radius'] = $validated['radius'] ?? 30;

        $checkpoint = Checkpoint::create($validated);

        $this->generateQRCode($checkpoint);

        return redirect()->route('admin.checkpoints.index')
            ->with('success', 'Checkpoint berhasil ditambahkan.');
    }

    public function show(Checkpoint $checkpoint)
    {
        $checkpoint->load('area', 'patrolLogs.guardRel.user');
        return view('admin.checkpoints.show', compact('checkpoint'));
    }

    public function edit(Checkpoint $checkpoint)
    {
        $areas = Area::where('is_active', true)->get();
        return view('admin.checkpoints.edit', compact('checkpoint', 'areas'));
    }

    public function update(Request $request, Checkpoint $checkpoint)
    {
        $validated = $request->validate([
            'code' => ['required', 'max:20', Rule::unique('checkpoints')->ignore($checkpoint->id)],
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'area_id' => 'required|exists:areas,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1',
            'order' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'instruction' => 'nullable|string',
            'require_photo' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['require_photo'] = $request->boolean('require_photo');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['radius'] = $validated['radius'] ?? 30;

        $checkpoint->update($validated);

        if ($request->boolean('regenerate_qr')) {
            $this->generateQRCode($checkpoint);
        }

        return redirect()->route('admin.checkpoints.index')
            ->with('success', 'Checkpoint berhasil diperbarui.');
    }

    public function destroy(Checkpoint $checkpoint)
    {
        if ($checkpoint->qr_path) {
            Storage::disk('public')->delete($checkpoint->qr_path);
        }
        $checkpoint->delete();

        return redirect()->route('admin.checkpoints.index')
            ->with('success', 'Checkpoint berhasil dihapus.');
    }

    public function generateQR(Checkpoint $checkpoint)
    {
        $this->generateQRCode($checkpoint);
        return back()->with('success', 'QR Code berhasil digenerate ulang.');
    }

    public function printQR(Checkpoint $checkpoint)
    {
        $checkpoint->load('area');
        return view('admin.qrcode.print', compact('checkpoint'));
    }

    public function printAllQR()
    {
        $checkpoints = Checkpoint::with('area')->where('is_active', true)->get();
        return view('admin.qrcode.print-all', compact('checkpoints'));
    }

    private function generateQRCode(Checkpoint $checkpoint): void
    {
        try {
            $qrData = json_encode([
                'type' => 'checkpoint',
                'code' => $checkpoint->code,
                'id' => $checkpoint->id,
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
                ->color(233, 88, 12)
                ->generate($qrData, Storage::disk('public')->path($path));

            $checkpoint->update([
                'qr_code' => $qrData,
                'qr_path' => $path,
            ]);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
