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
        $filename = $user->name . '_CV.pdf';

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

        // Configure PhpWord to use PclZip in environments where ZipArchive extension is missing
        \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

        $phpWord = new PhpWord();
        $this->createWordDocument($phpWord, $data);

        $filename = $user->name . '_CV.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'cv');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public function prepareCVData($user, $portfolio)
    {
        // 1. Profile image path resolution
        $profileImagePath = null;
        $profileImageBase64 = null;
        if ($portfolio->profile_image) {
            if (Storage::disk('public')->exists($portfolio->profile_image)) {
                $profileImagePath = storage_path('app/public/' . $portfolio->profile_image);
                if (file_exists($profileImagePath)) {
                    $type = pathinfo($profileImagePath, PATHINFO_EXTENSION);
                    $imgData = file_get_contents($profileImagePath);
                    $profileImageBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
                }
            }
        }

        // 2. Technical Section: 5 categories only
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
            $skillsData = array_slice($skillsData, 0, 5, true);
        }

        // 3. Flagship Projects: 4 projects, title and description only
        $projectsData = [];
        if ($portfolio->projects) {
            $projects = $portfolio->projects()
                ->orderBy('id', 'desc')
                ->limit(4)
                ->get();
            foreach ($projects as $project) {
                $projectsData[] = [
                    'title' => $project->title,
                    'description' => $project->description,
                ];
            }
        }

        // 4. Certifications: 5 latest, name only
        $certifications = [];
        if ($portfolio->certifications) {
            $certifications = $portfolio->certifications()
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->pluck('name')
                ->toArray();
        }

        // 5. Trainings: 5 latest, title only
        $trainings = [];
        if ($portfolio->trainings) {
            $trainings = $portfolio->trainings()
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->pluck('title')
                ->toArray();
        }

        // 6. Education: 5 latest, degree, institution, dates
        $education = [];
        if ($portfolio->education) {
            $education = $portfolio->education()
                ->orderBy('start_date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($edu) {
                    return [
                        'degree' => $edu->degree,
                        'institution' => $edu->institution,
                        'start_date' => $edu->start_date ? $edu->start_date->format('Y') : '',
                        'end_date' => $edu->end_date ? $edu->end_date->format('Y') : 'Present',
                    ];
                })
                ->toArray();
        }

        // 7. Experiences: 6 latest detailed, others with less details
        $experienceData = [];
        if ($portfolio->experiences) {
            $experiences = $portfolio->experiences()
                ->orderBy('start_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
            
            foreach ($experiences as $index => $exp) {
                $item = [
                    'position' => $exp->position,
                    'company' => $exp->company,
                    'start_date' => $exp->start_date ? $exp->start_date->format('M Y') : '',
                    'end_date' => $exp->end_date ? $exp->end_date->format('M Y') : 'Present',
                ];
                
                if ($index < 6) {
                    $item['description'] = $exp->description;
                    $item['has_details'] = true;
                } else {
                    $item['description'] = null;
                    $item['has_details'] = false;
                }
                
                $experienceData[] = $item;
            }
        }

        // 8. Website Address
        $website = url('/' . $user->username);

        return [
            'name' => $user->name,
            'title' => $portfolio->position ?? 'Professional',
            'profile_image' => $profileImagePath,
            'profile_image_base64' => $profileImageBase64,
            'email' => $portfolio->show_email ? $user->email : null,
            'phone' => $portfolio->show_phone ? $portfolio->contact_number : null,
            'linkedin' => $portfolio->show_linkedin ? $portfolio->linkedin_url : null,
            'website' => $website,
            'location' => ($portfolio->city ?? 'Gilgit-Baltistan') . ', ' . ($portfolio->country ?? 'Pakistan'),
            'summary' => $portfolio->description,
            'experience' => $experienceData,
            'education' => $education,
            'skills' => $skillsData,
            'certifications' => $certifications,
            'trainings' => $trainings,
            'projects' => $projectsData,
        ];
    }

    private function createWordDocument($phpWord, $data)
    {
        // Set margins (0.5 inch / 720 twips)
        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720
        ]);

        // Add 2-column table grid layout
        $table = $section->addTable([
            'borderColor' => 'ffffff',
            'borderSize' => 0,
            'cellMarginLeft' => 100,
            'cellMarginRight' => 100,
            'cellMarginTop' => 100,
            'cellMarginBottom' => 100,
        ]);

        $table->addRow();
        
        // Left Column (35% Width = 3660 twips)
        $leftCell = $table->addCell(3660, [
            'valign' => 'top',
            'bgColor' => 'F8FAFC' // Light tint background
        ]);
        
        // Right Column (65% Width = 6800 twips)
        $rightCell = $table->addCell(6800, [
            'valign' => 'top',
            'bgColor' => 'FFFFFF'
        ]);

        // --- LEFT COLUMN CONTENT ---

        // Contact Info
        $leftCell->addText('CONTACT INFO', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
        if ($data['phone']) $leftCell->addText('Phone: ' . $data['phone'], ['size' => 8.5]);
        if ($data['email']) $leftCell->addText('Email: ' . $data['email'], ['size' => 8.5]);
        if ($data['linkedin']) $leftCell->addText('LinkedIn: ' . $data['linkedin'], ['size' => 8.5]);
        if ($data['website']) $leftCell->addText('Website: ' . $data['website'], ['size' => 8.5]);
        if ($data['location']) $leftCell->addText('Location: ' . $data['location'], ['size' => 8.5]);
        $leftCell->addTextBreak(1);

        // Technical Skills
        if (!empty($data['skills'])) {
            $leftCell->addText('TECHNICAL SKILLS', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            foreach ($data['skills'] as $category => $skillList) {
                $leftCell->addText('• ' . $category . ':', ['bold' => true, 'size' => 8.5]);
                $leftCell->addText(implode(', ', $skillList), ['size' => 8]);
            }
            $leftCell->addTextBreak(1);
        }

        // Certifications
        if (!empty($data['certifications'])) {
            $leftCell->addText('CERTIFICATIONS', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            $idx = 1;
            foreach ($data['certifications'] as $cert) {
                $leftCell->addText($idx . '. ' . $cert, ['size' => 8.5]);
                $idx++;
            }
            $leftCell->addTextBreak(1);
        }

        // Trainings
        if (!empty($data['trainings'])) {
            $leftCell->addText('TRAININGS', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            foreach ($data['trainings'] as $training) {
                $leftCell->addText('• ' . $training, ['size' => 8.5]);
            }
            $leftCell->addTextBreak(1);
        }

        // Education
        if (!empty($data['education'])) {
            $leftCell->addText('EDUCATION', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            foreach ($data['education'] as $edu) {
                $leftCell->addText($edu['degree'], ['bold' => true, 'size' => 8.5]);
                $leftCell->addText($edu['institution'], ['italic' => true, 'size' => 8]);
                $leftCell->addText($edu['start_date'] . ' – ' . $edu['end_date'], ['size' => 7.5, 'color' => '4b5563']);
                $leftCell->addTextBreak(1);
            }
        }


        // --- RIGHT COLUMN CONTENT ---

        // Name, Title & Tagline
        $rightCell->addText(strtoupper($data['name']), ['bold' => true, 'size' => 18, 'color' => '000000']);
        $rightCell->addText($data['title'], ['bold' => true, 'size' => 11, 'color' => 'a51c30']);
        if ($data['summary']) {
            $rightCell->addText($data['summary'], ['italic' => true, 'size' => 8.5, 'color' => '374151']);
        }
        $rightCell->addTextBreak(1);

        // Professional Experience
        if (!empty($data['experience'])) {
            $rightCell->addText('PROFESSIONAL EXPERIENCE', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            
            // Detailed
            foreach (array_filter($data['experience'], function($e) { return $e['has_details']; }) as $exp) {
                $rightCell->addText($exp['position'] . ' — ' . $exp['company'], ['bold' => true, 'size' => 9]);
                $rightCell->addText($exp['start_date'] . ' – ' . $exp['end_date'], ['italic' => true, 'size' => 8, 'color' => '4b5563']);
                $rightCell->addText($exp['description'], ['size' => 8.5]);
                $rightCell->addTextBreak(1);
            }

            // Compact
            $compactExp = array_filter($data['experience'], function($e) { return !$e['has_details']; });
            if (!empty($compactExp)) {
                $rightCell->addText('Additional Work History', ['bold' => true, 'size' => 8.5, 'color' => '4b5563']);
                foreach ($compactExp as $exp) {
                    $rightCell->addText('• ' . $exp['position'] . ' at ' . $exp['company'] . ' (' . $exp['start_date'] . ' – ' . $exp['end_date'] . ')', ['size' => 8]);
                }
                $rightCell->addTextBreak(1);
            }
        }

        // Flagship Projects
        if (!empty($data['projects'])) {
            $rightCell->addText('FLAGSHIP PROJECTS', ['bold' => true, 'size' => 10, 'color' => 'a51c30']);
            foreach ($data['projects'] as $project) {
                $rightCell->addText($project['title'], ['bold' => true, 'size' => 9]);
                $rightCell->addText($project['description'], ['size' => 8.5]);
                $rightCell->addTextBreak(1);
            }
        }
    }
}