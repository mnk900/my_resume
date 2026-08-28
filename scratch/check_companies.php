<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;
use Illuminate\Support\Facades\File;

$files = File::files(storage_path('app/public/company_logos'));
echo "Company logo files in storage:\n";
foreach ($files as $file) {
    echo $file->getFilename() . " (" . $file->getSize() . " bytes)\n";
}

$companies = Company::all();
echo "\nDatabase Companies:\n";
foreach ($companies as $c) {
    $exists = $c->logo_path && file_exists(storage_path('app/public/' . $c->logo_path));
    echo "ID: {$c->id} | Name: {$c->name} | Logo Path: " . ($c->logo_path ?? 'NULL') . " | File Exists: " . ($exists ? 'YES' : 'NO') . "\n";
}
