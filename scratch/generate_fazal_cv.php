<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// 1. Fetch Fazal Ali Khan
$user = User::where('name', 'like', '%Fazal%')->first();
if (!$user) {
    echo "ERROR: Fazal Ali Khan not found in database.\n";
    exit(1);
}

$portfolio = $user->portfolio;
if (!$portfolio) {
    echo "ERROR: Portfolio not found.\n";
    exit(1);
}

$cvController = new \App\Http\Controllers\CVController();
$data = $cvController->prepareCVData($user, $portfolio);

echo "Generating files for: " . $user->name . "\n";

// 2. Generate PDF CV
try {
    echo "- Rendering PDF...\n";
    $pdf = Pdf::loadView('cv.template', $data)
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
    
    $pdfPath = __DIR__ . '/../public/Fazal_Ali_Khan_CV.pdf';
    $pdf->save($pdfPath);
    echo "  SUCCESS: PDF CV saved to " . realpath($pdfPath) . "\n";
} catch (\Exception $e) {
    echo "  ERROR generating PDF: " . $e->getMessage() . "\n";
}

// 3. Generate Word CV
try {
    echo "- Rendering Word document...\n";
    \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);
    
    $phpWord = new PhpWord();
    
    // Use reflection to call the private createWordDocument method
    $reflector = new \ReflectionClass(\App\Http\Controllers\CVController::class);
    $method = $reflector->getMethod('createWordDocument');
    $method->setAccessible(true);
    $method->invokeArgs($cvController, [$phpWord, $data]);
    
    $wordPath = __DIR__ . '/../public/Fazal_Ali_Khan_CV.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($wordPath);
    echo "  SUCCESS: Word CV saved to " . realpath($wordPath) . "\n";
} catch (\Exception $e) {
    echo "  ERROR generating Word CV: " . $e->getMessage() . "\n";
}
