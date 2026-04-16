<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use App\Models\User;
use App\Services\WhatsAppService;

class SendMatchReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de WhatsApp para partidos que empiezan pronto y no tienen pronóstico.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsapp)
    {
        $now = now();
        $inTwoHours = now()->addHours(2);

        // Partidos que empiezan en las próximas 2 horas y no han sido bloqueados
        $games = Game::where('match_date', '>', $now)
            ->where('match_date', '<=', $inTwoHours)
            ->where('status', 'timed')
            ->get();

        if ($games->isEmpty()) {
            $this->info("No hay partidos próximos que requieran recordatorios.");
            return;
        }

        $users = User::whereNotNull('whatsapp')->get();

        foreach ($games as $game) {
            $this->info("Procesando recordatorios para: {$game->team_a} vs {$game->team_b}");
            
            foreach ($users as $user) {
                // Verificar si el usuario ya hizo su predicción
                $hasPrediction = \App\Models\Prediction::where('game_id', $game->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$hasPrediction) {
                    $message = "⚠️ *RECORDATORIO QUINIELA* ⚠️\n\n"
                             . "¡Hola *{$user->name}*! ⚽\n\n"
                             . "El partido *{$game->team_a} vs {$game->team_b}* comienza pronto ({$game->match_date->format('h:i A')}).\n\n"
                             . "Aún no has guardado tu pronóstico. ¡No te quedes sin puntos! 🏆\n\n"
                             . "Entra aquí y marca tu resultado:\n" . url('/') . "\n\n"
                             . "¡Mucha suerte! 🍀";

                    try {
                        $whatsapp->sendMessage($user->whatsapp, $message);
                        $this->line("Notificado: {$user->name} ({$user->whatsapp})");
                    } catch (\Exception $e) {
                        $this->error("Error notificando a {$user->name}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("¡Proceso de recordatorios completado!");
    }
}
