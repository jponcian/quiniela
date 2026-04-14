<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Services\CedulaService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $cedulaService;
    protected $whatsappService;

    public function __construct(CedulaService $cedulaService, WhatsAppService $whatsappService)
    {
        $this->cedulaService = $cedulaService;
        $this->whatsappService = $whatsappService;
    }

    public function show()
    {
        return view('auth.register');
    }

    public function checkCedula(Request $request)
    {
        $data = $this->cedulaService->search($request->cedula);
        
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No encontrado'
        ], 404);
    }

    public function register(Request $request)
    {
        $request->validate([
            'cedula' => 'required|unique:users',
            'name' => 'required',
            'whatsapp' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'cedula' => $request->cedula,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);


        // Envío de WhatsApp de Bienvenida
        $this->whatsappService->sendMessage(
            $user->whatsapp, 
            "¡Hola {$user->name}! Bienvenido a la Quiniela Mundialista 2026. ¡Mucha suerte con tus pronósticos!"
        );

        return redirect('/')->with('success', 'Bienvenido a la Quiniela');
    }
}
