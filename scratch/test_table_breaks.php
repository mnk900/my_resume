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
    .cv-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .left-col {
        width: 30%;
        background: #f0f0f0;
        vertical-align: top;
        padding: 10px;
    }
    .right-col {
        width: 70%;
        background: #e0e0e0;
        vertical-align: top;
        padding: 10px;
    }
    .item {
        margin-bottom: 20px;
        padding: 10px;
        background: white;
        border: 1px solid #ccc;
    }
</style>
</head>
<body>
    <table class="cv-table">
        <tr>
            <td class="left-col">
                <h3>Left Column</h3>
                ' . $leftItems . '
            </td>
            <td class="right-col">
                <h3>Right Column</h3>
                ' . $rightItems . '
            </td>
        </tr>
    </table>
</body>
</html>
';

$pdf = Pdf::loadHTML($html)
    ->setPaper('a4', 'portrait')
    ->setOptions([
        'isHtml5ParserEnabled' => true,
    ]);

$pdfPath = __DIR__ . '/../public/test_table_breaks.pdf';
$pdf->save($pdfPath);
echo "SUCCESS: Saved to " . realpath($pdfPath) . "\n";
