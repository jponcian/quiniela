@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-brand-emerald text-[10px] font-black uppercase tracking-widest flex items-center gap-2 mb-4 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al Dashboard
            </a>
            <h1 class="text-3xl md:text-4xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Gestionar <span class="text-brand-neon">Partidos</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Actualiza marcadores y estados de juego.</p>
        </div>
        
        <div class="flex gap-2">
             <form action="{{ route('admin.sync') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white/5 border border-white/10 hover:border-brand-neon text-white px-4 py-2 rounded-xl text-[10px] font-black tracking-widest transition-all">
                    SINC. API
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.matches.index') }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request('status') ? 'bg-brand-emerald text-brand-dark' : 'bg-white/5 text-slate-400 border border-white/5' }}">Todos</a>
        <a href="{{ route('admin.matches.index', ['status' => 'timed']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'timed' ? 'bg-brand-yellow text-brand-dark' : 'bg-white/5 text-slate-400 border border-white/5' }}">Programados</a>
        <a href="{{ route('admin.matches.index', ['status' => 'in_play']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'in_play' ? 'bg-brand-neon text-brand-dark' : 'bg-white/5 text-slate-400 border border-white/5' }}">En Vivo</a>
        <a href="{{ route('admin.matches.index', ['status' => 'finished']) }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') === 'finished' ? 'bg-slate-700 text-white' : 'bg-white/5 text-slate-400 border border-white/5' }}">Finalizados</a>
    </div>

    <!-- Matches Table -->
    <div class="glass rounded-[2rem] border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha / Torneo</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Partido</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center" style="min-width: 250px;">Actualizar Marcador</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($games as $game)
                    <tr class="hover:bg-white/5 transition-colors">
                        <!-- Date & Group -->
                        <td class="px-6 py-5">
                            <div class="text-[10px] font-black text-brand-emerald mb-1">{{ $game->match_date->format('d/m/Y H:i') }}</div>
                            <div class="text-[8px] text-slate-500 uppercase font-black truncate max-w-[120px]">{{ $game->group }} - R{{ $game->round }}</div>
                        </td>

                        <!-- Team Names -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $game->flag_a }}" class="w-4 h-3 object-cover rounded-[1px] opacity-70" alt="">
                                        <span class="text-xs font-bold text-white uppercase">{{ $game->team_a }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $game->flag_b }}" class="w-4 h-3 object-cover rounded-[1px] opacity-70" alt="">
                                        <span class="text-xs font-bold text-white uppercase">{{ $game->team_b }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Update Form -->
                        <td class="px-6 py-5 text-center">
                            <form action="{{ route('admin.matches.update', $game) }}" method="POST" class="flex items-center justify-center gap-3">
                                @csrf
                                <input type="number" name="score_a" value="{{ $game->score_a ?? 0 }}" 
                                    class="w-12 h-10 bg-white/5 border border-white/10 rounded-xl text-center font-black text-white focus:outline-none focus:border-brand-neon transition" min="0">
                                
                                <span class="text-slate-600 font-bold">-</span>

                                <input type="number" name="score_b" value="{{ $game->score_b ?? 0 }}" 
                                    class="w-12 h-10 bg-white/5 border border-white/10 rounded-xl text-center font-black text-white focus:outline-none focus:border-brand-neon transition" min="0">

                                <select name="status" class="bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase text-slate-300 h-10 px-2 focus:outline-none focus:border-brand-neon">
                                    <option value="timed" {{ $game->status === 'timed' ? 'selected' : '' }}>Programado (TIMED)</option>
                                    <option value="scheduled" {{ $game->status === 'scheduled' ? 'selected' : '' }}>Calendario (SCHEDULED)</option>
                                    <option value="in_play" {{ $game->status === 'in_play' ? 'selected' : '' }}>En Vivo (LIVE)</option>
                                    <option value="finished" {{ $game->status === 'finished' ? 'selected' : '' }}>Finalizado (FINISHED)</option>
                                </select>

                                <button type="submit" class="bg-brand-emerald/10 border border-brand-emerald/30 hover:bg-brand-emerald hover:text-brand-dark px-3 h-10 rounded-xl text-[10px] font-black uppercase text-brand-emerald transition-all shadow-lg">
                                    OK
                                </button>
                            </form>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusMap = [
                                    'timed' => 'PROGRAMADO',
                                    'scheduled' => 'CALENDARIO',
                                    'in_play' => 'LIVE',
                                    'finished' => 'FINALIZADO',
                                    'paused' => 'DESCANSO',
                                    'postponed' => 'POSTPUESTO'
                                ];
                                $label = $statusMap[$game->status] ?? strtoupper($game->status);
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter border
                                {{ $game->status === 'finished' ? 'bg-slate-700 text-slate-300 border-white/10' : ($game->status === 'in_play' ? 'bg-red-500/20 text-red-500 border-red-500/30' : 'bg-brand-emerald/10 text-brand-emerald border-brand-emerald/20') }}">
                                {{ $label }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $games->links() }}
    </div>
</div>
@endsection
