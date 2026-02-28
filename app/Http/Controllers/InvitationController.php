<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
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

        return redirect()->route('invitations.link', $colocation)
            ->with('success', 'Invitation envoyée avec succès.');
    }

    private function sendInvitationEmail(Invitation $invitation): void
    {
        $inviteUrl = route('invitations.show', $invitation->token);
        
        $subject = 'Invitation EasyColoc';
        $message = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #fbf7f2; }
                    .container { background: white; border-radius: 12px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
                    .header { text-align: center; margin-bottom: 32px; }
                    .logo { font-size: 24px; font-weight: bold; color: #0f766e; }
                    .content { margin-bottom: 32px; }
                    .button { display: inline-block; background: #0f766e; color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin: 16px 0; }
                    .button:hover { background: #0b5f59; }
                    .footer { text-align: center; color: #64748b; font-size: 14px; margin-top: 32px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <div class='logo'>EasyColoc</div>
                    </div>
                    <div class='content'>
                        <h2>Vous êtes invité(e) à rejoindre une colocation !</h2>
                        <p>Bonjour,</p>
                        <p><strong>{$invitation->inviter->name}</strong> vous invite à rejoindre la colocation <strong>{$invitation->colocation->name}</strong> sur EasyColoc.</p>";
                        
                        if ($invitation->colocation->description) {
                            $message .= "<p><em>{$invitation->colocation->description}</em></p>";
                        }
                        
                        $message .= "
                        <p>EasyColoc vous aide à suivre les dépenses partagées, calculer qui doit quoi à qui et gérer le budget de la colocation.</p>
                        <p style='text-align: center;'>
                            <a href='{$inviteUrl}' class='button'>Accepter l'invitation</a>
                        </p>
                        <p><small>Cette invitation expirera dans 7 jours.</small></p>
                    </div>
                    <div class='footer'>
                        <p>Si vous n'avez pas encore de compte EasyColoc, vous pourrez en créer un après avoir cliqué sur le lien ci-dessus.</p>
                        <p>© " . date('Y') . " EasyColoc. Tous droits réservés.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        // Use Laravel's Mail system for better reliability
        try {
            Mail::raw($message, function ($message) use ($invitation, $subject) {
                $message->to($invitation->email)
                    ->subject($subject)
                    ->from('noreply@easycoloc.com', 'EasyColoc')
                    ->html($message);
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
