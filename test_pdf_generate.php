<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Portfolio;
use Barryvdh\DomPDF\Facade\Pdf;

$user = User::where('username', 'muhammad-naeem-khan')->first() ?? User::where('name', 'like', '%Wasim%')->first() ?? User::first();
if (!$user) {
    echo "ERROR: No users found in database.\n";
    exit(1);
}

$portfolio = $user->portfolio;
if (!$portfolio) {
    echo "ERROR: User {$user->name} does not have a portfolio.\n";
    exit(1);
}

// Use CVController's prepareCVData method
$cvController = new \App\Http\Controllers\CVController();
$data = $cvController->prepareCVData($user, $portfolio);

try {
    echo "Rendering PDF for user: {$user->name}...\n";
    $pdf = Pdf::loadView('cv.template', $data)
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        
    $outputPath = __DIR__ . '/public/test_cv.pdf';
    $pdf->save($outputPath);
    echo "SUCCESS: PDF successfully generated and saved to: {$outputPath}\n";
} catch (\Exception $e) {
    echo "ERROR generating PDF: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
