@extends('layouts.app')

@section('title', 'Iniciar Sesión - Quiniela Mundialista')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl">
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center tracking-tighter uppercase">
            Bienvenido de <span class="text-brand-emerald">Nuevo</span>
        </h2>
        
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Correo Electrónico</label>
                <input type="email" name="email" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                    placeholder="usuario@ejemplo.com">
                @error('email')
                    <p class="mt-2 text-xs text-red-500 font-bold uppercase">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition">
            </div>

            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-slate-800 text-brand-emerald focus:ring-brand-emerald">
                    <span class="text-sm text-slate-400 group-hover:text-slate-200 transition">Recordarme</span>
                </label>
                <a href="#" class="text-xs text-brand-emerald font-bold hover:underline">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" 
                class="w-full bg-brand-emerald hover:bg-brand-neon text-dark font-black py-4 rounded-2xl transition shadow-xl shadow-brand-emerald/20 active:scale-[0.98]">
                INICIAR SESIÓN
            </button>
            <p class="text-center mt-6 text-sm text-slate-500">
                ¿No tienes cuenta? <a href="{{ url('/registro') }}" class="text-brand-emerald font-bold hover:underline">Regístrate gratis</a>
            </p>
        </form>
    </div>
</div>
@endsection
