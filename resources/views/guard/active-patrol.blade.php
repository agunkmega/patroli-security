@extends('layouts.guard')

@section('title', 'Patroli Aktif')

@section('content')
<div class="space-y-4 animate-fade-in">
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                    <span class="text-sm">Patroli Aktif</span>
                </div>
                <p class="text-lg font-bold mt-1">{{ $patrol->patrol_number }}</p>
                <p class="text-green-100 text-xs">{{ $patrol->area?->name }}</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold">{{ $patrol->scanned_checkpoints }}/{{ $patrol->total_checkpoints }}</p>
                <p class="text-[10px] text-green-100">Checkpoint</p>
            </div>
        </div>
        <div class="mt-3 bg-white/20 rounded-full h-2.5">
            <div class="bg-white rounded-full h-2.5 transition-all duration-500" style="width: {{ $patrol->progressPercent() }}%"></div>
        </div>
        <p class="text-xs text-green-100 mt-1 text-right">{{ $patrol->progressPercent() }}% selesai</p>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Scan Checkpoint</h3>
        <a href="{{ route('guard.patrol.scan', $patrol) }}" class="w-full py-14 bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl flex flex-col items-center justify-center text-white">
            <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            <span class="text-lg font-bold">Scan QR Code</span>
            <span class="text-sm text-orange-200">Tekan untuk scan checkpoint</span>
        </a>
    </div>

    @if($patrol->logs->isNotEmpty())
    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Riwayat Scan</h3>
        <div class="space-y-2">
            @foreach($patrol->logs as $log)
            <div class="flex items-center justify-between p-2.5 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full {{ $log->status === 'safe' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->checkpoint?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $log->scan_time->format('H:i:s') }}</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 rounded-lg {{ $log->status === 'safe' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $log->status }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('guard.patrol.complete', $patrol) }}" onsubmit="return confirm('Selesaikan patroli?')">
        @csrf
        <button type="submit" class="w-full py-3 bg-red-500 text-white font-semibold rounded-2xl hover:bg-red-600 transition-all active:scale-[0.98]">
            Akhiri Patroli
        </button>
    </form>
</div>
@endsection
