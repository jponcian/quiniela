@extends('layouts.app')

@section('title', 'Partidos — Quiniela 2026')

@section('content')
<div class="mb-10">
    <h1 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tighter italic mb-4">
        Carga tus <span class="text-brand-neon">Pronósticos</span>
    </h1>
    <p class="text-slate-400 font-medium">Aquí puedes ver todos los partidos de la fase de grupos y guardar tus resultados.</p>
</div>

<!-- PARTIDOS EN VIVO -->
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
            @include('partials.game-card', ['game' => $game])
        @endforeach
    </div>
</section>
@endif

<!-- PARTIDOS POR GRUPOS -->
@foreach($groupedGames as $groupName => $games)
    <section class="mb-16 last:mb-0">
        <div class="flex items-center gap-4 mb-8">
            <h2 class="text-xl sm:text-2xl font-black uppercase tracking-tighter italic border-l-4 border-brand-yellow pl-4">
                {{ str_replace('_', ' ', $groupName) }}
            </h2>
            <div class="h-px bg-white/5 flex-1"></div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach($games as $game)
                @include('partials.game-card', ['game' => $game])
            @endforeach
        </div>
    </section>
@endforeach

<!-- PARTIDOS FINALIZADOS (Opcional) -->
@if($finishedGames->count() > 0)
<section class="mt-20 opacity-60">
    <h2 class="text-xl font-black uppercase tracking-tighter italic border-l-4 border-slate-600 pl-4 mb-8">Resultados Finales</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach($finishedGames->take(12) as $game)
            @include('partials.game-card', ['game' => $game])
        @endforeach
    </div>
</section>
@endif

@endsection
