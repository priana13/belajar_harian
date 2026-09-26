<?php

namespace App\Http\Controllers\Admin2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function create()
    {
        return Inertia::render('Admin2/Login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        if ($request->user()->jenis_user?->nama_jenis !== 'Admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages(['login' => 'Akun ini tidak memiliki akses administrator.']);
        }
        $request->session()->regenerate();

        return redirect('/admin2');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin2/login');
    }
}
