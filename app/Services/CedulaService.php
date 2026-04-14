<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CedulaService
{
    protected $appId;
    protected $token;
    protected $baseUrl = 'https://api.cedula.com.ve/api/v1';

    public function __construct()
    {
        $this->appId = config('services.cedula.id');
        $this->token = config('services.cedula.token');
    }

    public function search(string $cedula)
    {
        $nacionalidad = 'V';
        $numero = $cedula;

        // Extraer nacionalidad si viene en formato V-12345678 o V12345678
        if (preg_match('/^([VEJGP])[-]?(\d+)/i', $cedula, $matches)) {
            $nacionalidad = strtoupper($matches[1]);
            $numero = $matches[2];
        } else {
            // Solo números, limpiar cualquier otro caracter
            $numero = preg_replace('/[^0-9]/', '', $cedula);
        }

        try {
            $response = Http::timeout(10)->withoutVerifying()->get($this->baseUrl, [
                'app_id' => $this->appId,
                'token' => $this->token,
                'nacionalidad' => $nacionalidad,
                'cedula' => $numero,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                // Retornar la data interna si existe, o el payload según la versión de la API
                return $result['data'] ?? $result['payload'] ?? $result;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error API Cédula: ' . $e->getMessage());
            return null;
        }
    }
}
