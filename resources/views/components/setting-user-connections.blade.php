@props(['followers', 'followings'])

<div class="relative grid grid-cols-2 gap-4 mb-6">
    <div>
        <h3>Followers ({{ $followers->count() }})</h3>
        @foreach ($followers as $follower)
            <x-profile :user="$follower" />
        @endforeach
    </div>
    <div>
        <h3>Followings ({{ $followings->count() }})</h3>
        @foreach ($followings as $following)
            <x-profile :user="$following" />
        @endforeach
    </div>
</div>