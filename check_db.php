<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Theme;
use App\Models\Portfolio;
use App\Models\User;

echo "--- THEMES TABLE ---\n";
Theme::all()->each(fn($t) => print("- {$t->name} (Slug: {$t->slug})\n"));

echo "\n--- PORTFOLIOS TABLE (Top 3) ---\n";
Portfolio::with('user')->limit(3)->get()->each(function($p) {
    echo "- User: {$p->user->name} | Theme: {$p->theme}\n";
});
