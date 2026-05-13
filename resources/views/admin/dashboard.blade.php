@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest mb-3 border border-red-500/20">
                ⚡ Panel de Control
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Admin <span class="text-brand-neon">Dashboard</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Gestión global de la plataforma Quiniela 2026.</p>
        </div>
        
        <div class="flex gap-3">
             <form action="{{ route('admin.sync') }}" method="POST">
                @csrf
                <button type="submit" class="bg-brand-emerald hover:bg-brand-neon text-brand-dark px-6 py-3 rounded-2xl text-sm font-black transition-all shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    SINCRONIZAR
                </button>
            </form>
            <a href="{{ route('admin.payments.index') }}" class="bg-brand-neon hover:bg-white text-brand-dark px-6 py-3 rounded-2xl text-sm font-black transition-all shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                PAGOS
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="glass p-6 rounded-[2rem] border border-white/10">
            <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Usuarios</div>
            <div class="text-3xl font-black text-white">{{ $totalUsers }}</div>
        </div>
        <div class="glass p-6 rounded-[2rem] border border-white/10">
            <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Juegos Totales</div>
            <div class="text-3xl font-black text-white">{{ $totalGames }}</div>
        </div>
        <div class="glass p-6 rounded-[2rem] border border-white/10">
            <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Finalizados</div>
            <div class="text-3xl font-black text-brand-emerald">{{ $finishedGames }}</div>
        </div>
        <div class="glass p-6 rounded-[2rem] border border-white/10">
            <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Recaudado</div>
            <div class="text-3xl font-black text-brand-neon">${{ number_format($totalCollected, 2) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Activity / Matches -->
        <div class="lg:col-span-3">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-white font-black text-xs uppercase tracking-widest">Partidos Recientes</h3>
                <a href="{{ route('admin.matches.index') }}" class="text-brand-emerald text-[10px] font-black uppercase tracking-widest hover:underline">Ver todos</a>
            </div>

            <div class="glass rounded-[2rem] border border-white/10 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50">
                            <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase">Partido</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase text-center">Score</th>
                            <th class="px-6 py-4 text-[9px] font-black text-slate-500 uppercase text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($recentGames as $game)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="text-[10px] font-bold text-white">{{ $game->team_a }} vs {{ $game->team_b }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-black text-white bg-white/5 px-2 py-1 rounded-lg border border-white/5">
                                    {{ $game->score_a ?? 0 }} - {{ $game->score_b ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-[10px] font-black uppercase">
                                <span class="{{ $game->status === 'finished' ? 'text-brand-emerald' : 'text-brand-yellow' }}">
                                    {{ $game->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
