<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $url;
    protected $key;
    protected $instance;

    public function __construct()
    {
        $this->url = config('services.evolution.url');
        $this->key = config('services.evolution.key');
        $this->instance = config('services.evolution.instance');
    }

    public function sendMessage($number, $message)
    {
        // Format number if needed (Venezuela +58)
        if (!str_starts_with($number, '58')) {
            $number = '58' . ltrim($number, '0');
        }

        $response = Http::withHeaders([
            'apikey' => $this->key
        ])->post("{$this->url}/message/sendText/{$this->instance}", [
            'number' => $number,
            'text' => $message,
            'delay' => 1200,
            'linkPreview' => true
        ]);

        return $response->json();
    }
}
