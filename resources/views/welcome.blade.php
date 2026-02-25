<x-app-layout>
    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative overflow-hidden px-6 py-16 lg:px-10 lg:py-24">
            <div class="mx-auto max-w-7xl flex flex-col lg:flex-row items-center gap-12">
                <div class="flex flex-col gap-8 lg:w-1/2 text-left">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-sm font-semibold text-primary">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                        </span>
                        Joined by 10,000+ roommates
                    </div>
                    <h1 class="text-5xl font-black leading-[1.1] tracking-tight text-slate-900 dark:text-white lg:text-7xl">
                        Stress-free shared living <span class="text-primary">starts here</span>
                    </h1>
                    <p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400 max-w-xl">
                        Manage bills, track debts, and settle up with roommates easily. The all-in-one platform for effortless colocation management and financial peace of mind.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <button class="h-14 min-w-[180px] bg-primary text-white text-lg font-bold rounded-xl hover:scale-[1.02] transition-transform shadow-xl shadow-primary/20">
                            Get Started Free
                        </button>
                        <button class="h-14 min-w-[180px] flex items-center justify-center gap-2 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-lg font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined">play_circle</span>
                            See Demo
                        </button>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 relative">
                    <div class="aspect-square w-full rounded-3xl bg-gradient-to-tr from-primary/20 to-primary/5 p-4 ring-1 ring-slate-900/5">
                        <div class="h-full w-full rounded-2xl bg-slate-200 dark:bg-slate-800 bg-cover bg-center shadow-2xl" data-alt="Happy roommates laughing together in a modern bright living room" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBhCfRqyRH1c4Sa_MQoZx3v38PK4EZi7PL9vkB4FjxApfUZVTxqPEJQqULlXsDPbpniJmhXXu1NzUgnFcRKSHwQZRNdDAoTyalRRRQ7_eHgvUcuiIJHWUeZziUHJ7XG8gPiDuKrnK2x-llQ1SHHO3zyO2HpJGZHKAqpYigcn0t3Ua8dLpsdsV4ak74Kt2NjBMhlshiOkPifZFqGh7BtjksZ7aJSzyEASGruAKElBNPMIx05i6VTIWx2GME7OA6xJgXk4ouimSiT55w");'>
                        </div>
                        <div class="absolute -bottom-6 -left-6 rounded-2xl bg-white dark:bg-slate-900 p-6 shadow-xl ring-1 ring-slate-200 dark:ring-slate-800 hidden md:block">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Rent Paid</p>
                                    <p class="text-xs text-slate-500">All roommates settled up</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Social Proof -->
        <section class="border-y border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 py-10">
            <div class="mx-auto max-w-7xl px-6 lg:px-10">
                <p class="text-center text-sm font-semibold uppercase tracking-widest text-slate-500 mb-8">Trusted by households in</p>
                <div class="flex flex-wrap justify-center items-center gap-12 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                    <span class="text-2xl font-bold">PARIS</span>
                    <span class="text-2xl font-bold">LONDON</span>
                    <span class="text-2xl font-bold">BERLIN</span>
                    <span class="text-2xl font-bold">BARCELONA</span>
                    <span class="text-2xl font-bold">AMSTERDAM</span>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section class="px-6 py-20 lg:px-10 lg:py-32" id="features">
            <div class="mx-auto max-w-7xl">
                <div class="mb-16 text-center">
                    <h2 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white sm:text-4xl lg:text-5xl">
                        Simplify your shared expenses
                    </h2>
                    <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
                        Everything you need to keep your household finances transparent and stress-free.
                    </p>
                </div>
                <div class="grid gap-8 md:grid-cols-3">
                    <div class="group flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 hover:border-primary/50 transition-colors shadow-sm">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">receipt_long</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Expense Tracking</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Log every bill and shared purchase in seconds. Snap a photo of receipts and our OCR handles the rest. Never lose a receipt again.
                        </p>
                    </div>
                    <div class="group flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 hover:border-primary/50 transition-colors shadow-sm">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">handshake</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Debt Settlement</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Settle up with a single tap using integrated payments. Our system calculates the most efficient way to pay back roommates.
                        </p>
                    </div>
                    <div class="group flex flex-col gap-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 hover:border-primary/50 transition-colors shadow-sm">
                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">balance</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Fair Balances</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                            Custom split rules for different categories. Automatic balance calculations ensure everyone pays their fair share, always.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works Section -->
        <section class="bg-slate-100 dark:bg-slate-950 px-6 py-20 lg:px-10 lg:py-32" id="how-it-works">
            <div class="mx-auto max-w-5xl">
                <h2 class="text-3xl font-black text-center mb-20 text-slate-900 dark:text-white">How it works</h2>
                <div class="relative space-y-12">
                    <!-- Step 1 -->
                    <div class="flex gap-8 md:gap-16">
                        <div class="flex flex-col items-center">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold text-xl ring-8 ring-primary/10">1</div>
                            <div class="h-full w-0.5 bg-slate-300 dark:bg-slate-700 mt-4"></div>
                        </div>
                        <div class="pb-12">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Create a colocation</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Set up your shared home profile in under a minute. Define your address, monthly budget, and basic house rules.
                            </p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="flex gap-8 md:gap-16">
                        <div class="flex flex-col items-center">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold text-xl ring-8 ring-primary/10">2</div>
                            <div class="h-full w-0.5 bg-slate-300 dark:bg-slate-700 mt-4"></div>
                        </div>
                        <div class="pb-12">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Invite roommates</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Send a magic link via WhatsApp or email to your housemates. They can join the group instantly without complex sign-ups.
                            </p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="flex gap-8 md:gap-16">
                        <div class="flex flex-col items-center">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-white font-bold text-xl ring-8 ring-primary/10">3</div>
                        </div>
                        <div class="">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Start sharing</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Log groceries, utility bills, or cleaning supplies. Let EasyColoc handle the math, remind forgetful roommates, and keep everyone's debt clear.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Final CTA -->
        <section class="px-6 py-20 lg:px-10">
            <div class="mx-auto max-w-7xl rounded-3xl bg-primary px-8 py-16 text-center text-white lg:py-24 shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white/20 via-transparent to-transparent pointer-events-none"></div>
                <h2 class="relative z-10 text-4xl font-black sm:text-5xl lg:text-6xl mb-8">Ready to simplify your home?</h2>
                <p class="relative z-10 mx-auto max-w-2xl text-xl text-primary-100 opacity-90 mb-10">
                    Join over 10,000 roommates who have already saved time and avoided arguments by using EasyColoc.
                </p>
                <div class="relative z-10 flex flex-col sm:flex-row justify-center gap-4">
                    <button class="bg-white text-primary px-10 py-5 rounded-xl font-black text-lg hover:bg-slate-50 transition-all hover:scale-105">Get Started for Free</button>
                    <button class="border border-white/40 bg-white/10 backdrop-blur px-10 py-5 rounded-xl font-black text-lg hover:bg-white/20 transition-all">Contact Sales</button>
                </div>
            </div>
        </section>
    </main>
</x-app-layout>