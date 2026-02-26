@extends('layouts.dashboard')

@section('title', $colocation->name . ' - EasyColoc')

@section('page-title', $colocation->name)

@section('content')
    <!-- Colocation Header -->
    <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 p-6 mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-extrabold text-ink dark:text-slate-100 mb-2 font-display">{{ $colocation->name }}</h1>
                @if($colocation->description)
                    <p class="text-slate-600 dark:text-slate-400">{{ $colocation->description }}</p>
                @endif
            </div>
            
            @if($colocation->isOwnerOf(Auth::user()))
                <div class="flex gap-2">
                    <a href="{{ route('categories.index') }}" class="px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Catégories
                    </a>
                    <a href="{{ route('colocations.edit', $colocation) }}" class="px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette colocation?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            Annuler
                        </button>
                    </form>
                </div>
            @else
                <form method="POST" action="{{ route('colocations.leave', $colocation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter cette colocation?')">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-sm border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        Quitter la Colocation
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <!-- Members Section -->
    <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 p-6 mb-8">
        <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display mb-4">Membres ({{ $members->count() }})</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($members as $member)
                <div class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-700 rounded-lg">
                    <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                        <span class="text-sm font-semibold">{{ substr($member->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-ink dark:text-slate-100">{{ $member->name }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $member->pivot->role === 'owner' ? 'Propriétaire' : 'Membre' }}
                        </p>
                    </div>
                    
                    @if($colocation->isOwnerOf(Auth::user()) && $member->id !== Auth::id())
                        <form method="POST" action="{{ route('colocations.removeMember', [$colocation, $member]) }}" onsubmit="return confirm('Retirer {{ $member->name }} de la colocation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                Retirer
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Dépenses Récentes</h2>
                
                <!-- Month Filter -->
                <form method="GET" action="{{ route('colocations.show', $colocation) }}" class="flex items-center gap-2">
                    <select name="month" onchange="this.form.submit()" class="text-sm border border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 bg-white dark:bg-slate-800 text-ink dark:text-slate-100">
                        <option value="all" {{ $monthFilter == 'all' ? 'selected' : '' }}>Tous les Mois</option>
                        @foreach($availableMonths as $month)
                            <option value="{{ $month }}" {{ $monthFilter == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month((int)$month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <a href="{{ route('expenses.create') }}" class="bg-primary hover:bg-primary/90 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                Ajouter une Dépense
            </a>
        </div>
        
        @if($expenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-xs uppercase text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Titre</th>
                            <th class="px-6 py-3 text-left">Montant</th>
                            <th class="px-6 py-3 text-left">Payé par</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($expenses as $expense)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-{{ $expense->category->color ?? 'gray' }}-100 text-{{ $expense->category->color ?? 'gray' }}-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm">{{ $expense->category->icon ?? 'receipt' }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $expense->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $expense->category->name ?? 'Uncategorized' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold">€{{ number_format($expense->amount, 2) }}</td>
                                <td class="px-6 py-4">{{ $expense->payer->name }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $expense->date->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($expense->payer_id === Auth::id())
                                        <div class="flex gap-2">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-500 hover:text-blue-700 text-sm">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-2xl text-slate-400">receipt_long</span>
                </div>
                <h3 class="text-lg font-semibold text-ink dark:text-slate-100 mb-2">Aucune dépense pour le moment</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-4">Commencez à suivre les dépenses partagées en ajoutant votre première dépense.</p>
                <a href="{{ route('expenses.create') }}" class="bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    Ajouter la Première Dépense
                </a>
            </div>
        @endif
    </div>

    <!-- Settlements Section -->
    <div id="settlements" class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 mb-8">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800">
            <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Remboursements simplifiés</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Qui doit quoi à qui</p>
        </div>
        @if(count($settlements) > 0)
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach($settlements as $settlement)
                    <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="font-medium text-ink dark:text-slate-100">
                                {{ $settlement['from']->name }} doit {{ $settlement['to']->name }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Règlement recommandé</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-bold text-red-500">€{{ number_format($settlement['amount'], 2) }}</span>
                            @if(Auth::id() === $settlement['from']->id || $colocation->isOwnerOf(Auth::user()))
                                <form method="POST" action="{{ route('payments.store', $colocation) }}">
                                    @csrf
                                    <input type="hidden" name="payer_id" value="{{ $settlement['from']->id }}">
                                    <input type="hidden" name="receiver_id" value="{{ $settlement['to']->id }}">
                                    <input type="hidden" name="amount" value="{{ number_format($settlement['amount'], 2, '.', '') }}">
                                    <button type="submit" class="px-3 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                        Marquer payé
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                Aucun remboursement en attente.
            </div>
        @endif
    </div>

    <!-- Statistics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Statistiques par catégorie</h2>
            </div>
            @if($categoryStats->count() > 0)
                <div class="p-6 space-y-4">
                    @foreach($categoryStats as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded bg-{{ $category->color ?? 'gray' }}-100 text-{{ $category->color ?? 'gray' }}-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sm">{{ $category->icon ?? 'receipt' }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-ink dark:text-slate-100">{{ $category->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Total</p>
                                </div>
                            </div>
                            <span class="font-semibold text-ink dark:text-slate-100">€{{ number_format($category->expenses_sum_amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                    Pas encore de statistiques par catégorie.
                </div>
            @endif
        </div>

        <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-lg font-bold text-ink dark:text-slate-100 font-display">Statistiques mensuelles</h2>
            </div>
            @if($monthlyStats->count() > 0)
                <div class="p-6 space-y-4">
                    @foreach($monthlyStats as $stat)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-ink dark:text-slate-100">
                                    {{ \Carbon\Carbon::createFromDate((int) $stat->year, (int) $stat->month, 1)->format('F Y') }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Total du mois</p>
                            </div>
                            <span class="font-semibold text-ink dark:text-slate-100">€{{ number_format($stat->total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                    Pas encore de statistiques mensuelles.
                </div>
            @endif
        </div>
    </div>
@endsection
