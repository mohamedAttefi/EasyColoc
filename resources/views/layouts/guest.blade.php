<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "display": ["Inter"]
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

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-center p-4">
        <!-- Logo / Header Section -->
        <div class="mb-8 flex flex-col items-center gap-2">
            <div class="flex items-center gap-2 text-primary">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1">home_work</span>
                <h1 class="text-2xl font-bold tracking-tight">EasyColoc</h1>
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