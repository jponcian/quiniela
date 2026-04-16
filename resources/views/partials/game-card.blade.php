<div class="game-card glass p-3 rounded-2xl border {{ $game->isLocked() ? 'border-white/5' : 'border-white/10' }} hover:shadow-2xl hover:shadow-brand-emerald/5 transition-all group relative overflow-hidden" 
     data-team-a="{{ strtolower($game->team_a) }}" 
     data-team-b="{{ strtolower($game->team_b) }}">
    @if($game->isLocked() && $game->status != 'finished')
        <div class="absolute top-2 right-2 z-10">
            <span class="bg-brand-yellow/10 text-brand-yellow text-[8px] font-black px-2 py-0.5 rounded-full border border-brand-yellow/20 flex items-center gap-1">
                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg>
                CERRADO
            </span>
        </div>
    @endif

    <div class="flex justify-between items-center text-[8px] font-bold text-slate-500 uppercase tracking-widest mb-3">
        <span>{{ $game->match_date->translatedFormat('d M — h:i A') }}</span>
        @if($game->status == 'in_play')
            <span class="bg-red-500/10 text-red-500 text-[8px] font-black px-2 py-0.5 rounded-full border border-red-500/20 flex items-center gap-1.5 animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.2)]">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                LIVE
            </span>
        @else
            <span class="bg-white/5 px-1.5 py-0.5 rounded-md text-[7px]">{{ $game->group ?? 'Jornada' }}</span>
        @endif
    </div>
    
    <div class="flex flex-col gap-6">
        <!-- Home Team -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ $game->flag_a }}" class="w-7 h-7 rounded-full border border-white/10 object-cover shadow-lg" alt="{{ $game->team_a }}">
                <span class="font-bold text-sm text-white group-hover:text-brand-emerald transition-colors truncate">{{ $game->team_a }}</span>
            </div>
            @if($game->status == 'finished' || $game->status == 'in_play')
                <span class="text-lg font-black text-white px-1">{{ $game->score_a }}</span>
            @endif
        </div>
        
        <!-- Predicction Inputs -->
        <div class="flex items-center justify-center gap-2 py-1.5 border-y border-white/5 bg-white/5 rounded-xl mx-[-8px] px-2">
             <div class="flex items-center justify-center gap-2">
                @php $pred = $userPredictions[$game->id] ?? null; @endphp
                <input type="number" 
                    id="score_a_{{ $game->id }}"
                    {{ $game->isLocked() ? 'readonly' : '' }} 
                    placeholder="0"
                    value="{{ $pred ? $pred->home_score : '' }}"
                    class="w-9 h-9 glass bg-slate-900/80 rounded-lg text-center text-base font-black border-none focus:ring-2 focus:ring-brand-emerald text-white transition {{ $game->isLocked() ? 'opacity-50 cursor-not-allowed' : 'cursor-not-allowed' }}">
                
                <span class="text-slate-700 font-black text-[10px] italic">VS</span>
                
                <input type="number" 
                    id="score_b_{{ $game->id }}"
                    {{ $game->isLocked() ? 'readonly' : '' }} 
                    placeholder="0"
                    value="{{ $pred ? $pred->away_score : '' }}"
                    class="w-9 h-9 glass bg-slate-900/80 rounded-lg text-center text-base font-black border-none focus:ring-2 focus:ring-brand-emerald text-white transition {{ $game->isLocked() ? 'opacity-50 cursor-not-allowed' : 'cursor-not-allowed' }}">
            </div>
        </div>

        <!-- Away Team -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ $game->flag_b }}" class="w-7 h-7 rounded-full border border-white/10 object-cover shadow-lg" alt="{{ $game->team_b }}">
                <span class="font-bold text-sm text-white group-hover:text-brand-emerald transition-colors truncate">{{ $game->team_b }}</span>
            </div>
            @if($game->status == 'finished' || $game->status == 'in_play')
                <span class="text-lg font-black text-white px-1">{{ $game->score_b }}</span>
            @endif
        </div>
    </div>

    @if(!$game->isLocked())
        @auth
            @if($pred ?? null)
                <div class="flex items-center justify-between mt-4 mb-1 px-1">
                    <span class="text-[9px] font-black text-brand-emerald/70 uppercase tracking-widest flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Pronóstico guardado
                    </span>
                </div>
            @endif
            <button onclick="savePrediction({{ $game->id }})" id="btn_{{ $game->id }}"
                class="w-full mt-2 py-3 rounded-xl bg-brand-emerald text-dark font-black text-[10px] uppercase tracking-widest transition-all hover:bg-brand-neon hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-brand-emerald/10">
                {{ ($pred ?? null) ? 'ACTUALIZAR' : 'GUARDAR' }}
            </button>
        @else
            <a href="{{ url('/registro') }}" 
                class="w-full mt-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-brand-emerald font-black text-[9px] uppercase text-center border border-white/5 block tracking-widest transition-all italic">
                REGÍSTRATE
            </a>
        @endauth
    @else
        <div class="w-full mt-4 py-3 rounded-xl bg-slate-900/50 text-slate-600 font-black text-[9px] uppercase text-center border border-white/5 tracking-widest italic">
            @if($game->status == 'finished')
                FINALIZADO
            @else
                CERRADO
            @endif
        </div>
    @endif
</div>
