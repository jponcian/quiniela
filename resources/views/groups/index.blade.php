@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-emerald/10 text-brand-emerald text-[10px] font-black uppercase tracking-widest mb-3 border border-brand-emerald/20">
                👥 Competencia Social
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Mis <span class="text-brand-neon">Ligas Privadas</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Crea o únete a grupos para competir con amigos.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Join Group -->
        <div class="glass p-8 rounded-[2rem] border border-white/10">
            <h3 class="text-white font-black text-xl mb-4">Unirse a una Liga</h3>
            <p class="text-slate-400 text-sm mb-6">Ingresa el código de invitación que te compartieron.</p>
            
            <form action="{{ route('groups.join') }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="code" placeholder="CÓDIGO" class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-3 font-black text-white placeholder:text-slate-600 focus:outline-none focus:border-brand-neon transition uppercase">
                <button type="submit" class="bg-brand-emerald hover:bg-brand-neon text-brand-dark px-6 py-3 rounded-2xl font-black transition-all">UNIRSE</button>
            </form>
        </div>

        <!-- Create Group -->
        <div class="glass p-8 rounded-[2rem] border border-white/10">
            <h3 class="text-white font-black text-xl mb-4">Crear Nueva Liga</h3>
            <p class="text-slate-400 text-sm mb-6">Crea tu propio grupo y comparte el código con tus amigos.</p>
            
            <form action="{{ route('groups.store') }}" method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="name" placeholder="NOMBRE DE LA LIGA" class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-6 py-3 font-black text-white placeholder:text-slate-600 focus:outline-none focus:border-brand-neon transition">
                <button type="submit" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-2xl font-black transition-all border border-white/5">CREAR</button>
            </form>
        </div>
    </div>

    <!-- My Groups Grid -->
    <h3 class="text-white font-black text-xs uppercase tracking-widest mb-6 px-2">Mis Ligas Activas</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($myGroups as $group)
        <div class="glass p-6 rounded-[2rem] border border-white/10 flex flex-col justify-between hover:border-brand-neon/30 transition-all group">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-brand-emerald bg-brand-emerald/10 px-3 py-1 rounded-full border border-brand-emerald/20 uppercase tracking-widest">
                        Código: {{ $group->code }}
                    </span>
                    @if($group->user_id === Auth::id())
                        <span class="text-[8px] font-black text-brand-yellow uppercase tracking-widest">PROPIETARIO</span>
                    @endif
                </div>
                <h4 class="text-2xl font-black text-white mb-1 group-hover:text-brand-neon transition">{{ $group->name }}</h4>
                <p class="text-slate-500 text-xs font-bold uppercase mb-6">{{ $group->users()->count() }} Miembros</p>
            </div>

            <div class="flex items-center gap-2 pt-4 border-t border-white/5">
                <a href="{{ route('ranking', ['group_id' => $group->id]) }}" class="flex-1 bg-white/5 hover:bg-white/10 text-white text-center py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Ver Ranking</a>
                
                @if($group->user_id === Auth::id())
                    <form action="{{ route('groups.delete', $group) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este grupo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                @else
                    <form action="{{ route('groups.leave', $group) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white/5 text-slate-500 hover:text-white rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="text-4xl mb-4">🏜️</div>
            <p class="text-slate-500 font-bold uppercase tracking-widest">Aún no perteneces a ninguna liga privada.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
