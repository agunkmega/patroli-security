@extends(Auth::user()->isGuard() ? 'layouts.guard' : 'layouts.admin')

@section('title', 'Absensi')

@section('content')
<div class="space-y-6" x-data="attendance()">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Absensi</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Check in / check out kehadiran</p>
    </div>

    @if(Auth::user()->isGuard() && isset($todayAttendance))
    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Status Hari Ini</p>
                <p class="text-lg font-bold {{ $todayAttendance->check_out_time ? 'text-green-500' : 'text-orange-500' }}">
                    {{ $todayAttendance->check_out_time ? 'Sudah Check-out' : 'Sudah Check-in' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Check-in: {{ $todayAttendance->check_in_time?->format('H:i') }}</p>
                @if($todayAttendance->check_out_time)
                <p class="text-xs text-gray-500">Check-out: {{ $todayAttendance->check_out_time->format('H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->isGuard() && !isset($todayAttendance) || (isset($todayAttendance) && !$todayAttendance))
    <div class="grid grid-cols-2 gap-4">
        <form method="POST" action="{{ route('attendance.check-in') }}" enctype="multipart/form-data" class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 text-center">
            @csrf
            <input type="hidden" name="latitude" x-bind:value="latitude">
            <input type="hidden" name="longitude" x-bind:value="longitude">
            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white">Check In</h3>
            <p class="text-xs text-gray-500 mt-1">Mulai shift kerja</p>
            <div class="mt-4">
                <input type="file" name="photo" accept="image/*" capture="environment" class="text-xs w-full mb-3">
                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-green-500 to-green-700 text-white font-medium rounded-xl hover:from-green-600 hover:to-green-800 transition-all">Check In</button>
            </div>
        </form>

        <form method="POST" action="{{ route('attendance.check-out') }}" enctype="multipart/form-data" class="bg-white dark:bg-dark-800 rounded-2xl p-5 border border-gray-200 dark:border-dark-700 text-center">
            @csrf
            <input type="hidden" name="latitude" x-bind:value="latitude">
            <input type="hidden" name="longitude" x-bind:value="longitude">
            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </div>
            <h3 class="font-semibold text-gray-900 dark:text-white">Check Out</h3>
            <p class="text-xs text-gray-500 mt-1">Akhiri shift kerja</p>
            <div class="mt-4">
                <input type="file" name="photo" accept="image/*" capture="environment" class="text-xs w-full mb-3">
                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-red-500 to-red-700 text-white font-medium rounded-xl hover:from-red-600 hover:to-red-800 transition-all">Check Out</button>
            </div>
        </form>
    </div>
    @endif

    @if(!Auth::user()->isGuard())
    <div class="bg-white dark:bg-dark-800 rounded-2xl border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-dark-700/50 text-left">
                        <th class="px-5 py-3 font-semibold">Guard</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 font-semibold">Check In</th>
                        <th class="px-5 py-3 font-semibold">Check Out</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-dark-700">
                    @forelse($attendances as $attendance)
                    <tr>
                        <td class="px-5 py-4">{{ $attendance->guardRel?->full_name }}</td>
                        <td class="px-5 py-4">{{ $attendance->date->format('d/m/Y') }}</td>
                        <td class="px-5 py-4">{{ $attendance->check_in_time?->format('H:i') ?? '-' }}</td>
                        <td class="px-5 py-4">{{ $attendance->check_out_time?->format('H:i') ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 text-xs rounded-lg {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700' : ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $attendance->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada data absensi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(Auth::user()->isGuard())
    <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Riwayat Absensi</h3>
        <div class="space-y-2">
            @forelse($attendances as $attendance)
            <div class="flex items-center justify-between p-2.5 bg-gray-50 dark:bg-dark-700/50 rounded-xl">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $attendance->check_in_time?->format('H:i') }} - {{ $attendance->check_out_time?->format('H:i') ?? 'Belum out' }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-lg {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700' : ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $attendance->status }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat absensi</p>
            @endforelse
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function attendance() {
    return {
        latitude: '',
        longitude: '',
        init() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    this.latitude = pos.coords.latitude;
                    this.longitude = pos.coords.longitude;
                }, () => {}, { enableHighAccuracy: true, timeout: 10000 });
            }
        }
    }
}
</script>
@endpush
@endsection
