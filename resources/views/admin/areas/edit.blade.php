@extends('layouts.admin')
@section('title', 'Edit Area')
@section('content')
<div class="max-w-2xl mx-auto" x-data="{ latitude: '{{ old('latitude', $area->latitude) }}', longitude: '{{ old('longitude', $area->longitude) }}', getCurrentPosition() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(pos => { this.latitude = pos.coords.latitude; this.longitude = pos.coords.longitude; }, err => { alert('Gagal akses lokasi: ' + err.message + '. Coba pakai localhost atau HTTPS.'); }, { enableHighAccuracy: true, timeout: 10000 }) } else { alert('Browser tidak mendukung GPS.'); } } }">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Area</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Ubah area patroli</p>
    </div>
    <form method="POST" action="{{ route('admin.areas.update', $area) }}" class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700 space-y-5">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode *</label>
                <input type="text" name="code" value="{{ old('code', $area->code) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500" placeholder="AR-001">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $area->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500" placeholder="Area Utama">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude</label>
                <div class="flex gap-2">
                    <input type="text" name="latitude" x-model="latitude" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500">
                    <button type="button" @click="getCurrentPosition()" class="px-3 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 text-sm shrink-0">Detect</button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude</label>
                <input type="text" name="longitude" x-model="longitude" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
            <textarea name="address" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500">{{ old('address', $area->address) }}</textarea>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl">Simpan</button>
            <a href="{{ route('admin.areas.index') }}" class="px-6 py-2.5 text-gray-600 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700">Batal</a>
        </div>
    </form>
</div>
@endsection