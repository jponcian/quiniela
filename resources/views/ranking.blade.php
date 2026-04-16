@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald/10 text-brand-emerald text-[10px] font-black uppercase tracking-widest mb-3 border border-brand-emerald/20">
                🏆 Temporada 2025/26
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Ranking @if($group) de <span class="text-brand-yellow">{{ $group->name }}</span> @else de <span class="text-brand-neon">Participantes</span> @endif
            </h1>
            <p class="text-slate-400 text-sm font-medium">
                @if($group) Filipado por liga privada ({{ count($rankedUsers) }} miembros). @else Liderazgo y estadísticas en tiempo real (Global). @endif
            </p>
        </div>
        
        </div>
    </div>

    <!-- Trend Chart Container -->
    <div class="glass rounded-[2rem] border border-white/10 p-6 mb-10 overflow-hidden shadow-2xl relative">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-white font-black text-xs uppercase tracking-widest flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-brand-neon rounded-full animate-pulse"></span>
                Evolución del Ranking
            </h2>
        </div>
        <div class="h-[250px] w-full">
            <canvas id="trendChart"></canvas>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    
    // Preparar datos para el gráfico
    const historyData = @json($history);
    const datasets = [];
    const colors = ['#00ff9d', '#10b981', '#facc15', '#38bdf8', '#818cf8'];
    
    let labels = [];
    let i = 0;
    
    for (const userId in historyData) {
        const userHistory = historyData[userId];
        const userName = userHistory[0].user.name;
        
        // Asignar labels (fechas) solo una vez
        if (labels.length === 0) {
            labels = userHistory.map(h => h.formatted_date);
        }
        
        datasets.push({
            label: userName,
            data: userHistory.map(h => h.rank),
            borderColor: colors[i % colors.length],
            backgroundColor: colors[i % colors.length] + '20',
            borderWidth: 3,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6,
            yAxisID: 'y',
        });
        i++;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    reverse: true, // El rango 1 es mejor que 10
                    min: 1,
                    ticks: {
                        stepSize: 1,
                        color: '#64748b',
                        font: { weight: 'bold' }
                    },
                    grid: { color: 'rgba(255,255,255,0.05)' }
                },
                x: {
                    ticks: { color: '#64748b', font: { size: 9 } },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8', font: { weight: 'bold', size: 10 }, usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { weight: 'bold' },
                    padding: 12,
                    cornerRadius: 12,
                    borderColor: 'rgba(255,255,255,0.1)',
                    borderWidth: 1
                }
            }
        }
    });
});
</script>
@endpush
