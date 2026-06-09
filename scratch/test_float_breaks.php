<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;

$leftItems = '';
for ($i = 1; $i <= 40; $i++) {
    $leftItems .= "<div class=\"item\">Left Item $i</div>\n";
}

$rightItems = '';
for ($i = 1; $i <= 40; $i++) {
    $rightItems .= "<div class=\"item\">Right Item $i - Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>\n";
}

$html = '
<!DOCTYPE html>
<html>
<head>
<style>
    @page {
        size: A4 portrait;
        margin: 15mm;
    }
    body {
        font-family: sans-serif;
        font-size: 10pt;
        margin: 0;
        padding: 0;
    }
    .left-col {
        float: left;
        width: 30%;
        background: #f0f0f0;
    }
    .right-col {
        float: right;
        width: 65%;
        background: #e0e0e0;
    }
    .item {
        margin-bottom: 20px;
        padding: 10px;
        background: white;
        border: 1px solid #ccc;
    }
    .clear {
        clear: both;
    }
</style>
</head>
<body>
    <div class="left-col">
        <h3>Left Column</h3>
        ' . $leftItems . '
    </div>
    <div class="right-col">
        <h3>Right Column</h3>
        ' . $rightItems . '
    </div>
    <div class="clear"></div>
</body>
</html>
';

$pdf = Pdf::loadHTML($html)
    ->setPaper('a4', 'portrait')
    ->setOptions([
        'isHtml5ParserEnabled' => true,
    ]);

$pdfPath = __DIR__ . '/../public/test_float_breaks.pdf';
$pdf->save($pdfPath);
echo "SUCCESS: Saved to " . realpath($pdfPath) . "\n";
