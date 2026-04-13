<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\FootballDataService;

class SyncMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:matches {--competition=PD}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los partidos con football-data.org';

    /**
     * Execute the console command.
     */
    public function handle(FootballDataService $service)
    {
        $comp = $this->option('competition');

        $this->info("Iniciando sincronización para Competencia: $comp...");
        $count = $service->syncMatches($comp);
        $this->info("¡Sincronización completada! Se procesaron {$count} partidos.");
    }
}
