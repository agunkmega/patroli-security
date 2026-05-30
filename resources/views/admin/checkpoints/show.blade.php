@extends('layouts.admin')

@section('title', $checkpoint->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $checkpoint->name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $checkpoint->code }} - {{ $checkpoint->area?->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.checkpoints.print-qr', $checkpoint) }}" target="_blank" class="px-4 py-2.5 bg-purple-500 text-white rounded-xl hover:bg-purple-600">Print QR</a>
            <a href="{{ route('admin.checkpoints.edit', $checkpoint) }}" class="px-4 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Detail Checkpoint</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Kode</dt><dd class="font-medium text-gray-900 dark:text-white font-mono">{{ $checkpoint->code }}</dd></div>
                    <div><dt class="text-gray-500">Area</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $checkpoint->area?->name }}</dd></div>
                    <div><dt class="text-gray-500">Latitude</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $checkpoint->latitude }}</dd></div>
                    <div><dt class="text-gray-500">Longitude</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $checkpoint->longitude }}</dd></div>
                    <div><dt class="text-gray-500">Radius</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $checkpoint->radius }}m</dd></div>
                    <div><dt class="text-gray-500">Urutan</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $checkpoint->order }}</dd></div>
                </dl>
                @if($checkpoint->description)
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">{{ $checkpoint->description }}</p>
                @endif
                @if($checkpoint->instruction)
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Instruksi:</p>
                    <p class="text-sm text-blue-800 dark:text-blue-200">{{ $checkpoint->instruction }}</p>
                </div>
                @endif
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Riwayat Scan</h3>
                <div class="space-y-3">
                    @forelse($checkpoint->patrolLogs as $log)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->guardRel?->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $log->scan_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-lg {{ $log->status === 'safe' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $log->status }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada riwayat scan</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700 text-center">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">QR Code</h3>
                @if($checkpoint->qr_path)
                    <img src="{{ asset('storage/' . $checkpoint->qr_path) }}" alt="QR {{ $checkpoint->code }}" class="w-48 h-48 mx-auto">
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('admin.checkpoints.print-qr', $checkpoint) }}" target="_blank" class="block w-full px-4 py-2 bg-purple-500 text-white rounded-xl text-sm hover:bg-purple-600">Print QR</a>
                        <a href="{{ route('qrcode.download', $checkpoint) }}" class="block w-full px-4 py-2 bg-orange-500 text-white rounded-xl text-sm hover:bg-orange-600">Download PNG</a>
                    </div>
                @else
                    <div class="w-48 h-48 mx-auto bg-gray-100 dark:bg-dark-700 rounded-xl flex items-center justify-center">
                        <span class="text-gray-400 text-sm">Belum ada QR</span>
                    </div>
                    <form method="POST" action="{{ route('admin.checkpoints.generate-qr', $checkpoint) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 text-white rounded-xl text-sm hover:bg-orange-600">Generate QR</button>
                    </form>
                @endif
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Map</h3>
                <div id="checkpointMap" class="h-48 rounded-xl bg-gray-100 dark:bg-dark-700"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('checkpointMap').setView([{{ $checkpoint->latitude }}, {{ $checkpoint->longitude }}], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        L.circle([{{ $checkpoint->latitude }}, {{ $checkpoint->longitude }}], {
            color: '#f97316', fillColor: '#f97316', fillOpacity: 0.1, radius: {{ $checkpoint->radius }}
        }).addTo(map);
        L.marker([{{ $checkpoint->latitude }}, {{ $checkpoint->longitude }}]).addTo(map)
            .bindPopup('<b>{{ $checkpoint->name }}</b>');
    });
</script>
@endpush
@endsection
