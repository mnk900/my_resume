<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    $data = json_decode($line, true);
    $step = $data['step_index'] ?? 0;
    if ($step >= 2300 && $step <= 2440) {
        if (isset($data['tool_calls'])) {
            foreach ($data['tool_calls'] as $call) {
                $funcName = $call['name'] ?? $call['function']['name'] ?? '';
                $argsStr = $call['arguments'] ?? $call['function']['arguments'] ?? '';
                echo "=== STEP $step ($funcName) ===\n";
                echo "ARGS: " . $argsStr . "\n\n";
            }
        }
    }
}
fclose($file);
