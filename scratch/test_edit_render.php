<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
if (!$user) {
    echo "No user found in DB\n";
    exit(0);
}

\Illuminate\Support\Facades\Auth::login($user);

try {
    $response = (new \App\Http\Controllers\PortfolioController(new \App\Services\PortfolioService()))->edit();
    echo "Edit view rendered successfully!\n";
} catch (\Throwable $e) {
    echo "Error caught: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
