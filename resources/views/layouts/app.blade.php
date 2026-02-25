<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <title>EasyColoc | Simplify Shared Living</title>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased">
    <div class="relative flex min-h-screen flex-col overflow-x-hidden">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-10">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-3xl font-bold">payments</span>
                    <h2 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">EasyColoc</h2>
                </div>
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#features">Features</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#how-it-works">How it works</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Pricing</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">About</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-bold text-slate-700 dark:text-slate-300 px-4 py-2 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-lg transition-all">Log In</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-lg hover:shadow-lg hover:shadow-primary/30 transition-all">Get Started</a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <footer class="border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-6 py-12 lg:px-10">
            <div class="mx-auto max-w-7xl grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
                <div class="col-span-2 lg:col-span-2">
                    <div class="flex items-center gap-2 text-primary mb-6">
                        <span class="material-symbols-outlined text-3xl font-bold">payments</span>
                        <h2 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">EasyColoc</h2>
                    </div>
                    <p class="text-slate-500 max-w-xs mb-6">Making shared living fair, transparent, and easy for everyone involved. No more awkward conversations.</p>
                    <div class="flex gap-4">
                        <a class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">
                            <span class="material-symbols-outlined">public</span>
                        </a>
                        <a class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">
                            <span class="material-symbols-outlined">share</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-6">Product</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="#">Features</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Mobile App</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Integrations</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Security</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-6">Company</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="#">About Us</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Careers</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Blog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-6">Legal</h4>
                    <ul class="space-y-4 text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Terms of Service</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="mx-auto max-w-7xl mt-16 pt-8 border-t border-slate-100 dark:border-slate-800 text-center text-sm text-slate-400">
                <p>© 2024 EasyColoc. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>