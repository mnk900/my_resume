<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $name }} - Professional CV</title>
    <style>
        /* Margins set uniformly to maximize space and avoid overflow */
        @page {
            size: A4 portrait;
            margin: 0.5in;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #111827;
            font-size: 9.5pt;
            line-height: 1.45;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        * {
            box-sizing: border-box;
        }

        .clear {
            clear: both;
        }

        /* Section 1: Top Header Style */
        .header-section {
            border-bottom: 1px solid #ddd;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        
        .header-section h1 {
            font-size: 22pt;
            font-weight: bold;
            color: #111827;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .header-section h2 {
            font-size: 12.5pt;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }
        
        .profile-statement {
            font-size: 9.5pt;
            color: #374151;
            margin: 0 0 10px 0;
            text-align: justify;
        }
        
        .contact-details {
            font-size: 8.5pt;
            color: #4b5563;
            line-height: 1.4;
            font-weight: normal;
        }

        /* General Section Heading & Container styles */
        .section-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1.5px solid #1e3a8a;
            padding-bottom: 3px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            page-break-after: avoid;
        }

        .cv-section {
            page-break-inside: avoid;
            margin-bottom: 16px;
        }

        /* Section 2: Technical Skills styles */
        .skills-section {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        
        .skill-category {
            margin-bottom: 5px;
            font-size: 9pt;
        }
        
        .skill-name {
            font-weight: bold;
            color: #111827;
        }

        /* Section 3: Work Experience styles */
        .job-item {
            page-break-inside: avoid;
            margin-bottom: 12px;
        }
        
        .meta-line {
            font-size: 9.5pt;
            margin-bottom: 3px;
            width: 100%;
        }
        
        .meta-left {
            font-weight: bold;
            color: #111827;
        }
        
        .meta-role {
            font-style: italic;
            color: #4b5563;
            font-weight: normal;
        }
        
        .meta-date {
            color: #4b5563;
            float: right;
            font-weight: normal;
        }

        /* Lists */
        ul {
            margin: 0;
            padding-left: 18px;
            font-size: 9pt;
            color: #374151;
        }
        
        ul li {
            margin-bottom: 3px;
            text-align: justify;
        }

        /* Section 4: Flagship Projects styles */
        .project-item {
            page-break-inside: avoid;
            margin-bottom: 10px;
        }
        
        .project-title {
            font-weight: bold;
            font-size: 9.5pt;
            color: #111827;
            margin-bottom: 2px;
        }
        
        .project-desc {
            font-size: 9pt;
            color: #374151;
            text-align: justify;
            margin: 0;
        }

        .bullet-item {
            font-size: 9pt;
            color: #374151;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

    <!-- Section 1: Top Header -->
    <div class="header-section">
        <h1>{{ $name }}</h1>
        <h2>{{ $title }}</h2>
        @if(!empty($summary))
            <p class="profile-statement">{{ strip_tags($summary) }}</p>
        @endif
        <div class="contact-details">
            @php
                $contactItems = [];
                if (!empty($location)) $contactItems[] = $location;
                if (!empty($phone)) $contactItems[] = $phone;
                if (!empty($email)) $contactItems[] = $email;
                if (!empty($website)) $contactItems[] = $website;
                if (!empty($linkedin)) $contactItems[] = $linkedin;
            @endphp
            {{ implode('  |  ', $contactItems) }}
        </div>
    </div>

    <!-- Section 2: Technical Skills -->
    @if(!empty($skills))
        <div class="skills-section">
            <div class="section-title">Technical Skills</div>
            @foreach($skills as $cat => $list)
                <div class="skill-category">
                    <span class="skill-name">{{ $cat }}:</span> {{ implode(', ', $list) }}
                </div>
            @endforeach
        </div>
    @endif

    <!-- Section 3: Work Experience -->
    @if(!empty($experience))
        <div class="cv-section">
            <div class="section-title">Work Experience</div>
            @foreach($experience as $job)
                <div class="job-item">
                    <div class="meta-line">
                        <span class="meta-left">
                            <strong>{{ $job['company'] }}</strong> &ndash; <span class="meta-role">{{ $job['position'] }}</span>
                        </span>
                        <span class="meta-date">{{ $job['start_date'] }} – {{ $job['end_date'] }}</span>
                        <div class="clear"></div>
                    </div>
                    @if(!empty($job['description']))
                        @php
                            $bullets = [];
                            $cleanDesc = $job['description'];
                            if (str_contains($cleanDesc, '<ul>') || str_contains($cleanDesc, '<li>')) {
                                preg_match_all('/<li>(.*?)<\/li>/is', $cleanDesc, $matches);
                                if (!empty($matches[1])) {
                                    $bullets = array_map(function($b) { return trim(strip_tags($b)); }, $matches[1]);
                                }
                            } else {
                                // Only extract as bullets if there are explicit bullet indicators in text lines
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
                        @endphp
                        @if(!empty($bullets))
                            <ul>
                                @foreach($bullets as $bullet)
                                    <li>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p style="margin: 3px 0 0 0; font-size: 9pt; color: #374151; text-align: justify;">{!! nl2br(e(strip_tags($cleanDesc))) !!}</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Section 4: Flagship Projects -->
    @if(!empty($projects))
        <div class="cv-section">
            <div class="section-title">Flagship Projects</div>
            @foreach(array_slice($projects, 0, 4) as $p)
                <div class="project-item">
                    <div class="project-title">{{ $p['title'] }}</div>
                    <p class="project-desc">{{ strip_tags($p['description']) }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Section 5: Training and Certifications -->
    @if(!empty($certifications) || !empty($trainings))
        <div class="cv-section">
            <div class="section-title">Training & Certifications</div>
            <ul>
                @foreach($certifications as $cert)
                    <li>
                        <strong>{{ $cert['name'] }}</strong> &ndash; {{ $cert['issuer'] }}@if(!empty($cert['year'])) ({{ $cert['year'] }})@endif
                    </li>
                @endforeach
                @foreach($trainings as $t)
                    <li>
                        <strong>{{ $t['name'] }}</strong> &ndash; {{ $t['issuer'] }}@if(!empty($t['year'])) ({{ $t['year'] }})@endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Section 6: Soft Skills and Achievements -->
    @if(!empty($soft_skills))
        <div class="cv-section">
            <div class="section-title">Soft Skills & Achievements</div>
            <ul>
                @foreach($soft_skills as $skill)
                    <li>{{ $skill }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Section 7: Education -->
    @if(!empty($education))
        <div class="cv-section">
            <div class="section-title">Education</div>
            @foreach($education as $edu)
                <div class="bullet-item">
                    <strong>{{ $edu['degree'] }}</strong> &ndash; {{ $edu['institution'] }} ({{ $edu['end_date'] }})
                </div>
            @endforeach
        </div>
    @endif

</body>
</html>
