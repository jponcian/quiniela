<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prediction;

class PredictionPdfController extends Controller
{
    public function download()
    {
        $user = Auth::user();

        $predictions = Prediction::with('game')
            ->where('user_id', $user->id)
            ->get()
            ->sortBy(fn($p) => $p->game->match_date);

        $totalPredictions = $predictions->count();
        $totalPoints      = $predictions->sum('points_earned');
        $exactPredictions = $predictions->where('points_earned', 3)->count();
        $partialPredictions = $predictions->where('points_earned', 1)->count();

        $pdf = Pdf::loadView('pdf.predictions', compact(
            'user',
            'predictions',
            'totalPredictions',
            'totalPoints',
            'exactPredictions',
            'partialPredictions'
        ))
        ->setPaper('a4', 'portrait')
        ->setWarnings(false);

        $filename = 'quiniela-pronosticos-' . str($user->name)->slug() . '.pdf';

        return $pdf->download($filename);
    }
}
