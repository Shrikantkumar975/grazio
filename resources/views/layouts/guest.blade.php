<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-zenith-bg text-zenith-text min-h-screen flex items-center justify-center p-4 antialiased selection:bg-zenith-blue selection:text-white">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block text-center group">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-zenith-primary to-zenith-blue flex items-center justify-center text-white shadow-lg shadow-zenith-blue/20 group-hover:scale-105 transition-transform duration-300">
                        <i class="material-icons text-2xl">pets</i>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-zenith-text">Grazio</h1>
                </div>
                <p class="text-sm font-medium text-zenith-textLight uppercase tracking-widest mt-3">Intelligent Livestock Tracking</p>
            </a>
        </div>
        
        <div class="bg-zenith-card border border-zenith-border rounded-2xl shadow-xl shadow-zenith-bg/50 p-8 relative overflow-hidden">
            <!-- Decorative gradient orb -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-zenith-primary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-zenith-blue/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
