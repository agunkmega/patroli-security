@extends('layouts.admin')

@section('title', 'Laporan Patroli')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Patroli</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Lihat dan export laporan patroli</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export-excel', request()->query()) }}" class="px-4 py-2.5 bg-green-500 text-white rounded-xl hover:bg-green-600 text-sm">Export Excel</a>
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" class="px-4 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 text-sm">Export PDF</a>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tipe</label>
                <select name="type" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-sm">
                    <option value="daily" {{ $type == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="range" {{ $type == 'range' ? 'selected' : '' }}>Range</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Guard</label>
                <select name="guard_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-sm">
                    <option value="">Semua</option>
                    @foreach($guards as $guard)
                    <option value="{{ $guard->id }}" {{ $guardId == $guard->id ? 'selected' : '' }}>{{ $guard->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Area</label>
                <select name="area_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-sm">
                    <option value="">Semua</option>
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">Filter</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 border border-gray-200 dark:border-dark-700">
            <p class="text-xs text-gray-500">Total Patroli</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $summary['total_patrols'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 border border-gray-200 dark:border-dark-700">
            <p class="text-xs text-gray-500">Selesai</p>
            <p class="text-2xl font-bold text-green-500">{{ $summary['completed'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 border border-gray-200 dark:border-dark-700">
            <p class="text-xs text-gray-500">Total Scan</p>
            <p class="text-2xl font-bold text-blue-500">{{ $summary['total_scans'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-xl p-4 border border-gray-200 dark:border-dark-700">
            <p class="text-xs text-gray-500">Emergency</p>
            <p class="text-2xl font-bold text-red-500">{{ $summary['emergencies'] }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white">Data Patroli</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-700/50 text-left">
                        <th class="px-5 py-3 font-semibold">Number</th>
                        <th class="px-5 py-3 font-semibold">Guard</th>
                        <th class="px-5 py-3 font-semibold">Area</th>
                        <th class="px-5 py-3 font-semibold">Start</th>
                        <th class="px-5 py-3 font-semibold">End</th>
                        <th class="px-5 py-3 font-semibold">Progress</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700">
                    @forelse($patrols as $patrol)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30">
                        <td class="px-5 py-4 font-mono text-xs">{{ $patrol->patrol_number }}</td>
                        <td class="px-5 py-4">{{ $patrol->guardRel?->full_name ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $patrol->area?->name ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $patrol->start_time->format('d/m H:i') }}</td>
                        <td class="px-5 py-4">{{ $patrol->end_time?->format('H:i') ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $patrol->scanned_checkpoints }}/{{ $patrol->total_checkpoints }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 text-xs rounded-lg
                                {{ $patrol->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $patrol->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $patrol->status === 'missed' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $patrol->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('reportChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData->pluck('date')),
                datasets: [{
                    label: 'Scan',
                    data: @json($chartData->pluck('total')),
                    backgroundColor: '#f97316',
                }]
            }
        });
    }
</script>
@endpush
@endsection
