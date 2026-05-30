<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\Checkpoint;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
