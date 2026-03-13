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

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {

            // This automatically redirects back to the login page with error messages.
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
                'password' => __('auth.password'),
            ]);
        }

        // 3. Security: Regenerate the session to prevent Session Fixation.
        $request->session()->regenerate();

        // 4. Redirect to the dashboard
        return redirect()->route('admin.dashboard');
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
