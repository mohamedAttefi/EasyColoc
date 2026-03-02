@extends('layouts.dashboard')

@section('title', 'Tableau de Bord Admin - EasyColoc')

@section('page-title', 'Tableau de Bord Admin')

@section('content')
    <div class="space-y-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Utilisateurs</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_users']) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined">people</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Colocations Actives</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['active_colocations']) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <span class="material-symbols-outlined">home</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Dépenses</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['total_expenses']) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <span class="material-symbols-outlined">receipt</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Montant Total</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">€{{ number_format($stats['total_amount'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 text-orange-600 rounded-lg">
                        <span class="material-symbols-outlined">euro</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Activité des Utilisateurs</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Nouveaux Utilisateurs ce Mois</span>
                        <span class="font-semibold text-green-600">{{ $stats['new_users_this_month'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Utilisateurs Bannis</span>
                        <span class="font-semibold text-red-600">{{ $stats['banned_users'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Utilisateurs Récents</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-xs uppercase text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Inscription</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($recentUsers as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($user->is_banned)
                                        <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded">Banni</span>
                                    @elseif($user->is_super_admin)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs font-semibold rounded">Admin</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs font-semibold rounded">Actif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(!$user->is_super_admin)
                                        @if($user->is_banned)
                                            <form method="POST" action="{{ route('admin.unban-user', $user) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Débannir</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.ban-user', $user) }}" class="inline" onsubmit="return confirm('Bannir cet utilisateur ?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Bannir</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Colocations -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Colocations Récentes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-xs uppercase text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-3 text-left">Nom</th>
                            <th class="px-6 py-3 text-left">Propriétaire</th>
                            <th class="px-6 py-3 text-left">Membres</th>
                            <th class="px-6 py-3 text-left">Créée</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($recentColocations as $colocation)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                                <td class="px-6 py-4 font-medium">{{ $colocation->name }}</td>
                                <td class="px-6 py-4">{{ $colocation->owner?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $colocation->activeMembers()->count() }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $colocation->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($colocation->status === 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs font-semibold rounded">Actif</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
