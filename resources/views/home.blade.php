<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Grazio') }} — Intelligent Livestock Tracking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-zenith-bg text-zenith-text min-h-screen antialiased selection:bg-zenith-blue selection:text-white">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-zenith-card/80 backdrop-blur-lg border-b border-zenith-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-zenith-primary to-zenith-blue flex items-center justify-center text-white shadow-lg shadow-zenith-blue/20">
                    <i class="material-icons text-xl">pets</i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-zenith-text">{{ config('app.name', 'Grazio') }}</span>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-zenith-primary text-white font-semibold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-zenith-primary/20">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-zenith-textLight hover:text-white transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-zenith-primary text-white font-semibold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-zenith-primary/20">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-24 pb-32 flex flex-col items-center justify-center px-4 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 -right-1/4 w-[600px] h-[600px] bg-zenith-primary/10 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-1/4 -left-1/4 w-[600px] h-[600px] bg-zenith-blue/10 rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto text-center space-y-8 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-zenith-blue/10 border border-zenith-blue/20">
                <div class="w-2 h-2 rounded-full bg-zenith-blue animate-pulse"></div>
                <span class="text-sm text-zenith-blue font-semibold uppercase tracking-wider">Next-Gen Farm Intelligence</span>
            </div>

            <h1 class="text-5xl sm:text-7xl font-black text-white leading-tight tracking-tight">
                Intelligent Livestock <br/><span class="bg-gradient-to-r from-zenith-primary to-zenith-blue bg-clip-text text-transparent">Tracking & Analytics</span>
            </h1>

            <p class="text-lg sm:text-xl text-zenith-textLight max-w-2xl mx-auto leading-relaxed font-medium">
                Monitor your entire herd in real-time. Grazio uses advanced GPS collars and behavioral algorithms to keep your livestock safe, healthy, and accounted for.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-xl bg-zenith-primary text-white font-bold text-base hover:opacity-90 transition-opacity shadow-lg shadow-zenith-primary/20 flex items-center justify-center gap-2">
                        Enter Dashboard <i class="material-icons text-xl">arrow_forward</i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-zenith-primary text-white font-bold text-base hover:opacity-90 transition-opacity shadow-lg shadow-zenith-primary/20 flex items-center justify-center gap-2">
                        Start Free Trial <i class="material-icons text-xl">arrow_forward</i>
                    </a>
                    <a href="#features" class="px-8 py-4 rounded-xl bg-zenith-hover text-zenith-text font-bold text-base hover:bg-zenith-border transition-colors flex items-center justify-center">
                        Explore Features
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-4 bg-zenith-hover/20 border-y border-zenith-border">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-white mb-4">Complete Herd Visibility</h2>
                <p class="text-zenith-textLight max-w-2xl mx-auto">Everything you need to manage your farm's most valuable assets.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="bg-zenith-card border border-zenith-border p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-zenith-blue/10 rounded-xl flex items-center justify-center mb-6">
                        <i class="material-icons text-zenith-blue text-2xl">map</i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Live Geofencing</h3>
                    <p class="text-zenith-textLight leading-relaxed">Draw custom digital boundaries around your pastures. Get instant alerts if any animal breaches the perimeter.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-zenith-card border border-zenith-border p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-zenith-teal/10 rounded-xl flex items-center justify-center mb-6">
                        <i class="material-icons text-zenith-teal text-2xl">favorite</i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Health & Behavior</h3>
                    <p class="text-zenith-textLight leading-relaxed">Our system analyzes movement patterns to detect grazing anomalies, distress, or illness before symptoms appear.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-zenith-card border border-zenith-border p-8 rounded-2xl shadow-xl hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-zenith-primary/10 rounded-xl flex items-center justify-center mb-6">
                        <i class="material-icons text-zenith-primary text-2xl">forum</i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Team Comms</h3>
                    <p class="text-zenith-textLight leading-relaxed">Built-in messaging allows farm hands, vets, and managers to coordinate quickly when an animal needs attention.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-zenith-bg py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex items-center justify-center gap-2 mb-6">
                <i class="material-icons text-zenith-textLight">pets</i>
                <span class="text-lg font-bold text-zenith-textLight">{{ config('app.name', 'Grazio') }}</span>
            </div>
            <p class="text-zenith-textLight/70 text-sm">
                &copy; {{ date('Y') }} Grazio Inc. All rights reserved. Built for modern agriculture.
            </p>
        </div>
    </footer>
</body>
</html>
