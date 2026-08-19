<?php

$logoPath = __DIR__ . '/../public/images/logo.jpeg';
if (!file_exists($logoPath)) {
    echo "Logo not found at $logoPath\n";
    exit(1);
}

if (!function_exists('imagecreatefromjpeg')) {
    echo "GD extension not available.\n";
    exit(1);
}

$img = imagecreatefromjpeg($logoPath);
$width = imagesx($img);
$height = imagesy($img);

echo "Logo Image Dimensions: {$width}x{$height} pixels\n";

$colors = [];
for ($x = 0; $x < $width; $x += 5) {
    for ($y = 0; $y < $height; $y += 5) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Skip pure white / near-white background
        if ($r > 240 && $g > 240 && $b > 240) continue;
        // Skip pure black / near-black background
        if ($r < 15 && $g < 15 && $b < 15) continue;

        // Group into hex
        $hex = sprintf("#%02x%02x%02x", round($r / 16) * 16, round($g / 16) * 16, round($b / 16) * 16);
        $colors[$hex] = ($colors[$hex] ?? 0) + 1;
    }
}

arsort($colors);

echo "Sampled Color Clusters:\n";
$i = 0;
foreach ($colors as $hex => $count) {
    echo "Color: $hex (Count: $count)\n";
    if (++$i >= 15) break;
}

// Sample exact colors at key interest points (center, logo icon, text)
echo "\nDetailed Spot Samples:\n";
for ($y = 10; $y < $height; $y += round($height / 10)) {
    for ($x = 10; $x < $width; $x += round($width / 10)) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        $hex = sprintf("#%02x%02x%02x", $r, $g, $b);
        echo "At ($x, $y): $hex (R:$r G:$g B:$b)\n";
    }
}
