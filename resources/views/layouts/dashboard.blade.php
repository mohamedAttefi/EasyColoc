<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'EasyColoc Dashboard')</title>
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
                radial-gradient(circle at 20% 10%, rgba(15, 118, 110, 0.12), transparent 45%),
                radial-gradient(circle at 80% 0%, rgba(249, 115, 22, 0.15), transparent 40%),
                linear-gradient(180deg, rgba(251, 247, 242, 0.95), rgba(251, 247, 242, 0.7));
        }
        .glass {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
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
</head>

<body class="bg-background-light dark:bg-background-dark font-body text-ink dark:text-slate-100 antialiased app-bg">
    <div class="fixed inset-0 -z-10 grain"></div>
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute -top-20 -left-20 h-64 w-64 rounded-full bg-primary/20 blur-3xl float-slow"></div>
        <div class="absolute top-8 right-0 h-72 w-72 rounded-full bg-accent/20 blur-3xl float-slower"></div>
    </div>
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-64 flex-shrink-0 border-r border-slate-200/60 dark:border-slate-800 bg-white/80 dark:bg-background-dark/70 glass flex flex-col justify-between">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="size-10 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined">grid_view</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-ink dark:text-slate-100 text-lg font-bold leading-tight font-display">EasyColoc</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">Gérer la vie partagée</p>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-semibold rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined">dashboard</span>
                        Tableau de Bord
                    </a>
                    
                    @if(auth()->user()->activeColocation())
                        <a href="{{ route('colocations.show', auth()->user()->activeColocation()) }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg hover:bg-white/60 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined">home</span>
                            Ma Colocation
                        </a>
                    @endif
                    
                    @if(auth()->user()->is_super_admin)
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg hover:bg-white/60 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined">admin_panel_settings</span>
                            Panneau Admin
                        </a>
                    @endif
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg hover:bg-white/60 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined">person</span>
                        Profil
                    </a>
                </nav>
            </div>
            <div class="p-6 border-t border-slate-200 dark:border-slate-800">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-white/60 dark:hover:bg-slate-800 transition-colors mb-2" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-sm font-medium">Paramètres</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-sm font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto flex flex-col">
            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-8 border-b border-slate-200/70 dark:border-slate-800 bg-white/70 dark:bg-background-dark/80 glass sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Pages /</span>
                    <span class="text-ink dark:text-slate-100 font-semibold">@yield('page-title', 'Dashboard')</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative max-w-md hidden md:block">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                        <input class="bg-white/70 dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary w-64" placeholder="Search..." type="text" />
                    </div>
                    <button class="size-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 size-2 bg-red-500 border-2 border-white dark:border-background-dark rounded-full"></span>
                    </button>
                    <div class="size-10 rounded-full bg-cover bg-center border border-slate-200 dark:border-slate-700" style="background-image: url('https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=137fec&color=fff')" data-alt="User profile picture"></div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="p-8 page-enter">
                @yield('content')
            </div>
            
            <footer class="mt-auto p-8 text-center text-slate-400 text-sm">
                © 2024 EasyColoc Dashboard. Modern Living, Simplified.
            </footer>
        </main>
    </div>
</body>

</html>
