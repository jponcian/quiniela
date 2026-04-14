@extends('layouts.app')

@section('title', 'Registro - Quiniela Mundialista')

@section('content')
<div class="max-w-md mx-auto py-12">
    <div class="glass p-8 rounded-3xl border border-white/10 shadow-2xl">
        <h2 class="text-3xl font-extrabold text-white mb-6 text-center tracking-tighter uppercase">
            Únete a la <span class="text-brand-emerald">Quiniela</span>
        </h2>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-500 text-xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ url('/registro') }}" method="POST" id="registerForm">
            @csrf
            
            <!-- Cédula con búsqueda -->
            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Cédula de Identidad</label>
                <div class="flex gap-2">
                    <input type="text" name="cedula" id="cedula" required
                        class="flex-1 glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                        placeholder="12345678" value="{{ old('cedula') }}">
                    <button type="button" id="btnSearch" 
                        class="bg-white/5 hover:bg-white/10 border border-white/10 px-4 rounded-xl text-brand-emerald transition active:scale-95">
                        <span id="cedulaIcon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </button>
                </div>
                <p id="searchStatus" class="mt-2 text-[10px] font-bold uppercase hidden animate-pulse"></p>
            </div>

            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Nombre Completo</label>
                <input type="text" name="name" id="name" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                    placeholder="Ingresa tu cédula para autocompletar" value="{{ old('name') }}">
            </div>

            <div class="mb-4">
                <label class="block text-slate-400 text-xs font-bold uppercase mb-2">Número WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" required
                    class="w-full glass bg-slate-900/50 border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-brand-emerald outline-none transition"
                    placeholder="04121234567" value="{{ old('whatsapp') }}">
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
    const cedulaInput = document.getElementById('cedula');
    const nameInput = document.getElementById('name');
    const status = document.getElementById('searchStatus');
    const icon = document.getElementById('cedulaIcon');
    const btnSearch = document.getElementById('btnSearch');
    let isSearching = false;

    async function lookupCedula() {
        if (isSearching) return;
        
        const cedula = cedulaInput.value.trim();
        if (cedula.length < 6) return;

        isSearching = true;

        // Efecto visual de carga
        status.textContent = 'CONSULTANDO IDENTIDAD...';
        status.className = 'mt-2 text-[10px] font-bold uppercase block text-brand-yellow animate-pulse';
        nameInput.value = 'ESPERANDO RESPUESTA...';
        nameInput.readOnly = true;
        nameInput.classList.add('opacity-50');
        
        const originalIcon = icon ? icon.innerHTML : '';
        if (icon) {
            icon.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
        }

        try {
            const response = await fetch(`{{ url('/api/check-cedula') }}?cedula=${encodeURIComponent(cedula)}`);
            const result = await response.json();

            if (result.success) {
                const p = result.data;
                const firstName = p.primer_nombre || p.nombre || p.nombres || '';
                const middleName = p.segundo_nombre || '';
                const lastName = p.primer_apellido || p.apellido || p.apellidos || '';
                const secondLastName = p.segundo_apellido || '';
                
                const fullName = `${firstName} ${middleName} ${lastName} ${secondLastName}`.replace(/\s+/g, ' ').trim();
                nameInput.value = fullName.toUpperCase();
                
                status.textContent = 'REGISTRO ENCONTRADO';
                status.className = 'mt-2 text-[10px] font-bold uppercase block text-brand-emerald';
                if (icon) icon.innerHTML = `<svg class="w-5 h-5 text-brand-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            } else {
                status.textContent = 'CÉDULA NO ENCONTRADA - INGRESA MANUALMENTE';
                status.className = 'mt-2 text-[10px] font-bold uppercase block text-red-500';
                nameInput.value = '';
                nameInput.readOnly = false;
                nameInput.classList.remove('opacity-50');
                nameInput.focus();
                if (icon) icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            }
        } catch (error) {
            console.error(error);
            status.textContent = 'ERROR DE SERVIDOR';
            status.className = 'mt-2 text-[10px] font-bold uppercase block text-red-500';
            nameInput.value = '';
            nameInput.readOnly = false;
            nameInput.classList.remove('opacity-50');
            if (icon) icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
        } finally {
            isSearching = false;
        }
    }

    cedulaInput.addEventListener('change', lookupCedula);

    cedulaInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            lookupCedula();
        }
    });

    btnSearch.addEventListener('click', lookupCedula);

    // Validar que solo se ingresen números
    cedulaInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>

@endsection
