<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChampionBet extends Model
{
    protected $fillable = [
        'user_id',
        'team_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsConfirmedAttribute()
    {
        $user = $this->user;
        if (!$user) {
            return false;
        }

        // Obtener las apuestas del usuario ordenadas por ID para procesar secuencialmente
        $bets = $user->championBets->sortBy('id')->values();

        // Inscripción base dinámica
        $baseInscription = $user->predictions()->count() > 0 ? 10 : 0;

        foreach ($bets as $index => $bet) {
            if ($bet->id === $this->id) {
                // Costo: inscripción base + $5 por cada apuesta del campeón consecutiva
                $requiredAmount = $baseInscription + (($index + 1) * 5);
                return $user->total_paid >= $requiredAmount;
            }
        }

        return false;
    }
}
