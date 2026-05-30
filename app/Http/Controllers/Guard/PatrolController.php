<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatrolController extends Controller
{
    public function startPatrol(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $activePatrol = Patrol::where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        if ($activePatrol) {
            return redirect()->route('guard.patrol.active')
                ->with('info', 'Anda masih memiliki patroli aktif.');
        }

        $schedule = Schedule::with('checkpoints')
            ->where(function ($q) use ($guard) {
                $q->where('guard_id', $guard->id)->orWhereNull('guard_id');
            })
            ->where(function ($q) {
                $q->whereDate('date', today())->orWhereNull('date');
            })
            ->first();

        $area = null;
        $checkpoints = collect();
        $areas = Area::where('is_active', true)->get();

        if ($schedule && $schedule->area) {
            $area = $schedule->area;
            $checkpoints = $schedule->checkpoints;
        } else {
            if ($request->has('area_id')) {
                $area = Area::findOrFail($request->area_id);
                $checkpoints = Checkpoint::where('area_id', $area->id)
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();
            }
        }

        return view('guard.patrol', compact('area', 'checkpoints', 'schedule', 'areas'));
    }

    public function beginPatrol(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        $checkpoints = Checkpoint::where('area_id', $validated['area_id'])
            ->where('is_active', true)
            ->count();

        $patrolNumber = 'PTL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $patrol = Patrol::create([
            'patrol_number' => $patrolNumber,
            'guard_id' => $guard->id,
            'area_id' => $validated['area_id'],
            'schedule_id' => $validated['schedule_id'] ?? null,
            'start_time' => now(),
            'total_checkpoints' => $checkpoints,
            'scanned_checkpoints' => 0,
            'status' => 'in_progress',
        ]);

        return redirect()->route('guard.patrol.scan', $patrol->id)
            ->with('success', 'Patroli dimulai!');
    }

    public function activePatrol()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $patrol = Patrol::with('area', 'logs.checkpoint', 'schedule')
            ->where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        return view('guard.active-patrol', compact('patrol'));
    }

    public function scan(Patrol $patrol)
    {
        $this->authorizeGuard($patrol);

        $patrol->load('area.checkpoints', 'logs.checkpoint');
        $scannedIds = $patrol->logs->pluck('checkpoint_id')->toArray();

        $remainingCheckpoints = $patrol->area->checkpoints()
            ->where('is_active', true)
            ->whereNotIn('id', $scannedIds)
            ->orderBy('order')
            ->get();

        return view('guard.scan', compact('patrol', 'scannedIds', 'remainingCheckpoints'));
    }

    public function processScan(Request $request, Patrol $patrol)
    {
        $this->authorizeGuard($patrol);

        $validated = $request->validate([
            'checkpoint_code' => 'required|string|exists:checkpoints,code',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string',
            'status' => 'required|in:safe,unsafe',
            'condition' => 'nullable|string|max:50',
        ]);

        $checkpoint = Checkpoint::where('code', $validated['checkpoint_code'])->firstOrFail();

        $distance = $this->calculateDistance(
            $validated['latitude'], $validated['longitude'],
            $checkpoint->latitude, $checkpoint->longitude
        );

        $maxRadius = $checkpoint->radius ?? config('patrol.radius_meters', 30);
        if ($distance > $maxRadius) {
            return response()->json([
                'success' => false,
                'message' => "Anda berada di luar radius checkpoint. Jarak: " . round($distance, 1) . "m (maks: {$maxRadius}m)",
                'distance' => round($distance, 2),
            ], 422);
        }

        $alreadyScanned = PatrolLog::where('patrol_id', $patrol->id)
            ->where('checkpoint_id', $checkpoint->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'success' => false,
                'message' => 'Checkpoint ini sudah discan sebelumnya.',
            ], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('patrol-photos', 'public');
        }

        $log = PatrolLog::create([
            'patrol_id' => $patrol->id,
            'checkpoint_id' => $checkpoint->id,
            'guard_id' => $patrol->guard_id,
            'scan_time' => now(),
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
            $patrol->update([
                'status' => 'completed',
                'end_time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Checkpoint berhasil discan!',
            'data' => $log,
            'progress' => $patrol->progressPercent(),
            'patrol_completed' => $patrol->status === 'completed',
            'log' => [
                'id' => $log->id,
                'checkpoint' => $checkpoint->name,
                'scan_time' => $log->scan_time->format('H:i:s'),
                'status' => $log->status,
                'distance' => round($distance, 1),
            ],
        ]);
    }

    public function completePatrol(Patrol $patrol)
    {
        $this->authorizeGuard($patrol);

        $patrol->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        return redirect()->route('guard.dashboard')
            ->with('success', 'Patroli selesai!');
    }

    public function history()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $patrols = Patrol::with('area', 'logs.checkpoint')
            ->where('guard_id', $guard->id)
            ->latest()
            ->paginate(15);

        return view('guard.history', compact('patrols'));
    }

    private function authorizeGuard(Patrol $patrol): void
    {
        $user = Auth::user();
        if ($patrol->guard_id !== $user->guardProfile->id) {
            abort(403, 'Unauthorized access.');
        }
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
