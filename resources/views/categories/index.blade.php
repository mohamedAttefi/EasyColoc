@extends('layouts.dashboard')

@section('title', 'Categories - EasyColoc')

@section('page-title', 'Categories')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-ink dark:text-slate-100 font-display">GÃ©rer les catÃ©gories</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Colocation : {{ $colocation->name }}</p>
        </div>
        <a href="{{ route('colocations.show', $colocation) }}" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            Retour Ã  la colocation
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40 p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Nouvelle catÃ©gorie</h2>
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nom</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2" required>
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Couleur (Tailwind)</label>
                    <input id="color" name="color" type="text" value="{{ old('color', 'slate') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2" placeholder="slate, blue, green...">
                    @error('color')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">IcÃ´ne (Material)</label>
                    <input id="icon" name="icon" type="text" value="{{ old('icon', 'more_horiz') }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2" placeholder="shopping_cart, wifi, home...">
                    @error('icon')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    CrÃ©er
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white/80 dark:bg-slate-900 rounded-2xl border border-slate-200/70 dark:border-slate-800 shadow-lg shadow-slate-200/40">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">CatÃ©gories existantes</h2>
            </div>
            @if($categories->count() > 0)
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($categories as $category)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-{{ $category->color ?? 'gray' }}-100 text-{{ $category->color ?? 'gray' }}-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sm">{{ $category->icon ?? 'receipt' }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $category->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Couleur : {{ $category->color ?? 'slate' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('categories.edit', $category) }}" class="text-blue-500 hover:text-blue-700 text-sm">Modifier</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Supprimer cette catÃ©gorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                    Aucune catÃ©gorie personnalisÃ©e pour le moment.
                </div>
            @endif
        </div>
    </div>
@endsection
