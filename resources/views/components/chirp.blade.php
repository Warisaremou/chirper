@props(['chirp'])

<div class="card bg-base-100">
    <div class="card-body">
        <div class="flex space-x-3">
            <x-ui.avatar :user="$chirp->user" />

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-semibold">{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}</span>
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chirp->created_at->diffForHumans() }}</span>
                        @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                            <span class="text-base-content/60">·</span>
                            <span class="text-sm text-base-content/60 italic">edited</span>
                        @endif
                    </div>


                    <div class="flex gap-1">
                        @auth
                            @can('like', $chirp)
                                @php
                                    $isLiked = auth()->user()->likes->contains($chirp->id);
                                @endphp
                                <form
                                    action="{{ $isLiked ? route('chirp.unlike', $chirp->id) : route('chirp.like', $chirp->id) }}"
                                    method="POST">
                                    @csrf
                                    <button class="btn btn-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $isLiked ? 'red' : 'none' }}"
                                            viewBox="0 0 24 24" stroke-width="2.5"
                                            stroke="{{ $isLiked ? 'red' : 'currentColor' }}" class="size-[1.2em]">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        @endauth

                        <x-ui.follow-button :user="$chirp->user" />

                        @can('update', $chirp)
                            <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="/chirps/{{ $chirp->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this chirp?')"
                                    class="btn btn-xs text-red-600">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                <p class="mt-2">{{ $chirp->message }}</p>
            </div>
        </div>
    </div>
</div>