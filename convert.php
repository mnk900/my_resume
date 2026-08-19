<?php
$content = file_get_contents("c:\\xampp\\htdocs\\my_resume\\aug13_tools.txt");
// Convert UTF-16LE to UTF-8
$utf8 = mb_convert_encoding($content, "UTF-8", "UTF-16LE");
file_put_contents("c:\\xampp\\htdocs\\my_resume\\aug13_tools_utf8.txt", $utf8);
echo "Length: " . strlen($utf8) . "\n";
