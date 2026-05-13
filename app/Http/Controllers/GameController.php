<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $allGames = Game::orderBy('match_date', 'asc')->get();
        
        $liveGames = $allGames->where('status', 'in_play');
        $upcomingGames = $allGames->where('status', '!=', 'finished')->where('status', '!=', 'in_play');
        $finishedGames = $allGames->where('status', 'finished');

        $groupedGames = $upcomingGames->groupBy('group')->sortKeys();

        // Cargar predicciones del usuario autenticado
        $userPredictions = collect();
        if (Auth::check()) {
            $userPredictions = Prediction::where('user_id', Auth::id())
                ->get()
                ->keyBy('game_id');
        }

        return view('games.index', compact('liveGames', 'groupedGames', 'finishedGames', 'userPredictions'));
    }
}
