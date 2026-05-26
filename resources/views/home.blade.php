<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Grazio') }} — Smart Livestock Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body { font-family: 'Montserrat', sans-serif; }

        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        @keyframes fadeUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        @keyframes shimmer { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
        @keyframes rotateSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes pulse-ring { 0% { transform: scale(0.9); opacity: 1; } 100% { transform: scale(1.4); opacity: 0; } }
        @keyframes countUp { from { opacity:0; transform:scale(0.8); } to { opacity:1; transform:scale(1); } }

        .float-anim { animation: float 4s ease-in-out infinite; }
        .float-anim-2 { animation: float 5s ease-in-out infinite 1s; }
        .float-anim-3 { animation: float 6s ease-in-out infinite 0.5s; }
        .fade-up { animation: fadeUp 0.7s ease-out forwards; opacity: 0; }
        .fade-up-1 { animation-delay: 0.1s; }
        .fade-up-2 { animation-delay: 0.25s; }
        .fade-up-3 { animation-delay: 0.4s; }
        .fade-up-4 { animation-delay: 0.55s; }

        .shimmer-text {
            background: linear-gradient(90deg, #38bdf8, #a78bfa, #34d399, #38bdf8);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite;
        }

        .card-glow:hover { box-shadow: 0 0 30px rgba(56,189,248,0.15); }

        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(56,189,248,0.5);
            animation: pulse-ring 2s ease-out infinite;
        }

        .hero-orb-1 {
            position: absolute; top: -80px; right: -80px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(56,189,248,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-orb-2 {
            position: absolute; bottom: -100px; left: -100px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(52,211,153,0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .stat-number { animation: countUp 0.6s ease-out forwards; }

        .grid-bg {
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .feature-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            background: linear-gradient(135deg, rgba(255,255,255,0.07) 0%, rgba(255,255,255,0.03) 100%);
            border-color: rgba(56,189,248,0.3);
            transform: translateY(-4px);
        }

        .tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; }
    </style>
</head>
<body class="bg-[#080d14] text-white min-h-screen antialiased overflow-x-hidden">

    <!-- ── NAVBAR ── -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#080d14]/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-18" style="height:72px;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-sky-400 to-emerald-400 flex items-center justify-center shadow-lg">
                    <i class="material-icons text-white" style="font-size:18px;">pets</i>
                </div>
                <span class="text-xl font-black tracking-tight">{{ config('app.name', 'Grazio') }}</span>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition-all shadow-lg shadow-sky-500/25">Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-white/60 hover:text-white transition-colors">Sign In</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-sm transition-all shadow-lg shadow-sky-500/25">Get Started →</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden grid-bg pt-20">
        <div class="hero-orb-1"></div>
        <div class="hero-orb-2"></div>

        <!-- Background image — faded at bottom -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero.png') }}" alt="" class="w-full h-full object-cover object-bottom opacity-20" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#080d14] via-[#080d14]/60 to-[#080d14]/80"></div>
        </div>

        <!-- Floating status pills -->
        <div class="absolute top-36 right-12 lg:right-32 float-anim z-10 hidden md:block">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-2xl min-w-[180px]">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center relative">
                        <i class="material-icons text-emerald-400" style="font-size:18px;">favorite</i>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-emerald-400 rounded-full border-2 border-[#080d14]"></span>
                    </div>
                    <div>
                        <p class="text-xs text-white/50 font-semibold">Herd Health</p>
                        <p class="text-sm font-bold text-white">98.2% Good</p>
                    </div>
                </div>
                <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-300 rounded-full" style="width:98%"></div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-48 left-8 lg:left-24 float-anim-2 z-10 hidden md:block">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-500/20 flex items-center justify-center">
                        <i class="material-icons text-sky-400" style="font-size:18px;">location_on</i>
                    </div>
                    <div>
                        <p class="text-xs text-white/50 font-semibold">Live Tracking</p>
                        <p class="text-sm font-bold text-white">247 animals online</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute top-1/2 right-4 lg:right-16 float-anim-3 z-10 hidden lg:block">
            <div class="bg-amber-500/10 backdrop-blur-xl border border-amber-500/20 rounded-2xl p-4 shadow-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 flex items-center justify-center">
                        <i class="material-icons text-amber-400" style="font-size:18px;">warning</i>
                    </div>
                    <div>
                        <p class="text-xs text-amber-400/70 font-semibold">Alert</p>
                        <p class="text-sm font-bold text-amber-400">Bolt left zone 3</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Hero Copy -->
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <div class="fade-up fade-up-1 mb-6">
                <span class="tag bg-sky-500/10 border border-sky-500/20 text-sky-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse inline-block"></span>
                    Next-Gen Farm Intelligence
                </span>
            </div>

            <h1 class="fade-up fade-up-2 text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight mb-6">
                Your Herd.<br/>
                <span class="shimmer-text">Always Safe.</span>
            </h1>

            <p class="fade-up fade-up-3 text-base sm:text-lg text-white/50 max-w-xl mx-auto leading-relaxed mb-10">
                Real-time GPS tracking, smart health alerts, and team messaging — all in one platform built for modern livestock farms.
            </p>

            <div class="fade-up fade-up-4 flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="group px-8 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-base transition-all shadow-2xl shadow-sky-500/30 flex items-center justify-center gap-2">
                        Open Dashboard
                        <i class="material-icons group-hover:translate-x-1 transition-transform">arrow_forward</i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group px-8 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-base transition-all shadow-2xl shadow-sky-500/30 flex items-center justify-center gap-2">
                        Start Free
                        <i class="material-icons group-hover:translate-x-1 transition-transform">arrow_forward</i>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-base transition-all flex items-center justify-center">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-2 text-white/30">
            <span class="text-[10px] uppercase tracking-widest font-semibold">Scroll</span>
            <i class="material-icons animate-bounce text-sm">keyboard_arrow_down</i>
        </div>
    </section>

    <!-- ── STATS STRIP ── -->
    <section class="border-y border-white/5 bg-white/[0.02]">
        <div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach([['247', 'Animals Tracked', 'location_on', 'sky'], ['99.1%', 'Uptime', 'bolt', 'emerald'], ['<3s', 'Alert Latency', 'timer', 'amber'], ['5 min', 'GPS Refresh', 'gps_fixed', 'violet']] as $stat)
            <div class="text-center">
                <div class="flex items-center justify-center mb-2">
                    <i class="material-icons text-{{ $stat[3] }}-400 text-2xl">{{ $stat[2] }}</i>
                </div>
                <div class="text-3xl font-black text-white stat-number">{{ $stat[0] }}</div>
                <div class="text-xs text-white/40 font-semibold mt-1 uppercase tracking-wider">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- ── FEATURES ── -->
    <section id="features" class="py-28 px-6 relative overflow-hidden">
        <div class="hero-orb-2" style="right:0;left:auto;top:0;bottom:auto;opacity:0.5;"></div>

        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="tag bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 mb-4 inline-flex">
                    <i class="material-icons" style="font-size:12px;">auto_awesome</i>
                    What Grazio Does
                </span>
                <h2 class="text-4xl font-black text-white mt-4">Built for the farm floor</h2>
                <p class="text-white/40 mt-3 max-w-lg mx-auto">Every feature designed around real farming workflows — no bloat, no distractions.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- GPS Tracking -->
                <div class="feature-card card-glow rounded-3xl p-8">
                    <div class="w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center mb-6">
                        <i class="material-icons text-sky-400" style="font-size:26px;">gps_fixed</i>
                    </div>
                    <h3 class="text-lg font-black text-white mb-2">Live GPS Tracking</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Know exactly where every animal is, every moment. Sub-minute location updates via smart GPS collars.</p>
                    <div class="mt-6 flex gap-2 flex-wrap">
                        <span class="tag bg-sky-500/10 text-sky-400 border border-sky-500/10">Real-time</span>
                        <span class="tag bg-sky-500/10 text-sky-400 border border-sky-500/10">GPS Collar</span>
                    </div>
                </div>

                <!-- Geofencing -->
                <div class="feature-card card-glow rounded-3xl p-8 md:mt-6">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6">
                        <i class="material-icons text-emerald-400" style="font-size:26px;">fence</i>
                    </div>
                    <h3 class="text-lg font-black text-white mb-2">Smart Geofencing</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Draw digital boundaries around pastures. Instant alerts the moment any animal leaves its designated zone.</p>
                    <div class="mt-6 flex gap-2 flex-wrap">
                        <span class="tag bg-emerald-500/10 text-emerald-400 border border-emerald-500/10">Custom Zones</span>
                        <span class="tag bg-emerald-500/10 text-emerald-400 border border-emerald-500/10">Instant Alert</span>
                    </div>
                </div>

                <!-- Health -->
                <div class="feature-card card-glow rounded-3xl p-8">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mb-6">
                        <i class="material-icons text-rose-400" style="font-size:26px;">monitor_heart</i>
                    </div>
                    <h3 class="text-lg font-black text-white mb-2">Health Monitoring</h3>
                    <p class="text-white/40 text-sm leading-relaxed">Behavioral patterns reveal stress, illness, or distress before visible symptoms appear. Catch problems early.</p>
                    <div class="mt-6 flex gap-2 flex-wrap">
                        <span class="tag bg-rose-500/10 text-rose-400 border border-rose-500/10">AI Behavior</span>
                        <span class="tag bg-rose-500/10 text-rose-400 border border-rose-500/10">Early Alert</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <!-- Team Chat -->
                <div class="feature-card card-glow rounded-3xl p-8 flex gap-6 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="material-icons text-violet-400" style="font-size:26px;">forum</i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white mb-2">Team Messaging</h3>
                        <p class="text-white/40 text-sm leading-relaxed">Instant communication between farm managers, vets, and field hands — all in one place when seconds count.</p>
                    </div>
                </div>

                <!-- Registry -->
                <div class="feature-card card-glow rounded-3xl p-8 flex gap-6 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="material-icons text-amber-400" style="font-size:26px;">article</i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white mb-2">Livestock Registry</h3>
                        <p class="text-white/40 text-sm leading-relaxed">Full profiles for every animal — history, health records, GPS logs, and status, always at your fingertips.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── VISUAL SHOWCASE (farm image) ── -->
    <section class="py-10 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                <img src="{{ asset('images/hero.png') }}" alt="Grazio Farm Dashboard" class="w-full h-72 object-cover object-center" />
                <div class="absolute inset-0 bg-gradient-to-r from-[#080d14]/80 via-transparent to-[#080d14]/80"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 text-white font-bold text-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                            247 animals tracked right now
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── CTA ── -->
    <section class="py-28 px-6 relative overflow-hidden">
        <div class="hero-orb-1" style="left:0;right:auto;opacity:0.5;"></div>
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl sm:text-5xl font-black text-white mb-5 leading-tight">
                Your herd deserves<br/><span class="shimmer-text">the best protection.</span>
            </h2>
            <p class="text-white/40 mb-10 text-base max-w-lg mx-auto">Join farms already using Grazio to protect their livestock, save time, and make smarter decisions.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-10 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-lg transition-all shadow-2xl shadow-sky-500/30">
                    Open Dashboard <i class="material-icons">arrow_forward</i>
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-10 py-4 rounded-2xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-lg transition-all shadow-2xl shadow-sky-500/30">
                    Get Started Free <i class="material-icons">arrow_forward</i>
                </a>
            @endauth
        </div>
    </section>

    <!-- ── FOOTER ── -->
    <footer class="border-t border-white/5 py-10 px-6">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-sky-400 to-emerald-400 flex items-center justify-center">
                    <i class="material-icons text-white" style="font-size:16px;">pets</i>
                </div>
                <span class="font-black text-white">{{ config('app.name', 'Grazio') }}</span>
            </div>
            <p class="text-white/30 text-sm">© {{ date('Y') }} Grazio Inc. Built for modern agriculture.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-white/30 hover:text-white text-sm font-semibold transition-colors">Sign In</a>
                @guest
                <a href="{{ route('register') }}" class="text-sky-400 hover:text-sky-300 text-sm font-semibold transition-colors">Register</a>
                @endguest
            </div>
        </div>
    </footer>

</body>
</html>
