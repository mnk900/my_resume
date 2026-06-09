<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $name }} - Professional CV</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }
        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: 'Times New Roman', Times, Georgia, serif;
            color: #111827;
            font-size: 8.5pt;
            line-height: 1.45;
        }
        * {
            box-sizing: border-box;
        }
        
        .container {
            width: 100%;
        }

        /* Clean Two-Column Table Layout */
        .cv-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            table-layout: fixed;
        }
        .cv-row {
            page-break-inside: avoid; /* Avoid breaking in the middle of a row */
        }
        .left-col {
            width: 35%;
            vertical-align: top;
            padding-right: 15px;
            border-right: 1.5px solid #a51c30; /* Harvard Crimson separator */
            padding-bottom: 12px;
            padding-top: 8px;
            word-wrap: break-word;
            word-break: break-word;
        }
        .right-col {
            width: 65%;
            vertical-align: top;
            padding-left: 18px;
            padding-bottom: 12px;
            padding-top: 8px;
            word-wrap: break-word;
            word-break: break-word;
        }

        /* Contact Details */
        .contact-info {
            font-size: 7.8pt;
            color: #374151;
            line-height: 1.4;
            margin-bottom: 15px;
        }
        .contact-item {
            margin-bottom: 5px;
            word-wrap: break-word;
        }
        .contact-icon {
            display: inline-block;
            width: 9pt;
            height: 9pt;
            margin-right: 5px;
            vertical-align: -1px;
            color: #a51c30;
        }
        
        /* Headers */
        h1.name {
            font-size: 18pt;
            font-weight: bold;
            color: #000000;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        h2.title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #a51c30;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .summary-text {
            font-size: 8.5pt;
            color: #374151;
            margin: 0;
            text-align: justify;
            line-height: 1.4;
        }
        h3 {
            font-size: 9.5pt;
            font-weight: bold;
            color: #a51c30;
            border-bottom: 1px solid #a51c30;
            padding-bottom: 2px;
            margin-top: 0;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            page-break-after: avoid;
        }

        /* Skill list */
        .skill-group {
            margin-bottom: 6px;
            font-size: 7.8pt;
            color: #374151;
            line-height: 1.35;
        }

        /* Bullet & Numbered lists */
        .certs-list {
            margin: 0;
            padding-left: 12px;
            font-size: 7.8pt;
            color: #374151;
        }
        .certs-list li {
            margin-bottom: 3px;
        }
        .bullet-list {
            margin: 0;
            padding-left: 12px;
            font-size: 7.8pt;
            color: #374151;
        }
        .bullet-list li {
            margin-bottom: 3px;
        }

        /* Education */
        .edu-item {
            margin-bottom: 8px;
            font-size: 7.8pt;
            color: #374151;
        }

        /* Experience Entry */
        .exp-item {
            margin-bottom: 8px;
        }
        .entry-header {
            width: 100%;
            margin-bottom: 2px;
        }
        .entry-left {
            float: left;
            width: 73%;
            font-size: 8.8pt;
            color: #000000;
            text-align: left;
            font-weight: bold;
        }
        .entry-right {
            float: right;
            width: 25%;
            font-size: 8pt;
            color: #4b5563;
            text-align: right;
            font-style: italic;
        }
        .exp-desc {
            font-size: 8.2pt;
            color: #1f2937;
            text-align: justify;
            line-height: 1.35;
            clear: both;
        }

        /* Additional Work History */
        .additional-exp {
            margin-top: 8px;
            background: #fbfbfb;
            padding: 6px 10px;
            border-radius: 4px;
            border-left: 2.5px solid #a51c30;
        }
        .sub-section-title {
            font-size: 8pt;
            font-weight: bold;
            color: #4b5563;
            margin-top: 0;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .compact-exp-item {
            width: 100%;
            margin-bottom: 3px;
        }
        .compact-exp-left {
            float: left;
            width: 73%;
            font-size: 8pt;
            color: #111827;
            text-align: left;
        }
        .compact-exp-right {
            float: right;
            width: 25%;
            font-size: 7.5pt;
            color: #4b5563;
            text-align: right;
            font-style: italic;
        }

        /* Project Entry */
        .project-item {
            margin-bottom: 8px;
        }
        .project-title {
            font-weight: bold;
            font-size: 8.8pt;
            color: #000000;
            margin-bottom: 2px;
            border-left: 2px solid #a51c30;
            padding-left: 6px;
        }
        .project-desc {
            font-size: 8.2pt;
            color: #1f2937;
            text-align: justify;
            line-height: 1.35;
        }
    </style>
</head>
<body>
    @php
        $leftItems = [];
        
        // Skills
        if(!empty($skills)) {
            $skillsHtml = '<h3>TECHNICAL SKILLS</h3>';
            foreach($skills as $cat => $list) {
                $skillsHtml .= '<div class="skill-group"><strong>' . e($cat) . ':</strong><br>' . e(implode(', ', $list)) . '</div>';
            }
            $leftItems[] = $skillsHtml;
        }
        
        // Certs
        $certsHtml = '<h3>CERTIFICATIONS</h3>';
        if(!empty($certifications)) {
            $certsHtml .= '<ol class="certs-list">';
            foreach($certifications as $cert) {
                $certsHtml .= '<li>' . e($cert) . '</li>';
            }
            $certsHtml .= '</ol>';
        } else {
            $certsHtml .= '<em>No certifications listed</em>';
        }
        $leftItems[] = $certsHtml;
        
        // Trainings
        $trainingsHtml = '<h3>TRAININGS</h3>';
        if(!empty($trainings)) {
            $trainingsHtml .= '<ul class="bullet-list">';
            foreach($trainings as $training) {
                $trainingsHtml .= '<li>' . e($training) . '</li>';
            }
            $trainingsHtml .= '</ul>';
        } else {
            $trainingsHtml .= '<em>No trainings listed</em>';
        }
        $leftItems[] = $trainingsHtml;
        
        // Education
        if(!empty($education)) {
            $eduHtml = '<h3>EDUCATION</h3>';
            foreach($education as $edu) {
                $eduHtml .= '<div class="edu-item"><strong>' . e($edu['degree']) . '</strong><br>' . e($edu['institution']) . '<br><em>' . e($edu['start_date']) . ' – ' . e($edu['end_date']) . '</em></div>';
            }
            $leftItems[] = $eduHtml;
        }

        $rightItems = [];
        
        // Experiences
        $detailedExp = array_filter($experience, function($exp) { return $exp['has_details']; });
        foreach($detailedExp as $exp) {
            $expHtml = '<div class="exp-item">
                <div class="entry-header">
                    <span class="entry-left">' . e($exp['position']) . ' &mdash; <em>' . e($exp['company']) . '</em></span>
                    <span class="entry-right">' . e($exp['start_date']) . ' – ' . e($exp['end_date']) . '</span>
                    <div style="clear: both;"></div>
                </div>
                <div class="exp-desc">' . nl2br(e($exp['description'])) . '</div>
            </div>';
            $rightItems[] = ['type' => 'experience', 'html' => $expHtml];
        }
        
        // Compact Experience
        $compactExp = array_filter($experience, function($exp) { return !$exp['has_details']; });
        if(!empty($compactExp)) {
            $compactHtml = '<div class="additional-exp">
                <div class="sub-section-title">Additional Work History</div>';
            foreach($compactExp as $exp) {
                $compactHtml .= '<div class="compact-exp-item">
                    <span class="compact-exp-left"><strong>' . e($exp['position']) . '</strong> at ' . e($exp['company']) . '</span>
                    <span class="compact-exp-right">' . e($exp['start_date']) . ' – ' . e($exp['end_date']) . '</span>
                    <div style="clear: both;"></div>
                </div>';
            }
            $compactHtml .= '</div>';
            $rightItems[] = ['type' => 'compact_experience', 'html' => $compactHtml];
        }
        
        // Projects
        if(!empty($projects)) {
            $projectsHtml = '';
            foreach($projects as $p) {
                $projectsHtml .= '<div class="project-item">
                    <div class="project-title">' . e($p['title']) . '</div>
                    <div class="project-desc">' . nl2br(e($p['description'])) . '</div>
                </div>';
            }
            $rightItems[] = ['type' => 'projects', 'html' => $projectsHtml];
        }
    @endphp

    <div class="container">
        <table class="cv-table">
            <!-- Row 1: Contact Info / Header (always first) -->
            <tr class="cv-row">
                <td class="left-col">
                    <div class="contact-info">
                        <h3>CONTACT INFO</h3>
                        @if($phone)
                            <div class="contact-item">
                                <svg class="contact-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                {{ $phone }}
                            </div>
                        @endif
                        @if($email)
                            <div class="contact-item">
                                <svg class="contact-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                {{ $email }}
                            </div>
                        @endif
                        @if($linkedin)
                            <div class="contact-item">
                                <svg class="contact-icon" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                {{ $linkedin }}
                            </div>
                        @endif
                        @if($website)
                            <div class="contact-item">
                                <svg class="contact-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                {{ $website }}
                            </div>
                        @endif
                        @if($location)
                            <div class="contact-item">
                                <svg class="contact-icon" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $location }}
                            </div>
                        @endif
                    </div>
                </td>
                <td class="right-col" style="vertical-align: middle;">
                    <h1 class="name">{{ $name }}</h1>
                    <h2 class="title">{{ $title }}</h2>
                    @if($summary)
                        <div class="summary-text"><em>{!! $summary !!}</em></div>
                    @endif
                </td>
            </tr>

            <!-- Subsequent Rows for content -->
            @php
                $maxRows = max(count($leftItems), count($rightItems));
            @endphp
            @for($i = 0; $i < $maxRows; $i++)
                @php
                    $leftHtml = $leftItems[$i] ?? '';
                    $rightItem = $rightItems[$i] ?? null;
                    
                    $rightHtml = '';
                    if ($rightItem) {
                        if ($rightItem['type'] === 'projects') {
                            $rightHtml .= '<h3>FLAGSHIP PROJECTS</h3>';
                        } elseif ($rightItem['type'] === 'experience' && $i === 0) {
                            $rightHtml .= '<h3>PROFESSIONAL EXPERIENCE</h3>';
                        } elseif ($rightItem['type'] === 'experience') {
                            $prevItem = $rightItems[$i - 1] ?? null;
                            if (!$prevItem || $prevItem['type'] !== 'experience') {
                                $rightHtml .= '<h3>PROFESSIONAL EXPERIENCE</h3>';
                            }
                        }
                        $rightHtml .= $rightItem['html'];
                    }
                    
                    // We remove the right border on the last row's left-col
                    $isLastRow = ($i === $maxRows - 1);
                    $leftColStyle = $isLastRow ? 'border-right: none; padding-bottom: 0;' : '';
                    $rightColStyle = $isLastRow ? 'padding-bottom: 0;' : '';
                @endphp
                @if(!empty($leftHtml) || !empty($rightHtml))
                    <tr class="cv-row">
                        <td class="left-col" style="{{ $leftColStyle }}">
                            {!! $leftHtml !!}
                        </td>
                        <td class="right-col" style="{{ $rightColStyle }}">
                            {!! $rightHtml !!}
                        </td>
                    </tr>
                @endif
            @endfor
        </table>
    </div>
</body>
</html>
