@extends('layouts.dashboard')

@section('title', 'Edit Category - EasyColoc')

@section('page-title', 'Modifier une catÃ©gorie')

@section('content')
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-ink dark:text-slate-100 font-display">Modifier la catÃ©gorie</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Colocation : {{ $colocation->name }}</p>
            </div>
            <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Retour
            </a>
        </div>

        <div class="bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2" required>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Couleur (Tailwind)</label>
                    <input id="color" name="color" type="text" value="{{ old('color', $category->color) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2">
                    @error('color')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">IcÃ´ne (Material)</label>
                    <input id="icon" name="icon" type="text" value="{{ old('icon', $category->icon) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2">
                    @error('icon')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                        Enregistrer
                    </button>
                    <a href="{{ route('categories.index') }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
