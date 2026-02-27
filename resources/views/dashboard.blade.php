@extends('layouts.dashboard')

@section('title', 'Tableau de Bord - EasyColoc')

@section('page-title', 'Tableau de Bord')

@section('content')
    @if($activeColocation)
        <!-- Welcome Section -->
        <div class="flex flex-col gap-2 mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-ink dark:text-slate-100 font-display">Bon retour, {{ auth()->user()->name }} ! 👋</h1>
            <p class="text-slate-600 dark:text-slate-400">Tout va bien dans votre appartement partagé aujourd'hui.</p>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/80 dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Solde Total</span>
                    <div class="p-2 bg-primary/15 text-primary rounded-lg">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-ink dark:text-slate-100">+€{{ number_format($totalBalance, 2) }}</span>
                    <span class="text-primary text-sm font-semibold flex items-center">
                        <span class="material-symbols-outlined text-sm">trending_up</span> 5.2%
                    </span>
                </div>
                <p class="text-slate-400 text-xs">Depuis le mois dernier</p>
            </div>
            <div class="bg-white/80 dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Mes Dettes</span>
                    <div class="p-2 bg-accent/15 text-accent rounded-lg">
                        <span class="material-symbols-outlined">credit_card_off</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-ink dark:text-slate-100">-€{{ number_format($totalDebts, 2) }}</span>
                    <span class="text-slate-400 text-sm font-semibold">0% de changement</span>
                </div>
                <p class="text-slate-400 text-xs">Paiement dû dans 4 jours</p>
            </div>
            <div class="bg-white/80 dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Invitations</span>
                    <div class="p-2 bg-ink/10 text-ink rounded-xl">
                        <span class="material-symbols-outlined">group_add</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-ink dark:text-slate-100">{{ $pendingInvitations }}</span>
                    <span class="text-primary text-sm font-semibold">Nouvelles demandes</span>
                </div>
                <p class="text-slate-400 text-xs">{{ $pendingInvitations }} actions en attente</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Expenses Table -->
            <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Dépenses Récentes</h2>
                    <a href="{{ route('colocations.show', $activeColocation) }}" class="text-primary text-sm font-semibold hover:underline">Voir Tout</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Titre</th>
                                <th class="px-6 py-3">Montant</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3 text-right">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <?php if (count($recentExpenses) > 0): ?>
                                <?php foreach ($recentExpenses as $expense): ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-8 rounded bg-{{ $expense['color'] }}-100 text-{{ $expense['color'] }}-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-lg">{{ $expense['icon'] }}</span>
                                                </div>
                                                <span class="font-medium">{{ $expense['title'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">€{{ number_format($expense['amount'], 2) }}</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">{{ $expense['date'] }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-{{ $expense['status_color'] }}-100 dark:bg-{{ $expense['status_color'] }}-900/30 text-{{ $expense['status_color'] }}-600 dark:text-{{ $expense['status_color'] }}-400 text-[10px] font-bold rounded uppercase">{{ $expense['status'] }}</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                        Pas encore de dépenses. Commencez par ajouter votre première dépense!
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Quick View of Debts -->
            <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 flex flex-col h-fit">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Vue Rapide des Dettes</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Statut des dettes actuelles</p>
                </div>
                <div class="p-6 space-y-6">
                    <?php if (count($debts) > 0): ?>
                        <?php foreach ($debts as $debt): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-slate-200 bg-cover bg-center" style="background-image: url('https://ui-avatars.com/api/?name={{ $debt['name'] }}&background=6366f1&color=fff')" data-alt="Avatar de {{ $debt['name'] }}"></div>
                                    <div>
                                        <p class="text-sm font-semibold">{{ $debt['name'] }}</p>
                                        <p class="text-xs <?php echo $debt['amount'] > 0 ? 'text-green-500' : 'text-red-500'; ?>">
                                            <?php echo $debt['amount'] > 0 ? 'vous doit' : 'vous devez'; ?>
                                        </p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold <?php echo $debt['amount'] > 0 ? 'text-green-500' : 'text-red-500'; ?>">
                                    <?php echo ($debt['amount'] > 0 ? '+' : '') . '€' . number_format(abs($debt['amount']), 2); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        
                        <a href="{{ route('colocations.show', $activeColocation) }}#settlements" class="w-full py-3 bg-primary text-white rounded-lg font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-xl">send</span>
                            R?gler les Soldes
                        </a>
                    <?php else: ?>
                        <div class="text-center text-slate-500 py-4">
                            Pas de dettes pour le moment!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        @if($activeColocation)
        <div class="bg-gradient-to-br from-primary via-primary/80 to-accent rounded-2xl p-8 shadow-xl shadow-primary/30 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative mt-8">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-white mb-2">Invitez votre nouveau colocataire!</h3>
                <p class="text-white/80 max-w-sm mb-6">Trouvé quelqu'un pour la chambre vide? Invitez-le à commencer à gérer les dépenses partagées immédiatement.</p>
                <a href="{{ route('invitations.create', $activeColocation) }}" class="bg-white text-primary px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-50 transition-colors">
                    Envoyer une Invitation
                </a>
            </div>
            <div class="hidden md:block absolute right-0 top-0 h-full w-1/2 opacity-20 pointer-events-none">
                <div class="absolute inset-0 bg-gradient-to-l from-white/40 to-transparent"></div>
                <span class="material-symbols-outlined text-[200px] text-white absolute -right-8 -bottom-8">diversity_3</span>
            </div>
        </div>
        @endif
    @else
        <!-- No Colocation State -->
        <div class="max-w-2xl mx-auto text-center">
            <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 p-12">
                <div class="size-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-3xl">home</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-4">Bienvenue dans EasyColoc!</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
                    EasyColoc vous aide à gérer les dépenses partagées avec vos colocataires. 
                    Créez votre première colocation ou rejoignez-en une existante pour commencer.
                </p>
                
                <div class="space-y-4">
                    <a href="{{ route('colocations.create') }}" class="w-full bg-primary text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:bg-primary/90 transition-all flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-2xl">add_home</span>
                        Créer une Colocation
                    </a>
                    
                    <div class="text-slate-500 dark:text-slate-400 text-sm">
                        Ou rejoignez une colocation existante avec une invitation
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection