<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Patroli</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-bottom: 4px; }
        .subtitle { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f97316; color: white; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) { background: #f9f9f9; }
        .summary { display: flex; gap: 16px; margin-bottom: 20px; }
        .summary-item { flex: 1; border: 1px solid #ddd; padding: 10px; text-align: center; border-radius: 4px; }
        .summary-item .label { font-size: 10px; color: #666; }
        .summary-item .value { font-size: 18px; font-weight: bold; margin-top: 4px; }
        .section-title { font-size: 13px; font-weight: bold; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #f97316; }
    </style>
</head>
<body>
    <h1>Laporan Patroli Security</h1>
    <p class="subtitle">Periode: {{ $startDate }} - {{ $endDate }} | Tipe: {{ $type }}</p>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Patroli</div>
            <div class="value">{{ $summary['total_patrols'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Selesai</div>
            <div class="value">{{ $summary['completed'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Scan</div>
            <div class="value">{{ $summary['total_scans'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Emergency</div>
            <div class="value">{{ $summary['emergencies'] }}</div>
        </div>
    </div>

    <div class="section-title">Data Patroli</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor</th>
                <th>Guard</th>
                <th>Area</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Progress</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patrols as $patrol)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $patrol->patrol_number }}</td>
                <td>{{ $patrol->guardRel?->full_name ?? '-' }}</td>
                <td>{{ $patrol->area?->name ?? '-' }}</td>
                <td>{{ $patrol->start_time->format('d/m H:i') }}</td>
                <td>{{ $patrol->end_time?->format('H:i') ?? '-' }}</td>
                <td>{{ $patrol->scanned_checkpoints }}/{{ $patrol->total_checkpoints }}</td>
                <td>{{ $patrol->status }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detail Scan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Guard</th>
                <th>Checkpoint</th>
                <th>Area</th>
                <th>Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $log->guardRel?->full_name ?? '-' }}</td>
                <td>{{ $log->checkpoint?->name ?? '-' }}</td>
                <td>{{ $log->checkpoint?->area?->name ?? '-' }}</td>
                <td>{{ $log->scan_time->format('d/m H:i') }}</td>
                <td>{{ $log->status }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($emergencies->isNotEmpty())
    <div class="section-title">Laporan Emergency</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Guard</th>
                <th>Lokasi</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emergencies as $em)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $em->guardRel?->full_name ?? '-' }}</td>
                <td>{{ $em->location ?? '-' }}</td>
                <td>{{ $em->type ?? '-' }}</td>
                <td>{{ $em->status }}</td>
                <td>{{ $em->created_at->format('d/m H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <p style="text-align:right;color:#999;font-size:10px;margin-top:24px;">
        Dicetak: {{ now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
