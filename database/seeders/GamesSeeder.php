<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Carbon\Carbon;

class GamesSeeder extends Seeder
{
    /**
     * Partidos de FC Barcelona y Real Madrid CF en La Liga (PD)
     * hasta el Clásico del 10 de mayo de 2026 (inclusive).
     * Datos obtenidos de football-data.org
     */
    public function run(): void
    {
        // Limpiar partidos existentes antes de insertar
        Game::truncate();

        $games = [
            [
                'api_id'     => 544536,
                'team_a'     => 'Real Madrid CF',
                'team_b'     => 'Deportivo Alavés',
                'flag_a'     => 'https://crests.football-data.org/86.png',
                'flag_b'     => 'https://crests.football-data.org/263.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-04-21 15:30:00',
                'group'      => 'League',
                'round'      => 33,
                'status'     => 'timed',
                'venue'      => null,
            ],
            [
                'api_id'     => 544532,
                'team_a'     => 'FC Barcelona',
                'team_b'     => 'RC Celta de Vigo',
                'flag_a'     => 'https://crests.football-data.org/81.png',
                'flag_b'     => 'https://crests.football-data.org/558.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-04-22 15:30:00',
                'group'      => 'League',
                'round'      => 33,
                'status'     => 'timed',
                'venue'      => null,
            ],
            [
                'api_id'     => 544522,
                'team_a'     => 'Real Betis Balompié',
                'team_b'     => 'Real Madrid CF',
                'flag_a'     => 'https://crests.football-data.org/90.png',
                'flag_b'     => 'https://crests.football-data.org/86.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-04-24 15:00:00',
                'group'      => 'League',
                'round'      => 32,
                'status'     => 'timed',
                'venue'      => null,
            ],
            [
                'api_id'     => 544527,
                'team_a'     => 'Getafe CF',
                'team_b'     => 'FC Barcelona',
                'flag_a'     => 'https://crests.football-data.org/82.png',
                'flag_b'     => 'https://crests.football-data.org/81.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-04-25 10:15:00',
                'group'      => 'League',
                'round'      => 32,
                'status'     => 'timed',
                'venue'      => null,
            ],
            [
                'api_id'     => 544544,
                'team_a'     => 'RCD Espanyol de Barcelona',
                'team_b'     => 'Real Madrid CF',
                'flag_a'     => 'https://crests.football-data.org/80.png',
                'flag_b'     => 'https://crests.football-data.org/86.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-05-02 20:00:00',
                'group'      => 'League',
                'round'      => 34,
                'status'     => 'scheduled',
                'venue'      => null,
            ],
            [
                'api_id'     => 544548,
                'team_a'     => 'CA Osasuna',
                'team_b'     => 'FC Barcelona',
                'flag_a'     => 'https://crests.football-data.org/79.png',
                'flag_b'     => 'https://crests.football-data.org/81.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-05-02 20:00:00',
                'group'      => 'League',
                'round'      => 34,
                'status'     => 'scheduled',
                'venue'      => null,
            ],
            [
                'api_id'     => 544553,
                'team_a'     => 'FC Barcelona',
                'team_b'     => 'Real Madrid CF',
                'flag_a'     => 'https://crests.football-data.org/81.png',
                'flag_b'     => 'https://crests.football-data.org/86.png',
                'score_a'    => null,
                'score_b'    => null,
                'match_date' => '2026-05-10 15:00:00',
                'group'      => 'League',
                'round'      => 35,
                'status'     => 'timed',
                'venue'      => null,
            ],
        ];

        foreach ($games as $game) {
            Game::updateOrCreate(
                ['api_id' => $game['api_id']],
                $game
            );
        }

        $this->command->info('✅ ' . count($games) . ' partidos cargados correctamente.');
    }
}
