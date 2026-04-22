<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Game;
use Carbon\Carbon;

class FootballDataService
{
    protected $key;
    protected $url = 'https://api.football-data.org/v4';
    protected $whatsapp;

    public function __construct(\App\Services\WhatsAppService $whatsapp)
    {
        $this->key = config('services.football_data.key');
        $this->whatsapp = $whatsapp;
    }

    public function syncMatches($competition = 'WC')
    {
        $response = Http::withoutVerifying()->withHeaders([
            'X-Auth-Token' => $this->key
        ])->get("{$this->url}/competitions/{$competition}/matches");

        if ($response->successful()) {
            $matches = $response->json()['matches'];

            // Equipos de interés (Opcional: puedes quitar este filtro si quieres traer todos)
            $allowedTeams = ['FC Barcelona', 'Real Madrid CF'];

            // Ordenar por fecha para procesar cronológicamente
            usort($matches, fn($a, $b) => strcmp($a['utcDate'], $b['utcDate']));

            $imported = 0;

            foreach ($matches as $match) {
                $teamA = $match['homeTeam']['name'];
                $teamB = $match['awayTeam']['name'];
                $status = strtoupper($match['status']);

                // Filtrar por equipos (Opcional, según tu lógica de La Liga)
                $involvesAllowed = in_array($teamA, $allowedTeams) || in_array($teamB, $allowedTeams);
                if (!$involvesAllowed) {
                    continue;
                }

                // Guardar el partido
                $game = Game::updateOrCreate(
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

                // Si el partido terminó, reconciliar predicciones
                if ($status === 'FINISHED') {
                    $this->reconcileMatch($game);
                }

                // Detener DESPUÉS de guardar el Clásico
                $isClasico = in_array($teamA, $allowedTeams) && in_array($teamB, $allowedTeams);
                if ($isClasico) break;
            }

            // Después de sincronizar todo, refrescar posiciones del ranking
            $this->updateGlobalRanking();

            return $imported;
        }

        return 0;
    }

    /**
     * Calcula los puntos para todas las predicciones de un partido finalizado.
     */
    public function reconcileMatch(Game $game)
    {
        $predictions = \App\Models\Prediction::where('game_id', $game->id)
            ->where('is_calculated', false)
            ->get();

        foreach ($predictions as $prediction) {
            $points = $prediction->calculatePoints();
            
            $prediction->update([
                'points_earned' => $points,
                'is_calculated' => true
            ]);

            // Actualizar acumulados del usuario
            $user = $prediction->user;
            if ($user) {
                $user->points += $points;
                if ($points == 5) $user->hits_exact++;
                if ($points == 3) $user->hits_partial++;
                $user->save();
            }
        }
    }

    /**
     * Recalcula los puestos del ranking y notifica a los usuarios por WhatsApp.
     */
    public function updateGlobalRanking()
    {
        $users = \App\Models\User::orderBy('points', 'desc')
            ->orderBy('hits_exact', 'desc')
            ->get();

        $currentRank = 1;
        $prevScore = null;
        $prevExacts = null;

        // Primero calculamos y guardamos todos los rangos nuevos
        $reportData = [];

        foreach ($users as $index => $user) {
            if ($prevScore !== null && ($user->points < $prevScore || $user->hits_exact < $prevExacts)) {
                $currentRank = $index + 1;
            }

            $oldRank = $user->previous_rank ?: $currentRank;
            $user->previous_rank = $currentRank; // Para la próxima vez
            $user->save();

            // Determinamos flechita para el mensaje
            $trendIcon = '⚪'; // estable
            if ($currentRank < $oldRank) $trendIcon = '🟢 ▲';
            elseif ($currentRank > $oldRank) $trendIcon = '🔴 ▼';

            $reportData[] = [
                'user' => $user,
                'rank' => $currentRank,
                'trend' => $trendIcon
            ];

            $prevScore = $user->points;
            $prevExacts = $user->hits_exact;

            // Guardar en el historial de tendencias
            \App\Models\RankHistory::create([
                'user_id' => $user->id,
                'rank' => $currentRank,
                'points' => $user->points,
                'recorded_at' => now()
            ]);
        }

        // Enviamos notificaciones masivas a través de un Job para evitar timeouts
        if (!empty($reportData)) {
            \App\Jobs\ProcessRankingNotifications::dispatch($reportData);
        }
    }
}
