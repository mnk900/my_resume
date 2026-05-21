<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Portfolio;
use Dompdf\Dompdf;

$user = User::first();
$portfolio = $user->portfolio;

// prepare data
$skillsData = [];
if ($portfolio->skills) {
    foreach ($portfolio->skills->groupBy('category') as $category => $items) {
        $categorySkills = [];
        foreach ($items as $item) {
            $categorySkills[] = $item->name;
        }
        if (!empty($categorySkills)) {
            $skillsData[$category] = $categorySkills;
        }
    }
}

$projectsData = [];
if ($portfolio->projects) {
    $count = 0;
    foreach ($portfolio->projects as $project) {
        if ($count >= 5) break;
        $projectsData[] = [
            'title' => $project->title,
            'description' => $project->description,
            'technologies' => $project->technologies ?? []
        ];
        $count++;
    }
}

$data = [
    'name' => $user->name,
    'title' => $portfolio->position ?? 'Professional',
    'email' => $portfolio->show_email ? $user->email : null,
    'phone' => $portfolio->show_phone ? $portfolio->contact_number : null,
    'linkedin' => $portfolio->show_linkedin ? $portfolio->linkedin_url : null,
    'location' => ($portfolio->city ?? 'Gilgit-Baltistan') . ', ' . ($portfolio->country ?? 'Pakistan'),
    'summary' => $portfolio->description,
    'experience' => $portfolio->experiences ? $portfolio->experiences->map(function($exp) {
        return [
            'position' => $exp->position,
            'company' => $exp->company,
            'start_date' => $exp->start_date->format('M Y'),
            'end_date' => $exp->end_date ? $exp->end_date->format('M Y') : 'Present',
            'description' => $exp->description
        ];
    })->toArray() : [],
    'education' => $portfolio->education ? $portfolio->education->map(function($edu) {
        return [
            'degree' => $edu->degree,
            'institution' => $edu->institution,
            'start_date' => $edu->start_date->format('Y'),
            'end_date' => $edu->end_date->format('Y')
        ];
    })->toArray() : [],
    'skills' => $skillsData,
    'certifications' => $portfolio->certifications ? $portfolio->certifications->pluck('name')->toArray() : [],
    'trainings' => $portfolio->trainings ? $portfolio->trainings->pluck('title')->toArray() : [],
    'projects' => $projectsData
];

$html = view('cv.template', $data)->render();

$dompdf = new Dompdf();
$dompdf->setPaper('a4', 'portrait');
$options = new \Dompdf\Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->render();

// Inspect frame tree to find coordinates of key text
$rootFrame = $dompdf->getTree()->get_root();

function walkFrames($frame, $depth = 0) {
    $node = $frame->get_node();
    $style = $frame->get_style();
    $id = $node ? $node->nodeName : 'unknown';
    
    // Get text content if it's a text node
    $text = '';
    if ($node && $node->nodeType === XML_TEXT_NODE) {
        $text = trim($node->nodeValue);
    }
    
    // Get class or id if present
    $class = '';
    if ($node && $node instanceof DOMElement) {
        $class = $node->getAttribute('class');
        $text = trim($node->textContent);
        // Truncate long text
        if (strlen($text) > 40) {
            $text = substr($text, 0, 40) . '...';
        }
    }

    $box = $frame->get_padding_box();
    
    // Find page number
    $pageNum = 0;
    // DomPDF's frame stores page representation
    // Let's print out frames that have text or key classes
    if (!empty($text) || !empty($class)) {
        $x = round($box['x'], 1);
        $y = round($box['y'], 1);
        $w = round($box['w'], 1);
        $h = round($box['h'], 1);
        
        echo str_repeat("  ", $depth) . "<$id class='$class'> [X:$x Y:$y W:$w H:$h] text: '$text'\n";
    }

    foreach ($frame->get_children() as $child) {
        walkFrames($child, $depth + 1);
    }
}

walkFrames($rootFrame);
