<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
while (($line = fgets($file)) !== false) {
    $data = json_decode($line, true);
    if (($data['step_index'] ?? 0) == 2362) {
        file_put_contents("c:\\xampp\\htdocs\\my_resume\\step2362_raw.json", json_encode($data['tool_calls'], JSON_PRETTY_PRINT));
    }
}
fclose($file);
