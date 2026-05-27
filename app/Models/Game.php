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

        // Bloqueo manual administrativo (Sellar Quiniela)
        if (\App\Models\Setting::get('quiniela_sealed') === '1') {
            return true;
        }

        // Forzamos la comparación en la zona horaria de Caracas para evitar errores si el servidor está en UTC
        $now = now('America/Caracas');

        // Bloqueo global: 1 hora antes del primer partido del mundial
        $firstGame = self::orderBy('match_date', 'asc')->first();
        if ($firstGame) {
            $firstMatchDate = \Carbon\Carbon::parse($firstGame->getRawOriginal('match_date'), 'America/Caracas');
            if ($now->copy()->addHour()->greaterThanOrEqualTo($firstMatchDate)) {
                return true;
            }
        }

        $matchDate = \Carbon\Carbon::parse($this->getRawOriginal('match_date'), 'America/Caracas');

        return $now->addMinutes(15)->greaterThanOrEqualTo($matchDate);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    private function translateTeam($team)
    {
        $translations = [
            'Mexico' => 'México',
            'South Korea' => 'Corea del Sur',
            'Canada' => 'Canadá',
            'United States' => 'Estados Unidos',
            'Qatar' => 'Catar',
            'Brazil' => 'Brasil',
            'Haiti' => 'Haití',
            'Germany' => 'Alemania',
            'Netherlands' => 'Países Bajos',
            'Ivory Coast' => 'Costa de Marfil',
            'Sweden' => 'Suecia',
            'Spain' => 'España',
            'Belgium' => 'Bélgica',
            'Saudi Arabia' => 'Arabia Saudita',
            'Iran' => 'Irán',
            'France' => 'Francia',
            'Iraq' => 'Irak',
            'Uzbekistan' => 'Uzbekistán',
            'Czechia' => 'República Checa',
            'Switzerland' => 'Suiza',
            'Scotland' => 'Escocia',
            'Turkey' => 'Turquía',
            'Tunisia' => 'Túnez',
            'New Zealand' => 'Nueva Zelanda',
            'Norway' => 'Noruega',
            'Jordan' => 'Jordania',
            'Panama' => 'Panamá',
            'Bosnia-Herzegovina' => 'Bosnia y Herzegovina',
            'Morocco' => 'Marruecos',
            'South Africa' => 'Sudáfrica',
            'Curaçao' => 'Curazao',
            'Japan' => 'Japón',
            'Cape Verde Islands' => 'Cabo Verde',
            'Egypt' => 'Egipto',
            'Croatia' => 'Croacia',
            'Congo DR' => 'R.D. del Congo',
            'Algeria' => 'Argelia'
        ];

        return $translations[$team] ?? $team;
    }

    public function getTeamAAttribute($value)
    {
        return $this->translateTeam($value);
    }

    public function getTeamBAttribute($value)
    {
        return $this->translateTeam($value);
    }
}
