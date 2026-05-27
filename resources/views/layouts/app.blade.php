<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#020617">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title', 'Quiniela Mundial 2026')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        body { font-family: 'Montserrat', sans-serif; -webkit-tap-highlight-color: transparent; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .text-neon { text-shadow: 0 0 10px rgba(0, 255, 157, 0.5); }

        /* Prevent iOS input zoom (font-size >= 16px) */
        input[type="number"],
        input[type="text"],
        input[type="password"] { font-size: 16px !important; }

        /* Safe-area padding for notch phones */
        .safe-top    { padding-top:    env(safe-area-inset-top); }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }

        /* Mobile menu slide */
        #mobile-menu { transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 0; opacity: 0; overflow: hidden; }
        #mobile-menu.open { max-height: 300px; opacity: 1; }
    </style>
</head>
<body class="text-slate-200">
<div class="min-h-screen bg-slate-950 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-brand-dark">

    <!-- ── HEADER ─────────────────────────────────────────── -->
    <header class="sticky top-0 z-50 glass border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('images/trophy.png') }}" alt="Copa del Mundo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain drop-shadow-[0_0_8px_rgba(250,204,21,0.4)]">
                <span class="text-xl sm:text-2xl font-extrabold uppercase tracking-tighter text-neon">
                    Quiniela<span class="text-brand-emerald">2026</span>
                </span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex gap-6 text-sm font-semibold">
                <a href="{{ route('matches.all') }}" class="{{ request()->routeIs('matches.all') ? 'text-white' : 'text-slate-400 hover:text-white' }} transition">Partidos</a>
                <a href="{{ route('ranking') }}" class="{{ request()->routeIs('ranking') ? 'text-white' : 'text-slate-400 hover:text-white' }} transition">Ranking</a>
                {{-- <a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.index') ? 'text-white' : 'text-slate-400 hover:text-white' }} transition">Ligas</a> --}}
                <a href="{{ route('rules') }}" class="{{ request()->routeIs('rules') ? 'text-white' : 'text-slate-400 hover:text-white' }} transition">Reglas</a>
                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin') ? 'text-brand-neon' : 'text-slate-400 hover:text-brand-neon' }} transition border-l border-white/10 pl-4 font-black text-[10px]">ADMIN</a>
                        <a href="{{ route('admin.payments.index') }}" class="{{ request()->is('admin/payments*') ? 'text-brand-neon' : 'text-slate-400 hover:text-brand-neon' }} transition font-black text-[10px]">PAGOS</a>
                    @endif
                    <a href="{{ route('predictions.index') }}" class="{{ request()->routeIs('predictions.index') ? 'text-white' : 'text-slate-400 hover:text-white' }} transition">Mis Predicciones</a>
                    <a href="{{ route('champion.bets.index') }}" class="{{ request()->routeIs('champion.bets.index') ? 'text-brand-yellow font-bold' : 'text-slate-400 hover:text-brand-yellow' }} transition">🏆 Apuesta Campeón</a>
                @endauth
            </nav>

            <!-- Desktop Auth Actions -->
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <span class="text-xs text-slate-400 font-bold uppercase truncate max-w-[140px]">{{ Auth::user()->name }}</span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition text-xs font-bold underline">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-300 hover:text-brand-emerald border border-white/10 hover:border-brand-emerald/50 px-4 py-2 rounded-full text-sm font-bold transition-all">
                        INICIAR SESIÓN
                    </a>
                    <a href="{{ url('/registro') }}" class="bg-brand-emerald hover:bg-brand-neon text-brand-dark px-4 py-2 rounded-full text-sm font-bold transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        REGISTRARSE
                    </a>
                @endauth
            </div>

            <!-- Mobile Right Side -->
            <div class="flex md:hidden items-center gap-2">

                <!-- Hamburger -->
                <button id="menu-btn" onclick="toggleMenu()" class="p-2 text-slate-400 hover:text-white transition" aria-label="Menú">
                    <svg id="icon-menu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden border-t border-white/5">
            <div class="px-4 py-4 flex flex-col gap-3">
                <a href="{{ route('matches.all') }}" class="{{ request()->routeIs('matches.all') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">⚽ Partidos</a>
                <a href="{{ route('ranking') }}" class="{{ request()->routeIs('ranking') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">🏆 Ranking</a>
                {{-- <a href="{{ route('groups.index') }}" class="{{ request()->routeIs('groups.index') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">👥 Ligas</a> --}}
                <a href="{{ route('rules') }}" class="{{ request()->routeIs('rules') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">📜 Reglas</a>
                @auth
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.payments.index') }}" class="{{ request()->is('admin/payments*') ? 'text-brand-neon border-brand-neon' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">💰 Gestión de Pagos</a>
                    @endif
                    <a href="{{ route('predictions.index') }}" class="{{ request()->routeIs('predictions.index') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">📋 Mis Predicciones</a>
                    <a href="{{ route('champion.bets.index') }}" class="{{ request()->routeIs('champion.bets.index') ? 'text-white border-brand-emerald' : 'text-slate-400 border-white/5' }} font-semibold py-2 border-b">🏆 Apuesta Campeón</a>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs text-slate-400 font-bold uppercase truncate max-w-[200px]">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-bold uppercase">Salir</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center py-3 border border-white/10 rounded-xl text-sm font-bold text-slate-300">
                        INICIAR SESIÓN
                    </a>
                    <a href="{{ url('/registro') }}" class="w-full text-center py-3 bg-brand-emerald rounded-xl text-sm font-black text-brand-dark">
                        REGISTRARSE
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 sm:py-8">
        @yield('content')
    </main>

    <footer class="py-8 sm:py-12 border-t border-white/5 text-center text-slate-500 text-sm safe-bottom">
        <p>&copy; 2026 Quiniela Mundialista &middot; Calabozo, Venezuela</p>
    </footer>
</div>

<script>
function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    const iconMenu  = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');
    const isOpen = menu.classList.toggle('open');
    iconMenu.classList.toggle('hidden', isOpen);
    iconClose.classList.toggle('hidden', !isOpen);
}
// Close menu on outside click
document.addEventListener('click', function(e) {
    const menu = document.getElementById('mobile-menu');
    const btn  = document.getElementById('menu-btn');
    if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
        toggleMenu();
    }
});

const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

@if(session('success'))
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: '¡Atención!',
        text: "{{ session('error') }}",
        background: '#020617',
        color: '#fff',
        confirmButtonColor: '#10b981'
    });
@endif

async function savePrediction(gameId) {
    const scoreAInput = document.getElementById('score_a_' + gameId);
    const scoreBInput = document.getElementById('score_b_' + gameId);
    const btn = document.getElementById('btn_' + gameId);

    if(!scoreAInput || !scoreBInput || !btn) return;

    const scoreA = scoreAInput.value;
    const scoreB = scoreBInput.value;

    if(scoreA === '' || scoreB === '') {
        Toast.fire({ icon: 'warning', title: 'Ingresa ambos marcadores.' });
        return;
    }

    btn.disabled = true;
    const originalText = btn.textContent;
    btn.textContent = '...';

    try {
        const response = await fetch('{{ url("/api/predictions") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ game_id: gameId, score_a: scoreA, score_b: scoreB })
        });

        const data = await response.json();

        if(response.ok) {
            btn.textContent = '✓';
            btn.classList.add('bg-brand-neon');
            Toast.fire({ icon: 'success', title: '¡Pronóstico guardado!' });
            setTimeout(() => {
                btn.textContent = 'ACTUALIZAR';
                btn.classList.remove('bg-brand-neon');
                btn.disabled = false;
            }, 2000);
        } else {
            Toast.fire({ icon: 'error', title: data.message || 'Error al guardar.' });
            btn.disabled = false;
            btn.textContent = originalText;
        }
    } catch (error) {
        Toast.fire({ icon: 'error', title: 'Error de conexión.' });
        btn.disabled = false;
        btn.textContent = originalText;
    }
}
</script>
@stack('scripts')
</body>
</html>
