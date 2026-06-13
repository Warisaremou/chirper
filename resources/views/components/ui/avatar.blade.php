@props(['user'])

@if($user?->avatarUrl)
    <div class="avatar">
        <div class="size-10 rounded-full">
            <img src="{{ route('profile.show.avatar', $user) }}" alt="{{ $user->name }}'s avatar" />
        </div>
    </div>
@else
    <div class="avatar">
        <div class="size-10 rounded-full">
            <img src="https://avatars.laravel.cloud/{{ urlencode($user?->email) }}" alt="{{ $user?->name }}'s avatar"
                class="rounded-full" />
        </div>
    </div>
@endif