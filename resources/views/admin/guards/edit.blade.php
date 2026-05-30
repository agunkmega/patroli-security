@extends('layouts.admin')

@section('title', 'Edit Guard')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Guard</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ $guard->full_name }} ({{ $guard->guard_number }})</p>
    </div>

    <form method="POST" action="{{ route('admin.guards.update', $guard) }}" class="bg-white dark:bg-dark-800 rounded-2xl p-6 border border-gray-200 dark:border-dark-700 space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap *</label>
                <input type="text" name="full_name" value="{{ old('full_name', $guard->full_name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Guard *</label>
                <input type="text" name="guard_number" value="{{ old('guard_number', $guard->guard_number) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama User *</label>
                <input type="text" name="name" value="{{ old('name', $guard->user?->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $guard->user?->email) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $guard->nik) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $guard->phone) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                <select name="gender" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                    <option value="male" {{ $guard->gender === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ $guard->gender === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $guard->birth_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Bergabung *</label>
                <input type="date" name="join_date" value="{{ old('join_date', $guard->join_date?->format('Y-m-d') ?? date('Y-m-d')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gol Darah</label>
                <input type="text" name="blood_type" value="{{ old('blood_type', $guard->blood_type) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kontak Darurat</label>
                <input type="text" name="emergency_name" value="{{ old('emergency_name', $guard->emergency_name) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">No Kontak Darurat</label>
                <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $guard->emergency_contact) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
                <textarea name="address" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">{{ old('address', $guard->address) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ $guard->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-medium rounded-xl hover:from-orange-600 hover:to-orange-800 transition-all">Simpan</button>
            <a href="{{ route('admin.guards.index') }}" class="px-6 py-2.5 text-gray-600 dark:text-gray-300 font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-dark-700 transition-all">Batal</a>
        </div>
    </form>
</div>
@endsection
