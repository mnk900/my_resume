<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Portfolio;
use Illuminate\Support\Facades\File;

$imageFiles = [
    'profile_images/wfv86G88EWwtUGhWV5MQLx5QAMYz6Xo5ihFQxjMe.png',
    'profile_images/PFnOmpUQvpTp9mHwQD3slaZ6ELnxKq3hq2DOekOy.jpg',
    'profile_images/3WNOrXesNdGR2m4mTmTpqz1lGdneWL3z1H3z1YXj.jpg',
    'profile_images/TSgJtu4rLEY3Wa16ITR5HkBvuGvc35xI5Hq81wvW.jpg',
    'profile_images/ZhaxFQIxOUohHXtl4TWf9in110G6OM33pXHow6Zx.jpg',
    'profile_images/p5Nw8c2IL6CPprYQfzo0Un4N7w7sZnYq5gFI905M.jpg',
    'profile_images/yLyXVAgiq7Hg6k6TAwzq5nJnV2QGm9TD8IRA1rhC.jpg',
];

$activePortfolios = Portfolio::where('is_active', true)
    ->whereHas('user', function($q) { $q->where('role', '!=', 'admin'); })
    ->take(7)
    ->get();

foreach ($activePortfolios as $index => $portfolio) {
    if (isset($imageFiles[$index])) {
        $portfolio->profile_image = $imageFiles[$index];
        $portfolio->save();
        echo "Updated Portfolio ID {$portfolio->id} (User: {$portfolio->user->name}) -> {$imageFiles[$index]}\n";
    }
}
