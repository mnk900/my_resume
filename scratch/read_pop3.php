<?php
echo "Connecting to POP3 Hostinger Mail Server...\n";

$host = 'ssl://pop.hostinger.com';
$port = 995;
$username = 'info@myresume.cloud';
$password = '@Mhd@112233';

$fp = fsockopen($host, $port, $errno, $errstr, 15);
if (!$fp) {
    die("Connection failed: $errstr ($errno)\n");
}

function get_response($fp) {
    $response = '';
    while (!feof($fp)) {
        $line = fgets($fp, 512);
        $response .= $line;
        if (strpos($line, "\r\n") !== false && (substr($line, 0, 1) === '+' || substr($line, 0, 1) === '-')) {
            break;
        }
    }
    return trim($response);
}

// Read greeting
$response = fgets($fp, 512);
echo "Server Greeting: " . trim($response) . "\n";

// USER command
fwrite($fp, "USER $username\r\n");
$response = fgets($fp, 512);
echo "USER Response: " . trim($response) . "\n";

// PASS command
fwrite($fp, "PASS $password\r\n");
$response = fgets($fp, 512);
echo "PASS Response: " . trim($response) . "\n";

if (strpos($response, '+OK') === false) {
    die("Authentication failed!\n");
}

// STAT command to see message count
fwrite($fp, "STAT\r\n");
$response = fgets($fp, 512);
echo "STAT Response: " . trim($response) . "\n";

// Parse message count
preg_match('/\+OK\s+(\d+)\s+/', $response, $matches);
$msgCount = isset($matches[1]) ? (int)$matches[1] : 0;

if ($msgCount > 0) {
    echo "Found $msgCount emails in mailbox.\n";
    
    // Retrieve the headers of the latest email
    $latest = $msgCount;
    echo "Retrieving headers for email #$latest...\n";
    
    fwrite($fp, "TOP $latest 20\r\n"); // Retrieve headers and first 20 lines of body
    $line = fgets($fp, 512);
    if (strpos($line, '+OK') !== false) {
        $emailContent = '';
        while (!feof($fp)) {
            $line = fgets($fp, 512);
            if (trim($line) === '.') {
                break;
            }
            $emailContent .= $line;
        }
        echo "=== EMAIL HEADERS / PREVIEW ===\n";
        echo $emailContent;
        echo "===============================\n";
    } else {
        echo "Failed to retrieve email: " . trim($line) . "\n";
    }
} else {
    echo "Inbox is empty. No bounce messages found.\n";
}

// QUIT command
fwrite($fp, "QUIT\r\n");
fclose($fp);
echo "Connection closed.\n";
