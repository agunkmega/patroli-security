@extends(Auth::user()->isGuard() ? 'layouts.guard' : 'layouts.admin')

@section('title', 'Emergency Reports')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Emergency</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Daftar laporan darurat</p>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div class="divide-y divide-gray-200 dark:divide-dark-700">
            @forelse($reports as $report)
            <div class="p-5 hover:bg-gray-50 dark:hover:bg-dark-700/30">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold
                            {{ $report->status === 'pending' ? 'bg-red-500 animate-pulse' : ($report->status === 'responded' ? 'bg-yellow-500' : 'bg-green-500') }}">
                            {{ strtoupper(substr($report->type, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ strtoupper($report->type) }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full 
                                    {{ $report->status === 'pending' ? 'bg-red-100 text-red-700' : ($report->status === 'responded' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $report->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $report->description }}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                <span>{{ $report->guardRel?->full_name }}</span>
                                <span>{{ $report->created_at->diffForHumans() }}</span>
                                @if($report->latitude && $report->longitude)
                                <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="text-blue-500 hover:underline">Lihat Map</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->isSupervisor() || Auth::user()->isAdmin())
                    <div class="flex items-center gap-2 shrink-0">
                        @if($report->status === 'pending')
                        <form method="POST" action="{{ route('emergency.respond', $report) }}">
                            @csrf
                            <input type="hidden" name="notes" value="Sedang dalam penanganan">
                            <button type="submit" class="text-xs px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Respon</button>
                        </form>
                        @endif
                        @if(in_array($report->status, ['pending', 'responded']))
                        <form method="POST" action="{{ route('emergency.resolve', $report) }}">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1.5 bg-green-500 text-white rounded-lg hover:bg-green-600">Selesai</button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-400">Belum ada laporan emergency</div>
            @endforelse
        </div>
    </div>
    <div class="mt-4">{{ $reports->links() }}</div>
</div>
@endsection
