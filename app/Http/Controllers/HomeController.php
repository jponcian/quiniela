<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Game;

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

        $finishedGames = $allGames->where('status', 'finished')->take(10); // Solo los últimos 10 para no saturar

        return view('welcome', compact('liveGames', 'todayGames', 'upcomingGames', 'finishedGames'));
    }
}
