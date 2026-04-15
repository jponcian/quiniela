<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Game;
use Carbon\Carbon;

class FootballDataService
{
    protected $key;
    protected $url = 'https://api.football-data.org/v4';

    public function __construct()
    {
        $this->key = config('services.football_data.key');
    }

    public function syncMatches($competition = 'WC')
    {
        $response = Http::withoutVerifying()->withHeaders([
            'X-Auth-Token' => $this->key
        ])->get("{$this->url}/competitions/{$competition}/matches");

        if ($response->successful()) {
            $matches = $response->json()['matches'];

            // Equipos de interés
            $allowedTeams = ['FC Barcelona', 'Real Madrid CF'];

            // Ordenar por fecha para procesar cronológicamente
            usort($matches, fn($a, $b) => strcmp($a['utcDate'], $b['utcDate']));

            $imported = 0;

            foreach ($matches as $match) {
                $teamA = $match['homeTeam']['name'];
                $teamB = $match['awayTeam']['name'];
                $status = strtoupper($match['status']);

                // Solo partidos que aún no se han jugado
                if (!in_array($status, ['TIMED', 'SCHEDULED'])) {
                    continue;
                }

                // Solo partidos que involucren a Barcelona o Real Madrid
                $involvesAllowed = in_array($teamA, $allowedTeams) || in_array($teamB, $allowedTeams);
                if (!$involvesAllowed) {
                    continue;
                }

                // Guardar el partido
                Game::updateOrCreate(
                    ['api_id' => $match['id']],
                    [
                        'team_a'      => $teamA,
                        'team_b'      => $teamB,
                        'flag_a'      => $match['homeTeam']['crest'],
                        'flag_b'      => $match['awayTeam']['crest'],
                        'score_a'     => $match['score']['fullTime']['home'],
                        'score_b'     => $match['score']['fullTime']['away'],
                        'match_date'  => Carbon::parse($match['utcDate'])->setTimezone('America/Caracas'),
                        'group'       => $match['group'] ?? 'League',
                        'round'       => $match['matchday'],
                        'status'      => strtolower($status),
                        'venue'       => $match['venue'] ?? null,
                    ]
                );

                $imported++;

                // Detener DESPUÉS de guardar el Clásico (Barça vs Madrid o Madrid vs Barça)
                $isClasico = in_array($teamA, $allowedTeams)
                          && in_array($teamB, $allowedTeams);

                if ($isClasico) {
                    break; // El Clásico fue importado, no necesitamos más partidos
                }
            }

            return $imported;
        }

        return 0;
    }
}
