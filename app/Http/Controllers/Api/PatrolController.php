<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Checkpoint;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatrolController extends Controller
{
    public function areas()
    {
        $areas = Area::with('activeCheckpoints')
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $areas,
        ]);
    }

    public function schedules()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $schedules = Schedule::with('area', 'checkpoints')
            ->where(function ($q) use ($guard) {
                $q->where('guard_id', $guard->id)->orWhereNull('guard_id');
            })
            ->where(function ($q) {
                $q->whereDate('date', today())->orWhereNull('date');
            })
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function show(Patrol $patrol)
    {
        $user = Auth::user();
        if ($patrol->guard_id !== $user->guardProfile->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $patrol->load('area.checkpoints', 'logs.checkpoint', 'schedule'),
        ]);
    }

    public function complete(Patrol $patrol)
    {
        $user = Auth::user();
        if ($patrol->guard_id !== $user->guardProfile->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($patrol->status !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Patroli sudah selesai.',
            ], 422);
        }

        $patrol->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patroli selesai',
            'data' => $patrol,
        ]);
    }

    public function validateCheckpoint(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $validated = $request->validate([
            'checkpoint_code' => 'required|string|exists:checkpoints,code',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $checkpoint = Checkpoint::with('area')->where('code', $validated['checkpoint_code'])->firstOrFail();

        $distance = $this->calculateDistance(
            $validated['latitude'], $validated['longitude'],
            $checkpoint->latitude, $checkpoint->longitude
        );

        $maxRadius = $checkpoint->radius ?? config('patrol.radius_meters', 30);

        return response()->json([
            'success' => true,
            'valid' => $distance <= $maxRadius,
            'message' => $distance <= $maxRadius
                ? 'Checkpoint valid'
                : 'Anda berada di luar radius checkpoint',
            'data' => [
                'checkpoint' => $checkpoint,
                'distance' => round($distance, 2),
                'max_radius' => $maxRadius,
                'within_radius' => $distance <= $maxRadius,
            ],
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

    public function dashboard()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $activePatrol = Patrol::where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->with('area', 'logs.checkpoint')
            ->first();

        $todayAttendance = \App\Models\Attendance::where('guard_id', $guard->id)
            ->whereDate('date', today())
            ->first();

        $todayPatrols = Patrol::where('guard_id', $guard->id)
            ->whereDate('start_time', today())
            ->count();

        $totalPatrols = Patrol::where('guard_id', $guard->id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'guard' => $guard,
                'active_patrol' => $activePatrol,
                'today_attendance' => $todayAttendance,
                'today_patrols' => $todayPatrols,
                'total_patrols' => $totalPatrols,
            ],
        ]);
    }
    public function startPatrol(Request $request)
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $activePatrol = Patrol::where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        if ($activePatrol) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih memiliki patroli aktif',
                'data' => $activePatrol,
            ]);
        }

        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $checkpoints = Checkpoint::where('area_id', $validated['area_id'])
            ->where('is_active', true)
            ->count();

        $patrol = Patrol::create([
            'patrol_number' => 'PTL-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'guard_id' => $guard->id,
            'area_id' => $validated['area_id'],
            'schedule_id' => $validated['schedule_id'] ?? null,
            'start_time' => now(),
            'total_checkpoints' => $checkpoints,
            'status' => 'in_progress',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patroli dimulai',
            'data' => $patrol->load('area', 'logs.checkpoint'),
        ]);
    }

    public function activePatrol()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $patrol = Patrol::with('area.checkpoints', 'logs.checkpoint')
            ->where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$patrol) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada patroli aktif',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $patrol,
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        $patrols = Patrol::with('area', 'logs.checkpoint')
            ->where('guard_id', $guard->id)
            ->latest()
            ->paginate(20);

        return response()->json($patrols);
    }
}
