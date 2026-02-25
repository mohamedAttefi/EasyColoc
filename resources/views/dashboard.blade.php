<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EasyColoc Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
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
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-64 flex-shrink-0 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark flex flex-col justify-between">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="size-10 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-outlined">grid_view</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-tight">EasyColoc</h1>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">Manage shared living</p>
                    </div>
                </div>
                <nav class="space-y-1">
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 text-primary" href="#">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="text-sm font-semibold">Dashboard</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">home</span>
                        <span class="text-sm font-medium">Colocations</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">receipt_long</span>
                        <span class="text-sm font-medium">Expenses</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                        <span class="text-sm font-medium">Balances</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">bar_chart</span>
                        <span class="text-sm font-medium">Statistics</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">mail</span>
                        <span class="text-sm font-medium flex-1">Invitations</span>
                        <span class="bg-primary text-white text-[10px] px-1.5 py-0.5 rounded-full">2</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                        <span class="material-symbols-outlined">person</span>
                        <span class="text-sm font-medium">Profile</span>
                    </a>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 mt-4">
                        <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors italic" href="#">
                            <span class="material-symbols-outlined">admin_panel_settings</span>
                            <span class="text-sm font-medium">Admin</span>
                        </a>
                    </div>
                </nav>
            </div>
            <div class="p-6 border-t border-slate-200 dark:border-slate-800">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors mb-2" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-sm font-medium">Settings</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors" href="#">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-medium">Logout</span>
                </a>
            </div>
        </aside>
        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto flex flex-col">
            <!-- Top Header -->
            <header class="h-16 flex items-center justify-between px-8 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark sticky top-0 z-10">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Pages /</span>
                    <span class="text-slate-900 dark:text-slate-100 font-semibold">Dashboard</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative max-w-md hidden md:block">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                        <input class="bg-slate-100 dark:bg-slate-800 border-none rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary w-64" placeholder="Search..." type="text" />
                    </div>
                    <button class="size-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 relative">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2 right-2 size-2 bg-red-500 border-2 border-white dark:border-background-dark rounded-full"></span>
                    </button>
                    <div class="size-10 rounded-full bg-cover bg-center border border-slate-200 dark:border-slate-700" data-alt="User profile picture of a young professional" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAmCskqAw-Kvq5gvezFTAKT0wv3XwenqU0hIhMFizNErYWSGJSEdf9OiSRBhXtvlc62i-xWDRNsSDkwXPCYrkGSTrl-d6LSro27-8Bw343vH-OJKkpJt6WfZhYWS4_SIoBVQagPht3mvUgLI2thzZ5gf4QsWOuFDCQqJFtNIHW6rTIewIFNqGGOdRYIzdC48Rs8WjI-pn4WMAzo991xa72M2ablIuaIeSTCu7J8VfuiEuypEngLKmHgoslyT2N6BEKGj0wuTXQfSRw')"></div>
                </div>
            </header>
            <div class="p-8 space-y-8">
                <!-- Welcome Section -->
                <div class="flex flex-col gap-1">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Welcome back, Alex! 👋</h1>
                    <p class="text-slate-500 dark:text-slate-400">Everything looks great in your shared apartment today.</p>
                </div>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Solde Total</span>
                            <div class="p-2 bg-green-500/10 text-green-500 rounded-lg">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-slate-100">+€124.50</span>
                            <span class="text-green-500 text-sm font-semibold flex items-center">
                                <span class="material-symbols-outlined text-sm">trending_up</span> 5.2%
                            </span>
                        </div>
                        <p class="text-slate-400 text-xs">Since last month</p>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Mes Dettes</span>
                            <div class="p-2 bg-red-500/10 text-red-500 rounded-lg">
                                <span class="material-symbols-outlined">credit_card_off</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-slate-100">-€45.00</span>
                            <span class="text-slate-400 text-sm font-semibold">0% change</span>
                        </div>
                        <p class="text-slate-400 text-xs">Payment due in 4 days</p>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 dark:text-slate-400 font-medium text-sm uppercase tracking-wider">Invitations</span>
                            <div class="p-2 bg-primary/10 text-primary rounded-lg">
                                <span class="material-symbols-outlined">group_add</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-slate-100">2</span>
                            <span class="text-primary text-sm font-semibold">New requests</span>
                        </div>
                        <p class="text-slate-400 text-xs">2 pending actions</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Recent Expenses Table -->
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Recent Expenses</h2>
                            <button class="text-primary text-sm font-semibold hover:underline">View All</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase font-semibold">
                                    <tr>
                                        <th class="px-6 py-3">Title</th>
                                        <th class="px-6 py-3">Amount</th>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-8 rounded bg-orange-100 text-orange-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-lg">shopping_cart</span>
                                                </div>
                                                <span class="font-medium">Groceries (Lidl)</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">€84.20</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">Oct 24, 2023</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-bold rounded uppercase">Paid</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-lg">bolt</span>
                                                </div>
                                                <span class="font-medium">Electricity Bill</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">€120.00</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">Oct 22, 2023</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-[10px] font-bold rounded uppercase">Pending</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-8 rounded bg-purple-100 text-purple-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-lg">wifi</span>
                                                </div>
                                                <span class="font-medium">Internet Fiber</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">€29.99</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">Oct 20, 2023</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-bold rounded uppercase">Paid</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="size-8 rounded bg-gray-100 text-gray-600 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-lg">cleaning_services</span>
                                                </div>
                                                <span class="font-medium">Cleaning Products</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold">€12.50</td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-sm">Oct 18, 2023</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-[10px] font-bold rounded uppercase">Paid</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Quick View of Debts -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col h-fit">
                        <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Quick View of Debts</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Status of current IOUs</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-slate-200 bg-cover bg-center" data-alt="Avatar of roommate Sarah" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC8tV-OccKv8ToW3HKwXPMYqeBflho8yJ06suh_xvnxs8sP4UYYCUHpKE5VCZUNwZq4n5UeHDZLk0YTa9KZV0vCTZsYpGyC6rUxiib-3XcDA3AeBqbQGCbzYpW8FimkftzJYiFAorh-dDMcciNXT7fENxYqnnJNEYgH84-EBrBh-3knUslo3Bebjj_m9_g3AnSxP6ERV4KrhxzE_gThoETcx1PTuTa3N1_AYGGEJeJs1lFBFFqBBKszSMQLmen2qp3lILS0cK2CxUg')"></div>
                                    <div>
                                        <p class="text-sm font-semibold">Sarah L.</p>
                                        <p class="text-xs text-green-500">owes you</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-green-500">+€45.20</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-slate-200 bg-cover bg-center" data-alt="Avatar of roommate Mark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAidLy42yQMWc3t3q3LFfet6MXESNbmUYszP1PNQUfD_KcMWv6ol220zGWR6oYnxdZMGNV4V9UpBOfy30a_dWpRUV2nedB63Ak9cc9bFQolH8FX5dkNok2SiSi0z4NBZuNchjPg6HBjAQD3qM9DR_vZSl5S9XgIg70bn0hFzW82zMhd-u_0D2wKRM3wsKL_Q6ublYjNyAY0WeJo93d1L50_wBk1EQQTI79JEtQ9g0-un9aD5dvqG_r3fejDF3wvI8TK-6_1ekbr68o')"></div>
                                    <div>
                                        <p class="text-sm font-semibold">Mark J.</p>
                                        <p class="text-xs text-red-500">you owe him</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-red-500">-€15.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-slate-200 bg-cover bg-center" data-alt="Avatar of roommate Emma" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB6BSHrAX5lRgT1gG0hZS4ccCRPTSm5fDZk05t1deuJVEUWFUu6FHEyOigN8dPcNjF4lpq8dLoBuNOeTI_PkyONnR2W38r0VWvy-VZRffF82Cap9jQzU_1TcHjHtA-3FgYELo1_Ssse1--SOQQO_cMHXioWQQ7RBqyZjECzp2YkztO7JdA4xgH1RdIE3kC4w6k9NmG-dznJ45NpDesFpvIgu1A1oWLje_Cn0D2ZRDMEC4WK6uRyM_aoPiu0syeIFLrBwU5doISUC58')"></div>
                                    <div>
                                        <p class="text-sm font-semibold">Emma T.</p>
                                        <p class="text-xs text-green-500">owes you</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-green-500">+€12.00</span>
                            </div>
                            <button class="w-full py-3 bg-primary text-white rounded-lg font-bold text-sm shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-xl">send</span>
                                Settle Balances
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Colocation Insight / Graph Placeholder -->
                <div class="bg-primary rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold text-white mb-2">Invite your new roommate!</h3>
                        <p class="text-white/80 max-w-sm mb-6">Found someone new for the empty room? Invite them to start managing shared expenses immediately.</p>
                        <button class="bg-white text-primary px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-50 transition-colors">
                            Send Invitation
                        </button>
                    </div>
                    <div class="hidden md:block absolute right-0 top-0 h-full w-1/2 opacity-20 pointer-events-none">
                        <div class="absolute inset-0 bg-gradient-to-l from-white/40 to-transparent"></div>
                        <span class="material-symbols-outlined text-[200px] text-white absolute -right-8 -bottom-8">diversity_3</span>
                    </div>
                </div>
            </div>
            <footer class="mt-auto p-8 text-center text-slate-400 text-sm">
                © 2023 EasyColoc Dashboard. Modern Living, Simplified.
            </footer>
        </main>
    </div>
</body>

</html>