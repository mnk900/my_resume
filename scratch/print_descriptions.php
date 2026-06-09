<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('name', 'like', '%Fazal%')->first();
$p = $user->portfolio;
foreach ($p->experiences as $exp) {
    echo "Position: " . $exp->position . "\n";
    echo "Raw Description:\n";
    echo $exp->description . "\n";
    echo "---------------------------\n";
}
