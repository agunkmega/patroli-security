@extends('layouts.supervisor')

@section('title', 'Monitoring')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #monitoringMap { height: 500px; border-radius: 16px; }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Monitoring Realtime</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pantau posisi guard dan status checkpoint</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-1 border border-gray-200 dark:border-dark-700">
                <div id="monitoringMap"></div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Guard Online</h3>
                <div class="space-y-2">
                    @forelse($guards as $guard)
                    <div class="flex items-center justify-between p-2 rounded-lg {{ $guard->activePatrol ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-dark-700/50' }}">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $guard->activePatrol ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $guard->full_name }}</span>
                        </div>
                        <span class="text-xs {{ $guard->activePatrol ? 'text-green-500' : 'text-gray-400' }}">{{ $guard->activePatrol ? 'Patroli' : 'Idle' }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Tidak ada guard</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Scan Terakhir</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @forelse($latestLogs as $log)
                    <div class="text-xs p-2 bg-gray-50 dark:bg-dark-700/30 rounded-lg">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $log->guardRel?->full_name }}</p>
                        <p class="text-gray-500">{{ $log->checkpoint?->name }} <span class="{{ $log->status === 'safe' ? 'text-green-500' : 'text-red-500' }}">{{ $log->status }}</span></p>
                        <p class="text-gray-400">{{ $log->scan_time->format('H:i:s') }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada scan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('monitoringMap').setView([-8.409518, 115.188916], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        var guardIcon = L.divIcon({
            className: 'custom-guard-icon',
            html: '<div style="background:#f97316;width:12px;height:12px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.3);"></div>',
            iconSize: [12, 12],
            iconAnchor: [6, 6]
        });

        var checkpointIcon = L.divIcon({
            className: 'custom-checkpoint-icon',
            html: '<div style="background:#3b82f6;width:10px;height:10px;border-radius:50%;border:2px solid white;box-shadow:0 2px 4px rgba(0,0,0,0.3);"></div>',
            iconSize: [10, 10],
            iconAnchor: [5, 5]
        });

        @foreach($checkpoints as $cp)
        L.circle([{{ $cp->latitude }}, {{ $cp->longitude }}], {
            color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 0.05, radius: {{ $cp->radius }}, weight: 1
        }).addTo(map);
        L.marker([{{ $cp->latitude }}, {{ $cp->longitude }}], { icon: checkpointIcon })
            .bindPopup('<b>{{ $cp->name }}</b><br>{{ $cp->area?->name }}');
        @endforeach

        @foreach($guards as $guard)
            @if($guard->activePatrol && $guard->activePatrol->logs->isNotEmpty())
                @php $lastLog = $guard->activePatrol->logs->last(); @endphp
                L.marker([{{ $lastLog->latitude }}, {{ $lastLog->longitude }}], { icon: guardIcon })
                    .bindPopup('<b>{{ $guard->full_name }}</b><br>{{ $guard->guard_number }}');
            @endif
        @endforeach
    });
</script>
@endpush
@endsection
