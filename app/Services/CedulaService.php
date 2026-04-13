<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CedulaService
{
    protected $id;
    protected $token;
    protected $url = 'https://api.cedula.com.ve/api/v1/search';

    public function __construct()
    {
        $this->id = config('services.cedula.id');
        $this->token = config('services.cedula.token');
    }

    public function search($cedula)
    {
        $response = Http::get($this->url, [
            'id' => $this->id,
            'token' => $this->token,
            'cedula' => $cedula
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
