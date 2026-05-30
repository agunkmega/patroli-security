@extends('layouts.guard')

@section('title', 'Mulai Patroli')

@section('content')
<div class="space-y-4 animate-fade-in">
    <div>
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Mulai Patroli</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Pilih area patroli</p>
    </div>

    @if($schedule)
    <div class="bg-gradient-to-r from-orange-500 to-orange-700 rounded-2xl p-4 text-white">
        <p class="text-sm text-orange-100">Jadwal hari ini</p>
        <p class="font-bold">{{ $schedule->name }} - {{ $schedule->shift }}</p>
        <p class="text-xs text-orange-200 mt-1">{{ $schedule->area?->name }} | {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('guard.patrol.begin') }}" class="space-y-3">
        @csrf
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Area Patroli</label>
            @if($area && $area->id)
            <input type="hidden" name="area_id" value="{{ $area->id }}">
            <div class="p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                <p class="font-medium text-gray-900 dark:text-white">{{ $area->name }}</p>
                <p class="text-xs text-gray-500">{{ $checkpoints->count() }} checkpoint</p>
            </div>
            @else
            <select name="area_id" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500">
                <option value="">Pilih Area</option>
                @foreach(\App\Models\Area::where('is_active', true)->get() as $a)
                <option value="{{ $a->id }}">{{ $a->name }} ({{ $a->active_checkpoints_count ?? $a->checkpoints()->count() }} checkpoint)</option>
                @endforeach
            </select>
            @endif

            @if($schedule)
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            @endif
        </div>

        @if($checkpoints->isNotEmpty())
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Daftar Checkpoint</h3>
            <div class="space-y-2">
                @foreach($checkpoints as $cp)
                <div class="flex items-center gap-3 p-2.5 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">{{ $loop->iteration }}</div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $cp->name }}</p>
                        <p class="text-xs text-gray-500">{{ $cp->code }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-semibold rounded-2xl hover:from-orange-600 hover:to-orange-800 transition-all shadow-lg shadow-orange-500/20 active:scale-[0.98]">
            Mulai Patroli Sekarang
        </button>
    </form>
</div>
@endsection
