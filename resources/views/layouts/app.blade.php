<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Grazio') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    @stack('styles')
</head>

<body class="bg-zenith-bg text-zenith-text font-sans antialiased m-0 overflow-hidden">
    <div class="flex h-screen w-full">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-zenith-sidebar border-r border-zenith-border flex-shrink-0 flex flex-col transition-all duration-300 relative z-20">
            <!-- Logo -->
            <div class="h-[72px] flex items-center px-6 border-b border-zenith-border/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center">
                        <x-icon icon="spark" class="w-5 h-5" />
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-base tracking-wide text-zenith-text">Grazio</span>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
                <div class="text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-3 px-3">Overview</div>
                <ul class="space-y-1 mb-8">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-zenith-hover text-zenith-text' : 'text-zenith-textLight hover:bg-zenith-hover hover:text-zenith-text' }}">
                            <i class="material-icons text-[20px]">grid_view</i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('animals.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('animals.*') ? 'bg-zenith-hover text-zenith-text' : 'text-zenith-textLight hover:bg-zenith-hover hover:text-zenith-text' }}">
                            <i class="material-icons text-[20px]">pets</i>
                            Livestock Registry
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('messages.*') ? 'bg-zenith-hover text-zenith-text' : 'text-zenith-textLight hover:bg-zenith-hover hover:text-zenith-text' }}">
                            <i class="material-icons text-[20px]">chat</i>
                            Messages
                            @php
                                $unreadCount = \App\Models\ChatMessage::where('receiver_id', auth()->id())->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="ml-auto px-2 py-0.5 bg-zenith-blue text-white text-[10px] font-bold flex items-center justify-center rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <div class="text-xs font-semibold text-zenith-textLight uppercase tracking-wider mb-3 px-3">System</div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('settings.*') ? 'bg-zenith-hover text-zenith-text' : 'text-zenith-textLight hover:bg-zenith-hover hover:text-zenith-text' }}">
                            <i class="material-icons text-[20px]">settings</i>
                            Settings
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Profile (Bottom Sidebar) -->
            @auth
            <div class="p-4 border-t border-zenith-border/50">
                <div class="flex items-center justify-between hover:bg-zenith-hover p-2 rounded-xl cursor-pointer transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-zenith-hover text-zenith-text text-sm font-bold flex items-center justify-center">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-zenith-text truncate w-24">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-zenith-textLight">Admin</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-zenith-textLight hover:text-zenith-text">
                            <i class="material-icons text-[18px]">logout</i>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-zenith-bg">
            
            <!-- Topbar -->
            <header class="h-[72px] bg-zenith-sidebar border-b border-zenith-border flex items-center justify-between px-6 z-10 flex-shrink-0 transition-colors duration-200">
                <!-- Search -->
                <div class="flex-1 max-w-xl">
                    <div class="relative flex items-center w-full h-10 rounded-lg bg-zenith-hover border border-zenith-border focus-within:border-zenith-textLight focus-within:bg-zenith-sidebar transition-colors overflow-hidden">
                        <div class="grid place-items-center h-full w-12 text-zenith-textLight">
                            <i class="material-icons text-sm">search</i>
                        </div>
                        <input
                        type="text"
                        class="peer h-full w-full outline-none text-sm text-zenith-text pr-2 bg-transparent"
                        placeholder="Search anything..." />
                        <div class="pr-3 text-xs text-zenith-textLight font-mono">⌘K</div>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4 ml-4">

                    
                    <div class="flex items-center gap-2 text-zenith-textLight border-l border-zenith-border pl-4">
                        <button @click="darkMode = !darkMode" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-zenith-hover transition-colors">
                            <i class="material-icons text-[20px]" x-text="darkMode ? 'light_mode' : 'dark_mode'">dark_mode</i>
                        </button>
                        <!-- Chat Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-zenith-hover transition-colors">
                                <i class="material-icons text-[20px]">chat_bubble_outline</i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-[350px] bg-zenith-card rounded-xl shadow-lg border border-zenith-border z-50 overflow-hidden transform transition-all duration-200" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                <div class="p-4 border-b border-zenith-border flex justify-between items-center bg-zenith-hover/50">
                                    <h3 class="font-bold text-zenith-text text-sm">Messages</h3>
                                    <span class="text-xs text-zenith-blue cursor-pointer hover:underline whitespace-nowrap">Mark all read</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar p-2">
                                    @php
                                        $unreadMessages = \App\Models\ChatMessage::where('receiver_id', auth()->id())->where('is_read', false)->latest()->take(5)->get();
                                    @endphp

                                    @forelse($unreadMessages as $msg)
                                        @php
                                            $sender = \App\Models\User::find($msg->sender_id);
                                            $initials = $sender ? substr($sender->name, 0, 2) : '??';
                                        @endphp
                                        <div class="p-2 hover:bg-zenith-hover rounded-lg transition-colors cursor-pointer flex gap-3" onclick="window.location.href='{{ route('messages.index') }}'">
                                            <div class="w-8 h-8 rounded-full bg-zenith-teal/20 text-zenith-teal flex items-center justify-center text-sm font-bold flex-shrink-0">{{ strtoupper($initials) }}</div>
                                            <div>
                                                <p class="text-sm font-semibold text-zenith-text">{{ $sender->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-zenith-textLight line-clamp-1 mt-0.5">{{ $msg->text }}</p>
                                                <span class="text-[10px] text-zenith-textLight mt-1 block">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-zenith-textLight">
                                            <i class="material-icons text-3xl mb-2 text-zenith-border">chat_bubble_outline</i>
                                            <p class="text-sm">No new messages</p>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="p-3 border-t border-zenith-border text-center">
                                    <a href="{{ route('messages.index') }}" class="text-xs text-zenith-blue font-medium hover:underline">View all messages</a>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications Dropdown -->
                        @php
                            $distressedAnimals = \App\Models\Animal::where('user_id', auth()->id())->where('status', 'Distressed')->take(5)->get();
                        @endphp
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="relative w-8 h-8 flex items-center justify-center rounded-full hover:bg-zenith-hover transition-colors">
                                <i class="material-icons text-[20px]">notifications_none</i>
                                @if($distressedAnimals->count() > 0)
                                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-zenith-sidebar animate-pulse"></span>
                                @endif
                            </button>
                            <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-[350px] bg-zenith-card rounded-xl shadow-lg border border-zenith-border z-50 overflow-hidden transform transition-all duration-200" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                                <div class="p-4 border-b border-zenith-border flex justify-between items-center bg-zenith-hover/50">
                                    <h3 class="font-bold text-zenith-text text-sm">Alerts</h3>
                                    <span class="text-xs text-zenith-blue cursor-pointer hover:underline whitespace-nowrap">Clear all</span>
                                </div>
                                <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                    @if($distressedAnimals->count() > 0)
                                        @foreach($distressedAnimals as $animal)
                                            <a href="{{ route('animals.show', $animal) }}" class="block p-3 hover:bg-zenith-hover border-b border-zenith-border/50 transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                        <i class="material-icons text-[16px]">warning</i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-zenith-text">Distress Alert: {{ $animal->name }}</p>
                                                        <p class="text-xs text-zenith-textLight mt-0.5">Abnormal movement patterns detected.</p>
                                                        <span class="text-[10px] text-zenith-textLight mt-1 block">Just now</span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="p-6 text-center text-zenith-textLight">
                                            <i class="material-icons text-3xl mb-2 text-zenith-border">check_circle</i>
                                            <p class="text-sm">No new alerts</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 border-t border-zenith-border text-center">
                                    <a href="{{ route('dashboard') }}" class="text-xs text-zenith-blue font-medium hover:underline">View dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 shadow-sm flex items-center gap-3">
                        <i class="material-icons text-green-500">check_circle</i>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200 shadow-sm flex items-center gap-3">
                        <i class="material-icons text-red-500">error</i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>



    @stack('scripts')
</body>
</html>
