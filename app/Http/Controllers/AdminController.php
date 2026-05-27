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

        // Obtener lista de países y campeón actual
        $countries = Game::select('team_a as name')
            ->union(Game::select('team_b as name'))
            ->distinct()
            ->get()
            ->pluck('name')
            ->filter(fn($name) => $name && $name !== 'TBD')
            ->unique()
            ->sort()
            ->values();

        $championTeam = \App\Models\Setting::get('tournament_champion');
        $quinielaSealed = \App\Models\Setting::get('quiniela_sealed') === '1';
        $championSealed = \App\Models\Setting::get('champion_sealed') === '1';

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalGames', 
            'finishedGames', 
            'recentGames', 
            'totalCollected',
            'countries',
            'championTeam',
            'quinielaSealed',
            'championSealed'
        ));
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

    public function setChampion(Request $request)
    {
        $request->validate([
            'champion' => 'nullable|string'
        ]);

        if (empty($request->champion)) {
            \App\Models\Setting::set('tournament_champion', null);
            return back()->with('success', 'Campeón de la copa removido con éxito.');
        }

        // Validar que el equipo exista
        $teamExists = Game::where('team_a', $request->champion)
            ->orWhere('team_b', $request->champion)
            ->exists();

        if (!$teamExists) {
            return back()->with('error', 'El equipo seleccionado no es válido.');
        }

        \App\Models\Setting::set('tournament_champion', $request->champion);

        return back()->with('success', "¡El equipo {$request->champion} ha sido establecido como Campeón Oficial del torneo!");
    }

    public function toggleSealQuiniela()
    {
        $current = \App\Models\Setting::get('quiniela_sealed') === '1';
        \App\Models\Setting::set('quiniela_sealed', $current ? '0' : '1');
        
        $msg = $current ? 'La quiniela ha sido ABIERTA para modificaciones.' : 'La quiniela ha sido SELLADA y bloqueada por completo.';
        return back()->with('success', $msg);
    }

    public function toggleSealChampion()
    {
        $current = \App\Models\Setting::get('champion_sealed') === '1';
        \App\Models\Setting::set('champion_sealed', $current ? '0' : '1');
        
        $msg = $current ? 'Las apuestas de campeón han sido ABIERTAS para modificaciones.' : 'Las apuestas de campeón han sido SELLADAS y bloqueadas por completo.';
        return back()->with('success', $msg);
    }
}
