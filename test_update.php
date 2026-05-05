<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Portfolio;
use App\Models\User;
use App\Services\PortfolioService;

$user = User::first();
$portfolio = $user->portfolio;
$service = app(PortfolioService::class);

echo "Initial Theme: {$portfolio->theme}\n";

$newData = [
    'title' => 'Updated Title ' . rand(1,99),
    'theme' => ($portfolio->theme === 'vibrant' ? 'classic' : 'vibrant'),
    'show_contact_info' => false
];

echo "Attempting to update to Theme: {$newData['theme']}\n";

try {
    $service->updatePortfolio($portfolio, $newData);
    $portfolio->refresh();
    echo "Updated Theme in DB: {$portfolio->theme}\n";
    
    if ($portfolio->theme === $newData['theme']) {
        echo "SUCCESS: Theme updated correctly.\n";
    } else {
        echo "FAILURE: Theme did not change.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
