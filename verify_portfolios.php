<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Portfolio;

foreach (User::all() as $user) {
    if (!$user->portfolio) {
        echo "Creating missing portfolio for user: " . $user->name . "\n";
        $user->portfolio()->create([
            'title' => $user->name . "'s Portfolio",
            'theme' => 'classic'
        ]);
    }
}

echo "Done verification.\n";
