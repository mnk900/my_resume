<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Media;
use App\Models\Publication;

echo "=== Media & Publications Test Script ===\n";

$user = User::first();
if (!$user) {
    die("ERROR: No users found in database.\n");
}

$portfolio = $user->portfolio;
if (!$portfolio) {
    die("ERROR: First user has no portfolio.\n");
}

echo "Testing user: {$user->name} (Portfolio ID: {$portfolio->id})\n\n";

// --- 1. Test Media Creation (TV Show) ---
echo "- Creating TV appearance media record...\n";
$tvMedia = $portfolio->media()->create([
    'type' => 'tv',
    'title' => 'Future of Artificial Intelligence',
    'channel_platform' => 'PCTV News Channel',
    'date' => '2026-06-01',
    'link' => 'https://youtube.com/watch?v=ai_future'
]);

if ($tvMedia->exists) {
    echo "  SUCCESS: Created TV Media ID: {$tvMedia->id}\n";
    echo "  Title: {$tvMedia->title}, Platform: {$tvMedia->channel_platform}\n";
} else {
    echo "  FAILED: Could not create TV media.\n";
}

// --- 2. Test Media Update ---
echo "- Updating TV appearance media record...\n";
$tvMedia->update([
    'title' => 'Future of AI and Cloud Computing'
]);
echo "  SUCCESS: Updated Title to: {$tvMedia->title}\n";

// --- 3. Test Media Creation (Op-Ed) ---
echo "- Creating Op-ed media record...\n";
$opedMedia = $portfolio->media()->create([
    'type' => 'oped',
    'title' => 'Why Digital Literacy Matters in Gilgit-Baltistan',
    'newspaper_name' => 'The Dawn News',
    'date' => '2026-06-08',
    'link' => 'https://dawn.com/news/digital-literacy'
]);

if ($opedMedia->exists) {
    echo "  SUCCESS: Created Op-Ed Media ID: {$opedMedia->id}\n";
    echo "  Title: {$opedMedia->title}, Newspaper: {$opedMedia->newspaper_name}\n";
} else {
    echo "  FAILED: Could not create Op-ed media.\n";
}

// --- 4. Test Publication Creation ---
echo "- Creating publication record...\n";
$pub = $portfolio->publications()->create([
    'type' => 'Journal Article',
    'authors' => 'Wasim Abbas, Muhammad Naeem Khan',
    'year' => '2026',
    'title' => 'Microservice Architectures in Modern Web Applications',
    'publisher' => 'International Journal of Software Engineering',
    'link' => 'https://ijse.org/articles/microservices',
    'report_path' => 'reports/test_report.pdf'
]);

if ($pub->exists) {
    echo "  SUCCESS: Created Publication ID: {$pub->id}\n";
    echo "  Title: {$pub->title}, Publisher: {$pub->publisher}\n";
} else {
    echo "  FAILED: Could not create publication.\n";
}

// --- 5. Test Relation Loading ---
echo "- Fetching relationships from Portfolio...\n";
$portfolio->load('media', 'publications');
echo "  Total media entries: " . $portfolio->media->count() . "\n";
echo "  Total publication entries: " . $portfolio->publications->count() . "\n";

// --- 6. Cleanup Test Data ---
echo "- Cleaning up test data...\n";
$tvMedia->delete();
$opedMedia->delete();
$pub->delete();
echo "  SUCCESS: Cleaned up test records from database.\n";
echo "=== Test Completed Successfully! ===\n";
