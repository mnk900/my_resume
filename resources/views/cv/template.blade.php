<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $name }} - CV</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        html, body { margin: 0; padding: 0; }
        * { box-sizing: border-box; }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            background: #1a365d;
            color: #fff;
            padding: 12px;
            font-size: 7.8pt;
            line-height: 1.3;
            height: 100%;
            box-sizing: border-box;
            z-index: 10;
        }

        .main-content {
            margin-left: 230px;
            padding: 12px 12px 12px 24px;
            font-size: 8.8pt;
            line-height: 1.4;
            background: #f5f5f5;
            page-break-inside: auto;
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .break-avoid { page-break-inside: avoid; }
        .section-title { page-break-inside: avoid; }

        .sidebar .section-title {
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.3);
            font-size: 8pt;
            margin-top: 16px;
            margin-bottom: 8px;
        }

        .name   { font-size: 14pt; font-weight: bold; color: #1a365d; margin-bottom: 4px; }
        .title  { font-size: 8pt; color: #4a5568; margin-bottom: 8px; }
        .summary{ background:#f5f5f5; padding:8px; border-left:3px solid #1a365d; margin-bottom:8px; font-size:7pt; line-height:1.4; color:#4a5568; text-align:justify; word-wrap:break-word; }
        .section-title{ font-size:7pt; font-weight:bold; color:#1a365d; border-bottom:1px solid #cbd5e1; padding-bottom:2px; margin-top:10px; margin-bottom:8px; text-transform:uppercase; letter-spacing:.6px; page-break-inside: avoid; }
        .contact-item{ margin-bottom:6px; padding-bottom:5px; border-bottom:1px solid rgba(255,255,255,.12); }
        .contact-label{ font-size:6pt; font-weight:700; color:#b0c4de; text-transform:uppercase; display:block; margin-bottom:1px; }
        .contact-value{ font-size:7pt; color:#edf2f7; word-wrap:break-word; overflow-wrap:break-word; }
        .skill-category{ margin-bottom:12px; }
        .skill-cat-title{ font-weight:bold; font-size:6pt; color:#b0c4de; margin-bottom:2px; text-transform:uppercase; }
        .skill-item{ font-size:6pt; margin-bottom:2px; word-wrap:break-word; }
        .item{ margin-bottom:16px; }
        .item-title{ font-weight:bold; font-size:8pt; color:#1a365d; margin-bottom:2px; }
        .item-subtitle{ font-size:7pt; color:#2d3748; margin-bottom:2px; word-wrap:break-word; }
        .item-meta{ font-size:6pt; color:#718096; margin-bottom:4px; }
        .item-description{ font-size:7pt; color:#4a5568; line-height:1.45; text-align:justify; word-wrap:break-word; overflow-wrap:break-word; }
        .project{ background:#f8fafc; border-left:4px solid #1a365d; padding:10px 12px; margin-bottom:12px; }
        .project-title{ font-weight:bold; font-size:8pt; color:#1a365d; margin-bottom:3px; }
        .project-desc{ font-size:7pt; color:#4a5568; line-height:1.45; text-align:justify; word-wrap:break-word; overflow-wrap:break-word; }
        .edu-item{ margin-bottom:12px; }
        .edu-degree{ font-weight:bold; font-size:7pt; margin-bottom:2px; }
        .edu-institution{ font-size:6pt; font-style:italic; color:#b0c4de; }
        .edu-year{ font-size:6pt; color:rgba(255,255,255,.75); }
    </style>
</head>
<body>
<div class="cv-page">
  <div class="sidebar">
    <div class="section-title" style="margin-top:0;">Contact</div>
    @if($phone)
        <div class="contact-item"><span class="contact-label">Phone</span><div class="contact-value">{{ $phone }}</div></div>
    @endif
    @if($email)
        <div class="contact-item"><span class="contact-label">Email</span><div class="contact-value">{{ $email }}</div></div>
    @endif
    @if($linkedin)
        <div class="contact-item"><span class="contact-label">LinkedIn</span><div class="contact-value">{{ $linkedin }}</div></div>
    @endif
    @if($location)
        <div class="contact-item" style="border-bottom:none;margin:0;padding:0;"><span class="contact-label">Location</span><div class="contact-value">{{ $location }}</div></div>
    @endif
    @if(!empty($skills))
        <div class="section-title">Technical Skills</div>
        @foreach($skills as $cat => $list)
            <div class="skill-category">
                <div class="skill-cat-title">{{ $cat }}</div>
                @foreach($list as $s)
                    <div class="skill-item">• {{ $s }}</div>
                @endforeach
            </div>
        @endforeach
    @endif
    @if(!empty($certifications))
        <div class="section-title">Certifications</div>
        @foreach($certifications as $cert)
            <div class="skill-item">• {{ $cert }}</div>
        @endforeach
    @endif
    @if(!empty($trainings))
        <div class="section-title">Training</div>
        @foreach($trainings as $train)
            <div class="skill-item">• {{ $train }}</div>
        @endforeach
    @endif
    @if(!empty($education))
        <div class="section-title">Education</div>
        @foreach($education as $edu)
            <div class="edu-item">
                <div class="edu-degree">{{ $edu['degree'] }}</div>
                <div class="edu-institution">{{ $edu['institution'] }}</div>
                <div class="edu-year">{{ $edu['start_date'] }} - {{ $edu['end_date'] }}</div>
            </div>
        @endforeach
    @endif
  </div>
  <div class="main-content">
    <div class="name">{{ strtoupper($name) }}</div>
    <div class="title">{{ $title }}</div>
    @if($summary)
        <div class="summary">{{ $summary }}</div>
    @endif
    @if(!empty($experience))
        <div class="section-title" style="margin-top:10px;">Professional Experience</div>
        @foreach($experience as $exp)
            <div class="item">
                <div class="item-title">{{ $exp['position'] }}</div>
                <div class="item-subtitle">{{ $exp['company'] }}</div>
                <div class="item-meta">{{ $exp['start_date'] }} - {{ $exp['end_date'] }}</div>
                <div class="item-description">{!! nl2br(e($exp['description'])) !!}</div>
            </div>
        @endforeach
    @endif
    @if(!empty($projects))
        <div class="section-title">Flagship Projects</div>
        @foreach(array_slice($projects,0,5) as $p)
            <div class="project">
                <div class="project-title">{{ $p['title'] }}</div>
                <div class="project-desc">{!! nl2br(e($p['description'])) !!}</div>
            </div>
        @endforeach
    @endif
  </div>
</div>
</body>
</html>
