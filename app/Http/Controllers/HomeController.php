<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\Prediction;

class HomeController extends Controller
{
    public function index()
    {
        $allGames = Game::orderBy('match_date', 'asc')->get();
        
        $now = now()->setTimezone('America/Caracas');

        $liveGames = $allGames->where('status', 'in_play');
        
        $todayGames = $allGames->where('status', '!=', 'finished')
                               ->where('status', '!=', 'in_play')
                               ->filter(function($game) use ($now) {
                                   return $game->match_date->isToday();
                               });

        $upcomingGames = $allGames->where('status', '!=', 'finished')
                                  ->where('status', '!=', 'in_play')
                                  ->filter(function($game) use ($now) {
                                      return $game->match_date->isAfter($now) && !$game->match_date->isToday();
                                  });

        $finishedGames = $allGames->where('status', 'finished')->take(10);

        // Cargar predicciones del usuario autenticado, indexadas por game_id
        $userPredictions = collect();
        if (Auth::check()) {
            $userPredictions = Prediction::where('user_id', Auth::id())
                ->get()
                ->keyBy('game_id');
        }

        return view('welcome', compact('liveGames', 'todayGames', 'upcomingGames', 'finishedGames', 'userPredictions'));
    }
}
