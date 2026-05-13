<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;
use App\Models\Payment;
use App\Services\FootballDataService;

class AdminController extends Controller
{
    protected $footballService;

    public function __construct(FootballDataService $footballService)
    {
        $this->footballService = $footballService;
    }

    public function index()
    {
        $totalUsers = User::count();
        $totalGames = Game::count();
        $finishedGames = Game::where('status', 'finished')->count();
        $recentGames = Game::orderBy('match_date', 'desc')->take(5)->get();
        $totalCollected = Payment::sum('amount');

        return view('admin.dashboard', compact('totalUsers', 'totalGames', 'finishedGames', 'recentGames', 'totalCollected'));
    }

    public function matches(Request $request)
    {
        $status = $request->get('status');
        $query = Game::orderBy('match_date', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        $games = $query->paginate(15);

        return view('admin.matches.index', compact('games'));
    }

    public function updateMatch(Request $request, Game $game)
    {
        $request->validate([
            'score_a' => 'required|integer|min:0',
            'score_b' => 'required|integer|min:0',
            'status' => 'required|string'
        ]);

        $game->update([
            'score_a' => $request->score_a,
            'score_b' => $request->score_b,
            'status' => $request->status,
        ]);

        if ($game->status === 'finished') {
            $this->footballService->reconcileMatch($game);
            $this->footballService->updateGlobalRanking();
        }

        return back()->with('success', 'Partido actualizado correctamente.');
    }

    public function syncMatches()
    {
        try {
            $count = $this->footballService->syncMatches('WC');
            $this->footballService->updateGlobalRanking();
            return back()->with('success', "Sincronización completada. Se procesaron {$count} partidos y se actualizó el ranking.");
        } catch (\Exception $e) {
            return back()->with('error', "Error en la sincronización: " . $e->getMessage());
        }
    }
}
