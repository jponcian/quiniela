@extends('layouts.app')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <span class="inline-block px-4 py-1.5 rounded-full bg-brand-neon/10 text-brand-neon text-[10px] font-black uppercase tracking-widest mb-3 border border-brand-neon/20">
                💰 Gestión Financiera
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">
                Control de <span class="text-brand-emerald">Pagos</span>
            </h1>
            <p class="text-slate-400 text-sm font-medium">Registra abonos y monitorea el saldo de los participantes.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario de Carga -->
        <div class="lg:col-span-1">
            <div class="glass p-8 rounded-[2.5rem] border border-white/10 sticky top-24">
                <h3 class="text-white font-black text-xs uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-brand-emerald rounded-full"></span>
                    Cargar Nuevo Pago
                </h3>

                <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Participante</label>
                        <select name="user_id" id="user_select" required class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-emerald transition appearance-none">
                            <option value="">Seleccionar usuario...</option>
                            @foreach($pendingUsers as $user)
                                <option value="{{ $user->id }}" data-plays-quiniela="{{ $user->plays_quiniela ? '1' : '0' }}">{{ $user->name }} ({{ $user->cedula }}) - Debe ${{ number_format($user->balance, 2) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 bg-slate-900/50 border border-white/10 rounded-2xl p-4">
                        <input type="checkbox" name="plays_quiniela" id="plays_quiniela" value="1" checked class="w-5 h-5 rounded border-white/10 text-brand-emerald focus:ring-brand-emerald focus:ring-offset-slate-950 bg-slate-900 accent-brand-emerald cursor-pointer">
                        <div class="flex flex-col">
                            <label for="plays_quiniela" class="text-xs font-black text-white uppercase tracking-wider cursor-pointer select-none">
                                Juega Quiniela
                            </label>
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Inscripción Base ($10.00)</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Monto ($)</label>
                            <input type="number" name="amount" step="0.01" required placeholder="0.00" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-emerald transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Fecha</label>
                            <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-emerald transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">N° Referencia</label>
                        <input type="text" name="reference" required placeholder="Ej: 12345678" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-emerald transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Notas (Opcional)</label>
                        <textarea name="notes" rows="2" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-emerald transition"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand-emerald hover:bg-brand-neon text-brand-dark font-black py-4 rounded-2xl transition-all shadow-lg shadow-brand-emerald/20 flex items-center justify-center gap-2 uppercase text-xs tracking-widest">
                        Registrar Pago
                    </button>
                </form>
            </div>
        </div>

        <!-- Listado de Usuarios -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="text-white font-black text-xs uppercase tracking-widest">Estado de Participantes</h3>
                <span class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Inscripción: $10.00</span>
            </div>

            <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50">
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest">Participante</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Pagos Registrados</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Saldo</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors align-top">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white">{{ $user->name }}</span>
                                        <span class="text-[10px] text-slate-500 font-medium">CI: {{ $user->cedula }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2">
                                        @forelse($user->payments as $payment)
                                            <div class="flex items-center justify-between bg-white/5 px-3 py-2 rounded-xl border border-white/5">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-black text-white italic">${{ number_format($payment->amount, 2) }}</span>
                                                    <span class="text-[8px] text-slate-500 uppercase font-bold">Ref: {{ $payment->reference }}</span>
                                                </div>
                                                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this)" class="text-red-500/50 hover:text-red-500 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @empty
                                            <span class="text-[10px] text-slate-600 italic">Sin pagos</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-white italic">${{ number_format($user->total_paid, 2) }}</span>
                                        <span class="text-[9px] font-black {{ $user->balance > 0 ? 'text-brand-yellow' : 'text-slate-500' }}">
                                            Faltan: ${{ number_format($user->balance, 2) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->is_fully_paid)
                                        <span class="px-3 py-1 rounded-full bg-brand-emerald/10 text-brand-emerald text-[9px] font-black uppercase tracking-widest border border-brand-emerald/20">
                                            Completado
                                        </span>
                                    @elseif($user->total_paid > 0)
                                        <span class="px-3 py-1 rounded-full bg-brand-yellow/10 text-brand-yellow text-[9px] font-black uppercase tracking-widest border border-brand-yellow/20">
                                            Abono
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-widest border border-red-500/20">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            <!-- Detalle de Apuestas al Campeón -->
            <div class="flex items-center justify-between mb-4 mt-8 px-2">
                <h3 class="text-white font-black text-xs uppercase tracking-widest">Apuestas al Campeón Registradas</h3>
                <span class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Costo por apuesta: $5.00</span>
            </div>

            <div class="glass rounded-[2.5rem] border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/50">
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest">Participante</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest">Selección</th>
                                <th class="px-6 py-5 text-[9px] font-black text-slate-500 uppercase tracking-widest text-center">Estado Pago</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($bets as $bet)
                            <tr class="hover:bg-white/[0.02] transition-colors align-middle">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white">{{ $bet->user->name }}</span>
                                        <span class="text-[10px] text-slate-500 font-medium">CI: {{ $bet->user->cedula }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if(isset($flags[$bet->team_name]))
                                            <img src="{{ $flags[$bet->team_name] }}" alt="{{ $bet->team_name }}" class="w-6 h-4 object-cover rounded shadow border border-white/10">
                                        @endif
                                        <span class="text-sm font-bold text-white">{{ $bet->team_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($bet->is_confirmed)
                                        <span class="px-3 py-1 rounded-full bg-brand-emerald/10 text-brand-emerald text-[9px] font-black uppercase tracking-widest border border-brand-emerald/20">
                                            Confirmado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-brand-yellow/10 text-brand-yellow text-[9px] font-black uppercase tracking-widest border border-brand-yellow/20">
                                            Pendiente ($5.00)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-slate-500 text-sm italic">
                                    No hay apuestas de campeón registradas aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('user_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const playsQuinielaCheckbox = document.getElementById('plays_quiniela');
    
    if (selectedOption && selectedOption.value !== "") {
        const playsQuiniela = selectedOption.getAttribute('data-plays-quiniela') === '1';
        playsQuinielaCheckbox.checked = playsQuiniela;
    } else {
        // Default to checked if no user is selected
        playsQuinielaCheckbox.checked = true;
    }
});

function confirmDelete(button) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el registro del pago permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#020617',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    })
}
</script>
@endpush
@endsection
