<x-layout>
    <x-slot:title>
        Settings
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        @if (session('success'))
            <div class="toast toast-top toast-center">
                <div class="alert alert-success animate-fade-out">
                    <svg xmlns="<http://www.w3.org/2000/svg>" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="tabs tabs-border">
            <input type="radio" name="my_tabs_2" class="tab" aria-label="Profile" checked="checked" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                <x-setting-profile :user="$user" />
            </div>

            <input type="radio" name="my_tabs_2" class="tab" aria-label="My chirps" />
            <div class="tab-content border-base-300 bg-base-100 p-10">
                <x-setting-chirps :chirps="$user->chirps" />
            </div>
        </div>
    </div>
</x-layout>