<?php

namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessRankingNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reportData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsapp)
    {
        foreach ($this->reportData as $data) {
            $user = $data['user'];
            
            // Si el objeto user fue serializado y no tiene el atributo whatsapp, intentamos refrescarlo o usar el array
            // Pero reportData tiene el modelo User.
            
            if (!$user->whatsapp) continue;

            $message = "¡Hola *{$user->name}*! ⚽\n\n"
                     . "Se ha actualizado el ranking tras el último partido.\n\n"
                     . "📍 Tu nueva posición: *#{$data['rank']}* {$data['trend']}\n\n"
                     . "Ver tabla completa:\n" . url('/ranking') . "\n\n"
                     . "📜 Revisa las reglas aquí:\n" . url('/reglas') . "\n\n"
                     . "¡Sigue pronosticando y mucha suerte! 🍀";

            try {
                $whatsapp->sendMessage($user->whatsapp, $message);
                // Pequeño delay para no saturar la API
                usleep(500000); // 0.5 segundos
            } catch (\Exception $e) {
                Log::error("Error enviando WhatsApp a {$user->whatsapp} desde Job: " . $e->getMessage());
            }
        }
    }
}
