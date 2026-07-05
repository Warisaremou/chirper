<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\NotificationTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChirpResource;
use App\Models\Chirp;
use App\Notifications\ChirpInteraction;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChirpController extends Controller
{
    /**
     * @unauthenticated
     */
    #[QueryParameter('search', description: 'Filter chirps by keyword.', type: 'string', example: 'hello')]
    #[QueryParameter('page', description: 'Page number.', type: 'int', default: 1, example: 2)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $chirps = Chirp::with('user')
            ->withCount('likes')
            ->when($request->query('search'), function (Builder $query, string $search) {
                $query->whereLike('message', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return ChirpResource::collection($chirps);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        $chirp = $request->user()->chirps()->create($validated);

        return (new ChirpResource($chirp->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Chirp $chirp): ChirpResource
    {
        return new ChirpResource($chirp->load('user')->loadCount('likes'));
    }

    public function update(Request $request, Chirp $chirp): ChirpResource|JsonResponse
    {
        if ($request->user()->cannot('update', $chirp)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        $chirp->update($validated);

        return new ChirpResource($chirp->load('user'));
    }

    public function destroy(Request $request, Chirp $chirp): JsonResponse
    {
        if ($request->user()->cannot('delete', $chirp)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $chirp->deleteOrFail();

        return response()->json(null, 204);
    }

    public function like(Request $request, Chirp $chirp): JsonResponse
    {
        if ($request->user()->cannot('like', $chirp)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->user()->likes()->attach($chirp->id);
        $chirp->user->notify(new ChirpInteraction(NotificationTypeEnum::LikedChirp, $request->user(), $chirp));

        return response()->json(['message' => 'Chirp liked.']);
    }

    public function unlike(Request $request, Chirp $chirp): JsonResponse
    {
        if ($request->user()->cannot('unlike', $chirp)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->user()->likes()->detach($chirp->id);

        return response()->json(['message' => 'Chirp unliked.']);
    }
}
