<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $invitationToken = session('invitation_token') ?: $request->input('invitation_token') ?: $request->query('token');
        
        
        if ($invitationToken) {
            $invitation = \App\Models\Invitation::where('token', $invitationToken)->first();
            
            
            if ($invitation && $invitation->email === $user->email && !$invitation->isExpired()) {
                
                $colocation = $invitation->colocation;
                
                // Accept invitation
                $invitation->accept();
                
                // Add user to colocation
                $colocation->members()->attach($user->id, [
                    'joined_at' => now(),
                    'reputation' => 0,
                ]);
                
                
                // Clear the invitation token from session
                session()->forget('invitation_token');
                
                return redirect()->route('colocations.show', $colocation)
                    ->with('success', 'Bienvenue! Vous avez rejoint la colocation avec succès.');
            } else {
                Log::info('Login - Invitation validation failed');
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
