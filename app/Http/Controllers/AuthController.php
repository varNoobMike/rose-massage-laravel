<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request and redirect based on user role
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // VERIFICATION CHECK
            // If the user hasn't verified their email, kick them to the notice page
            /*if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('message', 'Please verify your email address before accessing the dashboard.');
            }*/

            // ROLE-BASED REDIRECT
            return match ($user->role) {
                'admin', 'owner', 'receptionist' => redirect('/dashboard'),
                'client' => redirect('/user/home'),
                default => abort(403),
            };
        }

        return back()->with('error', 'Incorrect email or password.')->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration logic
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Please enter your full name.',
            'name.max'           => 'Your name must not exceed 255 characters.',

            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email is already registered.',

            'password.required'  => 'A secure password is required to protect your privacy.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min'       => 'The password must contain at least 8 characters.',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['status']  = 'active';

        $user = User::create($data);

        $user->profile()->create();

        // event(new Registered($user));

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please log in with your new account.');
    }
}
