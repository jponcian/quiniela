@extends('layouts.app')

@section('title', 'Registro - Quiniela Mundialista')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl">
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center tracking-tighter uppercase">
            Únete a la <span class="text-brand-emerald">Quiniela</span>
        </h2>
        
        <form action="{{ url('/registro') }}" method="POST" id="registerForm">
            @csrf
            
            <!-- Cédula con búsqueda -->
            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Cédula de Identidad</label>
                <div class="flex gap-2">
                    <input type="text" name="cedula" id="cedula" required
                        class="flex-1 glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                        placeholder="V12345678">
                    <button type="button" id="btnSearch" 
                        class="bg-white/5 hover:bg-white/10 border border-white/10 px-4 rounded-xl text-brand-emerald transition active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
                <p id="searchStatus" class="mt-2 text-[10px] font-bold uppercase hidden animate-pulse"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Nombres</label>
                    <input type="text" name="name" id="name" required
                        class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition">
                </div>
                <div>
                    <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Apellidos</label>
                    <input type="text" name="lastname" id="lastname" required
                        class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Número WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                    placeholder="04121234567">
            </div>

            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Ccorreo Electrónico</label>
                <input type="email" name="email" id="email" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                    placeholder="usuario@ejemplo.com">
            </div>

            <div class="mb-8">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Contraseña</label>
                <input type="password" name="password" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition">
            </div>

            <button type="submit" 
                class="w-full bg-brand-emerald hover:bg-brand-neon text-dark font-black py-4 rounded-2xl transition shadow-xl shadow-brand-emerald/20 active:scale-[0.98]">
                REGISTRAR MI CUENTA
            </button>
            <p class="text-center mt-6 text-sm text-slate-500">
                ¿Ya tienes cuenta? <a href="{{ url('/login') }}" class="text-brand-emerald font-bold hover:underline">Inicia Sesión</a>
            </p>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnSearch').addEventListener('click', async () => {
        const cedula = document.getElementById('cedula').value;
        const status = document.getElementById('searchStatus');
        const btn = document.getElementById('btnSearch');

        if(!cedula) return;

        status.textContent = 'Buscando datos...';
        status.className = 'mt-2 text-[10px] font-bold uppercase block text-brand-yellow animate-pulse';
        btn.disabled = true;

        try {
            const response = await fetch(`/api/check-cedula?cedula=${cedula}`);
            const data = await response.json();

            if(data && data.payload) {
                document.getElementById('name').value = data.payload.nombre;
                document.getElementById('lastname').value = data.payload.apellido;
                status.textContent = '¡Datos encontrados!';
                status.className = 'mt-2 text-[10px] font-bold uppercase block text-brand-emerald';
            } else {
                status.textContent = 'No se encontraron datos. Ingresa manualmente.';
                status.className = 'mt-2 text-[10px] font-bold uppercase block text-red-500';
            }
        } catch (error) {
            status.textContent = 'Error al conectar con el servidor.';
            status.className = 'mt-2 text-[10px] font-bold uppercase block text-red-500';
        } finally {
            btn.disabled = false;
        }
    });
</script>
@endsection
