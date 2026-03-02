<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
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

        $invitationToken = session('invitation_token') ?: $request->input('invitation_token') ?: $request->query('token');
        
        if ($invitationToken) {
            $invitation = \App\Models\Invitation::where('token', $invitationToken)->first();
                        
            if ($invitation && $invitation->email === $user->email && !$invitation->isExpired()) {
                
                $colocation = $invitation->colocation;
                
                $invitation->accept();
                
                $colocation->members()->attach($user->id, [
                    'joined_at' => now(),
                    'reputation' => 0,
                ]);
                
                
                session()->forget('invitation_token');
                
                return redirect()->route('colocations.show', $colocation)
                    ->with('success', 'Bienvenue! Vous avez rejoint la colocation avec succès.');
            } else {
                \Log::info('Registration - Invitation validation failed');
            }
        }

        return redirect()->route('dashboard');
    }
}