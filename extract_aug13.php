<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    $data = json_decode($line, true);
    $step = $data['step_index'] ?? 0;
    if ($step >= 2400 && $step <= 2470) {
        echo "=== STEP $step (" . $data['type'] . ") ===\n";
        if (isset($data['tool_calls'])) {
            foreach ($data['tool_calls'] as $call) {
                echo "TOOL CALL: " . $call['function']['name'] . "\n";
                echo "ARGS: " . substr($call['function']['arguments'], 0, 300) . "...\n";
            }
        }
        if (isset($data['content']) && strpos($data['content'], 'edit.blade.php') !== false) {
            echo "CONTENT SNIPPET: " . substr($data['content'], 0, 300) . "...\n";
        }
    }
}
fclose($file);
