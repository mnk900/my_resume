<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$user = User::where('username', 'muhammad-naeem-khan')->first() ?? User::first();
if (!$user) {
    echo "ERROR: No users found.\n";
    exit(1);
}

$portfolio = $user->portfolio;
if (!$portfolio) {
    echo "ERROR: User has no portfolio.\n";
    exit(1);
}

$cvController = new \App\Http\Controllers\CVController();
$data = $cvController->prepareCVData($user, $portfolio);

try {
    echo "Generating Word doc for user: {$user->name}...\n";
    \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);
    $phpWord = new PhpWord();
    
    // We can access createWordDocument using reflection since it's private
    $reflector = new \ReflectionClass(\App\Http\Controllers\CVController::class);
    $method = $reflector->getMethod('createWordDocument');
    $method->setAccessible(true);
    $method->invokeArgs($cvController, [$phpWord, $data]);
    
    $outputPath = __DIR__ . '/../public/test_cv.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($outputPath);
    echo "SUCCESS: Word document successfully saved to: {$outputPath}\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
