@extends('layouts.admin')

@section('title', $guard->full_name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $guard->full_name }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $guard->guard_number }} - {{ $guard->user?->email }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.guards.edit', $guard) }}" class="px-4 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600">Edit</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Detail Guard</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Nama Lengkap</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->full_name }}</dd></div>
                    <div><dt class="text-gray-500">Nomor Guard</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->guard_number }}</dd></div>
                    <div><dt class="text-gray-500">NIK</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->nik ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Jenis Kelamin</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div><dt class="text-gray-500">Telepon</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->phone ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->user?->email }}</dd></div>
                    <div><dt class="text-gray-500">Tanggal Lahir</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->birth_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Tanggal Bergabung</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->join_date?->format('d/m/Y') ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Gol. Darah</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->blood_type ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd class="font-medium"><span class="px-2 py-1 text-xs rounded-lg {{ $guard->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $guard->is_active ? 'Aktif' : 'Nonaktif' }}</span></dd></div>
                    <div class="md:col-span-2"><dt class="text-gray-500">Alamat</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->address ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Kontak Darurat</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">Nama</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->emergency_name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Kontak</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $guard->emergency_contact ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Riwayat Patroli</h3>
                <div class="space-y-3">
                    @forelse($guard->patrols as $patrol)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $patrol->patrol_number }}</p>
                            <p class="text-xs text-gray-500">{{ $patrol->area?->name }} - {{ $patrol->start_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-lg {{ $patrol->status === 'completed' ? 'bg-green-100 text-green-700' : ($patrol->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $patrol->status }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada patroli</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Absensi</h3>
                <div class="space-y-2">
                    @forelse($guard->attendance->take(10) as $att)
                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-dark-700/50 rounded-lg">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $att->date->format('d/m') }}</span>
                        <span class="text-xs">{{ $att->check_in_time?->format('H:i') ?? '-' }} - {{ $att->check_out_time?->format('H:i') ?? '-' }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded {{ $att->status === 'present' ? 'bg-green-100 text-green-700' : ($att->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $att->status }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada absensi</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Jadwal</h3>
                <div class="space-y-2">
                    @forelse($guard->schedules as $sched)
                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-dark-700/50 rounded-lg">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $sched->shift }}</span>
                        <span class="text-xs text-gray-500">{{ $sched->area?->name }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Belum ada jadwal</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
