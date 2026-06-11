<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Registering migrations as already run...\n";

try {
    DB::table('migrations')->insert([
        ['migration' => '2026_05_11_000000_add_contact_visibility_to_portfolios_table', 'batch' => 7],
        ['migration' => '2026_05_11_000001_drop_contact_visibility_from_users_table', 'batch' => 7]
    ]);
    echo "SUCCESS: Registered migrations successfully!\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
