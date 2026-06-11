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

        // 2. Technical Section: 5 categories max
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

        // 3. Flagship Projects: 4 projects max, title and description
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

        // 4. Certifications: 5 latest, name, issuer, year
        $certifications = [];
        if ($portfolio->certifications) {
            $certifications = $portfolio->certifications()
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($cert) {
                    return [
                        'name' => $cert->name,
                        'issuer' => $cert->issuer,
                        'year' => $cert->date ? \Carbon\Carbon::parse($cert->date)->format('Y') : '',
                    ];
                })
                ->toArray();
        }

        // 5. Trainings: 5 latest, title, institution, year
        $trainings = [];
        if ($portfolio->trainings) {
            $trainings = $portfolio->trainings()
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get()
                ->map(function($t) {
                    return [
                        'name' => $t->title,
                        'issuer' => $t->institution ?? '',
                        'year' => $t->date ? \Carbon\Carbon::parse($t->date)->format('Y') : '',
                    ];
                })
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
                    'description' => $exp->description,
                    'has_details' => ($index < 6)
                ];
                
                $experienceData[] = $item;
            }
        }

        // 8. Soft Skills / Achievements
        $softSkills = [];
        if ($portfolio->achievements) {
            $softSkills = $portfolio->achievements()
                ->pluck('title')
                ->toArray();
        }

        // 9. Website Address
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
            'soft_skills' => $softSkills,
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

        // Add font style rules
        $nameStyle = ['bold' => true, 'size' => 20, 'color' => '111827'];
        $titleStyle = ['bold' => true, 'size' => 12.5, 'color' => '1e3a8a'];
        $contactStyle = ['size' => 8.5, 'color' => '4b5563'];
        $summaryStyle = ['size' => 9.5, 'color' => '374151'];
        $sectionTitleStyle = ['bold' => true, 'size' => 11.5, 'color' => '1e3a8a'];
        
        $regularText = ['size' => 9, 'color' => '374151'];

        // Header Section
        $section->addText(strtoupper($data['name']), $nameStyle);
        $section->addText($data['title'], $titleStyle);
        
        if (!empty($data['summary'])) {
            $section->addText(strip_tags($data['summary']), $summaryStyle);
            $section->addTextBreak(1);
        }

        // Contact info inline
        $contactItems = [];
        if (!empty($data['location'])) $contactItems[] = $data['location'];
        if (!empty($data['phone'])) $contactItems[] = $data['phone'];
        if (!empty($data['email'])) $contactItems[] = $data['email'];
        if (!empty($data['website'])) $contactItems[] = $data['website'];
        if (!empty($data['linkedin'])) $contactItems[] = $data['linkedin'];
        $section->addText(implode('  |  ', $contactItems), $contactStyle);

        // Header divider line (horizontal rule style)
        $section->addTextBreak(1);
        $section->addText('_________________________________________________________________________________', ['color' => 'dddddd']);
        $section->addTextBreak(1);

        // Technical Skills Section
        if (!empty($data['skills'])) {
            $section->addText('TECHNICAL SKILLS', $sectionTitleStyle);
            foreach ($data['skills'] as $cat => $list) {
                $textRun = $section->addTextRun();
                $textRun->addText($cat . ': ', ['bold' => true, 'size' => 9]);
                $textRun->addText(implode(', ', $list), $regularText);
            }
            $section->addTextBreak(1);
            $section->addText('_________________________________________________________________________________', ['color' => 'dddddd']);
            $section->addTextBreak(1);
        }

        // Work Experience Section
        if (!empty($data['experience'])) {
            $section->addText('WORK EXPERIENCE', $sectionTitleStyle);
            foreach ($data['experience'] as $job) {
                // Meta Line: Company - Position (Dates)
                $textRun = $section->addTextRun();
                $textRun->addText($job['company'] . ' – ', ['bold' => true, 'size' => 9.5]);
                $textRun->addText($job['position'], ['italic' => true, 'size' => 9.5]);
                $textRun->addText(' (' . $job['start_date'] . ' – ' . $job['end_date'] . ')', ['size' => 9.5, 'color' => '4b5563']);

                if (!empty($job['description'])) {
                    // Extract bullets
                    $bullets = [];
                    $cleanDesc = $job['description'];
                    if (str_contains($cleanDesc, '<ul>') || str_contains($cleanDesc, '<li>')) {
                        preg_match_all('/<li>(.*?)<\/li>/is', $cleanDesc, $matches);
                        if (!empty($matches[1])) {
                            $bullets = array_map(function($b) { return trim(strip_tags($b)); }, $matches[1]);
                        }
                    } else {
                        $lines = array_filter(array_map('trim', explode("\n", strip_tags($cleanDesc))));
                        $hasBullets = false;
                        foreach ($lines as $line) {
                            if (preg_match('/^[\s•\-\*]/u', $line)) {
                                $hasBullets = true;
                                break;
                            }
                        }
                        if ($hasBullets) {
                            foreach ($lines as $line) {
                                $cleanedLine = preg_replace('/^[\s•\-\*]+\s*/u', '', $line);
                                if (!empty($cleanedLine)) {
                                    $bullets[] = $cleanedLine;
                                }
                            }
                        }
                    }
                    $bullets = array_slice($bullets, 0, 5); // Limit to 4-5 bullets

                    if (!empty($bullets)) {
                        foreach ($bullets as $bullet) {
                            $section->addListItem($bullet, 0, $regularText);
                        }
                    } else {
                        $section->addText(strip_tags($cleanDesc), $regularText);
                    }
                }
                $section->addTextBreak(1);
            }
        }

        // Flagship Projects Section
        if (!empty($data['projects'])) {
            $section->addText('FLAGSHIP PROJECTS', $sectionTitleStyle);
            foreach (array_slice($data['projects'], 0, 4) as $p) {
                $section->addText($p['title'], ['bold' => true, 'size' => 9.5]);
                $section->addText(strip_tags($p['description']), $regularText);
                $section->addTextBreak(1);
            }
        }

        // Training and Certifications Section
        if (!empty($data['certifications']) || !empty($data['trainings'])) {
            $section->addText('TRAINING & CERTIFICATIONS', $sectionTitleStyle);
            foreach ($data['certifications'] as $cert) {
                $yearPart = !empty($cert['year']) ? ' (' . $cert['year'] . ')' : '';
                $section->addListItem($cert['name'] . ' – ' . $cert['issuer'] . $yearPart, 0, $regularText);
            }
            foreach ($data['trainings'] as $t) {
                $yearPart = !empty($t['year']) ? ' (' . $t['year'] . ')' : '';
                $section->addListItem($t['name'] . ' – ' . $t['issuer'] . $yearPart, 0, $regularText);
            }
            $section->addTextBreak(1);
        }

        // Soft Skills and Achievements Section
        if (!empty($data['soft_skills'])) {
            $section->addText('SOFT SKILLS & ACHIEVEMENTS', $sectionTitleStyle);
            foreach ($data['soft_skills'] as $skill) {
                $section->addListItem($skill, 0, $regularText);
            }
            $section->addTextBreak(1);
        }

        // Education Section
        if (!empty($data['education'])) {
            $section->addText('EDUCATION', $sectionTitleStyle);
            foreach ($data['education'] as $edu) {
                $section->addText($edu['degree'] . ' – ' . $edu['institution'] . ' (' . $edu['end_date'] . ')', $regularText);
            }
        }
    }
}