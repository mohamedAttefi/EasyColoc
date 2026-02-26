@extends('layouts.dashboard')

@section('title', 'Modifier ' . $colocation->name . ' - EasyColoc')

@section('page-title', 'Modifier la Colocation')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Modifier la Colocation</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Mettre à jour les informations de votre colocation</p>
            </div>
            
            <form method="POST" action="{{ route('colocations.update', $colocation) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nom de la Colocation
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $colocation->name) }}"
                        required
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                        placeholder="ex: Appartement Ensoleillé, Maison 123"
                    >
                    @error('name')
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
                        rows="4"
                        class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary dark:bg-slate-800 dark:text-slate-100"
                        placeholder="Décrivez votre colocation...">{{ old('description', $colocation->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit -->
                <div class="flex gap-4">
                    <a href="{{ route('colocations.show', $colocation) }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="flex-1 bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                        Mettre à jour la Colocation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
