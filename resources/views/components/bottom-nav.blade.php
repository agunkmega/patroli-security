<nav class="fixed bottom-0 left-0 right-0 z-30 bg-white/90 dark:bg-dark-800/90 backdrop-blur-xl border-t border-gray-200 dark:border-dark-700 safe-area-bottom">
    <div class="flex items-center justify-around max-w-lg mx-auto px-2 py-2">
        <a href="{{ route('guard.dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('guard.dashboard') ? 'text-orange-500' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        <a href="{{ route('guard.patrol.start') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('guard.patrol.*') ? 'text-orange-500' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="text-[10px] font-medium">Patroli</span>
        </a>

        <a href="{{ route('guard.dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl relative -mt-5">
            <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-700 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <span class="text-[10px] font-medium mt-1 text-orange-500">Scan</span>
        </a>

        <a href="{{ route('guard.history') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('guard.history') ? 'text-orange-500' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span class="text-[10px] font-medium">Riwayat</span>
        </a>

        <a href="{{ route('attendance.index') }}" class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('attendance.*') ? 'text-orange-500' : 'text-gray-400 dark:text-gray-500' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-[10px] font-medium">Absen</span>
        </a>
    </div>
</nav>

<style>
    .safe-area-bottom { padding-bottom: env(safe-area-inset-bottom, 0px); }
</style>
