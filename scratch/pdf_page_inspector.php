<?php
// PDF Page Inspector
// Parses the PDF structure page by page and prints out the text found on each page.

$pdfPath = __DIR__ . '/../public/test_cv.pdf';
if (!file_exists($pdfPath)) {
    die("PDF file not found.\n");
}

$pdf = file_get_contents($pdfPath);

// 1. Find all Page objects
// PDF pages are defined by dictionaries like: /Type /Page ...
preg_match_all('/<<[^>]*?\/Type\s*?\/Page\b.*?>>/is', $pdf, $pageMatches);
echo "Total Page Objects found: " . count($pageMatches[0]) . "\n";

// Alternatively, let's find pages by tracing the Kids array in /Type /Pages
preg_match('/\/Type\s*\/Pages\s*\/Kids\s*\[(.*?)\]/s', $pdf, $kidsMatch);
$pageRefs = [];
if ($kidsMatch) {
    preg_match_all('/(\d+\s+\d+\s+R)/', $kidsMatch[1], $refMatches);
    $pageRefs = $refMatches[1];
    echo "Traced " . count($pageRefs) . " page references from /Pages Kids list.\n";
}

// Helper to get object contents by ID (e.g. "5 0")
function getPdfObject($pdf, $objId) {
    $startToken = $objId . " obj";
    $startPos = strpos($pdf, $startToken);
    if ($startPos === false) return null;
    
    $startPos += strlen($startToken);
    $endPos = strpos($pdf, "endobj", $startPos);
    if ($endPos === false) return null;
    
    $rawObj = substr($pdf, $startPos, $endPos - $startPos);
    
    $streamToken = "stream";
    $streamPos = strpos($rawObj, $streamToken);
    if ($streamPos !== false) {
        $header = substr($rawObj, 0, $streamPos);
        $endstreamToken = "endstream";
        $endstreamPos = strrpos($rawObj, $endstreamToken);
        if ($endstreamPos !== false) {
            $stream = substr($rawObj, $streamPos + strlen($streamToken), $endstreamPos - ($streamPos + strlen($streamToken)));
        } else {
            $stream = substr($rawObj, $streamPos + strlen($streamToken));
        }
        $stream = ltrim($stream, "\r\n");
        $stream = rtrim($stream, "\r\n");
    } else {
        $header = $rawObj;
        $stream = null;
    }
    
    return [
        'header' => $header,
        'stream' => $stream
    ];
}

// Parse text from decompressed stream
function parseTextFromStream($streamContent) {
    // Look for text segments: (text) Tj, (text) TJ, etc.
    // PDF text can be in format: (text) Tj or [(t1) 120 (t2)] TJ
    preg_match_all('/\((.*?)\)\s*(?:Tj|TD|T\*|TJ|Tj\b)/', $streamContent, $matches);
    $text = '';
    if (!empty($matches[1])) {
        foreach ($matches[1] as $m) {
            $m = stripcslashes($m);
            $text .= $m . ' ';
        }
    }
    return trim($text);
}

// Decompress stream content
function decompressStream($stream, $header) {
    if (strpos($header, '/Filter /FlateDecode') !== false || strpos($header, '/FlateDecode') !== false) {
        $decompressed = @gzuncompress($stream);
        if ($decompressed === false) {
            // Try raw inflate without headers
            $decompressed = @gzinflate(substr($stream, 2, -4));
        }
        if ($decompressed === false) {
            $decompressed = @gzinflate($stream);
        }
        return $decompressed;
    }
    return $stream;
}

// Inspect each page ref
$pIndex = 1;
foreach ($pageRefs as $ref) {
    $parts = explode(' ', $ref);
    $objId = $parts[0] . ' ' . $parts[1];
    $pageObj = getPdfObject($pdf, $objId);
    
    if (!$pageObj) {
        echo "Page $pIndex: Reference $ref object not found.\n";
        $pIndex++;
        continue;
    }
    
    // Find /Contents in header
    if (preg_match('/\/Contents\s*(\d+\s+\d+\s+R)/i', $pageObj['header'], $contentsMatch)) {
        $contentsRef = $contentsMatch[1];
        $cParts = explode(' ', $contentsRef);
        $cObjId = $cParts[0] . ' ' . $cParts[1];
        
        $contentsObj = getPdfObject($pdf, $cObjId);
        if ($contentsObj && $contentsObj['stream']) {
            $decompressed = decompressStream($contentsObj['stream'], $contentsObj['header']);
            if ($decompressed) {
                $pageText = parseTextFromStream($decompressed);
                echo "Page $pIndex Raw Stream Snippet (First 500 chars):\n";
                echo "--------------------------------------------------\n";
                echo substr($decompressed, 0, 500) . "\n";
                echo "--------------------------------------------------\n\n";
            } else {
                echo "Page $pIndex Contents stream decompression failed.\n\n";
            }
        } else {
            echo "Page $pIndex Contents stream object $contentsRef not found or empty.\n\n";
        }
    } else {
        echo "Page $pIndex has no /Contents field.\n\n";
    }
    $pIndex++;
}
