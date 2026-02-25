<x-guest-layout>
            <div class="w-full max-w-[440px] bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2">Connexion</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Ravi de vous revoir ! Entrez vos accès pour continuer.</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium block" for="email">Adresse email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                            <input class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100 placeholder:text-slate-400 @error('email') border-red-500 @enderror" id="email" name="email" placeholder="votre@email.com" type="email" value="{{ old('email') }}" />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-sm font-medium block" for="password">Mot de passe</label>
                            <a class="text-sm text-primary hover:underline font-medium" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                            <input class="w-full pl-10 pr-12 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all text-slate-900 dark:text-slate-100 placeholder:text-slate-400 @error('password') border-red-500 @enderror" id="password" name="password" placeholder="••••••••" type="password" />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" type="button">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                        </div>
                    </div>
                    <!-- Remember Me -->
                    <div class="flex items-center gap-2">
                        <input class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary bg-slate-50 dark:bg-slate-800" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }} />
                        <label class="text-sm text-slate-600 dark:text-slate-400 select-none" for="remember">Se souvenir de moi</label>
                    </div>
                    <!-- Submit Button -->
                    <button class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2" type="submit">
                        Se connecter
                        <span class="material-symbols-outlined text-xl">login</span>
                    </button>
                </form>
                <!-- Social Login Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200 dark:border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white dark:bg-slate-900 px-2 text-slate-500">Ou continuer avec</span>
                    </div>
                </div>
                <!-- Social Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-2 py-2 px-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <img alt="Google Logo" class="w-4 h-4" data-alt="Google authentication logo icon" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAJydmhnWRz3y4UAsyGPgcFo_uFJxvHmDERxOzs-_R6aTB_hfDxbbunR4fKbRUHqr2pbHi7snSf9DfPEPBAUUvOdXm9xZEMc6dTv0a6tmVcYGb0yfSPysfXnBA4xwb9Scvuxvkv5i6AS-OKxCmhwtyGs6jsdA4o5u27DqQukwsR_VeGYvUdMajlZOOX6VdngugoOodOrnjio4-COuicY4Nb3nQtPmUfi-d8K7avPMqappuzHYqCOfJQrCV444V2MHeTLbfxxSmNovc" />
                        <span class="text-sm font-medium">Google</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 py-2 px-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-xl">social_leaderboard</span>
                        <span class="text-sm font-medium">Facebook</span>
                    </button>
                </div>
            </div>
</x-guest-layout>