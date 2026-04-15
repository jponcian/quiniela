@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald/10 text-brand-emerald text-[10px] font-black uppercase tracking-widest mb-3 border border-brand-emerald/20">
                🏆 Temporada 2025/26
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Ranking de <span class="text-brand-neon">Participantes</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Liderazgo y estadísticas en tiempo real.</p>
        </div>
        
        <div class="glass p-5 rounded-3xl border border-white/10 flex items-center gap-6">
            <div class="text-center px-4 border-r border-white/5">
                <div class="text-2xl font-black text-white leading-none mb-1">{{ count($rankedUsers) }}</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Jugadores</div>
            </div>
            <div class="text-center px-4">
                <div class="text-2xl font-black text-brand-neon leading-none mb-1">95%</div>
                <div class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Pozo Premios</div>
            </div>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass rounded-[2rem] border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/50 border-bottom border-white/5">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pos</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Trend</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Participante</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Puntos</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Plenos (5)</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Prob</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Estatus</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Premio Est.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($rankedUsers as $ru)
                    @php 
                        $user = $ru['user'];
                        $isMe = $currentUser && $currentUser->id === $user->id;
                        $prize = $prizes[$user->id] ?? 0;
                    @endphp
                    <tr class="hover:bg-white/5 transition-colors {{ $isMe ? 'bg-brand-emerald/5' : '' }}">
                        <!-- Rank -->
                        <td class="px-6 py-5">
                            @if($ru['rank'] === 1)
                                <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-dark font-black shadow-lg shadow-yellow-400/20">1</div>
                            @elseif($ru['rank'] === 2)
                                <div class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center text-dark font-black shadow-lg shadow-slate-300/20">2</div>
                            @elseif($ru['rank'] === 3)
                                <div class="w-8 h-8 rounded-full bg-amber-600 flex items-center justify-center text-white font-black shadow-lg shadow-amber-600/20">3</div>
                            @else
                                <div class="text-slate-500 font-bold ml-3 italic">{{ $ru['rank'] }}</div>
                            @endif
                        </td>
                        
                        <!-- Trend -->
                        <td class="px-6 py-5 text-center">
                            @if($ru['trend'] === 'up')
                                <span class="text-brand-emerald">▲</span>
                            @elseif($ru['trend'] === 'down')
                                <span class="text-red-500">▼</span>
                            @else
                                <span class="text-slate-600">—</span>
                            @endif
                        </td>

                        <!-- Name -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white text-sm {{ $isMe ? 'text-brand-neon' : '' }}">
                                        {{ $user->name }} {{ $user->lastname }}
                                        @if($isMe) <span class="ml-1 text-[8px] bg-brand-neon text-dark px-1.5 py-0.5 rounded font-black uppercase">Tú</span> @endif
                                    </div>
                                    <div class="text-[9px] text-slate-500 font-bold uppercase tracking-tighter">ID: {{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Points -->
                        <td class="px-6 py-5 text-center">
                            <span class="text-lg font-black text-white px-3 py-1 bg-white/5 rounded-xl border border-white/5">{{ $user->points }}</span>
                        </td>

                        <!-- Exact Hits -->
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-bold text-brand-emerald">{{ $user->hits_exact }}</span>
                        </td>

                        <!-- Probability -->
                        <td class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-[10px] font-black text-white">{{ $ru['probability'] }}%</span>
                                <div class="w-16 h-1 bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-emerald" style="width: {{ $ru['probability'] }}%"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-5 text-center">
                            @if($ru['is_alive'])
                                <span class="px-3 py-1 rounded-full bg-brand-emerald/10 text-brand-emerald text-[9px] font-black uppercase tracking-widest border border-brand-emerald/20">Vivo</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-widest border border-red-500/20">Fuera</span>
                            @endif
                        </td>

                        <!-- Prize Share -->
                        <td class="px-6 py-5 text-right">
                            @if($prize > 0)
                                <div class="text-brand-neon font-black text-lg">{{ $prize }}%</div>
                                <div class="text-[8px] text-slate-500 uppercase font-black tracking-widest">Pozo Compartido</div>
                            @else
                                <span class="text-slate-700 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass p-6 rounded-3xl border border-white/10">
            <h4 class="text-white font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-brand-neon rounded-full"></span>
                Lógica del Ranking
            </h4>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                El ranking se ordena primordialmente por **Puntos Totales**. En caso de empate, el segundo criterio es el número de **Marcadores Exactos (5 pts)**. Si el empate persiste, los participantes comparten la posición y los premios porcentuales se promedian entre los involucrados.
            </p>
        </div>
        <div class="glass p-6 rounded-3xl border border-white/10">
            <h4 class="text-white font-black text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-brand-yellow rounded-full"></span>
                Cálculo de Probabilidades
            </h4>
            <p class="text-slate-400 text-[11px] leading-relaxed">
                Las probabilidades y estatus matemáticos se calculan basados en la cantidad de partidos restantes y el puntaje máximo obtenible (5 pts por partido). Se considera que un jugador está "Fuera" cuando matemáticamente no puede alcanzar al líder actual.
            </p>
        </div>
    </div>
</div>
@endsection
