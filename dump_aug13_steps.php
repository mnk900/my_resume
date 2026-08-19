<?php
$transcript_path = "C:\\Users\\Lenovo\\.gemini\\antigravity-ide\\brain\\9c8b8c9c-781a-4d9c-bebe-90cb46f61c06\\.system_generated\\logs\\transcript_full.jsonl";
$file = fopen($transcript_path, "r");
$steps = [2311, 2315, 2321, 2336, 2342, 2379, 2389, 2399];
$out = [];
while (($line = fgets($file)) !== false) {
    $data = json_decode($line, true);
    $step = $data['step_index'] ?? 0;
    if (in_array($step, $steps)) {
        $out[$step] = $data['tool_calls'] ?? [];
    }
}
fclose($file);
file_put_contents("c:\\xampp\\htdocs\\my_resume\\aug13_all_edits.json", json_encode($out, JSON_PRETTY_PRINT));
echo "Done dumping steps.\n";
