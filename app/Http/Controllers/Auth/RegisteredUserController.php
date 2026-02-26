<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email:rfc,dns', 
                'max:255', 
                'unique:'.User::class,
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    $blockedDomains = [
                        'tempmail.org', '10minutemail.com', 'guerrillamail.com',
                        'mailinator.com', 'yopmail.com', 'temp-mail.org',
                        'throwaway.email', 'maildrop.cc', 'tempmail.de'
                    ];
                    
                    if (in_array($domain, $blockedDomains)) {
                        $fail('Les adresses email temporaires ne sont pas autorisées.');
                    }
                }
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'reputation' => 0,
            'is_super_admin' => User::count() === 0
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}