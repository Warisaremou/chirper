@props(['user'])

<div class="space-y-6">
    <div class="flex flex-col gap-6">
        <div class="relative flex flex-col gap-1">
            <div class="size-22 overflow-hidden rounded-full flex items-center">
                <img src="{{ route('profile.show.avatar', $user) }}" alt="{{ $user->name }}'s avatar"
                    class="object-cover">
            </div>
            <form action="{{ route('profile.edit.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" class="absolute inset-0 size-22 opacity-0" accept="image/*">
                <button type="submit" class="btn btn-primary btn-xs">
                    Update avatar
                </button>
            </form>
        </div>

        <form method="POST" action="{{ route('profile.edit') }}" class="space-y-3">
            @csrf
            @method('PATCH')

            <!-- Email -->
            <label class="floating-label">
                <input disabled type="email" name="email" placeholder="mail@example.com" value="{{ $user->email }}"
                    class="input input-bordered @error('email') input-error @enderror" required>
                <span>Email</span>
            </label>
            @error('email')
                <div class="mt-1">
                    <span class="text-xs text-red-500">{{ $message }}</span>
                </div>
            @enderror

            <!-- Name -->
            <label class="floating-label">
                <span>Name</span>
                <input type="text" name="name" placeholder="John Doe" value="{{ $user->name }}"
                    class="input input-bordered @error('name') input-error @enderror" required>
            </label>
            @error('name')
                <div class="mt-1">
                    <span class="text-xs text-red-500">{{ $message }}</span>
                </div>
            @enderror

            @error('credentials')
                <div class="mt-1">
                    <span class="text-xs text-red-500">{{ $message }}</span>
                </div>
            @enderror

            <!-- Submit Button -->
            <div class="form-control mt-4">
                <button type="submit" class="btn btn-primary btn-sm">
                    Update profile
                </button>
            </div>
        </form>
    </div>
    <div class="divider"></div>
    <div>
        <h3>Settings</h3>
        <div class="flex items-center justify-between text-sm">
            <span>Allow push notifications</span>
            <input type="checkbox" class="toggle" id="notification-subscriber" />
        </div>
    </div>
</div>