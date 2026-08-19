<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    if (strpos($line, 'edit.blade.php') !== false) {
        $data = json_decode($line, true);
        echo "Keys: " . implode(", ", array_keys($data)) . "\n";
        echo "Type: " . ($data['type'] ?? '') . "\n";
        if (isset($data['content'])) {
            echo "Content len: " . strlen($data['content']) . "\n";
        }
        break;
    }
}
fclose($file);
