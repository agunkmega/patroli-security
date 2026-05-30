@extends('layouts.admin')

@section('title', 'Data Guard')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Guard</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola data security guard</p>
        </div>
        <a href="{{ route('admin.guards.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-800 transition-all shadow-lg shadow-orange-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Guard
        </a>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-700/50 text-left">
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Nama</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">No Guard</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Email</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">No Telp</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700">
                    @forelse($guards as $guard)
                    <tr class="hover:bg-gray-50 dark:hover:bg-dark-700/30">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-xs">{{ strtoupper(substr($guard->full_name, 0, 1)) }}</div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $guard->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $guard->guard_number }}</td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $guard->user->email }}</td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $guard->phone ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium {{ $guard->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                {{ $guard->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.guards.show', $guard) }}" class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-100">Lihat</a>
                                <a href="{{ route('admin.guards.edit', $guard) }}" class="p-2 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 hover:bg-orange-100">Edit</a>
                                <form method="POST" action="{{ route('admin.guards.destroy', $guard) }}" onsubmit="return confirm('Hapus guard ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada data guard</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200 dark:border-dark-700">
            {{ $guards->links() }}
        </div>
    </div>
</div>
@endsection
