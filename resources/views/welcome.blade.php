@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative h-[360px] sm:h-[480px] mb-10 rounded-2xl sm:rounded-[2.5rem] overflow-hidden group shadow-2xl">
    <img src="{{ asset('images/stadium.png') }}" class="absolute inset-0 w-full h-full object-cover transition duration-1000 group-hover:scale-105" alt="Estadio">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-5">
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald text-dark text-[10px] font-black uppercase tracking-widest mb-4 sm:mb-6 animate-pulse">⚽ La Liga</span>
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-[0.9] mb-3 sm:mb-4">
            Demuestra que <br> <span class="text-brand-neon">sabes de fútbol</span>
        </h1>
        <p class="text-slate-300 text-sm sm:text-lg max-w-xl mx-auto mb-6 sm:mb-8 font-medium leading-relaxed">
            ¡La Quiniela de La Liga está aquí! Pronostica los resultados para ganar puntos.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-3 w-full max-w-xs sm:max-w-none">
            @guest
                <a href="{{ url('/registro') }}" class="px-7 py-3.5 bg-brand-emerald text-dark font-black rounded-2xl hover:bg-brand-neon transition-all hover:scale-105 shadow-xl shadow-brand-emerald/20 text-sm">
                    REGÍSTRATE Y JUEGA
                </a>
            @endguest
            <a href="#como-jugar" class="px-7 py-3.5 bg-white/5 border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition text-sm">
                ¿CÓMO FUNCIONA?
            </a>
        </div>
    </div>
</section>

<!-- HOW TO PLAY -->
<section id="como-jugar" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-brand-emerald/40 transition">
        <span class="text-3xl mb-3 block">🔥</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Marcador Exacto</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Si pegas el resultado exacto (ej. 2-1), te llevas <span class="text-brand-emerald font-black">3 PUNTOS</span>.</p>
    </div>
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-brand-yellow/40 transition">
        <span class="text-3xl mb-3 block">⚽</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Acierto Parcial</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Si aciertas el ganador o empate, obtienes <span class="text-brand-yellow font-black">1 PUNTO</span>.</p>
    </div>
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-slate-600 transition">
        <span class="text-3xl mb-3 block">⚡</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Notificaciones</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Recibe avisos por <span class="text-brand-emerald font-black">WhatsApp</span> de tus resultados.</p>
    </div>
</section>

<!-- Filters Bar -->
<div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mb-8">
    <div class="flex gap-2 p-1 glass rounded-2xl border border-white/5 overflow-x-auto">
        <button class="px-4 sm:px-6 py-2 rounded-xl bg-brand-emerald text-dark font-bold text-xs sm:text-sm shadow-lg shadow-brand-emerald/20 transition whitespace-nowrap">Todos</button>
        <button class="px-4 sm:px-6 py-2 rounded-xl text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition whitespace-nowrap">Fase de Grupos</button>
        <button class="px-4 sm:px-6 py-2 rounded-xl text-slate-400 hover:text-white font-bold text-xs sm:text-sm transition whitespace-nowrap">Eliminatorias</button>
    </div>
    <div class="relative group">
        <input type="text" placeholder="Buscar equipo..."
            class="glass bg-slate-900/50 border-white/10 rounded-2xl px-10 py-3 text-sm focus:ring-2 focus:ring-brand-emerald outline-none transition w-full sm:w-56">
        <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-1/2 -translate-y-1/2 group-focus-within:text-brand-emerald transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
</div>

<!-- PARTIDOS EN VIVO -->
@if($liveGames->count() > 0)
<section class="mb-12">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
        <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic">En Vivo Ahora</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @foreach($liveGames as $game)
            @include('partials.game-card', ['game' => $game, 'isLive' => true])
        @endforeach
    </div>
</section>
@endif

<!-- JUEGOS DE HOY -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-emerald pl-4">Juegos de Hoy</h2>
        <span class="text-slate-500 text-xs font-bold uppercase hidden sm:block">{{ now()->translatedFormat('d F, Y') }}</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($todayGames as $game)
            @include('partials.game-card', ['game' => $game])
        @empty
            <div class="col-span-full py-12 text-center glass rounded-3xl border-dashed border-white/5">
                <p class="text-slate-500 italic text-sm">No hay más partidos programados para hoy.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- PRÓXIMOS PARTIDOS -->
<section class="mb-12">
    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-yellow pl-4 mb-5">Próximos Encuentros</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 opacity-80 hover:opacity-100 transition-opacity">
        @foreach($upcomingGames->take(6) as $game)
            @include('partials.game-card', ['game' => $game])
        @endforeach

        @if($upcomingGames->count() > 6)
            <div id="extraMatches" class="contents hidden">
                @foreach($upcomingGames->slice(6) as $game)
                    @include('partials.game-card', ['game' => $game])
                @endforeach
            </div>
        @endif
    </div>

    @if($upcomingGames->count() > 6)
        <div class="mt-8 text-center">
            <button id="btnLoadMore" onclick="showMoreMatches()"
                class="bg-white/5 hover:bg-white/10 border border-white/10 px-8 py-3.5 rounded-2xl text-xs font-bold uppercase tracking-widest transition active:scale-95">
                Cargar más partidos
            </button>
        </div>
    @endif
</section>

<script>
function showMoreMatches() {
    document.getElementById('extraMatches').classList.remove('hidden');
    document.getElementById('btnLoadMore').classList.add('hidden');
}
</script>

<!-- RESULTADOS RECIENTES -->
<section class="mb-4">
    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-slate-600 pl-4 mb-5">Resultados Finales</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 opacity-60">
        @foreach($finishedGames as $game)
            @include('partials.game-card', ['game' => $game])
        @endforeach
    </div>
</section>

<script>
async function savePrediction(gameId) {
    const scoreA = document.getElementById('score_a_' + gameId).value;
    const scoreB = document.getElementById('score_b_' + gameId).value;
    const btn = document.getElementById('btn_' + gameId);

    if(scoreA === '' || scoreB === '') {
        showToast('Por favor, ingresa ambos marcadores.', 'error');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'GUARDANDO...';

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
            btn.textContent = '¡GUARDADO! ✓';
            btn.classList.add('bg-brand-neon');
            showToast('¡Pronóstico guardado!', 'success');
            setTimeout(() => {
                btn.textContent = 'ACTUALIZAR PRONÓSTICO';
                btn.classList.remove('bg-brand-neon');
                btn.disabled = false;
            }, 2000);
        } else {
            showToast(data.message || 'Error al guardar.', 'error');
            btn.disabled = false;
            btn.textContent = 'GUARDAR PRONÓSTICO';
        }
    } catch (error) {
        showToast('Error de conexión.', 'error');
        btn.disabled = false;
        btn.textContent = 'GUARDAR PRONÓSTICO';
    }
}

// Toast nativo mobile-friendly (reemplaza alert)
function showToast(msg, type) {
    const existing = document.getElementById('app-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'app-toast';
    const color = type === 'success' ? '#10b981' : '#ef4444';
    toast.style.cssText = `
        position:fixed; bottom:24px; left:50%; transform:translateX(-50%);
        background:#0f172a; border:1px solid ${color}40; color:${color};
        padding:12px 24px; border-radius:16px; font-size:13px; font-weight:700;
        box-shadow:0 8px 32px #00000080; z-index:9999; white-space:nowrap;
        animation:slideUp .25s ease; font-family:'Montserrat',sans-serif;
        max-width:90vw; text-align:center;
    `;
    toast.textContent = msg;
    document.body.appendChild(toast);

    const style = document.createElement('style');
    style.textContent = '@keyframes slideUp{from{opacity:0;transform:translateX(-50%) translateY(16px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}';
    document.head.appendChild(style);

    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
