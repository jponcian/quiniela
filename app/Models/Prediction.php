<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'user_id',
        'game_id',
        'home_score',
        'away_score',
        'points_earned',
        'is_calculated'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Calcula los puntos ganados para esta predicción basado en el resultado oficial del juego.
     * Exacto: 5 pts
     * Ganador/Empate (Parcial): 3 pts
     */
    public function calculatePoints(): int
    {
        $game = $this->game;
        
        if (!$game || $game->status !== 'finished') {
            return 0;
        }

        $homeActual = $game->score_a;
        $awayActual = $game->score_b;
        $homePredValue = $this->home_score;
        $awayPredValue = $this->away_score;

        // Acierto EXACTO (5 pts)
        if ($homeActual == $homePredValue && $awayActual == $awayPredValue) {
            return 5;
        }

        // Determinar resultado (1, X, 2)
        $actualRes = $homeActual <=> $awayActual; // 1: Home, 0: Draw, -1: Away
        $predRes   = $homePredValue <=> $awayPredValue;

        // Acierto PARCIAL (3 pts)
        if ($actualRes === $predRes) {
            return 3;
        }

        return 0;
    }
}
