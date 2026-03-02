<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $invitation = Invitation::create([
            'email' => $validated['email'],
            'token' => $token,
            'colocation_id' => $colocation->id,
            'invited_by' => $user->id,
            'expires_at' => now()->addDays(7),
        ]);

        // Send email invitation
        $this->sendInvitationEmail($invitation);
        
        // Debug: Log the invitation details
        \Log::info('Invitation Created - Token: ' . $invitation->token);
        \Log::info('Invitation Created - Email: ' . $invitation->email);
        \Log::info('Invitation Created - Colocation ID: ' . $invitation->colocation_id);
        \Log::info('Invitation Created - Invite URL: ' . route('invitations.show', $invitation->token));

        return redirect()->route('invitations.link', $colocation)
            ->with('success', 'Invitation envoyée avec succès.');
    }

    private function sendInvitationEmail(Invitation $invitation): void
    {
        $inviteUrl = route('invitations.show', $invitation->token);
        
        // Use Laravel's Mail system with proper HTML content
        try {
            Mail::send('emails.invitation', [
                'invitation' => $invitation,
                'colocation' => $invitation->colocation,
                'inviter' => $invitation->inviter,
                'inviteUrl' => $inviteUrl,
            ], function ($message) use ($invitation) {
                $message->to($invitation->email)
                    ->subject('Invitation EasyColoc')
                    ->from('noreply@easycoloc.com', 'EasyColoc');
            });
        } catch (\Exception $e) {
            // Log error but don't fail the invitation creation
            \Log::error('Failed to send invitation email: ' . $e->getMessage());
        }
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
        // Debug: Log incoming token and session
        \Log::info('Invitation Show - Incoming token: ' . $token);
        \Log::info('Invitation Show - Session ID: ' . session()->getId());
        \Log::info('Invitation Show - All session data: ' . json_encode(session()->all()));
        
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

        // Check if email already exists in system
        $existingUser = \App\Models\User::where('email', $invitation->email)->first();
        
        // Debug: Log invitation details
        \Log::info('Invitation Show - Token: ' . $token);
        \Log::info('Invitation Show - Email: ' . $invitation->email);
        \Log::info('Invitation Show - Existing User: ' . ($existingUser ? 'YES' : 'NO'));
        
        if ($existingUser) {
            // Email exists, redirect to login with invitation info
            \Log::info('Invitation Show - Redirecting to login with token');
            return redirect()->route('login')
                ->with('invitation_token', $token)
                ->with('info', 'Veuillez vous connecter pour accepter cette invitation.');
        } else {
            // Email doesn't exist, redirect to register with invitation info
            \Log::info('Invitation Show - Redirecting to register with token');
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

        // Check if user already has active colocation (Scenario 4)
        if ($user->activeColocation()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà une colocation active. Vous ne pouvez pas en rejoindre une nouvelle.');
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
