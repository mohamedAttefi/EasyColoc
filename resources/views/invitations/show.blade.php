<x-guest-layout>
    <div class="w-full max-w-[520px] bg-white/80 dark:bg-slate-900 rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-200/70 dark:border-slate-800 p-8">
        <div class="mb-6">
            <h2 class="text-3xl font-extrabold text-ink font-display mb-2">Invitation ? rejoindre une colocation</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm">
                {{ $invitation->inviter->name }} vous invite ? rejoindre <strong>{{ $invitation->colocation->name }}</strong>.
            </p>
        </div>

        @if($invitation->colocation->description)
            <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg text-sm text-slate-600 dark:text-slate-300">
                {{ $invitation->colocation->description }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-3">
            <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    Accepter l'invitation
                </button>
            </form>
            <form method="POST" action="{{ route('invitations.decline', $invitation->token) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 py-2.5 rounded-lg font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Refuser
                </button>
            </form>
        </div>

        <p class="text-xs text-slate-400 mt-6">
            Cette invitation expire le {{ $invitation->expires_at->format('d/m/Y') }}.
        </p>
    </div>
</x-guest-layout>
