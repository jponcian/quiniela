<div class="glass p-6 rounded-3xl border {{ $game->isLocked() ? 'border-white/5' : 'border-white/10' }} hover:shadow-2xl hover:shadow-brand-emerald/5 transition-all group relative overflow-hidden">
    @if($game->isLocked() && $game->status != 'finished')
        <div class="absolute top-2 right-2 z-10">
            <span class="bg-brand-yellow/10 text-brand-yellow text-[8px] font-black px-2 py-0.5 rounded-full border border-brand-yellow/20 flex items-center gap-1">
                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"></path></svg>
                CERRADO
            </span>
        </div>
    @endif

    <div class="flex justify-between items-center text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">
        <span>{{ $game->match_date->translatedFormat('d M — h:i A') }}</span>
        @if($game->status == 'in_play')
            <span class="text-red-500 flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                EN VIVO
            </span>
        @else
            <span class="bg-white/5 px-2 py-1 rounded-md">{{ $game->group ?? 'Jornada' }}</span>
        @endif
    </div>
    
    <div class="flex flex-col gap-6">
        <!-- Home Team -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ $game->flag_a }}" class="w-10 h-10 rounded-full border border-white/10 object-cover shadow-lg" alt="{{ $game->team_a }}">
                <span class="font-bold text-base sm:text-lg text-white group-hover:text-brand-emerald transition-colors truncate">{{ $game->team_a }}</span>
            </div>
            @if($game->status == 'finished' || $game->status == 'in_play')
                <span class="text-2xl font-black text-white px-2">{{ $game->score_a }}</span>
            @endif
        </div>
        
        <!-- Predicction Inputs -->
        <div class="flex items-center justify-center gap-4 py-2 border-y border-white/5 bg-white/5 rounded-2xl mx-[-12px] px-3">
             <div class="flex items-center justify-center gap-3">
                @php $pred = $userPredictions[$game->id] ?? null; @endphp
                <input type="number" 
                    id="score_a_{{ $game->id }}"
                    {{ $game->isLocked() ? 'readonly' : '' }} 
                    placeholder="0"
                    value="{{ $pred ? $pred->home_score : '' }}"
                    class="w-12 h-12 glass bg-slate-900/80 rounded-xl text-center text-xl font-black border-none focus:ring-2 focus:ring-brand-emerald text-white transition {{ $game->isLocked() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                
                <span class="text-slate-700 font-black text-sm italic">VS</span>
                
                <input type="number" 
                    id="score_b_{{ $game->id }}"
                    {{ $game->isLocked() ? 'readonly' : '' }} 
                    placeholder="0"
                    value="{{ $pred ? $pred->away_score : '' }}"
                    class="w-12 h-12 glass bg-slate-900/80 rounded-xl text-center text-xl font-black border-none focus:ring-2 focus:ring-brand-emerald text-white transition {{ $game->isLocked() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
            </div>
        </div>

        <!-- Away Team -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ $game->flag_b }}" class="w-10 h-10 rounded-full border border-white/10 object-cover shadow-lg" alt="{{ $game->team_b }}">
                <span class="font-bold text-base sm:text-lg text-white group-hover:text-brand-emerald transition-colors truncate">{{ $game->team_b }}</span>
            </div>
            @if($game->status == 'finished' || $game->status == 'in_play')
                <span class="text-2xl font-black text-white px-2">{{ $game->score_b }}</span>
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
                class="w-full mt-2 py-4 rounded-2xl bg-brand-emerald text-dark font-black text-xs uppercase tracking-widest transition-all hover:bg-brand-neon hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-brand-emerald/10">
                {{ ($pred ?? null) ? 'ACTUALIZAR PRONÓSTICO' : 'GUARDAR PRONÓSTICO' }}
            </button>
        @else
            <a href="{{ url('/registro') }}" 
                class="w-full mt-6 py-4 rounded-2xl bg-white/5 hover:bg-white/10 text-brand-emerald font-black text-[10px] uppercase text-center border border-white/5 block tracking-widest transition-all italic">
                REGÍSTRATE PARA JUGAR
            </a>
        @endauth
    @else
        <div class="w-full mt-6 py-4 rounded-2xl bg-slate-900/50 text-slate-600 font-black text-[10px] uppercase text-center border border-white/5 tracking-widest italic">
            @if($game->status == 'finished')
                PARTIDO FINALIZADO
            @else
                APUESTAS CERRADAS
            @endif
        </div>
    @endif
</div>
