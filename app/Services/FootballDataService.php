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

            foreach ($matches as $match) {
                $teamA = $match['homeTeam']['name'];
                $teamB = $match['awayTeam']['name'];

                // Filtrar solo Barcelona y Real Madrid
                $allowedTeams = ['FC Barcelona', 'Real Madrid CF'];
                if (!in_array($teamA, $allowedTeams) && !in_array($teamB, $allowedTeams)) {
                    continue;
                }

                Game::updateOrCreate(
                    ['api_id' => $match['id']],
                    [
                        'team_a' => $match['homeTeam']['name'],
                        'team_b' => $match['awayTeam']['name'],
                        'flag_a' => $match['homeTeam']['crest'],
                        'flag_b' => $match['awayTeam']['crest'],
                        'score_a' => $match['score']['fullTime']['home'],
                        'score_b' => $match['score']['fullTime']['away'],
                        'match_date' => Carbon::parse($match['utcDate'])->setTimezone('America/Caracas'),
                        'group' => $match['group'] ?? 'League',
                        'round' => $match['matchday'],
                        'status' => strtolower($match['status']),
                        'venue' => $match['venue'] ?? null
                    ]
                );
            }

            return count($matches);
        }

        return 0;
    }
}
