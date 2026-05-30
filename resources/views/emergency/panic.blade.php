@extends('layouts.guard')

@section('title', 'SOS Darurat')

@section('content')
<div class="space-y-4 animate-fade-in" x-data="emergencySOS()">
    <div class="text-center">
        <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <h1 class="text-xl font-bold text-red-600 dark:text-red-400">Emergency SOS</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kirim sinyal darurat ke supervisor</p>
    </div>

    <form method="POST" action="{{ route('emergency.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <input type="hidden" name="latitude" x-model="latitude">
        <input type="hidden" name="longitude" x-model="longitude">

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tipe Darurat</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach(['sos' => 'SOS', 'fire' => 'Kebakaran', 'theft' => 'Pencurian', 'accident' => 'Kecelakaan', 'medical' => 'Medis', 'other' => 'Lainnya'] as $val => $label)
                <label class="flex items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all"
                    x-data x-bind:class="type === '{{ $val }}' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-dark-600'">
                    <input type="radio" name="type" value="{{ $val }}" x-model="type" class="sr-only">
                    <span class="text-sm font-medium" x-text="'{{ $label }}'"></span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
            <textarea name="description" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-red-500" placeholder="Jelaskan situasi darurat..."></textarea>
        </div>

        <div class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto (opsional)</label>
            <input type="file" name="photo" accept="image/*" capture="environment" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white">
        </div>

        <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-500 to-red-700 text-white font-bold text-lg rounded-2xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg shadow-red-500/30 active:scale-[0.98] animate-pulse-slow">
            Kirim SOS Darurat
        </button>
    </form>
</div>

@push('scripts')
<script>
function emergencySOS() {
    return {
        type: 'sos',
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
