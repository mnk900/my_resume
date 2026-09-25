<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $emailSubject }}</title>
    <style>
        body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f172a, #1e293b); color: #ffffff; padding: 25px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; font-weight: 700; }
        .header p { margin: 5px 0 0 0; opacity: 0.8; font-size: 14px; }
        .content { padding: 30px 25px; line-height: 1.6; color: #334155; }
        .job-tag { display: inline-block; background: #e2e8f0; color: #1e293b; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h2>{{ $emailSubject }}</h2>
            <p>{{ $opportunity->company->name ?? 'MyResume.cloud Career Portal' }}</p>
        </div>
        <div class="content">
            <div class="job-tag">Position: {{ $opportunity->title }}</div>

            @php
                $trimBody = trim($emailBody);
                $hasSalutation = (bool) preg_match('/^(dear|hi|hello|greetings)/i', $trimBody);
                $hasSignoff = (bool) preg_match('/(regards|sincerely|thanks|thank you|best regards|warm regards)[,\s\n]*[A-Za-z\s]*$/i', $trimBody);
            @endphp

            @if(!$hasSalutation)
                <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 15px;">Dear {{ $applicantName }},</p>
            @endif
            
            <div style="font-size: 15px; line-height: 1.7; color: #334155;">
                {!! nl2br(e($trimBody)) !!}
            </div>

            @if(!$hasSignoff)
                <p style="margin-top: 30px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 14px; padding-top: 15px;">
                    Warm regards,<br>
                    <strong>{{ $senderName }}</strong><br>
                    <span style="font-size: 13px;">{{ $opportunity->company->name ?? 'Recruitment Team' }}</span>
                </p>
            @endif
        </div>
        <div class="footer">
            Sent via MyResume.cloud Platform &bull; &copy; {{ date('Y') }} All rights reserved.
        </div>
    </div>
</body>
</html>
