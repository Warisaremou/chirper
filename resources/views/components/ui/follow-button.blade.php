@props(['user'])

<div>
    @can('follow', $user)
        <form method="POST" action="{{ route('profile.follow', $user) }}">
            @csrf
            <button class="btn btn-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-[1.2em]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user-plus-icon lucide-user-plus">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="19" x2="19" y1="8" y2="14" />
                    <line x1="22" x2="16" y1="11" y2="11" />
                </svg>
                <span>Follow</span>
            </button>
        </form>
    @endcan

    @can('unfollow', $user)
        <form method="POST" action="{{ route('profile.unfollow', $user) }}">
            @csrf
            <button class="btn btn-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-[1.2em]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user-minus-icon lucide-user-minus">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="22" x2="16" y1="11" y2="11" />
                </svg>
                <span>Unfollow</span>
            </button>
        </form>
    @endcan
</div>