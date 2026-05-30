@extends('layouts.guard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 animate-fade-in">
    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -translate-x-1/2 translate-y-1/2"></div>
        <div class="relative z-10">
            <p class="text-orange-100 text-sm">Selamat datang,</p>
            <h2 class="text-xl font-bold mt-1">{{ $guard->full_name }}</h2>
            <p class="text-orange-200 text-xs mt-1">{{ $guard->guard_number }}</p>
            <div class="flex items-center gap-4 mt-4">
                <div class="bg-white/20 backdrop-blur rounded-xl px-4 py-2 text-center">
                    <p class="text-2xl font-bold">{{ $stats['total_patrols'] }}</p>
                    <p class="text-[10px] text-orange-100">Patroli</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-xl px-4 py-2 text-center">
                    <p class="text-2xl font-bold">{{ $stats['completed'] }}</p>
                    <p class="text-[10px] text-orange-100">Selesai</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-xl px-4 py-2 text-center">
                    <p class="text-2xl font-bold">{{ $stats['today_scans'] }}</p>
                    <p class="text-[10px] text-orange-100">Scan Hari Ini</p>
                </div>
            </div>
        </div>
    </div>

    @if($activePatrol)
    <a href="{{ route('guard.patrol.scan', $activePatrol) }}" class="block bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 text-white relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                    <span class="text-sm font-semibold">Patroli Aktif</span>
                </div>
                <p class="text-lg font-bold mt-1">{{ $activePatrol->patrol_number }}</p>
                <p class="text-green-100 text-xs">{{ $activePatrol->area?->name }}</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold">{{ $activePatrol->scanned_checkpoints }}/{{ $activePatrol->total_checkpoints }}</p>
                <p class="text-[10px] text-green-100">Checkpoint</p>
            </div>
        </div>
        <div class="mt-3 bg-white/20 rounded-full h-2">
            <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: {{ $activePatrol->progressPercent() }}%"></div>
        </div>
    </a>
    @endif

    @if($todaySchedule)
    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Jadwal Hari Ini</h3>
            <span class="text-xs px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg">{{ $todaySchedule->shift }}</span>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <div class="flex items-center gap-1 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ substr($todaySchedule->start_time, 0, 5) }} - {{ substr($todaySchedule->end_time, 0, 5) }}
            </div>
            <span class="text-gray-300">|</span>
            <span class="text-gray-500">{{ $todaySchedule->area?->name }}</span>
        </div>
        <div class="mt-2 flex flex-wrap gap-1">
            @foreach($todaySchedule->checkpoints as $cp)
            <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 rounded-full">{{ $cp->name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-3">
        @if(!$activePatrol)
        <a href="{{ route('guard.patrol.start') }}" class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700 text-center hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Mulai Patroli</p>
            <p class="text-xs text-gray-500 mt-1">Scan checkpoint</p>
        </a>
        @endif

        <a href="{{ route('guard.history') }}" class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700 text-center hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Riwayat</p>
            <p class="text-xs text-gray-500 mt-1">Patroli selesai</p>
        </a>

        <a href="{{ route('attendance.index') }}" class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700 text-center hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white">Absensi</p>
            <p class="text-xs text-gray-500 mt-1">Check in/out</p>
        </a>

        <a href="{{ route('emergency.create') }}" class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-red-200 dark:border-red-900/30 text-center hover:shadow-lg transition-all">
            <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-2 animate-pulse-slow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <p class="text-sm font-medium text-red-600 dark:text-red-400">SOS Darurat</p>
            <p class="text-xs text-gray-500 mt-1">Bantuan segera</p>
        </a>
    </div>

    @if($todayLogs->isNotEmpty())
    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Scan Terakhir</h3>
        <div class="space-y-2">
            @foreach($todayLogs as $log)
            <div class="flex items-center justify-between p-2.5 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full {{ $log->status === 'safe' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->checkpoint?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $log->scan_time->format('H:i') }}</p>
                    </div>
                </div>
                <span class="text-xs {{ $log->status === 'safe' ? 'text-green-500' : 'text-red-500' }}">{{ $log->status }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
