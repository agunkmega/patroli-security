@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Jadwal</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buat jadwal patroli baru</p>
    </div>
    <form method="POST" action="{{ route('admin.schedules.store') }}" class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700 space-y-5">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Jadwal *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="Patroli Pagi">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Guard (opsional)</label>
                <select name="guard_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Semua Guard (fleksibel)</option>
                    @foreach($guards as $guard)
                    <option value="{{ $guard->id }}" {{ old('guard_id') == $guard->id ? 'selected' : '' }}>{{ $guard->user?->name }} - {{ $guard->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Area</label>
                <select name="area_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Pilih Area</option>
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Shift *</label>
                <select name="shift" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Pilih Shift</option>
                    <option value="morning" {{ old('shift') == 'morning' ? 'selected' : '' }}>Pagi (06:00 - 14:00)</option>
                    <option value="afternoon" {{ old('shift') == 'afternoon' ? 'selected' : '' }}>Siang (14:00 - 22:00)</option>
                    <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Malam (22:00 - 06:00)</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Jadwal</label>
                <div class="flex gap-4" x-data="{ isDaily: true }">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_daily" value="1" x-model="isDaily" checked class="text-orange-500 focus:ring-orange-500/20">
                        <span class="text-sm">Harian (berulang)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_daily" value="0" x-model="isDaily" class="text-orange-500 focus:ring-orange-500/20">
                        <span class="text-sm">Tanggal Spesifik</span>
                    </label>
                    <div x-show="!isDaily" x-transition>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Mulai *</label>
                <input type="time" name="start_time" value="{{ old('start_time', '06:00') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jam Selesai *</label>
                <input type="time" name="end_time" value="{{ old('end_time', '14:00') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Checkpoints</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-200 dark:border-dark-600 rounded-xl p-3">
                    @foreach($checkpoints as $cp)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="checkpoints[]" value="{{ $cp->id }}" {{ in_array($cp->id, old('checkpoints', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-orange-500 focus:ring-orange-500/20">
                        <span>{{ $cp->code }} - {{ $cp->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-800 transition-all">Simpan</button>
            <a href="{{ route('admin.schedules.index') }}" class="px-6 py-2.5 text-gray-600 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection