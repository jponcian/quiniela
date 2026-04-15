<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index()
    {
        // 1. Obtener todos los usuarios ordenados por puntos (desc) y luego por exactos (desc)
        $users = User::orderBy('points', 'desc')
            ->orderBy('hits_exact', 'desc')
            ->get();

        // 2. Calcular estadísticas globales
        $remainingGames = Game::whereNotIn('status', ['finished', 'in_play'])->count();
        $maxPossiblePointsFromNow = $remainingGames * 5;
        $leaderPoints = $users->first() ? $users->first()->points : 0;

        // 3. Procesar cada usuario para el ranking
        $rankedUsers = [];
        $currentRank = 1;
        $prevUser = null;

        foreach ($users as $index => $user) {
            // Lógica de ranking con empates (Competition Ranking)
            if ($prevUser && ($user->points < $prevUser->points || $user->hits_exact < $prevUser->hits_exact)) {
                $currentRank = $index + 1;
            }

            // ¿Sigue con vida?
            $isAlive = ($user->points + $maxPossiblePointsFromNow) >= $leaderPoints;

            // Probabilidad (Muy simplificada: Proporción de puntos actuales vs líder + factor partidos restantes)
            $probability = 0;
            if ($leaderPoints > 0) {
                $baseProb = ($user->points / $leaderPoints) * 100;
                $probability = $isAlive ? min(100, round($baseProb, 1)) : 0;
            } else {
                $probability = 100; // Todos empiezan igual
            }

            // Tendencia
            $trend = 'stable';
            if ($user->previous_rank) {
                if ($currentRank < $user->previous_rank) {
                    $trend = 'up';
                } elseif ($currentRank > $user->previous_rank) {
                    $trend = 'down';
                }
            }

            $rankedUsers[] = [
                'user' => $user,
                'rank' => $currentRank,
                'is_alive' => $isAlive,
                'probability' => $probability,
                'trend' => $trend,
            ];

            $prevUser = $user;
        }

        // 4. Calcular premios estimados (vía lógica de pozo compartido)
        // Por ahora simularemos un pozo (ej. total de usuarios * 10$)
        $totalPot = $users->count() * 10; // Esto es una referencia
        $prizes = $this->calculatePrizes($rankedUsers);

        return view('ranking', [
            'rankedUsers' => $rankedUsers,
            'prizes' => $prizes,
            'totalPot' => $totalPot,
            'currentUser' => Auth::user()
        ]);
    }

    private function calculatePrizes($rankedUsers)
    {
        $prizeDistribution = [1 => 60, 2 => 25, 3 => 10]; // Porcentajes del pozo (95% total)
        $results = [];

        // Agrupar usuarios por su puesto (rank)
        $rankGroups = [];
        foreach ($rankedUsers as $ru) {
            $rankGroups[$ru['rank']][] = $ru['user']->id;
        }

        ksort($rankGroups);

        $positionsCounted = 0;
        foreach ($rankGroups as $rank => $userIds) {
            $count = count($userIds);
            $relevantPositions = [];
            
            // Ver qué posiciones de premio "consume" este grupo de empate
            for ($i = 1; $i <= $count; $i++) {
                $pos = $positionsCounted + $i;
                if (isset($prizeDistribution[$pos])) {
                    $relevantPositions[] = $prizeDistribution[$pos];
                }
            }

            if (!empty($relevantPositions)) {
                $sharedPercentage = array_sum($relevantPositions) / $count;
                foreach ($userIds as $uid) {
                    $results[$uid] = $sharedPercentage;
                }
            }

            $positionsCounted += $count;
            if ($positionsCounted >= 3) break; // Solo nos interesan los top 3 para premios
        }

        return $results;
    }
}
