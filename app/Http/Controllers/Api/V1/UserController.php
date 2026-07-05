<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\NotificationTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChirpResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ChirpInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * @unauthenticated
     */
    public function show(User $user): UserResource
    {
        $user->loadCount(['followers', 'followings', 'chirps']);

        return new UserResource($user);
    }

    public function update(Request $request): UserResource
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request->user()->update($validated);

        return new UserResource($request->user()->fresh());
    }

    /**
     * @unauthenticated
     */
    public function chirps(User $user): AnonymousResourceCollection
    {
        $chirps = $user->chirps()
            ->with('user')
            ->withCount('likes')
            ->latest()
            ->paginate(10);

        return ChirpResource::collection($chirps);
    }

    public function follow(Request $request, User $user): JsonResponse
    {
        if ($request->user()->cannot('follow', $user)) {
            return response()->json(['message' => 'You cannot follow this user.'], 403);
        }

        $request->user()->followings()->syncWithoutDetaching([$user->id]);
        $user->notify(new ChirpInteraction(NotificationTypeEnum::NewFollow, $request->user()));

        return response()->json(['message' => "You are now following {$user->name}."]);
    }

    public function unfollow(Request $request, User $user): JsonResponse
    {
        if ($request->user()->cannot('unfollow', $user)) {
            return response()->json(['message' => 'You cannot unfollow this user.'], 403);
        }

        $request->user()->followings()->detach($user->id);

        return response()->json(['message' => "You are no longer following {$user->name}."]);
    }
}
