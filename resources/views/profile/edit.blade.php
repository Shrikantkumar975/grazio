<x-app-layout>
    <x-slot name="title">Profile</x-slot>
    <x-slot name="subtitle">Account credentials and preferences</x-slot>

    <div class="space-y-6 max-w-3xl">
        <div class="sky-card p-6 sm:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="sky-card p-6 sm:p-8">
            @include('profile.partials.update-password-form')
        </div>
        <div class="sky-card border-red-500/20 p-6 sm:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
