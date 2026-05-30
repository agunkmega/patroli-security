@extends('layouts.admin')

@section('title', 'Area Patroli')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Area Patroli</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola area patroli</p>
        </div>
        <a href="{{ route('admin.areas.create') }}" class="px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white rounded-xl font-medium">Tambah Area</a>
    </div>

    <div class="grid gap-4">
        @forelse($areas as $area)
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold">{{ substr($area->code, 0, 3) }}</div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $area->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $area->code }} - {{ $area->checkpoints_count }} checkpoint</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.areas.edit', $area) }}" class="px-3 py-1.5 text-xs bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-lg hover:bg-orange-100">Edit</a>
                    <form method="POST" action="{{ route('admin.areas.destroy', $area) }}" onsubmit="return confirm('Hapus area?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs bg-red-50 dark:bg-red-900/20 text-red-600 rounded-lg hover:bg-red-100">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-400">Belum ada area patroli</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $areas->links() }}</div>
</div>
@endsection
