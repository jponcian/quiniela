<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('sync:matches', function () {
    Artisan::call('sync:matches', ['--competition' => 'PD']);
})->purpose('Sincroniza los partidos de La Liga')->hourly();
