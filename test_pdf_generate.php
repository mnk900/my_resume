<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Portfolio;
use Barryvdh\DomPDF\Facade\Pdf;

$user = User::where('name', 'like', '%Wasim%')->first() ?? User::first();
if (!$user) {
    echo "ERROR: No users found in database.\n";
    exit(1);
}

$portfolio = $user->portfolio;
if (!$portfolio) {
    echo "ERROR: User {$user->name} does not have a portfolio.\n";
    exit(1);
}

// Replicate prepareCVData logic
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

try {
    echo "Rendering PDF for user: {$user->name}...\n";
    $pdf = Pdf::loadView('cv.template', $data)
        ->setPaper('a4', 'portrait')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
        ]);
        
    $outputPath = __DIR__ . '/public/test_cv.pdf';
    $pdf->save($outputPath);
    echo "SUCCESS: PDF successfully generated and saved to: {$outputPath}\n";
} catch (\Exception $e) {
    echo "ERROR generating PDF: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
