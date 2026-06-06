<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        return view('settings', [
            'user' => $user,
            'likes' => $likes,
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

        return redirect('settings')->with('success', 'Avatar uploaded successfully.');
    }

    public function showAvatar(): BinaryFileResponse
    {
        $user = auth()->user();

        if (!$user->avatarUrl || false === Storage::exists($user->avatarUrl)) {
            abort(404);
        }

        return response()
            ->file(Storage::path($user->avatarUrl))
            ->setPublic()
            ->setMaxAge(0)
            ->setPrivate();
    }
}