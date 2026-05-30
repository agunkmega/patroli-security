<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        .auth-bg { background: linear-gradient(135deg, #0f0d1e 0%, #1e1b2e 50%, #2d1b0e 100%); }
        .auth-card { backdrop-filter: blur(20px); background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); }
        .glow { box-shadow: 0 0 60px rgba(233,88,12,0.15); }
    </style>
</head>
<body class="auth-bg min-h-screen flex items-center justify-center p-4">
    @yield('content')
</body>
</html>
