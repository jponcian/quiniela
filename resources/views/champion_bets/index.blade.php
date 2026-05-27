@extends('layouts.app')

@section('content')
<div class="mb-10">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-yellow/10 text-brand-yellow text-[10px] font-black uppercase tracking-widest mb-3 border border-brand-yellow/20">
                🏆 Evento Especial
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Apuesta al <span class="text-brand-emerald">Campeón</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Predice qué selección ganará la Copa del Mundo 2026 y gana el bote acumulado.</p>
        </div>
    </div>

    <!-- Resultados del Campeonato (Si el administrador ya definió el campeón) -->
    @if($championTeam)
        <div class="glass p-8 rounded-[2.5rem] border border-brand-yellow/30 bg-brand-yellow/5 mb-8 text-center relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-yellow/10 rounded-full blur-2xl"></div>
            <span class="text-4xl mb-4 inline-block">👑</span>
            <h2 class="text-2xl font-black text-white uppercase tracking-tight mb-2">
                ¡Campeón Oficial: <span class="text-brand-yellow">{{ $championTeam }}</span>!
            </h2>
            <p class="text-slate-300 max-w-2xl mx-auto mb-6 text-sm">
                El pozo acumulado de <span class="text-brand-neon font-black">${{ number_format($totalPot, 2) }}</span> se ha repartido entre los ganadores que predijeron correctamente a la selección campeona.
            </p>
            
            @if($winners && $winners->count() > 0)
                <div class="max-w-md mx-auto">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Ganadores (${{ number_format($winAmount, 2) }} c/u):</h3>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($winners as $winner)
                            <span class="px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-white">
                                👤 {{ $winner->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-slate-500 text-xs italic">Nadie apostó por el campeón. ¡El bote queda acumulado!</p>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel Izquierdo: Resumen y Mis Apuestas -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Pote Acumulado Card -->
            <div class="glass p-8 rounded-[2.5rem] border border-white/10 relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-brand-emerald/10">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-brand-emerald/10 rounded-full blur-2xl"></div>
                <h3 class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-2">Bote Total Acumulado</h3>
                <div class="text-5xl font-black text-neon text-brand-neon italic leading-none mb-4">
                    ${{ number_format($totalPot, 2) }}
                </div>
                <p class="text-slate-400 text-xs font-medium leading-relaxed">
                    Todo el dinero recaudado de las apuestas al campeón va directamente a este pozo único. ¡Quienes acierten al equipo ganador se repartirán el 100% de este bote!
                </p>
            </div>

            <!-- Mis Apuestas -->
            <div class="glass p-8 rounded-[2.5rem] border border-white/10">
                <h3 class="text-white font-black text-xs uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-brand-yellow rounded-full"></span>
                    Mis Selecciones
                </h3>

                <div class="space-y-3">
                    @forelse($myBets as $bet)
                        <div class="flex items-center justify-between bg-white/5 p-4 rounded-2xl border border-white/5 hover:border-white/10 transition">
                            <div class="flex items-center gap-3">
                                @if(isset($flags[$bet->team_name]))
                                    <img src="{{ $flags[$bet->team_name] }}" alt="{{ $bet->team_name }}" class="w-6 h-4 object-cover rounded shadow">
                                @endif
                                <span class="text-sm font-bold text-white">{{ $bet->team_name }}</span>
                            </div>
                            
                            @if($bet->is_confirmed)
                                <span class="px-2.5 py-1 rounded-full bg-brand-emerald/10 text-brand-emerald text-[8px] font-black uppercase tracking-wider border border-brand-emerald/25">
                                    Confirmado
                                </span>
                            @elseif(!$isLocked)
                                <form action="{{ route('champion.bets.destroy', $bet) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500/50 hover:text-red-500 transition text-xs font-black uppercase tracking-widest">
                                        Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                    Bloqueado
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <span class="text-3xl mb-2 inline-block">🎟️</span>
                            <p class="text-slate-500 text-xs italic">Aún no tienes apuestas registradas.</p>
                        </div>
                    @endforelse
                </div>

                @if($myBets->count() > 0)
                    <div class="mt-6 pt-4 border-t border-white/5 flex justify-between text-xs">
                        <span class="text-slate-400 font-medium">Inversión Especial:</span>
                        <span class="text-white font-black">${{ number_format($myBets->count() * 5, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Derecho: Registrar Apuesta -->
        <div class="lg:col-span-2">
            <div class="glass p-8 rounded-[2.5rem] border border-white/10">
                <h3 class="text-white font-black text-xs uppercase tracking-widest mb-6">
                    Hacer Nueva Apuesta
                </h3>

                @if($isLocked)
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-6 rounded-2xl text-sm mb-4">
                        🔒 **Apuestas Cerradas:** El mundial ha comenzado. Ya no se permiten registrar ni modificar apuestas para el campeón.
                    </div>
                @else
                    <p class="text-slate-400 text-xs mb-6">
                        Puedes escoger tantas selecciones como desees. Cada selección añadirá **$5.00** a tu costo de inscripción, los cuales se reflejarán en tu balance general. Puedes eliminar tus selecciones en cualquier momento antes del inicio del torneo.
                    </p>

                    <form action="{{ route('champion.bets.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Seleccionar País</label>
                            <select id="team_name_select" name="team_name" required class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-4 text-sm text-white focus:outline-none focus:border-brand-emerald transition appearance-none">
                                <option value="">Elegir selección...</option>
                                @foreach($countries as $country)
                                    @php
                                        $alreadySelected = $myBets->contains('team_name', $country);
                                    @endphp
                                    <option value="{{ $country }}" {{ $alreadySelected ? 'disabled' : '' }}>
                                        {{ $country }} {{ $alreadySelected ? '(Ya seleccionado)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Preview de Selección con Bandera -->
                        <div id="team-preview" class="hidden bg-white/5 p-6 rounded-2xl border border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img id="preview-flag" src="" alt="Bandera" class="w-12 h-8 object-cover rounded-lg shadow-md border border-white/10">
                                <div>
                                    <h4 id="preview-name" class="text-lg font-black text-white leading-none"></h4>
                                    <span class="text-[10px] text-brand-yellow font-black uppercase tracking-widest mt-1 inline-block">Costo: $5.00</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-brand-emerald hover:bg-brand-neon text-brand-dark font-black py-4 rounded-2xl transition-all shadow-lg shadow-brand-emerald/20 flex items-center justify-center gap-2 uppercase text-xs tracking-widest">
                            Confirmar Apuesta ($5.00)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const flags = @json($flags);
    const select = document.getElementById('team_name_select');
    const preview = document.getElementById('team-preview');
    const previewFlag = document.getElementById('preview-flag');
    const previewName = document.getElementById('preview-name');

    if (select) {
        select.addEventListener('change', function() {
            const team = this.value;
            if (team && flags[team]) {
                previewFlag.src = flags[team];
                previewName.textContent = team;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        });
    }
</script>
@endpush
