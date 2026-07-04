@props(['user'])

<div class="drawer drawer-end w-fit">
    <input id="my-drawer" type="checkbox" class="drawer-toggle"
        data-mark-read-url="{{ route('notifications.readAll') }}" />
    <div class="drawer-content relative">
        @if ($user->unreadNotifications()->exists())
            <div id="unread-dot" class="bg-red-600 absolute size-2 rounded-full right-[30%] top-[12%]"></div>
        @endif
        <label for="my-drawer" class="drawer-button btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-[1.2em]" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-bell-icon lucide-bell">
                <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                <path
                    d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
            </svg>
        </label>
    </div>
    <div class="drawer-side z-50">
        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-white min-h-full w-96 p-4 gap-2">
            @forelse ($user->notifications as $notification)
                <x-ui.notification :notification="$notification" />
            @empty
                <div class="hero py-6">
                    <div class="hero-content text-center">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 opacity-30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-inbox-icon lucide-inbox">
                                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12" />
                                <path
                                    d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
                            </svg>
                            <p class="mt-4 text-base-content/60">You haven't received any notifications yet.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </ul>
    </div>
</div>