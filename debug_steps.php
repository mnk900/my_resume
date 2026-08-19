<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    if (strpos($line, 'edit.blade.php') !== false) {
        $data = json_decode($line, true);
        echo "Step: " . $data['step_index'] . " | Type: " . ($data['type'] ?? '') . " | Status: " . ($data['status'] ?? '') . "\n";
    }
}
fclose($file);
