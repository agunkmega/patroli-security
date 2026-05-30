@extends('layouts.supervisor')

@section('title', 'Dashboard Supervisor')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Supervisor</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Pantau patroli secara realtime</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Patroli Aktif</span>
                <div class="w-10 h-10 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['activePatrols'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Guard Online</span>
                <div class="w-10 h-10 bg-green-50 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['activeGuards'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Scan Hari Ini</span>
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['scansToday'] }}</p>
        </div>
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Emergency</span>
                <div class="w-10 h-10 bg-red-50 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-500">{{ $data['pendingEmergencies'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Guard Online</h3>
            <div class="space-y-3">
                @forelse($guardsOnline as $guard)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($guard->full_name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $guard->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $guard->guard_number }}</p>
                        </div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada guard online</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Emergency Pending</h3>
            <div class="space-y-3">
                @forelse($emergencies as $emergency)
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ strtoupper($emergency->type) }}</p>
                        </div>
                        <p class="text-xs text-gray-500">{{ $emergency->guardRel?->full_name }} - {{ $emergency->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('emergency.index') }}" class="text-xs px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600">Respon</a>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada emergency</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Aktivitas Patroli Terbaru</h3>
        <div class="space-y-2">
            @forelse($recentLogs as $log)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-dark-700/30 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full {{ $log->status === 'safe' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->guardRel?->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $log->checkpoint?->name }} - {{ $log->scan_time->format('H:i') }}</p>
                    </div>
                </div>
                <span class="text-xs {{ $log->status === 'safe' ? 'text-green-500' : 'text-red-500' }}">{{ $log->status }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
