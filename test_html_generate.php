<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Portfolio;

$user = User::where('name', 'like', '%Naeem%')->first() ?? User::first();
if (!$user) {
    echo "ERROR: No users found in database.\n";
    exit(1);
}

$portfolio = $user->portfolio;

$cvController = new \App\Http\Controllers\CVController();
$data = $cvController->prepareCVData($user, $portfolio);

$html = view('cv.template', $data)->render();
file_put_contents(__DIR__ . '/public/test_cv.html', $html);
echo "SUCCESS: Saved rendered HTML to public/test_cv.html\n";
