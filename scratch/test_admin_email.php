<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Mail\AdminUserEmail;
use Illuminate\Support\Facades\Mail;

echo "Initializing test for AdminUserEmail...\n";

$subject = "Test Admin Notification";
$message = "<h1>Hello!</h1><p>This is a test notification from admin center.</p>";

$mailable = new AdminUserEmail($subject, $message);

// Check envelope configuration
$envelope = $mailable->envelope();
$from = $envelope->from;

echo "Mailable Subject: " . $envelope->subject . "\n";
echo "Sender Email: " . $from->address . "\n";
echo "Sender Name: " . $from->name . "\n";

if ($from->address === 'info@myresume.cloud') {
    echo "SUCCESS: Sender email matches info@myresume.cloud!\n";
} else {
    echo "FAILED: Sender email is " . $from->address . "\n";
}

// Verify view renders without error
try {
    $mailable->assertSeeInHtml("Test Admin Notification"); // wait, assertSeeInHtml is for testing context
    // Instead let's just render the view to be safe
    $html = $mailable->render();
    if (strpos($html, $message) !== false) {
        echo "SUCCESS: Message content is visible in rendered HTML!\n";
    } else {
        echo "WARNING: Message content not found in rendered HTML.\n";
    }
} catch (\Exception $e) {
    echo "ERROR rendering mailable: " . $e->getMessage() . "\n";
}
