<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    if (strpos($line, 'edit.blade.php') !== false && strpos($line, 'tool_calls') !== false) {
        $data = json_decode($line, true);
        if (isset($data['tool_calls'])) {
            foreach ($data['tool_calls'] as $call) {
                if (isset($call['function'])) {
                    echo "Tool call function name: " . $call['function']['name'] . "\n";
                }
            }
        }
    }
}
fclose($file);
