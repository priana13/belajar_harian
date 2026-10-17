<?php

namespace App\Http\Controllers;

use App\Models\LoginLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginLinkController extends Controller
{
    public function login(string $token): RedirectResponse
    {
        $link = LoginLink::where('token', $token)->first();

        if (! $link || $link->isExpired()) {
            return redirect()->route('login')->with('error', 'Link login tidak valid atau sudah expired.');
        }

        $link->increment('use_count');
        $link->update(['last_used_at' => now()]);

        Auth::login($link->user);

        request()->session()->regenerate();

        return redirect()->route('home');
    }
}
