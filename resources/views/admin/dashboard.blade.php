@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6" x-data="dashboard()">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500 dark:text-gray-400" x-text="currentTime"></span>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patroli Hari Ini</span>
                <div class="w-10 h-10 bg-orange-50 dark:bg-orange-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['totalPatrolsToday'] }}</p>
            <p class="text-xs text-green-500 mt-1">{{ $data['completedPatrolsToday'] }} selesai</p>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Guard Aktif</span>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['activeGuards'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total: {{ $totalUsers ?? $data['activeGuards'] }}</p>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Checkpoint</span>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['totalCheckpoints'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total titik</p>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Emergency</span>
                <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-500">{{ $data['pendingEmergencies'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu respon</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Grafik Patroli 7 Hari</h3>
            <canvas id="patrolChart" height="200"></canvas>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Top Guard Performa</h3>
            <div class="space-y-3">
                @forelse($topGuards as $guard)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm">
                            {{ strtoupper(substr($guard->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $guard->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $guard->guard_number }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-orange-500">{{ $guard->patrols_count }}x</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Patroli Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentPatrols as $patrol)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $patrol->status === 'completed' ? 'bg-green-500' : ($patrol->status === 'in_progress' ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $patrol->patrol_number }}</p>
                            <p class="text-xs text-gray-500">{{ $patrol->guardRel?->full_name ?? 'N/A' }} - {{ $patrol->area?->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <span class="text-xs {{ $patrol->status === 'completed' ? 'text-green-500' : ($patrol->status === 'in_progress' ? 'text-yellow-500' : 'text-red-500') }}">{{ $patrol->status }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada patroli</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Emergency Pending</h3>
            <div class="space-y-3">
                @forelse($recentEmergencies as $emergency)
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $emergency->type }}</p>
                        <p class="text-xs text-gray-500">{{ $emergency->guardRel?->full_name ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-lg">Pending</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">Tidak ada emergency</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function dashboard() {
    return {
        currentTime: new Date().toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }),
        init() {
            setInterval(() => {
                this.currentTime = new Date().toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            }, 1000);

            const ctx = document.getElementById('patrolChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($patrolChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))),
                        datasets: [{
                            label: 'Total Patroli',
                            data: @json($patrolChart->pluck('total')),
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249,115,22,0.1)',
                            fill: true,
                            tension: 0.4,
                        }, {
                            label: 'Selesai',
                            data: @json($patrolChart->pluck('completed')),
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.1)',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }, x: { grid: { display: false } } }
                    }
                });
            }
        }
    }
}
</script>
@endpush
@endsection
