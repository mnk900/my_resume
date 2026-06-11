<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

foreach (User::all() as $user) {
    $p = $user->portfolio;
    echo "User: " . $user->name . " (" . $user->username . ") - Email: " . $user->email . "\n";
    if ($p) {
        echo "  - Position: " . $p->position . "\n";
        echo "  - Experiences: " . ($p->experiences ? $p->experiences->count() : 0) . "\n";
        echo "  - Educations: " . ($p->education ? $p->education->count() : 0) . "\n";
        echo "  - Skills: " . ($p->skills ? $p->skills->count() : 0) . "\n";
        echo "  - Certifications: " . ($p->certifications ? $p->certifications->count() : 0) . "\n";
        echo "  - Trainings: " . ($p->trainings ? $p->trainings->count() : 0) . "\n";
        echo "  - Projects: " . ($p->projects ? $p->projects->count() : 0) . "\n";
    } else {
        echo "  - No portfolio\n";
    }
    echo "\n";
}
