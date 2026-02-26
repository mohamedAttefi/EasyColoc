<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InvitationMail;

class InvitationController extends Controller
{

    public function create(): View
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();

        if (!$colocation) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to join a colocation first.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can invite members.');
        }

        return view('invitations.create', compact('colocation'));
    }


    public function store(Request $request): View
    {
        $user = Auth::user();
        $colocation = $user->activeColocation();

        if (!$colocation) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to join a colocation first.');
        }

        if (!$colocation->isOwnerOf($user)) {
            abort(403, 'Only owners can invite members.');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $existingUser = \App\Models\User::where('email', $validated['email'])->first();
        if ($existingUser && $colocation->hasActiveMember($existingUser)) {
            return back()->with('error', 'This user is already a member of your colocation.');
        }

        $existingInvitation = Invitation::where('email', $validated['email'])
            ->where('colocation_id', $colocation->id)
            ->pending()
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'An invitation has already been sent to this email.');
        }

        $invitation = Invitation::create([
            'email' => $validated['email'],
            'colocation_id' => $colocation->id,
            'invited_by' => $user->id,
            'expires_at' => now()->addDays(7),
        ]);

        $link = $invitation->invite_url;

        try {
            Mail::to($validated['email'])->send(new InvitationMail($invitation));
            Log::info("Invitation email sent to {$validated['email']} with link: {$link}");
        } catch (\Exception $e) {
            // If email fails, log and continue to show link
            Log::error('Failed to send invitation email: ' . $e->getMessage());
            Log::info("Invitation link for manual sharing: {$link}");
        }

        return view('invitations.link', compact('link', 'colocation'));
    }

    public function show($token): View
    {
        $invitation = Invitation::where('token', $token)
            ->with(['colocation', 'inviter'])
            ->firstOrFail();

        if ($invitation->isExpired()) {
            return view('invitations.expired');
        }

        if ($invitation->isAccepted()) {
            return view('invitations.already_accepted');
        }

        if ($invitation->isDeclined()) {
            return view('invitations.declined');
        }

        return view('invitations.show', compact('invitation'));
    }

    public function accept($token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has already been accepted.');
        }

        $user = \App\Models\User::where('email', $invitation->email)->first();

        if (!$user) {
            // User doesn't have an account - redirect to register
            return redirect()->route('register', ['email' => $invitation->email, 'token' => $token]);
        }

        if ($user->activeColocation()) {
            return redirect()->route('login')
                ->with('error', 'You are already part of an active colocation.');
        }

        $invitation->accept();
        $invitation->colocation->addMember($user, 'member');

        // Check if user is already logged in
        if (Auth::check()) {
            // User is logged in - redirect to dashboard
            return redirect()->route('dashboard')
                ->with('success', 'Invitation accepted! Welcome to your colocation.');
        } else {
            // User is not logged in - redirect to login
            return redirect()->route('login')
                ->with('success', 'Invitation accepted! Please login to access your colocation.');
        }
    }
    public function decline($token): RedirectResponse
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired.');
        }

        $invitation->decline();

        return redirect()->route('login')
            ->with('success', 'Invitation declined.');
    }
}