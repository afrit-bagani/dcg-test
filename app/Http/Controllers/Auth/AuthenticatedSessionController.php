<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // 1. Validate the input
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Attempt to log the user in
        // The 'attempt' method handles the hashing check (Bcrypt) automatically.
        // 'remember' (boolean) allows for long-lived session cookies.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Industry Standard: Throw a validation error if credentials fail.
            // This automatically redirects back to the login page with error messages.
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
                'password' => __('auth.password'),
            ]);
        }

        // 3. Security: Regenerate the session to prevent Session Fixation.
        $request->session()->regenerate();

        // 4. Redirect to the dashboard
        return redirect()->intended('/batches.index');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        // Invalidate the session data
        $request->session()->invalidate();

        // Regenerate the CSRF token for the next visitor
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
