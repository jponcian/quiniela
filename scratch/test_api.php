<?php
require 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$appId = '2014';
$token = '718e73fc92191e60ffda1fd2c1ec0ee4';
$baseUrl = 'https://api.cedula.com.ve/api/v1';

echo "Testing API Cedula...\n";
try {
    $response = \Illuminate\Support\Facades\Http::timeout(10)->withoutVerifying()->get($baseUrl, [
        'app_id' => $appId,
        'token' => $token,
        'nacionalidad' => 'V',
        'cedula' => '16912337',
    ]);

    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
