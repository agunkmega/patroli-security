<!DOCTYPE html>
<html lang="id" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true, mobileSidebar: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#ea580c">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-dark-950 text-gray-900 dark:text-gray-100 antialiased" x-data="{ loading: false }" @loading.window="loading = true" @loaded.window="loading = false">
    <div x-show="loading" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-dark-800 rounded-2xl p-8 shadow-2xl">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Loading...</span>
            </div>
        </div>
    </div>

    @if(Auth::check())
        @include('components.sidebar')
    @endif

    <div :class="{ 'lg:ml-64': $store.sidebar?.isOpen }" class="min-h-screen transition-all duration-300">
        @if(Auth::check())
            @include('components.navbar')
            <div class="h-16"></div>
        @endif

        <main class="p-4 md:p-6 lg:p-8">
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-xl px-5 py-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                    <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-xl px-5 py-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                    <button @click="show = false" class="text-red-600 dark:text-red-400 hover:text-red-800">&times;</button>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 flex items-center gap-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 rounded-xl px-5 py-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium flex-1">{{ session('info') }}</p>
                    <button @click="show = false" class="text-blue-600 dark:text-blue-400 hover:text-blue-800">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

        @include('components.footer')
    </div>

    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @stack('scripts')
</body>
</html>
