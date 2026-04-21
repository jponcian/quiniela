<?php

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

function testLocked($matchDateStr, $nowStr, $timezone = 'America/Caracas') {
    $matchDate = Carbon::parse($matchDateStr, 'UTC'); // Assuming DB stores in UTC
    $now = Carbon::parse($nowStr, $timezone);
    
    // Logic from Game.php
    $lockTime = (clone $matchDate)->subMinutes(15);
    $isLocked = $now->greaterThanOrEqualTo($lockTime);
    
    echo "Match Date (UTC): " . $matchDate->toDateTimeString() . " (" . $matchDate->getTimezone()->getName() . ")\n";
    echo "Now ($timezone): " . $now->toDateTimeString() . " (" . $now->getTimezone()->getName() . ")\n";
    echo "Lock Time (UTC): " . $lockTime->toDateTimeString() . "\n";
    echo "Is Locked: " . ($isLocked ? 'YES' : 'NO') . "\n";
    echo "--------------------------\n";
}

// Case 1: 20 mins before
testLocked('2026-04-21 14:00:00', '2026-04-21 09:40:00'); // 14:00 UTC is 10:00 Caracas (Vzla is UTC-4)
// Case 2: 10 mins before
testLocked('2026-04-21 14:00:00', '2026-04-21 09:50:00');
// Case 3: Exactly 15 mins before
testLocked('2026-04-21 14:00:00', '2026-04-21 09:45:00');
