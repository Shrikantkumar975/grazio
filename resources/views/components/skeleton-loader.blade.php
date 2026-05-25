{{-- Loading Skeleton Component --}}
<div class="space-y-4" {{ $attributes }}>
    @for ($i = 0; $i < ($count ?? 3); $i++)
        <div class="space-y-3">
            <div class="skeleton h-4 w-1/4 rounded"></div>
            <div class="skeleton h-12 rounded-lg"></div>
            <div class="flex gap-2">
                <div class="skeleton h-8 w-16 rounded"></div>
                <div class="skeleton h-8 w-16 rounded"></div>
            </div>
        </div>
    @endfor
</div>
