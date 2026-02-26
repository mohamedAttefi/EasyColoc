<x-guest-layout>
    <div class="w-full max-w-[520px] bg-white/80 dark:bg-slate-900 rounded-2xl shadow-lg shadow-slate-200/40 border border-slate-200/70 dark:border-slate-800 p-8 text-center">
        <h2 class="text-3xl font-extrabold text-ink font-display mb-2">Invitation refus?e</h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">
            Vous avez refus? cette invitation. Si besoin, demandez ? l'owner de vous en renvoyer une.
        </p>
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Cr?er un compte
        </a>
    </div>
</x-guest-layout>
