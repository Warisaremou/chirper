<?php

namespace App\Http\Controllers;

use App\Enums\NotificationTypeEnum;
use App\Models\Chirp;
use App\Notifications\ChirpInteraction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $chirps = Chirp::with('user')
            ->when($request->query('search'), function (Builder $query, string $searchParam) {
                $query->whereLike('message', "%{$searchParam}%");
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('home', ['chirps' => $chirps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'message.required' => 'Please write something to chirp!',
        ]);

        auth()->user()->chirps()->create($validated);

        return redirect('/')->with('success', 'Chirp created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Chirp $chirp): View
    {
        if ($request->user()->cannot('update', $chirp)) {
            abort(403);
        }

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        if ($request->user()->cannot('delete', $chirp)) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:255'],
        ]);

        $chirp->update($validated);

        return redirect('/')->with('success', 'Chirp updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Chirp $chirp)
    {
        if ($request->user()->cannot('delete', $chirp)) {
            abort(403);
        }

        $chirp->deleteOrFail();

        return back()->with('success', 'Chirp deleted!');
    }

    /**
     * Add a chirp to likes.
     */
    public function like(Request $request, string $chirpId)
    {
        $chirp = Chirp::findOrFail($chirpId);
        $user = auth()->user();

        if ($request->user()->cannot('like', $chirp)) {
            abort(403);
        }

        $user->likes()->attach($chirp->id);
        $chirp->user->notify(new ChirpInteraction(NotificationTypeEnum::LikedChirp, $user, $chirp));

        return back()->with('success', 'Chirp added to likes!');
    }

    /**
     * Unlike a chirp.
     */
    public function unlike(Request $request, string $chirpId)
    {
        $chirp = Chirp::findOrFail($chirpId);

        if ($request->user()->cannot('unlike', $chirp)) {
            abort(403);
        }

        try {
            auth()->user()->likes()->detach($chirp->id);
            return back()->with('success', 'Chirp removed from likes!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to remove chirp from likes.');
        }
    }
}
