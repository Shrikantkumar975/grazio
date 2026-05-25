{{-- Progress Modal Component --}}
<dialog id="progressModal" class="backdrop:bg-black/50">
    <div class="sky-card p-8 max-w-md mx-auto space-y-6">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <x-spinner type="ring" />
            </div>
            <h2 class="sky-section-subtitle">Processing Trajectories</h2>
            <p class="sky-section-desc">Please wait while we analyze your data...</p>
        </div>

        <div class="space-y-2">
            <div class="flex justify-between text-xs text-slate-400">
                <span>Progress</span>
                <span x-text="Math.round(progress) + '%'"></span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                <div
                    class="bg-gradient-to-r from-sky-500 to-violet-500 h-full transition-all duration-300"
                    :style="{ width: progress + '%' }"
                ></div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-sm text-slate-400" x-text="status"></p>
        </div>
    </div>
</dialog>
