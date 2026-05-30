<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guard = $user->guardProfile;

        if (!$guard) {
            return redirect()->route('login')->with('error', 'Profil guard tidak ditemukan.');
        }

        $activePatrol = Patrol::with('area', 'logs.checkpoint')
            ->where('guard_id', $guard->id)
            ->where('status', 'in_progress')
            ->first();

        $todaySchedule = Schedule::with('area', 'checkpoints')
            ->where('guard_id', $guard->id)
            ->whereDate('date', now())
            ->first();

        $todayLogs = PatrolLog::with('checkpoint')
            ->where('guard_id', $guard->id)
            ->whereDate('scan_time', now())
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_patrols' => Patrol::where('guard_id', $guard->id)->count(),
            'completed' => Patrol::where('guard_id', $guard->id)->where('status', 'completed')->count(),
            'today_scans' => PatrolLog::where('guard_id', $guard->id)->whereDate('scan_time', now())->count(),
            'missed' => Patrol::where('guard_id', $guard->id)->where('status', 'missed')->count(),
        ];

        $recentPatrols = Patrol::with('area')
            ->where('guard_id', $guard->id)
            ->latest()
            ->take(5)
            ->get();

        return view('guard.dashboard', compact(
            'guard', 'activePatrol', 'todaySchedule',
            'todayLogs', 'stats', 'recentPatrols'
        ));
    }
}
