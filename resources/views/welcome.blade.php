@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Hero Section Premium -->
<section class="relative h-[500px] mb-12 rounded-[2.5rem] overflow-hidden group shadow-2xl">
    <img src="{{ asset('images/stadium.png') }}" class="absolute inset-0 w-full h-full object-cover transition duration-1000 group-hover:scale-105" alt="Estadio">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
    <div class="relative h-full flex flex-col items-center justify-center text-center px-4">
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald text-dark text-[10px] font-black uppercase tracking-widest mb-6 animate-bounce">Mundial 2026</span>
        <h1 class="text-5xl md:text-7xl font-black text-white tracking-tighter uppercase italic leading-[0.9] mb-4">
            Demuestra que <br> <span class="text-brand-neon">sabes de fútbol</span>
        </h1>
        <p class="text-slate-300 text-lg max-w-xl mx-auto mb-8 font-medium">
            ¡La Quiniela oficial ya está aquí! Pronostica los resultados, suma puntos y sube en el ranking de Calabozo.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ url('/registro') }}" class="px-8 py-4 bg-brand-emerald text-dark font-black rounded-2xl hover:bg-brand-neon transition-all hover:scale-105 shadow-xl shadow-brand-emerald/20">
                REGÍSTRATE Y JUEGA
            </a>
            <a href="#como-jugar" class="px-8 py-4 bg-white/5 border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition">
                ¿CÓMO FUNCIONA?
            </a>
        </div>
    </div>
</section>

<!-- HOW TO PLAY -->
<section id="como-jugar" class="grid md:grid-cols-3 gap-6 mb-20">
    <div class="glass p-8 rounded-3xl border border-white/10 text-center hover:border-brand-emerald/40 transition">
        <span class="text-3xl mb-4 block">🔥</span>
        <h3 class="text-lg font-bold text-white uppercase mb-2">Marcador Exacto</h3>
        <p class="text-slate-500 text-xs">Si pegas el resultado exacto (ej. 2-1), te llevas <span class="text-brand-emerald font-black">3 PUNTOS</span>.</p>
    </div>
    <div class="glass p-8 rounded-3xl border border-white/10 text-center hover:border-brand-yellow/40 transition">
        <span class="text-3xl mb-4 block">⚽</span>
        <h3 class="text-lg font-bold text-white uppercase mb-2">Acierto Parcial</h3>
        <p class="text-slate-500 text-xs">Si aciertas el ganador o empate, obtienes <span class="text-brand-yellow font-black">1 PUNTO</span>.</p>
    </div>
    <div class="glass p-8 rounded-3xl border border-white/10 text-center hover:border-slate-600 transition">
        <span class="text-3xl mb-4 block">⚡</span>
        <h3 class="text-lg font-bold text-white uppercase mb-2">Notificaciones</h3>
        <p class="text-slate-500 text-xs">Recibe avisos por <span class="text-brand-emerald font-black">WhatsApp</span> de tus resultados.</p>
    </div>
</section>

<!-- Search / Filters -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex gap-2 p-1 glass rounded-2xl border border-white/5">
        <button class="px-6 py-2 rounded-xl bg-brand-emerald text-dark font-bold text-sm shadow-lg shadow-brand-emerald/20 transition">Todos</button>
        <button class="px-6 py-2 rounded-xl text-slate-400 hover:text-white font-bold text-sm transition">Fase de Grupos</button>
        <button class="px-6 py-2 rounded-xl text-slate-400 hover:text-white font-bold text-sm transition">Eliminatorias</button>
    </div>
    
    <div class="relative group">
        <input type="text" placeholder="Buscar país..." class="glass bg-slate-900/50 border-white/10 rounded-2xl px-12 py-3 text-sm focus:ring-2 focus:ring-brand-emerald outline-none transition w-64">
        <svg class="w-5 h-5 text-slate-500 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-brand-emerald transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </div>
</div>

<!-- PARTIDOS EN VIVO -->
@if($liveGames->count() > 0)
<section class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
        <h2 class="text-2xl font-black uppercase tracking-tighter italic">En Vivo Ahora</h2>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($liveGames as $game)
            @include('partials.game-card', ['game' => $game, 'isLive' => true])
        @endforeach
    </div>
</section>
@endif

<!-- JUEGOS DE HOY -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-emerald pl-4">Juegos de Hoy</h2>
        <span class="text-slate-500 text-xs font-bold uppercase">{{ now()->translatedFormat('d F, Y') }}</span>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($todayGames as $game)
            @include('partials.game-card', ['game' => $game])
        @empty
            <div class="col-span-full py-16 text-center glass rounded-3xl border-dashed border-white/5">
                <p class="text-slate-500 italic">No hay más partidos programados para hoy.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- PRÓXIMOS PARTIDOS -->
<section class="mb-12">
    <h2 class="text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-yellow pl-4 mb-6">Próximos Encuentros</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-80 hover:opacity-100 transition-opacity">
        @foreach($upcomingGames->take(6) as $game)
            @include('partials.game-card', ['game' => $game])
        @endforeach
    </div>
    <div class="mt-8 text-center">
        <button class="bg-white/5 hover:bg-white/10 border border-white/10 px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-widest transition">Cargar más partidos</button>
    </div>
</section>

<!-- RESULTADOS RECIENTES -->
<section>
    <h2 class="text-2xl font-black uppercase tracking-tighter italic border-l-4 border-slate-600 pl-4 mb-6">Resultados Finales</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-60">
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
        alert('Por favor, ingresa ambos marcadores.');
        return;
    }

    btn.disabled = true;
    btn.textContent = 'GUARDANDO...';

    try {
        const response = await fetch('/api/predictions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                game_id: gameId,
                score_a: scoreA,
                score_b: scoreB
            })
        });

        const data = await response.json();

        if(response.ok) {
            btn.textContent = '¡GUARDADO!';
            btn.classList.add('bg-brand-neon');
            setTimeout(() => {
                btn.textContent = 'ACTUALIZAR PRONÓSTICO';
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'Error al guardar.');
            btn.disabled = false;
            btn.textContent = 'GUARDAR PRONÓSTICO';
        }
    } catch (error) {
        alert('Error de conexión.');
        btn.disabled = false;
        btn.textContent = 'GUARDAR PRONÓSTICO';
    }
}
</script>
@endsection
