<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\RankHistory;
use App\Models\User;

class ResetQuiniela extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiniela:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia la base de datos para iniciar una nueva quiniela';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->confirm('¿Estás seguro de que deseas resetear TODA la quiniela? Esto borrará juegos, predicciones y puntos de usuarios.')) {
            return;
        }

        $this->info('Limpiando predicciones...');
        Prediction::truncate();

        $this->info('Limpiando historial de ranking...');
        RankHistory::truncate();

        $this->info('Limpiando juegos...');
        // Usamos delete en lugar de truncate si hay claves foráneas o queremos disparar eventos, 
        // pero truncate es más rápido para limpieza total.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Game::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Reseteando puntos de usuarios...');
        User::query()->update([
            'points' => 0,
            'hits_exact' => 0,
            'hits_partial' => 0,
            'previous_rank' => null
        ]);

        $this->info('¡Quiniela reseteada con éxito!');
    }
}
