<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
$count = 0;
while (($line = fgets($file)) !== false) {
    if (strpos($line, 'edit.blade.php') !== false) {
        $data = json_decode($line, true);
        if (isset($data['tool_calls'])) {
            foreach ($data['tool_calls'] as $call) {
                if (strpos($call['function']['name'], 'replace') !== false || strpos($call['function']['name'], 'write') !== false) {
                    echo "--- STEP " . $data['step_index'] . " (" . $call['function']['name'] . ") ---\n";
                    $args = json_decode($call['function']['arguments'], true);
                    echo "Instruction: " . ($args['Instruction'] ?? $args['Description'] ?? '') . "\n";
                    if (isset($args['ReplacementChunks'])) {
                        foreach ($args['ReplacementChunks'] as $chunk) {
                            echo "Chunk target: " . substr($chunk['TargetContent'], 0, 50) . "...\n";
                        }
                    } elseif (isset($args['ReplacementContent'])) {
                        echo "ReplacementContent length: " . strlen($args['ReplacementContent']) . "\n";
                    }
                }
            }
        }
    }
}
fclose($file);
