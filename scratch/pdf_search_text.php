<?php
$pdfPath = __DIR__ . '/../public/Fazal_Ali_Khan_CV.pdf';
$pdf = file_get_contents($pdfPath);

preg_match('/\/Type\s*\/Pages\s*\/Kids\s*\[(.*?)\]/s', $pdf, $kidsMatch);
$pageRefs = $kidsMatch ? explode(' ', trim(preg_replace('/\s+/', ' ', $kidsMatch[1]))) : [];

// Deduplicate refs (remove R)
$realRefs = [];
for ($i = 0; $i < count($pageRefs); $i += 3) {
    if (isset($pageRefs[$i])) {
        $realRefs[] = $pageRefs[$i] . ' ' . $pageRefs[$i+1];
    }
}

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

function decompressStream($stream, $header) {
    if (strpos($header, '/Filter /FlateDecode') !== false || strpos($header, '/FlateDecode') !== false) {
        $decompressed = @gzuncompress($stream);
        if ($decompressed === false) {
            $decompressed = @gzinflate(substr($stream, 2, -4));
        }
        return $decompressed;
    }
    return $stream;
}

$pIndex = 1;
foreach ($realRefs as $ref) {
    $pageObj = getPdfObject($pdf, $ref);
    if ($pageObj && preg_match('/\/Contents\s*(\d+\s+\d+\s+R)/i', $pageObj['header'], $contentsMatch)) {
        $cRef = explode(' ', $contentsMatch[1]);
        $cObj = getPdfObject($pdf, $cRef[0] . ' ' . $cRef[1]);
        if ($cObj && $cObj['stream']) {
            $decompressed = decompressStream($cObj['stream'], $cObj['header']);
            if ($decompressed) {
                echo "Page $pIndex has 'MUHAMMAD' or 'WASIM': " . ((strpos($decompressed, 'MUHAMMAD') !== false || strpos($decompressed, 'WASIM') !== false) ? 'YES' : 'NO') . "\n";
                echo "Page $pIndex has 'EXPERIENCE': " . (strpos($decompressed, 'EXPERIENCE') !== false ? 'YES' : 'NO') . "\n";
                echo "Page $pIndex has 'FLAGSHIP': " . (strpos($decompressed, 'FLAGSHIP') !== false ? 'YES' : 'NO') . "\n";
                echo "Page $pIndex has 'CONTACT': " . (strpos($decompressed, 'CONTACT') !== false ? 'YES' : 'NO') . "\n";
                echo "Page $pIndex has 'TECHNICAL': " . (strpos($decompressed, 'TECHNICAL') !== false ? 'YES' : 'NO') . "\n";
            }
        }
    }
    $pIndex++;
}
