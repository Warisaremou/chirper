@props(['user'])

<div class="card bg-base-100">
    <div class="card-body px-0!">
        <div class="flex items-center space-x-3">
            <x-ui.avatar :user="$user" />

            <div class="flex justify-between w-full">
                <span class="text-sm font-semibold">{{ $user->name }}</span>
                <x-ui.follow-button :user="$user" />
            </div>
        </div>
    </div>
</div>