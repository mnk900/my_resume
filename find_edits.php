<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    $data = json_decode($line, true);
    if (isset($data['tool_calls'])) {
        foreach ($data['tool_calls'] as $call) {
            if (in_array($call['function']['name'], ['default_api:replace_file_content', 'default_api:multi_replace_file_content', 'default_api:write_to_file'])) {
                $args = json_decode($call['function']['arguments'], true);
                if (isset($args['TargetFile']) && strpos($args['TargetFile'], 'edit.blade.php') !== false) {
                    echo "Found modification in step " . $data['step_index'] . ": " . $call['function']['name'] . "\n";
                }
            }
        }
    }
}
fclose($file);
