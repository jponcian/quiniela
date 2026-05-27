<?php

namespace App\Http\Controllers;

use App\Models\ChampionBet;
use App\Models\Game;
use App\Models\Setting;
use Illuminate\Http\Request;

class ChampionBetController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Determinar si las apuestas están bloqueadas (por tiempo o sellado administrativo)
        $firstGame = Game::orderBy('match_date', 'asc')->first();
        $isLocked = (Setting::get('champion_sealed') === '1') || ($firstGame && now()->addHour()->greaterThanOrEqualTo($firstGame->match_date));

        // Obtener lista de países y banderas
        $gamesData = Game::select('team_a as name', 'flag_a as flag')
            ->union(Game::select('team_b as name', 'flag_b as flag'))
            ->distinct()
            ->get()
            ->filter(fn($c) => $c->name && $c->name !== 'TBD');

        // Agrupar banderas únicas
        $flags = $gamesData->pluck('flag', 'name')->toArray();
        $countries = $gamesData->pluck('name')->unique()->sort()->values();

        // Apuestas del usuario actual
        $myBets = $user->championBets;

        // Total del pozo (todos los registros * $5)
        $totalPot = ChampionBet::count() * 5;

        // Si el campeonato finalizó, ver quién ganó
        $championTeam = Setting::get('tournament_champion');
        $winners = null;
        $winAmount = 0;

        if ($championTeam) {
            $correctBets = ChampionBet::where('team_name', $championTeam)->get();
            $winnerCount = $correctBets->count();
            if ($winnerCount > 0) {
                $winAmount = $totalPot / $winnerCount;
                $winners = $correctBets->map(fn($bet) => $bet->user)->unique();
            }
        }

        return view('champion_bets.index', compact(
            'countries', 
            'flags', 
            'myBets', 
            'isLocked', 
            'totalPot', 
            'championTeam', 
            'winners', 
            'winAmount'
        ));
    }

    public function store(Request $request)
    {
        $firstGame = Game::orderBy('match_date', 'asc')->first();
        $isLocked = (Setting::get('champion_sealed') === '1') || ($firstGame && now()->addHour()->greaterThanOrEqualTo($firstGame->match_date));

        if ($isLocked) {
            return back()->with('error', 'Las apuestas de campeón están cerradas porque el mundial ya inició.');
        }

        $request->validate([
            'team_name' => 'required|string'
        ]);

        $user = auth()->user();

        // Validar que el equipo exista en los partidos
        $teamExists = Game::where('team_a', $request->team_name)
            ->orWhere('team_b', $request->team_name)
            ->exists();

        if (!$teamExists) {
            return back()->with('error', 'El equipo seleccionado no es válido.');
        }

        // Validar que no se haya apostado ya a este equipo
        $alreadyBet = ChampionBet::where('user_id', $user->id)
            ->where('team_name', $request->team_name)
            ->exists();

        if ($alreadyBet) {
            return back()->with('error', 'Ya tienes una apuesta registrada para este equipo.');
        }

        ChampionBet::create([
            'user_id' => $user->id,
            'team_name' => $request->team_name
        ]);

        return back()->with('success', '¡Apuesta registrada correctamente! Tu saldo deudor ha aumentado $5.00.');
    }

    public function destroy(ChampionBet $championBet)
    {
        $firstGame = Game::orderBy('match_date', 'asc')->first();
        $isLocked = (Setting::get('champion_sealed') === '1') || ($firstGame && now()->addHour()->greaterThanOrEqualTo($firstGame->match_date));

        if ($isLocked) {
            return back()->with('error', 'No puedes eliminar apuestas dentro de la hora previa al inicio del mundial o una vez comenzado.');
        }

        if ($championBet->user_id !== auth()->id()) {
            abort(403);
        }

        if ($championBet->is_confirmed) {
            return back()->with('error', 'No puedes eliminar una apuesta de campeón cuyo pago ya ha sido verificado y confirmado por el administrador.');
        }

        $championBet->delete();

        return back()->with('success', 'Apuesta eliminada correctamente. Tu saldo deudor ha disminuido $5.00.');
    }
}
