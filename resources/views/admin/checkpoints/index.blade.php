@extends('layouts.admin')

@section('title', 'Checkpoint')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Checkpoint</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola titik checkpoint patroli</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.checkpoints.print-all-qr') }}" class="px-4 py-2.5 border border-gray-300 dark:border-dark-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700 transition-all">Print All QR</a>
            <a href="{{ route('admin.checkpoints.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-800 transition-all shadow-lg shadow-orange-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Checkpoint
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-700/50 text-left">
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Kode</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Area</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Koordinat</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">QR Code</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700">
                    @forelse($checkpoints as $cp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30">
                        <td class="px-5 py-4 font-mono text-sm text-gray-900 dark:text-white">{{ $cp->code }}</td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $cp->name }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $cp->area?->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-xs text-gray-500">
                            {{ $cp->latitude }}, {{ $cp->longitude }}
                        </td>
                        <td class="px-5 py-4">
                            @if($cp->qr_path)
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg text-xs">Ada</span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-dark-600 text-gray-500 rounded-lg text-xs">Belum</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.checkpoints.show', $cp) }}" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-100">Lihat</a>
                                <a href="{{ route('admin.checkpoints.edit', $cp) }}" class="p-2 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 hover:bg-orange-100">Edit</a>
                                <a href="{{ route('admin.checkpoints.print-qr', $cp) }}" target="_blank" class="p-2 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 hover:bg-purple-100">QR</a>
                                <form method="POST" action="{{ route('admin.checkpoints.destroy', $cp) }}" onsubmit="return confirm('Hapus checkpoint?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada checkpoint</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200 dark:border-dark-700">{{ $checkpoints->links() }}</div>
    </div>
</div>
@endsection
