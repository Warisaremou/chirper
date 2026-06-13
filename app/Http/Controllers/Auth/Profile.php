<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Profile extends Controller
{
    private const string FILE_INPUT_KEY = 'avatar';

    public function index(): View
    {
        $user = auth()->user();
        $likes = $user->likes()->with('user')->get();
        $followers = $user->followers;
        $followings = $user->followings;

        return view('settings', [
            'user' => $user,
            'likes' => $likes,
            'followers' => $followers,
            'followings' => $followings,
        ]);
    }

    public function edit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->update($validated);

        return redirect('settings')->with('success', 'Profile updated successfully.');
    }

    public function editAvatar(Request $request): RedirectResponse
    {
        if (false === $request->hasFile(self::FILE_INPUT_KEY) || false === $request->file(self::FILE_INPUT_KEY)->isValid()) {
            return redirect('settings')->with('error', 'Invalid file uploaded.');
        }

        $validated = $request->validate([
            self::FILE_INPUT_KEY => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($user->avatarUrl && Storage::exists($user->avatarUrl)) {
            Storage::delete($user->avatarUrl);
        }

        $path = $validated[self::FILE_INPUT_KEY]->store('uploads');
        $user->update(['avatarUrl' => $path]);

        return back()->with('success', 'Avatar uploaded successfully.');
    }

    public function showAvatar(User $user): BinaryFileResponse
    {
        if (!$user->avatarUrl || false === Storage::exists($user->avatarUrl)) {
            abort(404);
        }

        return response()
            ->file(Storage::path($user->avatarUrl))
            ->setPublic()
            ->setMaxAge(0)
            ->setPrivate();
    }

    /**
     * Follow a user.
     */
    public function follow(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->cannot('follow', $user)) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        auth()->user()->followings()->syncWithoutDetaching([$user->id]);
        return back()->with('success', "You are now following {$user->name}.");
    }

    /**
     * Unfollow a user profile.
     */
    public function unfollow(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->cannot('unfollow', $user)) {
            return back()->with('error', 'You cannot unfollow yourself.');
        }

        auth()->user()->followings()->detach($user->id);
        return back()->with('success', "You are no longer following {$user->name}.");
    }
}