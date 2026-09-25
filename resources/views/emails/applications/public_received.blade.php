<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Received - {{ $opportunity->title }}</title>
    <style>
        body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: #ffffff; padding: 30px 25px; text-align: center; }
        .header h2 { margin: 0 0 8px 0; font-size: 24px; font-weight: 700; }
        .header p { margin: 0; opacity: 0.9; font-size: 15px; }
        .content { padding: 30px 25px; line-height: 1.6; color: #4b5563; }
        .job-box { background: #f8fafc; border-left: 4px solid #3b82f6; border-radius: 6px; padding: 15px 20px; margin: 20px 0; }
        .job-box h4 { margin: 0 0 5px 0; color: #1e293b; font-size: 17px; }
        .job-box p { margin: 0; font-size: 14px; color: #64748b; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 30px; font-weight: 600; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h2>Application Confirmation</h2>
            <p>MyResume.cloud Career Platform</p>
        </div>
        <div class="content">
            <p style="font-size: 16px; font-weight: 600; color: #1e293b;">Dear {{ $application->applicant_name }},</p>
            <p>We have received your application for the position of <strong>{{ $opportunity->title }}</strong>@if($opportunity->company) at <strong>{{ $opportunity->company->name }}</strong>@endif.</p>
            
            <div class="job-box">
                <h4>{{ $opportunity->title }}</h4>
                <p>
                    @if($opportunity->company) {{ $opportunity->company->name }} &bull; @endif
                    {{ ucfirst($opportunity->location_type) }} ({{ $opportunity->city ?? 'Global' }}) &bull;
                    {{ ucfirst($opportunity->employment_type) }}
                </p>
            </div>

            <p>Our hiring team will review your profile, qualifications, and attached CV/resume. We will get back to you regarding the next steps in our recruitment process.</p>
            
            <p>Thank you for your interest and for taking the time to apply!</p>
            
            <p style="margin-top: 25px; color: #64748b; font-size: 14px;">
                Best regards,<br>
                <strong>Hiring Team &bull; {{ $opportunity->company->name ?? 'MyResume.cloud' }}</strong>
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MyResume.cloud. All rights reserved.
        </div>
    </div>
</body>
</html>
