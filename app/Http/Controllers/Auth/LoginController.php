<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('student')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('department')) {
                return redirect()->route('department.dashboard');
            } elseif ($user->hasRole('cleaning')) {
                return redirect()->route('cleaning.dashboard');
            } elseif ($user->hasRole('librarian')) {
                return redirect()->route('librarian.dashboard');
            } elseif ($user->hasRole('canteen')) {
                return redirect()->route('canteen.dashboard');
            } elseif ($user->hasRole('warden')) {
                return redirect()->route('warden.dashboard');
            } else {
                Auth::logout();

                return redirect()->back()->withErrors(['email' => 'Unauthorized role.']);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
