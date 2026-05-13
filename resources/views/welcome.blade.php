@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative h-[360px] sm:h-[480px] mb-10 rounded-2xl sm:rounded-[2.5rem] overflow-hidden group shadow-2xl">
    <img src="{{ asset('images/stadium.png') }}" class="absolute inset-0 w-full h-full object-cover transition duration-1000 group-hover:scale-105" alt="Estadio">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-5">
        <!-- Floating Assets -->
        <div class="absolute -top-10 -right-20 sm:-right-10 w-64 h-64 opacity-20 sm:opacity-40 pointer-events-none animate-pulse">
            <img src="{{ asset('images/assets/wc_assets.png') }}" class="w-full h-full object-contain rotate-12" alt="WC Trophy & Ball">
        </div>

        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald text-dark text-[10px] font-black uppercase tracking-widest mb-4 sm:mb-6 animate-pulse">⚽ Mundial 2026</span>
        <h1 class="text-4xl sm:text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-[0.9] mb-3 sm:mb-4">
            Demuestra que <br> <span class="text-brand-neon">sabes de fútbol</span>
        </h1>
        <p class="text-slate-300 text-sm sm:text-lg max-w-xl mx-auto mb-6 sm:mb-8 font-medium leading-relaxed">
            ¡La Quiniela del Mundial 2026 está aquí! Pronostica los resultados para ganar puntos.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-3 w-full max-w-xs sm:max-w-none">
            @guest
                <a href="{{ url('/registro') }}" class="px-7 py-3.5 bg-brand-emerald text-dark font-black rounded-2xl hover:bg-brand-neon transition-all hover:scale-105 shadow-xl shadow-brand-emerald/20 text-sm">
                    REGÍSTRATE Y JUEGA
                </a>
            @endguest

        </div>
    </div>
</section>

<!-- HOW TO PLAY -->
<section id="como-jugar" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-brand-emerald/40 transition">
        <span class="text-3xl mb-3 block">🔥</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Marcador Exacto</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Si pegas el resultado exacto (ej. 2-1), te llevas <span class="text-brand-emerald font-black">5 PUNTOS</span>.</p>
    </div>
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-brand-yellow/40 transition">
        <span class="text-3xl mb-3 block">⚽</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Acierto Parcial</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Si aciertas el ganador o empate, obtienes <span class="text-brand-yellow font-black">3 PUNTOS</span>.</p>
    </div>
    <div class="glass p-6 sm:p-8 rounded-3xl border border-white/10 text-center hover:border-slate-600 transition">
        <span class="text-3xl mb-3 block">⚡</span>
        <h3 class="text-base font-bold text-white uppercase mb-2">Notificaciones</h3>
        <p class="text-slate-500 text-xs leading-relaxed">Recibe avisos por <span class="text-brand-emerald font-black">WhatsApp</span> de tus resultados.</p>
    </div>
</section>

@if($liveGames->count() > 0)
    <section class="mb-12">
        <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-red-500 pl-4 mb-5 flex items-center gap-3">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            En Vivo Ahora
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($liveGames as $game)
                @include('partials.game-card', ['game' => $game, 'informative' => true])
            @endforeach
        </div>
    </section>
@endif

<!-- JUEGOS DE HOY -->
@if($todayGames->count() > 0)
    <section class="mb-12">
        <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-emerald pl-4 mb-5">Partidos para Hoy</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
            @foreach($todayGames as $game)
                @include('partials.game-card', ['game' => $game, 'informative' => true])
            @endforeach
        </div>
    </section>
@else
    <section class="mb-12 glass p-6 rounded-3xl border border-dashed border-white/10 text-center">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] italic">No hay partidos programados para hoy ({{ now()->translatedFormat('d M') }})</p>
    </section>
@endif

<!-- PRÓXIMOS PARTIDOS (INFORMATIVO) -->
@if($nextGames->count() > 0)
<section class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-yellow pl-4">Siguientes Encuentros</h2>
        <a href="{{ route('matches.all') }}" class="text-brand-emerald font-black text-[10px] uppercase tracking-widest hover:text-brand-neon transition flex items-center gap-2 group">
            Ver todos los partidos
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
        </a>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach($nextGames as $game)
            @include('partials.game-card', ['game' => $game, 'informative' => true])
        @endforeach
    </div>
</section>
@endif

 <!-- REGLAS Y PREMIOS -->
<section class="mb-12 glass p-8 sm:p-12 rounded-[2.5rem] border border-white/10 relative overflow-hidden">
    <div class="absolute top-0 right-0 p-10 opacity-10 pointer-events-none">
        <span class="text-9xl">💰</span>
    </div>
    <div class="relative z-10">
        <h2 class="text-3xl sm:text-4xl font-black text-white uppercase italic tracking-tighter mb-6">Pozo de Premios</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <p class="text-slate-400 text-sm sm:text-base leading-relaxed mb-6">
                    El 95% del pozo acumulado será repartido entre los mejores participantes al finalizar la <b>fase de grupos</b>. ¡Cada acierto cuenta para subir en el ranking!
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-2xl bg-brand-neon/20 flex items-center justify-center text-brand-neon font-black text-lg group-hover:scale-110 transition">1°</div>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase">60% del pozo</h4>
                            <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest">Campeón Absoluto</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-2xl bg-brand-emerald/20 flex items-center justify-center text-brand-emerald font-black text-lg group-hover:scale-110 transition">2°</div>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase">25% del pozo</h4>
                            <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest">Subcampeón</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-2xl bg-brand-yellow/20 flex items-center justify-center text-brand-yellow font-black text-lg group-hover:scale-110 transition">3°</div>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase">10% del pozo</h4>
                            <p class="text-slate-500 text-[10px] uppercase font-bold tracking-widest">Tercer Lugar</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/5 border border-white/5 rounded-3xl p-6">
                <h4 class="text-white font-black uppercase text-xs tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 bg-brand-neon rounded-full"></span>
                    Reglas de Empate
                </h4>
                <ul class="text-slate-400 text-xs space-y-3 leading-relaxed">
                    <li class="flex gap-3"><span class="text-brand-emerald font-bold">01.</span> A igual puntaje, el sistema prioriza a quien tenga más marcadores exactos acertados.</li>
                    <li class="flex gap-3"><span class="text-brand-emerald font-bold">02.</span> Si persiste el empate, se suman los premios de las posiciones involucradas y se reparten equitativamente.</li>
                    <li class="flex gap-3"><span class="text-brand-emerald font-bold">03.</span> El ranking se actualiza en tiempo real tras cada partido finalizado.</li>
                </ul>
                <div class="mt-6 p-4 bg-brand-emerald/10 rounded-2xl border border-brand-emerald/20">
                    <p class="text-brand-emerald text-[10px] font-black uppercase text-center tracking-tighter">El 5% restante del pozo es destinado a gastos administrativos y organización.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// BUSCADOR EN TIEMPO REAL
document.getElementById('searchInput').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.game-card');
    
    cards.forEach(card => {
        const teamA = card.getAttribute('data-team-a');
        const teamB = card.getAttribute('data-team-b');
        
        if (teamA.includes(term) || teamB.includes(term)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });

    // Ocultar secciones vacías si no hay resultados
    document.querySelectorAll('section').forEach(section => {
        const visibleCards = section.querySelectorAll('.game-card[style="display: block;"]').length;
        const totalCards = section.querySelectorAll('.game-card').length;
        
        if (totalCards > 0 && visibleCards === 0) {
            section.classList.add('hidden');
        } else {
            section.classList.remove('hidden');
        }
    });
});

function showMoreMatches() {
    document.getElementById('extraMatches').classList.remove('hidden');
    document.getElementById('btnLoadMore').classList.add('hidden');
}
</script>

<!-- RESULTADOS RECIENTES -->
<section class="mb-4">
    <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-slate-600 pl-4 mb-5">Resultados Finales</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 opacity-60">
        @foreach($finishedGames as $game)
            @include('partials.game-card', ['game' => $game, 'informative' => true])
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
