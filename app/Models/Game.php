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

        // Bloqueo 15 minutos antes
        // now() ya usa la zona horaria America/Caracas según el .env
        return now()->addMinutes(15)->greaterThanOrEqualTo($this->match_date);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }}
