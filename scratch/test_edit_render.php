<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'mhd.naeem90@gmail.com')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

Auth::login($user);

$html = view('portfolio.edit', [
    'portfolio' => $user->portfolio,
    'themes' => \App\Models\Theme::all(),
])->render();

echo "Length of rendered HTML: " . strlen($html) . "\n";
echo "Bottom 1000 characters:\n";
echo substr($html, -1000);
