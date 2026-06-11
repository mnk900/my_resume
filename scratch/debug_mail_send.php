<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminUserEmail;

echo "Active Mailer Default: " . config('mail.default') . "\n";
echo "Active Mailer Driver: " . config('mail.mailers.' . config('mail.default') . '.transport') . "\n";
echo "Active Mailer Host: " . config('mail.mailers.' . config('mail.default') . '.host') . "\n";
echo "Active Mailer Port: " . config('mail.mailers.' . config('mail.default') . '.port') . "\n";
echo "Active Mailer Username: " . config('mail.mailers.' . config('mail.default') . '.username') . "\n";
echo "Active Mailer Encryption: " . config('mail.mailers.' . config('mail.default') . '.encryption') . "\n\n";

echo "Attempting to send direct email to mhd.naeem90@gmail.com using Laravel Mail...\n";

try {
    $subject = "Real SMTP Test Email";
    $message = "<p>This is a real SMTP test email sent from the debug script to verify connection.</p>";
    
    Mail::to('myresumetest123@mailinator.com')->send(new AdminUserEmail($subject, $message));
    
    echo "SUCCESS: Email sent to Mailinator without exceptions!\n";
} catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
    echo "Symfony Transport Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
} catch (\Exception $e) {
    echo "General Exception: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
