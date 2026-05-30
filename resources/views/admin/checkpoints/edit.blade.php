@extends('layouts.admin')
@section('title', 'Edit Checkpoint')
@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{ latitude: '{{ old('latitude', $checkpoint->latitude) }}', longitude: '{{ old('longitude', $checkpoint->longitude) }}', getCurrentPosition() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(pos => { this.latitude = pos.coords.latitude; this.longitude = pos.coords.longitude; }, err => { alert('Gagal akses lokasi: ' + err.message + '. Coba pakai localhost atau HTTPS.'); }, { enableHighAccuracy: true, timeout: 10000 }) } else { alert('Browser tidak mendukung GPS.'); } } }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Checkpoint</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Ubah titik checkpoint</p>
    </div>

    <form method="POST" action="{{ route('admin.checkpoints.update', $checkpoint) }}" class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode *</label>
                <input type="text" name="code" value="{{ old('code', $checkpoint->code) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="CP-001">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama *</label>
                <input type="text" name="name" value="{{ old('name', $checkpoint->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="Pos Depan Utama">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Area *</label>
                <select name="area_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Pilih Area</option>
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id', $checkpoint->area_id) == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Radius (meter)</label>
                <input type="number" name="radius" value="{{ old('radius', $checkpoint->radius ?? 30) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Latitude *</label>
                <div class="flex gap-2">
                    <input type="text" name="latitude" x-model="latitude" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="-8.409518">
                    <button type="button" @click="getCurrentPosition()" class="px-3 py-2.5 bg-blue-500 text-white rounded-xl hover:bg-blue-600 text-sm shrink-0">Detect</button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Longitude *</label>
                <input type="text" name="longitude" x-model="longitude" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="115.188916">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $checkpoint->order ?? 0) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Wajib Foto</label>
                <label class="flex items-center gap-3 mt-2">
                    <input type="checkbox" name="require_photo" value="1" {{ $checkpoint->require_photo ? 'checked' : '' }} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500/20">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Ya, wajib upload foto</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">{{ old('description', $checkpoint->description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instruksi</label>
                <textarea name="instruction" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="Instruksi khusus di checkpoint ini">{{ old('instruction', $checkpoint->instruction) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-800 transition-all">Simpan</button>
            <a href="{{ route('admin.checkpoints.index') }}" class="px-6 py-2.5 text-gray-600 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection