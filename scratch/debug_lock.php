<?php

define('LARAVEL_START', microtime(true));
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use Carbon\Carbon;

$g = Game::where('team_a', 'like', '%Madrid%')->first();
if (!$g) {
    echo "Game not found\n";
    exit;
}

echo "Game: " . $g->team_a . " vs " . $g->team_b . "\n";
echo "Status: " . $g->status . "\n";
echo "Match Date (as string in DB): " . $g->getRawOriginal('match_date') . "\n";
echo "Match Date (Carbon): " . $g->match_date . " (" . $g->match_date->timezoneName . ")\n";
echo "Now: " . now() . " (" . now()->timezoneName . ")\n";
echo "Now + 15 min: " . now()->addMinutes(15) . "\n";
echo "Is Locked: " . ($g->isLocked() ? 'YES' : 'NO') . "\n";

$isLockedManual = now()->addMinutes(15)->greaterThanOrEqualTo($g->match_date);
echo "Manual Comparison (now+15 >= match_date): " . ($isLockedManual ? 'YES' : 'NO') . "\n";
