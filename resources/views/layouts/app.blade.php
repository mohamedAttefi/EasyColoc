<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&amp;family=Spline+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #0f766e;
            --accent: #f97316;
            --ink: #0f172a;
            --paper: #fbf7f2;
            --mist: #f1f5f9;
        }
        .app-bg {
            background-image:
                radial-gradient(circle at 15% 12%, rgba(15, 118, 110, 0.18), transparent 45%),
                radial-gradient(circle at 85% 0%, rgba(249, 115, 22, 0.2), transparent 40%),
                linear-gradient(180deg, rgba(251, 247, 242, 0.98), rgba(251, 247, 242, 0.75));
        }
        .grain::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='1' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-enter {
            animation: fadeUp 0.6s ease both;
        }
        @keyframes floaty {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }
        .float-slow {
            animation: floaty 12s ease-in-out infinite;
        }
        .float-slower {
            animation: floaty 16s ease-in-out infinite;
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "var(--primary)",
                        "accent": "var(--accent)",
                        "ink": "var(--ink)",
                        "paper": "var(--paper)",
                        "mist": "var(--mist)",
                        "background-light": "var(--paper)",
                        "background-dark": "#0f172a",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"],
                        "body": ["Spline Sans", "sans-serif"]
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

<body class="bg-background-light dark:bg-background-dark font-body text-ink dark:text-slate-100 antialiased app-bg">
    <div class="fixed inset-0 -z-10 grain"></div>
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute -top-24 -left-16 h-72 w-72 rounded-full bg-primary/20 blur-3xl float-slow"></div>
        <div class="absolute top-10 right-0 h-80 w-80 rounded-full bg-accent/20 blur-3xl float-slower"></div>
    </div>
    <div class="relative flex min-h-screen flex-col overflow-x-hidden">
        <!-- Navigation -->
        <header class="sticky top-0 z-50 w-full border-b border-slate-200/70 dark:border-slate-800 bg-background-light/70 dark:bg-background-dark/80 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-10">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-3xl font-bold">payments</span>
                    <h2 class="text-xl font-extrabold tracking-tight text-ink dark:text-slate-100 font-display">EasyColoc</h2>
                </div>
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#features">Features</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#how-it-works">How it works</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Pricing</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">About</a>
                </nav>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-bold text-slate-700 dark:text-slate-300 px-4 py-2 hover:bg-white/60 dark:hover:bg-slate-800 rounded-lg transition-all">Log In</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-lg hover:shadow-lg hover:shadow-primary/30 transition-all">Get Started</a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-enter">
            {{ $slot }}
        </main>
        <footer class="border-t border-slate-200/70 dark:border-slate-800 bg-white/80 dark:bg-slate-950/80 px-6 py-12 lg:px-10 backdrop-blur">
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
