<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('name', 'like', '%Fazal%')->first();
if (!$user) {
    echo "User not found\n";
    exit(1);
}

$p = $user->portfolio;
echo "User: " . $user->name . "\n";
echo "Trainings count: " . ($p->trainings ? $p->trainings->count() : 0) . "\n";
if ($p->trainings) {
    foreach ($p->trainings as $t) {
        echo "- " . $t->title . "\n";
    }
}

echo "Certifications count: " . ($p->certifications ? $p->certifications->count() : 0) . "\n";
if ($p->certifications) {
    foreach ($p->certifications as $c) {
        echo "- " . $c->name . "\n";
    }
}
