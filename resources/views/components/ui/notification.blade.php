@props(['notification'])

@php
    $triggeringUser = \App\Models\User::find($notification->data['user_id']);
    $message = \App\Enums\NotificationTypeEnum::from($notification->type)->notificationMessage();
@endphp

<div class="flex items-center gap-3 p-2 rounded-lg {{ $notification->read_at ? '' : 'bg-base-200' }}">
    <x-ui.avatar :user="$triggeringUser" />
    <div class="text-xs flex-1">
        <p>
            <span class="font-semibold">{{ $triggeringUser?->name }}</span>
            {{ $message }}
        </p>
        <p class="text-base-content/50 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
    </div>
</div>