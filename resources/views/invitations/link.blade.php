@extends('layouts.dashboard')

@section('title', 'Invitation Link - EasyColoc')

@section('page-title', 'Invitation Link')

@section('content')
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
        <div class="text-center">
            <div class="mb-6">
                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">Invitation Créée!</h2>
                <p class="text-slate-600 dark:text-slate-400">
                    Votre invitation a été créée avec succès. Partagez ce lien avec votre ami:
                </p>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-mono text-slate-700 dark:text-slate-300">{{ $link }}</span>
                    <button onclick="copyLink()" class="ml-4 px-3 py-1 bg-primary text-white text-sm rounded-lg hover:bg-primary/90 transition-colors">
                        Copier
                    </button>
                </div>
            </div>

            <div class="text-sm text-slate-500 dark:text-slate-400">
                <p class="mb-2">Cette invitation expirera dans 7 jours.</p>
                <p>Vous pouvez également vérifier les logs pour voir si l'email a été envoyé avec succès.</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('colocations.show', $colocation) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la Colocation
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            const linkText = '{{ $link }}';
            navigator.clipboard.writeText(linkText).then(function() {
                alert('Lien copié dans le presse-papiers!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
