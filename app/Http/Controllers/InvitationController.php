<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class InvitationController extends Controller
{
    public function create(Request $request, Colocation $colocation): View
    {
        $user = Auth::user();

        if (!$colocation->hasActiveMember($user)) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can create invitations.');
        }

        return view('invitations.create', compact('colocation'));
    }

    public function store(Request $request, Colocation $colocation): RedirectResponse
    {
        $user = Auth::user();

        if (!$colocation->hasActiveMember($user)) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can create invitations.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $existingInvitation = Invitation::where('email', $validated['email'])
            ->where('colocation_id', $colocation->id)
            ->whereNull('accepted_at')
            ->whereNull('declined_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->with('error', "Une invitation a déjà été envoyée à l'adresse email '{$validated['email']}'. Cette invitation expirera le {$existingInvitation->expires_at->format('d M Y à H:i')}.");
        }

        $memberExists = $colocation->activeMembers()
            ->where('email', $validated['email'])
            ->exists();

        if ($memberExists) {
            return back()->with('error', 'Cet utilisateur est déjà membre de cette colocation.');
        }

        $token = Invitation::generateToken();

        Invitation::create([
            'email' => $validated['email'],
            'token' => $token,
            'colocation_id' => $colocation->id,
            'invited_by' => $user->id,
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('invitations.link', $colocation)
            ->with('success', 'Invitation envoyée avec succès.');
    }

    public function link(Colocation $colocation): View
    {
        $user = Auth::user();

        if (!$colocation->hasActiveMember($user)) {
            abort(403, 'Unauthorized access to this colocation.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can view invitation links.');
        }

        $invitations = Invitation::where('colocation_id', $colocation->id)
            ->whereNull('accepted_at')
            ->whereNull('declined_at')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('invitations.link', compact('colocation', 'invitations'));
    }

    public function show($token): View|RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('invitations.expired');
        }

        if ($invitation->isAccepted()) {
            return view('invitations.already_accepted');
        }

        if ($invitation->isDeclined()) {
            return view('invitations.declined');
        }

        // Check if email already exists in the system
        $existingUser = \App\Models\User::where('email', $invitation->email)->first();
        
        if ($existingUser) {
            // Email exists, redirect to login with invitation info
            return redirect()->route('login')
                ->with('invitation_token', $token)
                ->with('info', 'Veuillez vous connecter pour accepter cette invitation.');
        } else {
            // Email doesn't exist, redirect to register with invitation info
            return redirect()->route('register')
                ->with('invitation_token', $token)
                ->with('info', 'Veuillez créer un compte pour accepter cette invitation.');
        }
    }

    public function accept($token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a expiré.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a déjà été acceptée.');
        }

        if ($invitation->isDeclined()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a été refusée.');
        }

        $user = Auth::user();

        if (!$user) {
            // Store token in session and redirect to appropriate auth page
            $existingUser = \App\Models\User::where('email', $invitation->email)->first();
            
            if ($existingUser) {
                return redirect()->route('login')
                    ->with('invitation_token', $token)
                    ->with('info', 'Veuillez vous connecter pour accepter cette invitation.');
            } else {
                return redirect()->route('register')
                    ->with('invitation_token', $token)
                    ->with('info', 'Veuillez créer un compte pour accepter cette invitation.');
            }
        }

        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')
                ->with('error', 'Cette invitation n\'est pas destinée à votre compte.');
        }

        $colocation = $invitation->colocation;

        if ($colocation->hasActiveMember($user)) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous êtes déjà membre de cette colocation.');
        }

        $invitation->accept();

        $colocation->members()->attach($user->id, [
            'joined_at' => now(),
            'reputation' => 0,
        ]);

        return redirect()->route('colocations.show', $colocation)
            ->with('success', 'Vous avez rejoint la colocation avec succès.');
    }

    public function decline($token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a expiré.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a déjà été acceptée.');
        }

        if ($invitation->isDeclined()) {
            return redirect()->route('login')
                ->with('error', 'Cette invitation a déjà été refusée.');
        }

        $invitation->decline();

        return redirect()->route('login')
            ->with('success', 'Invitation refusée avec succès.');
    }
}
