@extends('layouts.app')

@section('title', 'Mis Pronósticos — Quiniela 2026')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-white uppercase tracking-tighter italic">
                Mis <span class="text-brand-neon">Pronósticos</span>
            </h1>
            <p class="text-slate-500 text-sm font-medium">Listado de todos tus marcadores guardados para el mundial.</p>
        </div>
        
        <a href="{{ route('predictions.pdf') }}" 
           class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-300 transition-all active:scale-95 group">
            <svg class="w-4 h-4 text-red-500 group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
            </svg>
            Exportar PDF
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="glass p-4 rounded-3xl border border-white/5 text-center">
            <span class="block text-2xl font-black text-white">{{ $predictions->count() }}</span>
            <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Registrados</span>
        </div>
        <div class="glass p-4 rounded-3xl border border-white/5 text-center">
            <span class="block text-2xl font-black text-brand-emerald">{{ $predictions->sum('points_earned') }}</span>
            <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Puntos</span>
        </div>
        <div class="glass p-4 rounded-3xl border border-white/5 text-center">
            <span class="block text-2xl font-black text-brand-yellow">{{ $predictions->where('points_earned', 5)->count() }}</span>
            <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Exactos</span>
        </div>
    </div>

    <!-- Predictions List -->
    <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Partido</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Mi Marcador</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Real</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Puntos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($predictions as $prediction)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-6">
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-black text-white uppercase tracking-tight">
                                        {{ $prediction->game->team_a }} vs {{ $prediction->game->team_b }}
                                    </span>
                                    <span class="text-[9px] font-bold text-slate-600 uppercase">
                                        {{ $prediction->game->group }} — {{ $prediction->game->match_date->translatedFormat('d M, h:i A') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if(!$prediction->game->isLocked())
                                    <div class="inline-flex items-center gap-2 bg-slate-900/50 px-2 py-1 rounded-xl border border-white/5">
                                        <input type="number" id="score_a_{{ $prediction->game->id }}" value="{{ $prediction->home_score }}" 
                                            class="w-8 h-8 bg-transparent text-center text-sm font-black text-brand-neon border-none focus:ring-0 p-0">
                                        <span class="text-[10px] text-slate-700 font-bold">—</span>
                                        <input type="number" id="score_b_{{ $prediction->game->id }}" value="{{ $prediction->away_score }}" 
                                            class="w-8 h-8 bg-transparent text-center text-sm font-black text-brand-neon border-none focus:ring-0 p-0">
                                    </div>
                                    <button onclick="updatePrediction({{ $prediction->game->id }})" id="btn_{{ $prediction->game->id }}"
                                        class="ml-2 p-2 bg-brand-emerald/10 hover:bg-brand-emerald/20 text-brand-emerald rounded-lg transition active:scale-90">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <div class="inline-flex items-center gap-2 bg-white/5 px-3 py-1.5 rounded-xl border border-white/5 opacity-60">
                                        <span class="text-sm font-black text-slate-400">{{ $prediction->home_score }}</span>
                                        <span class="text-[10px] text-slate-700 font-bold">—</span>
                                        <span class="text-sm font-black text-slate-400">{{ $prediction->away_score }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-center">
                                @if($prediction->game->status == 'finished')
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl">
                                        <span class="text-sm font-black text-white">{{ $prediction->game->score_a }}</span>
                                        <span class="text-[10px] text-slate-700 font-bold">:</span>
                                        <span class="text-sm font-black text-white">{{ $prediction->game->score_b }}</span>
                                    </div>
                                @else
                                    <span class="text-[9px] font-bold text-slate-600 uppercase italic">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-6 py-6 text-right">
                                @if($prediction->game->status == 'finished')
                                    <span class="text-sm font-black {{ $prediction->points_earned > 0 ? 'text-brand-emerald' : 'text-slate-600' }}">
                                        +{{ $prediction->points_earned }}
                                    </span>
                                @else
                                    <span class="text-slate-700 text-lg opacity-20">•••</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <span class="text-4xl opacity-20 grayscale">⚽</span>
                                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Aún no has guardado pronósticos.</p>
                                    <a href="/" class="text-brand-emerald font-black text-[10px] uppercase underline">Ver partidos ahora</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script>
async function updatePrediction(gameId) {
    const scoreA = document.getElementById('score_a_' + gameId).value;
    const scoreB = document.getElementById('score_b_' + gameId).value;
    const btn = document.getElementById('btn_' + gameId);

    if(scoreA === '' || scoreB === '') {
        showToast('Por favor, ingresa ambos marcadores.', 'error');
        return;
    }

    btn.disabled = true;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

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
            showToast('¡Pronóstico actualizado!', 'success');
            btn.classList.add('bg-brand-neon', 'text-dark');
            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.classList.remove('bg-brand-neon', 'text-dark');
                btn.disabled = false;
            }, 1000);
        } else {
            showToast(data.message || 'Error al actualizar.', 'error');
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    } catch (error) {
        showToast('Error de conexión.', 'error');
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}

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
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endpush
@endsection
