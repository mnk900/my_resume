<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        \App\Services\SeoService::set([
            'title' => 'Register Candidate User | Admin Panel',
            'description' => 'Admin control panel registration form for candidate portfolio users.',
            'robots' => 'noindex, nofollow'
        ]);

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request from Admin.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Auto-generate a unique username from the full name
        $base  = \Illuminate\Support\Str::slug($request->name);
        $slug  = $base;
        $count = 1;
        while (User::where('username', $slug)->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }

        $user = User::create([
            'name'     => $request->name,
            'username' => $slug,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->route('admin.index')->with('success', "Portfolio user account '{$user->name}' ({$user->email}) was successfully created!");
    }
}
