<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Profile extends Controller
{
    public function index(): View
    {
        return view('settings', [
            'user' => auth()->user(),
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
}