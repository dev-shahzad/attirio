<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }


    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Create dummy messages
        $messages = [
            [
                'type' => 'bot',
                'content' => 'Hello! How can I assist you today?',
                'created_at' => now(),
            ],
            [
                'type' => 'user',
                'content' => 'I just logged in and want to test the chat',
                'created_at' => now()->subMinutes(5),
            ],
            [
                'type' => 'bot',
                'content' => 'Welcome back! Your session is active now.',
                'created_at' => now()->subMinutes(3),
            ],
        ];

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('messages', $messages);
    }

    // /**
    //  * Handle an incoming authentication request.
    //  */
    // public function store(LoginRequest $request): RedirectResponse
    // {



    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(route('dashboard', absolute: false));
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
