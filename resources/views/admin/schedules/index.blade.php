@extends('layouts.admin')
@section('title', 'Jadwal Patroli')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Patroli</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola jadwal shift guard</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white rounded-xl font-medium">Tambah Jadwal</a>
    </div>
    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 dark:bg-dark-700/50 text-left">
                    <th class="px-5 py-3 font-semibold">Nama</th>
                    <th class="px-5 py-3 font-semibold">Guard</th>
                    <th class="px-5 py-3 font-semibold">Shift</th>
                    <th class="px-5 py-3 font-semibold">Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Jam</th>
                    <th class="px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-dark-700">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30">
                    <td class="px-5 py-4 font-medium">{{ $schedule->name }}</td>
                    <td class="px-5 py-4">{{ $schedule->guardRel?->full_name ?? 'Fleksibel' }}</td>
                    <td class="px-5 py-4"><span class="px-2 py-1 text-xs rounded-lg bg-orange-100 dark:bg-orange-900/30 text-orange-700">{{ $schedule->shift }}</span></td>
                    <td class="px-5 py-4">{{ $schedule->date ? $schedule->date->format('d/m/Y') : 'Harian' }}</td>
                    <td class="px-5 py-4">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="px-3 py-1.5 text-xs bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-lg">Edit</a>
                        <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Hapus jadwal?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs bg-red-50 dark:bg-red-900/20 text-red-600 rounded-lg">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada jadwal</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $schedules->links() }}
</div>
@endsection
