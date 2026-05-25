<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-zenith-text mb-2">Create Account</h2>
        <p class="text-sm text-zenith-textLight">Join Grazio to start monitoring your livestock.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-zenith-textLight mb-2">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full bg-zenith-bg border border-zenith-border text-zenith-text text-sm rounded-xl px-4 py-3 outline-none focus:border-zenith-blue transition-colors @error('name') border-red-500 @enderror"
                placeholder="John Doe" />
            @error('name')
                <div class="text-red-500 text-xs mt-1.5">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-zenith-textLight mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
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

        <div>
            <label class="block text-sm font-medium text-zenith-textLight mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full bg-zenith-bg border border-zenith-border text-zenith-text text-sm rounded-xl px-4 py-3 outline-none focus:border-zenith-blue transition-colors"
                placeholder="••••••••" />
        </div>

        <button type="submit" class="w-full flex items-center justify-center mt-2 py-3 px-4 bg-zenith-primary hover:opacity-90 text-white font-bold rounded-xl shadow-lg shadow-zenith-primary/20 transition-all active:scale-[0.98]">
            Register Farm
        </button>

        <p class="text-center text-sm font-medium text-zenith-textLight mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-zenith-blue hover:text-white transition-colors ml-1">
                Sign in here
            </a>
        </p>
    </form>
</x-guest-layout>
