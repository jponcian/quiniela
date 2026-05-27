@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mb-10">
    <div class="text-center mb-12">
        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald/10 text-brand-emerald text-[10px] font-black uppercase tracking-widest mb-4 border border-brand-emerald/20">
            📖 Reglamento Oficial
        </span>
        <h1 class="text-4xl md:text-6xl font-black text-white uppercase italic tracking-tighter leading-none mb-4">
            Reglas de la <span class="text-brand-neon">Quiniela</span>
        </h1>
        <p class="text-slate-400 text-sm md:text-lg font-medium">Todo lo que necesitas saber para ganar esta temporada.</p>
    </div>

    <div class="space-y-6">
        <!-- Puntos -->
        <section class="glass p-8 rounded-[2rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 text-8xl opacity-10 grayscale group-hover:grayscale-0 transition duration-500">🔥</div>
            <h2 class="text-2xl font-black text-white uppercase italic mb-6 flex items-center gap-3">
                <span class="text-brand-emerald">01.</span> Sistema de Puntuación
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                    <div class="text-brand-neon font-black text-3xl mb-1">5 PTS</div>
                    <div class="text-white font-bold uppercase text-sm mb-2">Marcador Exacto</div>
                    <p class="text-slate-500 text-xs leading-relaxed">Si aciertas el resultado final exacto del partido. Ejemplo: Pronóstico 2-1, Resultado Real 2-1.</p>
                </div>
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                    <div class="text-brand-yellow font-black text-3xl mb-1">3 PTS</div>
                    <div class="text-white font-bold uppercase text-sm mb-2">Acierto Parcial</div>
                    <p class="text-slate-500 text-xs leading-relaxed">Si aciertas quién gana o si hay empate, pero no el marcador exacto. Ejemplo: Pronóstico 1-0, Resultado Real 3-0.</p>
                </div>
            </div>
        </section>

        <!-- Ranking -->
        <section class="glass p-8 rounded-[2rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 text-8xl opacity-10 grayscale group-hover:grayscale-0 transition duration-500">📊</div>
            <h2 class="text-2xl font-black text-white uppercase italic mb-6 flex items-center gap-3">
                <span class="text-brand-emerald">02.</span> El Ranking y Desempates
            </h2>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                El ranking se calcula automáticamente tras cada partido. Si dos o más jugadores tienen la misma puntuación, se aplican los siguientes criterios de desempate en orden:
            </p>
            <div class="space-y-3">
                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 rounded-lg bg-brand-emerald text-dark font-black flex items-center justify-center text-xs">1°</div>
                    <div class="text-sm font-bold text-white uppercase">Mayor número de Plenos (Marcadores Exactos)</div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-white font-black flex items-center justify-center text-xs">2°</div>
                    <div class="text-sm font-bold text-slate-300 uppercase">Posición compartida y reparto de pozo</div>
                </div>
            </div>
        </section>

        <!-- Premios -->
        <section class="glass p-8 rounded-[2rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 text-8xl opacity-10 grayscale group-hover:grayscale-0 transition duration-500">💰</div>
            <h2 class="text-2xl font-black text-white uppercase italic mb-6 flex items-center gap-3">
                <span class="text-brand-emerald">03.</span> Premios y Posiciones
            </h2>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                El pozo total acumulado se distribuye de la siguiente manera, reteniendo un 5% para la organización y mantenimiento de la plataforma:
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="text-center p-6 bg-brand-neon/10 rounded-2xl border border-brand-neon/20">
                    <div class="text-brand-neon font-black text-2xl uppercase">60%</div>
                    <div class="text-white text-[10px] font-bold uppercase tracking-widest">1er Lugar</div>
                </div>
                <div class="text-center p-6 bg-brand-emerald/10 rounded-2xl border border-brand-emerald/20">
                    <div class="text-brand-emerald font-black text-2xl uppercase">25%</div>
                    <div class="text-white text-[10px] font-bold uppercase tracking-widest">2do Lugar</div>
                </div>
                <div class="text-center p-6 bg-brand-yellow/10 rounded-2xl border border-brand-yellow/20">
                    <div class="text-brand-yellow font-black text-2xl uppercase">10%</div>
                    <div class="text-white text-[10px] font-bold uppercase tracking-widest">3er Lugar</div>
                </div>
            </div>
            <div class="mt-6 p-6 bg-slate-900/50 rounded-2xl border border-white/5">
                <h4 class="text-brand-neon font-bold text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-brand-neon rounded-full"></span>
                    Ejemplo de Empate (Pozo Compartido)
                </h4>
                <div class="space-y-4 text-[11px] leading-relaxed">
                    <p class="text-slate-400">Si hay un empate en el 1er lugar entre dos personas:</p>
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-xl border border-white/5">
                        <div class="text-slate-300">Premio 1° (60%) + Premio 2° (25%)</div>
                        <div class="text-brand-emerald font-black">= 85% Total</div>
                    </div>
                    <div class="text-center text-slate-500 font-medium">Cada uno recibe: <span class="text-white font-bold">42.5%</span> del pozo.</div>
                    <p class="text-slate-500 italic">El siguiente jugador en la tabla pasaría a ocupar el 3er lugar (10%).</p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-red-500/5 rounded-2xl border border-red-500/10">
                <p class="text-red-400 text-[11px] font-medium leading-relaxed italic text-center">
                    Importante: Esta lógica garantiza que el premio se reparta con total transparencia sin importar la antigüedad del registro.
                </p>
            </div>
        </section>

        <!-- Apuesta al Campeón -->
        <section class="glass p-8 rounded-[2rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 text-8xl opacity-10 grayscale group-hover:grayscale-0 transition duration-500">🏆</div>
            <h2 class="text-2xl font-black text-white uppercase italic mb-6 flex items-center gap-3">
                <span class="text-brand-emerald">04.</span> Apuesta al Campeón del Mundial
            </h2>
            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                Una modalidad especial e independiente de la quiniela regular. Los participantes pueden apostar directamente por la selección que consideren se coronará campeona del mundo.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                    <div class="text-brand-yellow font-black text-3xl mb-1">$5.00</div>
                    <div class="text-white font-bold uppercase text-sm mb-2">Costo y Multi-Apuesta</div>
                    <p class="text-slate-500 text-xs leading-relaxed">Cada selección de equipo tiene un valor de $5.00. Tienes la libertad de apostar por más de una selección para aumentar tus probabilidades (ej. apostar a Brasil y a Francia te costaría $10.00 en total).</p>
                </div>
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                    <div class="text-brand-neon font-black text-3xl mb-1">100%</div>
                    <div class="text-white font-bold uppercase text-sm mb-2">Pozo Acumulado</div>
                    <p class="text-slate-500 text-xs leading-relaxed">El 100% del dinero recaudado en esta modalidad formará un pote único. Al finalizar el mundial, todo el pozo se dividirá de manera equitativa únicamente entre los ganadores que hayan acertado al campeón.</p>
                </div>
            </div>
        </section>

        <!-- Reglas de Cierre y Sellado -->
        <section class="glass p-8 rounded-[2rem] border border-white/10 relative overflow-hidden group">
            <div class="absolute -top-6 -right-6 text-8xl opacity-10 grayscale group-hover:grayscale-0 transition duration-500">🔒</div>
            <h2 class="text-2xl font-black text-white uppercase italic mb-6 flex items-center gap-3">
                <span class="text-brand-emerald">05.</span> Reglas de Cierre y Sellado
            </h2>
            <div class="space-y-4 text-slate-400 text-sm leading-relaxed">
                <div class="flex gap-4 p-4 bg-white/5 rounded-xl border border-white/5">
                    <div class="text-brand-neon font-bold">1. Límite de Tiempo</div>
                    <p class="text-xs">Tanto la quiniela regular como las apuestas de campeón cerrarán de forma automática e irreversible **1 hora antes del primer partido del mundial**. Ningún usuario podrá realizar pronósticos ni registrar o modificar apuestas después de este límite.</p>
                </div>
                <div class="flex gap-4 p-4 bg-white/5 rounded-xl border border-white/5">
                    <div class="text-brand-yellow font-bold">2. Sellado Administrativo</div>
                    <p class="text-xs">El administrador del sistema tiene la facultad de **Sellar (bloquear)** manualmente y de forma inmediata la quiniela o las apuestas de campeón en cualquier momento. Una vez sellada una modalidad, queda totalmente congelada para modificaciones.</p>
                </div>
                <div class="flex gap-4 p-4 bg-white/5 rounded-xl border border-white/5">
                    <div class="text-brand-emerald font-bold">3. Confirmación de Pago</div>
                    <p class="text-xs">Para las apuestas de campeón, una vez que el administrador confirma y valida el pago en el sistema, dicha apuesta de campeón se considera **sellada individualmente** para ese participante, impidiendo que pueda ser cambiada o eliminada.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Boton Volver -->
    <div class="mt-12 text-center">
        <a href="/" class="inline-flex items-center gap-2 text-brand-emerald font-black uppercase text-xs tracking-widest hover:text-brand-neon transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a los partidos
        </a>
    </div>
</div>
@endsection
