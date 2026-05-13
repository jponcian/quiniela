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
        $now = now()->setTimezone('America/Caracas');
        
        $liveGames = Game::where('status', 'in_play')->get();
        
        $todayGames = Game::where('status', '!=', 'finished')
                           ->where('status', '!=', 'in_play')
                           ->whereDate('match_date', $now->toDateString())
                           ->orderBy('match_date', 'asc')
                           ->get();

        $nextGames = Game::where('status', '!=', 'finished')
                         ->where('status', '!=', 'in_play')
                         ->where('match_date', '>', $now)
                         ->whereDate('match_date', '!=', $now->toDateString())
                         ->orderBy('match_date', 'asc')
                         ->take(6)
                         ->get();

        $finishedGames = Game::where('status', 'finished')->orderBy('match_date', 'desc')->take(10)->get();

        return view('welcome', compact('liveGames', 'todayGames', 'nextGames', 'finishedGames'));
    }
}
