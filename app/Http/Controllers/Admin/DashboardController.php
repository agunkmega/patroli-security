<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Guard;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\EmergencyReport;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $data = [
            'totalPatrolsToday' => Patrol::whereDate('start_time', $today)->count(),
            'activePatrols' => Patrol::where('status', 'in_progress')->count(),
            'activeGuards' => Guard::where('is_active', true)->whereHas('user', fn($q) => $q->where('is_active', true))->count(),
            'totalCheckpoints' => Checkpoint::where('is_active', true)->count(),
            'completedPatrolsToday' => Patrol::whereDate('start_time', $today)->where('status', 'completed')->count(),
            'missedPatrols' => Patrol::whereDate('start_time', $today)->where('status', 'missed')->count(),
            'emergencyToday' => EmergencyReport::whereDate('created_at', $today)->count(),
            'pendingEmergencies' => EmergencyReport::where('status', 'pending')->count(),
            'totalUsers' => User::count(),
            'presentToday' => Attendance::whereDate('date', $today)->count(),
        ];

        $patrolChart = Patrol::select(
            DB::raw('DATE(start_time) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
        )->where('start_time', '>=', (clone $today)->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentPatrols = Patrol::with('guardRel.user', 'area')
            ->latest()
            ->take(10)
            ->get();

        $recentEmergencies = EmergencyReport::with('guardRel.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $topGuards = Guard::withCount(['patrols' => function ($q) {
            $q->where('status', 'completed');
        }])->orderBy('patrols_count', 'desc')->take(5)->get();

        $hourFn = DB::connection()->getDriverName() === 'sqlite' ? "strftime('%%H', scan_time)" : 'HOUR(scan_time)';
        $todayPatrolsByHour = PatrolLog::select(
            DB::raw("$hourFn as hour"),
            DB::raw('COUNT(*) as total')
        )->whereDate('scan_time', Carbon::today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.dashboard', compact(
            'data', 'patrolChart', 'recentPatrols',
            'recentEmergencies', 'topGuards', 'todayPatrolsByHour'
        ));
    }
}
