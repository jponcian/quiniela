<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prediction;
use App\Models\Game;
use Illuminate\Support\Facades\Auth;

class PredictionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'score_a' => 'required|integer|min:0',
            'score_b' => 'required|integer|min:0',
        ]);

        $game = Game::findOrFail($request->game_id);

        if ($game->isLocked()) {
            return response()->json([
                'message' => 'El tiempo para pronosticar este partido ha expirado (15 min antes).'
            ], 403);
        }

        $prediction = Prediction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'game_id' => $game->id
            ],
            [
                'home_score' => $request->score_a,
                'away_score' => $request->score_b,
                'points_earned' => 0 // Se calcularán después del partido
            ]
        );

        return response()->json([
            'message' => 'Predicción guardada exitosamente.',
            'prediction' => $prediction
        ]);
    }
}
