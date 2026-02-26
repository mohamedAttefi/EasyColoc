@extends('layouts.dashboard')

@section('title', 'Ajouter une Dépense - EasyColoc')

@section('page-title', 'Ajouter une Dépense')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Ajouter une Nouvelle Dépense</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Suivez les dépenses partagées pour {{ $colocation->name }}</p>
            </div>
            
            <form method="POST" action="{{ route('expenses.store') }}" class="p-6 space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Titre de la Dépense *
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title') }}"
                        required
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                        placeholder="ex: Courses, Facture d'électricité, Internet"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Description (Optionnel)
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                        placeholder="Ajoutez des détails supplémentaires...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Amount and Date Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Montant (€) *
                        </label>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            value="{{ old('amount') }}"
                            step="0.01"
                            min="0.01"
                            required
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                            placeholder="0.00"
                        >
                        @error('amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Date *
                        </label>
                        <input 
                            type="date" 
                            id="date" 
                            name="date" 
                            value="{{ old('date', now()->format('Y-m-d')) }}"
                            required
                            max="{{ now()->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                        >
                        @error('date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Catégorie *
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                    >
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit -->
                <div class="flex gap-4">
                    <a href="{{ route('colocations.show', $colocation) }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Ajouter une Dépense
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
