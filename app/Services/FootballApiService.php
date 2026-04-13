<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Game;
use Carbon\Carbon;

class FootballApiService
{
    protected $key;
    protected $url = 'https://v3.football.api-sports.io';

    public function __construct()
    {
        $this->key = config('services.football_api.key');
    }

    public function syncMatches($league = 1, $season = 2026)
    {
        $response = Http::withHeaders([
            'x-apisports-key' => $this->key
        ])->get("{$this->url}/fixtures", [
            'league' => $league,
            'season' => $season
        ]);

        if ($response->successful()) {
            $fixtures = $response->json()['response'];

            foreach ($fixtures as $fixture) {
                Game::updateOrCreate(
                    ['api_id' => $fixture['fixture']['id']],
                    [
                        'team_a' => $fixture['teams']['home']['name'],
                        'team_b' => $fixture['teams']['away']['name'],
                        'flag_a' => $fixture['teams']['home']['logo'],
                        'flag_b' => $fixture['teams']['away']['logo'],
                        'score_a' => $fixture['goals']['home'],
                        'score_b' => $fixture['goals']['away'],
                        'match_date' => Carbon::parse($fixture['fixture']['date'])->setTimezone('America/Caracas'),
                        'group' => $fixture['league']['round'], // Often contains group info
                        'status' => $fixture['fixture']['status']['short'] == 'FT' ? 'finished' : 'scheduled',
                        'venue' => $fixture['fixture']['venue']['name']
                    ]
                );
            }

            return count($fixtures);
        }

        return 0;
    }
}
