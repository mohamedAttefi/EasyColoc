<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!DOCTYPE html>

<html lang="fr">

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
                radial-gradient(circle at 10% 10%, rgba(15, 118, 110, 0.18), transparent 45%),
                radial-gradient(circle at 90% 0%, rgba(249, 115, 22, 0.18), transparent 40%),
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
    <title>Connexion - EasyColoc</title>
</head>

<body class="bg-background-light dark:bg-background-dark font-body text-ink dark:text-slate-100 min-h-screen flex flex-col app-bg antialiased">
    <div class="fixed inset-0 -z-10 grain"></div>
    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-primary/20 blur-3xl float-slow"></div>
        <div class="absolute top-12 right-0 h-80 w-80 rounded-full bg-accent/20 blur-3xl float-slower"></div>
    </div>
    <div class="flex-1 flex flex-col items-center justify-center p-4 page-enter">
        <!-- Logo / Header Section -->
        <div class="mb-8 flex flex-col items-center gap-2">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1">home_work</span>
                <h1 class="text-2xl font-bold tracking-tight font-display">EasyColoc</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Simplifiez votre vie en communauté</p>
        </div>
        {{ $slot }}
    </div>
    <!-- Background Decoration -->
    <div class="fixed top-0 right-0 -z-10 opacity-10 dark:opacity-5">
        <span class="material-symbols-outlined text-[400px]" style="font-variation-settings: 'wght' 100">groups</span>
    </div>
    <div class="fixed bottom-0 left-0 -z-10 opacity-10 dark:opacity-5">
        <span class="material-symbols-outlined text-[300px]" style="font-variation-settings: 'wght' 100">apartment</span>
    </div>
</body>

</html>
