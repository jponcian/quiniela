<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $allUsers = User::with('payments')->get();
        
        // Usuarios que aún deben dinero para el select
        $pendingUsers = $allUsers->filter(function($user) {
            return $user->balance > 0;
        })->sortBy('name');

        // Todos los usuarios para la tabla, ordenados por total pagado
        $users = $allUsers->sortByDesc('total_paid');

        return view('admin.payments.index', compact('users', 'pendingUsers'));
    }

    public function store(Request $request)
    {
        $existing = Payment::where('reference', $request->reference)->first();
        
        if ($existing) {
            $user = $existing->user;
            $fecha = $existing->payment_date ? $existing->payment_date->format('d/m/Y') : 'N/A';
            return back()->with('error', "La referencia #{$request->reference} ya fue registrada por {$user->name} (CI: {$user->cedula}) el {$fecha} por un monto de \${$existing->amount}.");
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'required|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        Payment::create($request->all());

        return back()->with('success', '¡Pago registrado exitosamente!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Pago eliminado correctamente.');
    }
}
