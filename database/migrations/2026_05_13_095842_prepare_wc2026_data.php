<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\RankHistory;
use App\Models\User;
use App\Services\FootballDataService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Limpiar datos existentes
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Prediction::truncate();
        RankHistory::truncate();
        Game::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Resetear puntos de usuarios
        User::query()->update([
            'points' => 0,
            'hits_exact' => 0,
            'hits_partial' => 0,
            'previous_rank' => null
        ]);

        // 3. Sincronizar partidos del Mundial 2026 (Solo Fase de Grupos ya configurado en el servicio)
        try {
            $service = app(FootballDataService::class);
            $service->syncMatches('WC');
        } catch (\Exception $e) {
            // Log the error but don't fail the migration if the API is down
            \Log::error("Error sincronizando partidos en la migración: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay vuelta atrás fácil para limpieza de datos, pero podríamos borrar los juegos.
        Game::truncate();
    }
};
