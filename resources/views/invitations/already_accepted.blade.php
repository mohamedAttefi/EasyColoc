<x-guest-layout>
    <div class="w-full max-w-[520px] bg-white/80 dark:bg-slate-900 rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-200/70 dark:border-slate-800 p-8 text-center">
        <h2 class="text-3xl font-extrabold text-ink font-display mb-2">Invitation d?j? accept?e</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">
            Cette invitation a d?j? ?t? accept?e. Vous pouvez vous connecter pour acc?der ? votre colocation.
        </p>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors">
            Se connecter
        </a>
    </div>
</x-guest-layout>
