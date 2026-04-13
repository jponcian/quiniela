<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quiniela Mundialista 2026')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#020617',
                            emerald: '#10b981',
                            neon: '#00ff9d',
                            yellow: '#facc15'
                        }
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-neon { text-shadow: 0 0 10px rgba(0, 255, 157, 0.5); }
    </style>
</head>
<body class="text-slate-200">
    <div class="min-h-screen bg-slate-950 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-brand-dark">
        <!-- Header -->
        <header class="sticky top-0 z-50 glass border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-extrabold uppercase tracking-tighter text-neon">
                        Quiniela<span class="text-brand-emerald">2026</span>
                    </span>
                </div>
                <nav class="hidden md:flex gap-6 text-sm font-semibold">
                    <a href="#" class="text-white hover:text-brand-emerald transition">Partidos</a>
                    <a href="#" class="text-slate-400 hover:text-white transition">Ranking</a>
                    <a href="#" class="text-slate-400 hover:text-white transition">Mis Predicciones</a>
                </nav>
                <div>
                    <button class="bg-brand-emerald hover:bg-brand-neon text-dark px-4 py-2 rounded-full text-sm font-bold transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        REGISTRARSE
                    </button>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 py-8">
            @yield('content')
        </main>

        <footer class="py-12 border-t border-white/5 text-center text-slate-500 text-sm">
            <p>&copy; 2026 Quiniela Mundialista - Calabozo, Venezuela</p>
        </footer>
    </div>
</body>
</html>
