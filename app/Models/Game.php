<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'api_id',
        'team_a',
        'team_b',
        'flag_a',
        'flag_b',
        'score_a',
        'score_b',
        'match_date',
        'group',
        'round',
        'status',
        'venue'
    ];

    protected $casts = [
        'match_date' => 'datetime',
    ];

    /**
     * Determina si el partido permite registrar predicciones.
     */
    public function isLocked(): bool
    {
        if ($this->status == 'finished' || $this->status == 'in_play') {
            return true;
        }

        // Forzamos la comparación en la zona horaria de Caracas para evitar errores si el servidor está en UTC
        $matchDate = \Carbon\Carbon::parse($this->getRawOriginal('match_date'), 'America/Caracas');
        $now = now('America/Caracas');

        return $now->addMinutes(15)->greaterThanOrEqualTo($matchDate);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }}
