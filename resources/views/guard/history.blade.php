@extends('layouts.guard')

@section('title', 'Riwayat Patroli')

@section('content')
<div class="space-y-4 animate-fade-in">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Patroli</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Semua riwayat patroli</p>
    </div>

    <div class="space-y-3">
        @forelse($patrols as $patrol)
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $patrol->patrol_number }}</span>
                <span class="text-xs px-2.5 py-1 rounded-lg font-medium 
                    {{ $patrol->status === 'completed' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : '' }}
                    {{ $patrol->status === 'in_progress' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : '' }}
                    {{ $patrol->status === 'missed' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : '' }}">
                    {{ $patrol->status === 'completed' ? 'Selesai' : ($patrol->status === 'in_progress' ? 'Aktif' : 'Terlewat') }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span>{{ $patrol->area?->name ?? '-' }}</span>
                <span>{{ $patrol->start_time->format('d/m/Y H:i') }}</span>
                @if($patrol->end_time)
                <span>- {{ $patrol->end_time->format('H:i') }}</span>
                @endif
            </div>
            <div class="mt-2 flex items-center gap-2">
                <div class="flex-1 bg-gray-200 dark:bg-dark-700 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full {{ $patrol->progressPercent() >= 100 ? 'bg-green-500' : 'bg-orange-500' }}" style="width: {{ $patrol->progressPercent() }}%"></div>
                </div>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $patrol->scanned_checkpoints }}/{{ $patrol->total_checkpoints }}</span>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-dark-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400">Belum ada riwayat patroli</p>
            <a href="{{ route('guard.patrol.start') }}" class="inline-block mt-3 px-6 py-2.5 bg-orange-500 text-white rounded-xl text-sm font-medium">Mulai Patroli</a>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $patrols->links() }}
    </div>
</div>
@endsection
