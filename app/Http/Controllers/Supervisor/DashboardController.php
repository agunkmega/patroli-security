<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Guard;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\EmergencyReport;
use App\Models\Checkpoint;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data = [
            'activePatrols' => Patrol::where('status', 'in_progress')->count(),
            'activeGuards' => Guard::where('is_active', true)
                ->whereHas('user', fn($q) => $q->where('is_active', true))
                ->whereHas('patrols', fn($q) => $q->where('status', 'in_progress'))
                ->count(),
            'completedToday' => Patrol::whereDate('start_time', $today)->where('status', 'completed')->count(),
            'missedToday' => Patrol::whereDate('start_time', $today)->where('status', 'missed')->count(),
            'pendingEmergencies' => EmergencyReport::where('status', 'pending')->count(),
            'scansToday' => PatrolLog::whereDate('scan_time', $today)->count(),
        ];

        $activePatrols = Patrol::with('guardRel.user', 'area')
            ->where('status', 'in_progress')
            ->latest()
            ->get();

        $recentLogs = PatrolLog::with('guardRel.user', 'checkpoint', 'patrol')
            ->latest()
            ->take(20)
            ->get();

        $emergencies = EmergencyReport::with('guardRel.user')
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $guardsOnline = Guard::where('is_active', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true))
            ->whereHas('patrols', fn($q) => $q->where('status', 'in_progress'))
            ->with('user')
            ->get();

        $latestPositions = PatrolLog::selectRaw('DISTINCT guard_id, patrol_logs.*')
            ->latest()
            ->get()
            ->groupBy('guard_id')
            ->map->first();

        return view('supervisor.dashboard', compact(
            'data', 'activePatrols', 'recentLogs',
            'emergencies', 'guardsOnline', 'latestPositions'
        ));
    }

    public function monitoring()
    {
        $guards = Guard::with('user', 'activePatrol')
            ->where('is_active', true)
            ->get();

        $checkpoints = Checkpoint::with('area')
            ->where('is_active', true)
            ->get();

        $activePatrols = Patrol::with('guardRel.user', 'area', 'logs.checkpoint')
            ->where('status', 'in_progress')
            ->get();

        $latestLogs = PatrolLog::with('guardRel.user', 'checkpoint')
            ->latest()
            ->take(50)
            ->get();

        return view('supervisor.monitoring', compact(
            'guards', 'checkpoints', 'activePatrols', 'latestLogs'
        ));
    }
}
