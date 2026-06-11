<?php
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpClient\HttpClient;

echo "Starting low-level SMTP diagnostics...\n";

try {
    // Connect directly using Symfony SMTP transport
    $transport = new EsmtpTransport('smtp.hostinger.com', 465, true); // true for SSL
    $transport->setUsername('info@myresume.cloud');
    $transport->setPassword('@Mhd@112233');

    // Create a mailer using the transport
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from('info@myresume.cloud')
        ->to('mhd.naeem90@gmail.com')
        ->subject('SMTP Low-Level Diagnostic Test')
        ->text('This is a low-level SMTP diagnostic test.')
        ->html('<p>This is a low-level SMTP diagnostic test.</p>');

    echo "Sending email...\n";
    $mailer->send($email);
    echo "SUCCESS: Email sent without errors!\n";
    
} catch (\Exception $e) {
    echo "SMTP ERROR: " . $e->getMessage() . "\n";
    echo "Exception Class: " . get_class($e) . "\n";
    echo "Trace: \n" . $e->getTraceAsString() . "\n";
}
