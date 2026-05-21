<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    public function downloadPDF($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $portfolio = $user->portfolio;

        if (!$portfolio) {
            abort(404);
        }

        $data = $this->prepareCVData($user, $portfolio);

        $pdf = Pdf::loadView('cv.template', $data)
    ->setPaper('a4', 'portrait')
    ->setOptions([
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
        'isRemoteEnabled' => true,
    ]);
        $filename = $user->name . '_Europass_CV.pdf';

        return $pdf->download($filename);
    }

    public function downloadWord($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $portfolio = $user->portfolio;

        if (!$portfolio) {
            abort(404);
        }

        $data = $this->prepareCVData($user, $portfolio);

        $phpWord = new PhpWord();
        $this->createWordDocument($phpWord, $data);

        $filename = $user->name . '_Europass_CV.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'cv');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    private function prepareCVData($user, $portfolio)
    {
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

        return [
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
    }

    private function createWordDocument($phpWord, $data)
    {
        // Set margins
        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720
        ]);

        // Header with name and position
        $section->addText($data['name'], ['bold' => true, 'size' => 24, 'color' => '1a365d']);
        $section->addText($data['title'], ['size' => 14, 'color' => '4a5568']);
        $section->addTextBreak(1);

        // Professional Summary
        if ($data['summary']) {
            $section->addText('Professional Summary', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            $section->addText($data['summary'], ['size' => 9]);
            $section->addTextBreak(1);
        }

        // Experience Section
        if (!empty($data['experience'])) {
            $section->addText('Experience', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            foreach ($data['experience'] as $exp) {
                $section->addText($exp['position'], ['bold' => true, 'size' => 10, 'color' => '1a365d']);
                $section->addText($exp['company'], ['italic' => true, 'size' => 9, 'color' => '4a5568']);
                $section->addText($exp['start_date'] . ' - ' . $exp['end_date'], ['size' => 8, 'color' => '718096']);
                $section->addText($exp['description'], ['size' => 9]);
                $section->addTextBreak(1);
            }
        }

        // Flagship Projects Section (Max 5)
        if (!empty($data['projects'])) {
            $section->addText('Flagship Projects', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            $projectCount = 0;
            foreach ($data['projects'] as $project) {
                if ($projectCount < 5) {
                    $section->addText($project['title'], ['bold' => true, 'size' => 10, 'color' => '1a365d']);
                    $section->addText($project['description'], ['size' => 9]);
                    if (!empty($project['technologies'])) {
                        $section->addText('Technologies: ' . implode(', ', $project['technologies']), ['italic' => true, 'size' => 8, 'color' => '718096']);
                    }
                    $section->addTextBreak(1);
                    $projectCount++;
                }
            }
        }

        // Add page break for sidebar information
        $section->addPageBreak();

        // Sidebar information on second page
        $section->addText('Contact & Skills', ['bold' => true, 'size' => 12, 'color' => '1a365d']);
        $section->addTextBreak(1);

        // Contact
        $section->addText('Contact', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
        if ($data['phone']) $section->addText('Phone: ' . $data['phone'], ['size' => 9]);
        if ($data['email']) $section->addText('Email: ' . $data['email'], ['size' => 9]);
        if ($data['linkedin']) $section->addText('LinkedIn: ' . $data['linkedin'], ['size' => 9]);
        $section->addText('Location: ' . $data['location'], ['size' => 9]);
        $section->addTextBreak(1);

        // Skills (Max 6)
        if (!empty($data['skills'])) {
            $section->addText('Skills', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            $skillCount = 0;
            foreach ($data['skills'] as $category => $skillList) {
                foreach ($skillList as $skill) {
                    if ($skillCount < 6) {
                        $section->addText('• ' . $skill, ['size' => 9]);
                        $skillCount++;
                    }
                }
            }
            $section->addTextBreak(1);
        }

        // Technical Skills
        if (!empty($data['skills'])) {
            $section->addText('Technical Skills', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            foreach ($data['skills'] as $category => $skillList) {
                $section->addText(ucfirst($category) . ': ' . implode(', ', $skillList), ['size' => 9]);
            }
            $section->addTextBreak(1);
        }

        // Training
        if (!empty($data['trainings'])) {
            $section->addText('Training', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            foreach ($data['trainings'] as $training) {
                $section->addText('• ' . $training, ['size' => 9]);
            }
            $section->addTextBreak(1);
        }

        // Certifications
        if (!empty($data['certifications'])) {
            $section->addText('Certifications', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            foreach ($data['certifications'] as $cert) {
                $section->addText('• ' . $cert, ['size' => 9]);
            }
            $section->addTextBreak(1);
        }

        // Education
        if (!empty($data['education'])) {
            $section->addText('Education', ['bold' => true, 'size' => 11, 'color' => '1a365d']);
            foreach ($data['education'] as $edu) {
                $section->addText($edu['degree'], ['bold' => true, 'size' => 10, 'color' => '1a365d']);
                $section->addText($edu['institution'], ['italic' => true, 'size' => 9, 'color' => '4a5568']);
                $section->addText($edu['start_date'] . ' - ' . $edu['end_date'], ['size' => 8, 'color' => '718096']);
                $section->addTextBreak(1);
            }
        }
    }
}