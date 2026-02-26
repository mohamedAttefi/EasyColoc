<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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


    public function store(Request $request): RedirectResponse
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

        Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return back()->with('success', 'Invitation sent successfully!');
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
            return redirect()->route('register', ['email' => $invitation->email, 'token' => $token]);
        }

        if ($user->activeColocation()) {
            return redirect()->route('login')
                ->with('error', 'You are already part of an active colocation.');
        }

        $invitation->accept();
        $invitation->colocation->addMember($user, 'member');

        return redirect()->route('login')
            ->with('success', 'Invitation accepted! You can now login to access your colocation.');
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
