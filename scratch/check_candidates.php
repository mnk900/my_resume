<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$featuredCandidates = User::whereHas('portfolio', function($q) {
        $q->where('is_active', true);
    })
    ->where('role', '!=', 'admin')
    ->where('account_status', '!=', 'suspended')
    ->with('portfolio', 'professionalPreference')
    ->join('portfolios', 'users.id', '=', 'portfolios.user_id')
    ->select('users.*')
    ->orderByRaw('CASE WHEN portfolios.profile_image IS NOT NULL AND portfolios.profile_image != "" THEN 0 ELSE 1 END')
    ->orderBy('users.id', 'desc')
    ->take(6)
    ->get();

echo "Featured candidates with smart image sorting:\n";
foreach ($featuredCandidates as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Profile Image: " . ($user->portfolio->profile_image ?? 'NULL') . "\n";
}
