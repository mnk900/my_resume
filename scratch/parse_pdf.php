<?php
// Scratch script to extract text from the generated PDF to see what content is on which page.

$pdfFile = __DIR__ . '/../public/test_cv.pdf';
if (!file_exists($pdfFile)) {
    echo "PDF file does not exist.\n";
    exit(1);
}

$content = file_get_contents($pdfFile);

// Simple PDF text extraction for uncompressed or flat PDFs
// DomPDF usually generates uncompressed stream objects if we don't compress them, or we can search for Tj / TJ operators.
// Let's find all streams
preg_match_all('/stream(.*?)endstream/is', $content, $matches);

echo "Found " . count($matches[1]) . " streams in PDF.\n";

$pageNum = 1;
foreach ($matches[1] as $idx => $stream) {
    $stream = trim($stream);
    // Try to decompress if it's compressed (FlateDecode)
    // In some environments, it might be compressed
    if (strpos($content, 'FlateDecode') !== false) {
        $decompressed = @gzuncompress($stream);
        if ($decompressed === false) {
            // Try to find if we can decompress raw
            $decompressed = @gzinflate(substr($stream, 2, -4));
        }
    } else {
        $decompressed = $stream;
    }

    if ($decompressed) {
        // Look for text inside parenthesis (e.g. (text) Tj or [(text)] TJ)
        preg_match_all('/\((.*?)\)\s*(Tj|TD|T\*|TJ)/', $decompressed, $textMatches);
        if (!empty($textMatches[1])) {
            echo "--- STREAM " . ($idx + 1) . " ---\n";
            foreach ($textMatches[1] as $text) {
                // Decode octal codes or backslashes
                $text = stripcslashes($text);
                echo $text . " ";
            }
            echo "\n\n";
        }
    }
}
