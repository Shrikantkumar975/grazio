<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-zenith-text mb-2">Welcome Back</h2>
        <p class="text-sm text-zenith-textLight">Sign in to manage your livestock and monitor alerts.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-zenith-textLight mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full bg-zenith-bg border border-zenith-border text-zenith-text text-sm rounded-xl px-4 py-3 outline-none focus:border-zenith-blue transition-colors @error('email') border-red-500 @enderror"
                placeholder="you@farm.com" />
            @error('email')
                <div class="text-red-500 text-xs mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-zenith-textLight mb-2">Password</label>
            <input type="password" name="password" required
                class="w-full bg-zenith-bg border border-zenith-border text-zenith-text text-sm rounded-xl px-4 py-3 outline-none focus:border-zenith-blue transition-colors @error('password') border-red-500 @enderror"
                placeholder="••••••••" />
            @error('password')
                <div class="text-red-500 text-xs mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer group">
                <div class="relative flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="peer appearance-none w-5 h-5 border border-zenith-border rounded-md bg-zenith-bg checked:bg-zenith-blue checked:border-zenith-blue transition-colors cursor-pointer" />
                    <i class="material-icons absolute text-white text-[14px] pointer-events-none opacity-0 peer-checked:opacity-100 left-0.5 top-0.5">check</i>
                </div>
                <span class="text-sm font-medium text-zenith-textLight group-hover:text-zenith-text transition-colors">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-zenith-blue hover:text-white transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full flex items-center justify-center py-3 px-4 bg-zenith-primary hover:opacity-90 text-white font-bold rounded-xl shadow-lg shadow-zenith-primary/20 transition-all active:scale-[0.98]">
            Sign In to Dashboard
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm font-medium text-zenith-textLight mt-6">
                New to Grazio?
                <a href="{{ route('register') }}" class="text-zenith-blue hover:text-white transition-colors ml-1">
                    Create an account
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
