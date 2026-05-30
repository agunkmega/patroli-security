<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guard;
use App\Models\Area;
use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\EmergencyReport;
use App\Exports\PatrolReportExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $guardId = $request->get('guard_id');
        $areaId = $request->get('area_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));

        $guards = Guard::where('is_active', true)->with('user')->get();
        $areas = Area::where('is_active', true)->get();

        $query = Patrol::with('guardRel.user', 'area', 'logs.checkpoint');
        $logQuery = PatrolLog::with('patrol.guardRel.user', 'checkpoint.area');

        switch ($type) {
            case 'daily':
                $query->whereDate('start_time', $date);
                $logQuery->whereDate('scan_time', $date);
                break;
            case 'monthly':
                $month = Carbon::parse($date);
                $query->whereMonth('start_time', $month->month)->whereYear('start_time', $month->year);
                $logQuery->whereMonth('scan_time', $month->month)->whereYear('scan_time', $month->year);
                break;
            case 'range':
                $query->whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                $logQuery->whereBetween('scan_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                break;
        }

        if ($guardId) {
            $query->where('guard_id', $guardId);
            $logQuery->where('guard_id', $guardId);
        }
        if ($areaId) {
            $query->where('area_id', $areaId);
            $logQuery->whereHas('checkpoint', fn($q) => $q->where('area_id', $areaId));
        }

        $patrols = $query->latest()->get();
        $logs = $logQuery->latest()->get();
        $emergencies = EmergencyReport::whereBetween('created_at', [
            $startDate . ' 00:00:00', $endDate . ' 23:59:59'
        ])->with('guardRel.user')->get();

        $summary = [
            'total_patrols' => $patrols->count(),
            'completed' => $patrols->where('status', 'completed')->count(),
            'in_progress' => $patrols->where('status', 'in_progress')->count(),
            'missed' => $patrols->where('status', 'missed')->count(),
            'total_scans' => $logs->count(),
            'safe_scans' => $logs->where('status', 'safe')->count(),
            'unsafe_scans' => $logs->where('status', 'unsafe')->count(),
            'emergencies' => $emergencies->count(),
        ];

        $chartData = PatrolLog::select(
            DB::raw('DATE(scan_time) as date'),
            DB::raw('COUNT(*) as total')
        )->whereBetween('scan_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')->orderBy('date')->get();

        return view('admin.reports.index', compact(
            'patrols', 'logs', 'emergencies', 'summary',
            'guards', 'areas', 'type', 'date', 'guardId', 'areaId',
            'startDate', 'endDate', 'chartData'
        ));
    }

    public function exportExcel(Request $request)
    {
        ini_set('max_execution_time', 300);
        return Excel::download(
            new PatrolReportExport($request),
            'laporan-patroli-' . Carbon::today()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPDF(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $guardId = $request->get('guard_id');
        $areaId = $request->get('area_id');
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));

        $guards = Guard::where('is_active', true)->with('user')->get();
        $areas = Area::where('is_active', true)->get();

        $query = Patrol::with('guardRel.user', 'area', 'logs.checkpoint');
        $logQuery = PatrolLog::with('patrol.guardRel.user', 'checkpoint.area');

        switch ($type) {
            case 'daily':
                $query->whereDate('start_time', $date);
                $logQuery->whereDate('scan_time', $date);
                break;
            case 'monthly':
                $month = Carbon::parse($date);
                $query->whereMonth('start_time', $month->month)->whereYear('start_time', $month->year);
                $logQuery->whereMonth('scan_time', $month->month)->whereYear('scan_time', $month->year);
                break;
            case 'range':
                $query->whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                $logQuery->whereBetween('scan_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                break;
        }

        if ($guardId) {
            $query->where('guard_id', $guardId);
            $logQuery->where('guard_id', $guardId);
        }
        if ($areaId) {
            $query->where('area_id', $areaId);
            $logQuery->whereHas('checkpoint', fn($q) => $q->where('area_id', $areaId));
        }

        $patrols = $query->latest()->get();
        $logs = $logQuery->latest()->get();
        $emergencies = EmergencyReport::whereBetween('created_at', [
            $startDate . ' 00:00:00', $endDate . ' 23:59:59'
        ])->with('guardRel.user')->get();

        $summary = [
            'total_patrols' => $patrols->count(),
            'completed' => $patrols->where('status', 'completed')->count(),
            'in_progress' => $patrols->where('status', 'in_progress')->count(),
            'missed' => $patrols->where('status', 'missed')->count(),
            'total_scans' => $logs->count(),
            'safe_scans' => $logs->where('status', 'safe')->count(),
            'unsafe_scans' => $logs->where('status', 'unsafe')->count(),
            'emergencies' => $emergencies->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'patrols', 'logs', 'emergencies', 'summary',
            'type', 'date', 'startDate', 'endDate'
        ));

        return $pdf->download('laporan-patroli-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }
}
