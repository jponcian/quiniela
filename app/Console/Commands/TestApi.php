<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

class TestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la conexión con API-Football';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = config('services.football_api.key');
        $this->info("Usando llave: $key");

        $response = Http::withHeaders([
            'x-apisports-key' => $key
        ])->get('https://v3.football.api-sports.io/leagues', [
            'name' => 'La Liga',
            'country' => 'Spain'
        ]);

        if ($response->successful()) {
            $this->info(json_encode($response->json(), JSON_PRETTY_PRINT));
        } else {
            $this->error('Fallo en la petición');
            $this->error($response->body());
        }
    }
}
