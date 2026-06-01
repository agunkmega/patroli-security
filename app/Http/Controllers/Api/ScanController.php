<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Patrol;
use App\Models\PatrolLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function scanCheckpoint(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        if (!$guard) {
            return response()->json(['message' => 'Guard not found'], 404);
        }

        $validated = $request->validate([
            'checkpoint_code' => 'required|string|exists:checkpoints,code',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:safe,unsafe',
            'condition' => 'nullable|string|max:50',
            'scanned_at' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $checkpoint = Checkpoint::where('code', $validated['checkpoint_code'])->firstOrFail();

        $patrol = Patrol::where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$patrol) {
            $patrolNumber = 'PTL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $patrol = Patrol::create([
                'patrol_number' => $patrolNumber,
                'guard_id' => $guard->id,
                'area_id' => $checkpoint->area_id,
                'start_time' => now(),
                'total_checkpoints' => 1,
                'scanned_checkpoints' => 0,
                'status' => 'in_progress',
            ]);
        }

        $distance = $this->calculateDistance(
            $validated['latitude'], $validated['longitude'],
            $checkpoint->latitude, $checkpoint->longitude
        );

        $alreadyScanned = PatrolLog::where('patrol_id', $patrol->id)
            ->where('checkpoint_id', $checkpoint->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false,
                'message' => 'Checkpoint sudah discan sebelumnya',
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('patrol-photos', 'public');
        }

        $scanTime = !empty($validated['scanned_at']) ? $validated['scanned_at'] : now();

        $log = PatrolLog::create([
            'patrol_id' => $patrol->id,
            'checkpoint_id' => $checkpoint->id,
            'guard_id' => $guard->id,
            'scan_time' => $scanTime,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'distance' => $distance,
            'photo' => $photoPath,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
            'condition' => $validated['condition'] ?? null,
            'device_info' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        $patrol->increment('scanned_checkpoints');

        if ($patrol->scanned_checkpoints >= $patrol->total_checkpoints) {
            $patrol->update(['status' => 'completed', 'end_time' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Scan berhasil',
            'data' => [
                'log' => $log,
                'checkpoint' => $checkpoint->name,
                'distance' => round($distance, 2),
                'progress' => $patrol->progressPercent(),
                'patrol_completed' => $patrol->status === 'completed',
            ],
        ]);
    }

    public function getCheckpoint($code)
    {
        $checkpoint = Checkpoint::with('area')
            ->where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $checkpoint,
        ]);
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371000;
        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);
        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}
