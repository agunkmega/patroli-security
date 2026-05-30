@extends('layouts.guard')

@section('title', 'Scan QR')

@section('styles')
<style>
    #qr-scanner { width: 100%; max-width: 400px; margin: 0 auto; }
    #qr-scanner video { border-radius: 16px !important; }
    #qr-scanner img { border-radius: 16px !important; }
    #qr-scanner__scan_region { min-height: 300px; background: #0f0d1e; border-radius: 16px; display: flex; align-items: center; justify-content: center; }
    #qr-scanner__dashboard_section_csr button {
        background: #f97316 !important; color: white !important; border: none !important;
        padding: 10px 24px !important; border-radius: 12px !important; font-weight: 600 !important;
        margin: 8px 0 !important; cursor: pointer !important;
    }
    #qr-scanner__dashboard_section_csr button:hover { background: #ea580c !important; }
    #qr-scanner__dashboard_section_csr span { color: #94a3b8 !important; }
    .popup-overlay { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); padding: 1rem; }
    .popup-card { background: white; border-radius: 20px; padding: 2rem; max-width: 360px; width: 100%; text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.25); }
    .dark .popup-card { background: #1e1b2e; }
</style>
@endsection

@section('content')
<div class="space-y-4 animate-fade-in" x-data="scanner()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Scan QR</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm" x-text="clock"></p>
        </div>
        <a href="{{ route('guard.patrol.active') }}" class="text-sm text-orange-500 font-medium">Kembali</a>
    </div>

    <div class="bg-white dark:bg-dark-800 rounded-2xl p-1 border border-gray-200 dark:border-dark-700 overflow-hidden">
        <div id="qr-scanner" x-ref="scanner"></div>
    </div>

    <div x-show="popup.show" class="popup-overlay" x-transition @click.away="popup.show = false">
        <div class="popup-card">
            <div x-show="popup.type === 'success'" class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div x-show="popup.type === 'error'" class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div x-show="popup.type === 'warning'" class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" x-text="popup.title"></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6" x-text="popup.message"></p>
            <button @click="popup.show = false; if (popup.type === 'success') showForm()" class="w-full py-3 rounded-xl font-semibold text-white transition-all"
                    :class="popup.type === 'success' ? 'bg-gradient-to-r from-orange-500 to-orange-700 hover:from-orange-600 hover:to-orange-800' : 'bg-gray-500 hover:bg-gray-600'"
                    x-text="popup.type === 'success' ? 'Lanjutkan' : 'Tutup'"></button>
        </div>
    </div>

    <div x-show="scanResult" class="bg-white dark:bg-dark-800 rounded-2xl p-4 border border-gray-200 dark:border-dark-700" x-transition>
        <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">Hasil Scan</h3>
        <div class="text-center">
            <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="checkpointName"></p>
            <p class="text-sm text-gray-500" x-text="checkpointCode"></p>
        </div>

        <form method="POST" action="{{ route('guard.patrol.scan.process', $patrol->id) }}" enctype="multipart/form-data" class="mt-4 space-y-3" x-ref="scanForm">
            @csrf
            <input type="hidden" name="checkpoint_code" x-model="checkpointCode">
            <input type="hidden" name="latitude" x-model="latitude">
            <input type="hidden" name="longitude" x-model="longitude">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all"
                           :class="status === 'safe' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-dark-600'">
                        <input type="radio" name="status" value="safe" x-model="status" class="sr-only">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm font-medium">Aman</span>
                    </label>
                    <label class="flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all"
                           :class="status === 'unsafe' ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : 'border-gray-200 dark:border-dark-600'">
                        <input type="radio" name="status" value="unsafe" x-model="status" class="sr-only">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <span class="text-sm font-medium">Tidak Aman</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kondisi</label>
                <input type="text" name="condition" x-model="condition" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500" placeholder="Contoh: Normal, Rusak, Hilang">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto (opsional)</label>
                <input type="file" name="photo" accept="image/*" capture="environment"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 dark:file:bg-orange-900/20 file:text-orange-600 dark:file:text-orange-400 file:text-sm file:font-medium">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                <textarea name="notes" x-model="notes" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-dark-600 bg-white dark:bg-dark-700 text-gray-900 dark:text-white focus:border-orange-500" placeholder="Catatan kejadian..."></textarea>
            </div>

            <button type="button" @click="submitScan()"
                class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-orange-700 text-white font-semibold rounded-2xl hover:from-orange-600 hover:to-orange-800 transition-all active:scale-[0.98] shadow-lg shadow-orange-500/20">
                Simpan Scan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function scanner() {
    return {
        html5QrCode: null,
        scanResult: false,
        checkpointName: '',
        checkpointCode: '',
        latitude: '',
        longitude: '',
        status: 'safe',
        condition: '',
        notes: '',
        popup: { show: false, type: '', title: '', message: '' },
        submitting: false,
        clock: '',

        init() {
            this.getLocation();
            this.startScanner();
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },

        updateClock() {
            this.clock = new Date().toLocaleString('id-ID', {
                weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
        },

        getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    this.latitude = pos.coords.latitude;
                    this.longitude = pos.coords.longitude;
                }, err => {
                    console.error('GPS error:', err);
                    this.latitude = {{ $patrol->area?->latitude ?? '-8.409518' }};
                    this.longitude = {{ $patrol->area?->longitude ?? '115.188916' }};
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            }
        },

        startScanner() {
            this.html5QrCode = new Html5Qrcode("qr-scanner");
            this.html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 15,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1
                },
                (decodedText) => {
                    this.onScanSuccess(decodedText);
                },
                () => {}
            ).catch(err => {
                console.error('Camera error:', err);
            });
        },

        onScanSuccess(decodedText) {
            try {
                const data = JSON.parse(decodedText);
                this.checkpointName = data.name || decodedText;
                this.checkpointCode = data.code || decodedText;
            } catch {
                this.checkpointName = decodedText;
                this.checkpointCode = decodedText;
            }

            this.html5QrCode.stop();

            if (navigator.vibrate) navigator.vibrate(200);

            try {
                const utterance = new SpeechSynthesisUtterance('Memvalidasi checkpoint');
                utterance.lang = 'id-ID';
                speechSynthesis.speak(utterance);
            } catch(e) {}

            this.validateCheckpoint();
        },

        validateCheckpoint() {
            fetch('{{ route("guard.patrol.validate-checkpoint", $patrol->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    checkpoint_code: this.checkpointCode,
                    latitude: this.latitude,
                    longitude: this.longitude,
                })
            })
            .then(res => {
                if (!res.ok) return res.json().then(err => { throw err; });
                return res.json();
            })
            .then(data => {
                this.popup = {
                    show: true,
                    type: 'success',
                    title: 'Checkpoint Valid',
                    message: data.message + ' (Jarak: ' + data.distance + 'm)',
                };
            })
            .catch((err) => {
                this.popup = {
                    show: true,
                    type: 'error',
                    title: 'Luar Radius',
                    message: err.message || 'Terjadi kesalahan validasi.',
                };
            });
        },

        showForm() {
            this.scanResult = true;
        },

        submitScan() {
            if (this.submitting) return;
            this.submitting = true;

            const formData = new FormData(this.$refs.scanForm);

            fetch(this.$refs.scanForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(res => {
                if (!res.ok) return res.json().then(err => { throw err; });
                return res.json();
            })
            .then(data => {
                this.submitting = false;
                this.popup = {
                    show: true,
                    type: 'success',
                    title: 'Scan Berhasil',
                    message: data.message,
                };
                this.scanResult = false;
                this.startScanner();
                if (data.patrol_completed) {
                    setTimeout(() => window.location.href = '{{ route("guard.dashboard") }}', 2000);
                }
            })
            .catch((err) => {
                this.submitting = false;
                this.popup = {
                    show: true,
                    type: 'error',
                    title: 'Scan Gagal',
                    message: err.message || 'Terjadi kesalahan.',
                };
            });
        }
    }
}
</script>
@endpush
@endsection