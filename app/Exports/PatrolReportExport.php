<?php

namespace App\Exports;

use App\Models\Patrol;
use App\Models\PatrolLog;
use App\Models\EmergencyReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatrolReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $type = $this->request->get('type', 'daily');
        $date = $this->request->get('date', Carbon::today()->format('Y-m-d'));
        $guardId = $this->request->get('guard_id');
        $areaId = $this->request->get('area_id');
        $startDate = $this->request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $this->request->get('end_date', Carbon::today()->format('Y-m-d'));

        $query = Patrol::with('guardRel.user', 'area', 'logs.checkpoint');

        switch ($type) {
            case 'daily':
                $query->whereDate('start_time', $date);
                break;
            case 'monthly':
                $month = Carbon::parse($date);
                $query->whereMonth('start_time', $month->month)->whereYear('start_time', $month->year);
                break;
            case 'range':
                $query->whereBetween('start_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                break;
        }

        if ($guardId) {
            $query->where('guard_id', $guardId);
        }
        if ($areaId) {
            $query->where('area_id', $areaId);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Patroli',
            'Guard',
            'Area',
            'Shift',
            'Mulai',
            'Selesai',
            'Checkpoint Discan',
            'Total Checkpoint',
            'Status',
        ];
    }

    public function map($patrol): array
    {
        static $i = 0;
        $i++;

        return [
            $i,
            $patrol->patrol_number,
            $patrol->guardRel?->full_name ?? '-',
            $patrol->area?->name ?? '-',
            $patrol->shift ?? '-',
            $patrol->start_time->format('d/m/Y H:i'),
            $patrol->end_time?->format('H:i') ?? '-',
            $patrol->scanned_checkpoints,
            $patrol->total_checkpoints,
            $patrol->status,
        ];
    }
}
