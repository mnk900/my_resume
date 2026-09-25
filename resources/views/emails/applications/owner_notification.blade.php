<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Public Application Received</title>
    <style>
        body { font-family: 'Segoe UI', Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333333; margin: 0; padding: 20px; }
        .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #059669, #10b981); color: #ffffff; padding: 25px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; font-weight: 700; }
        .content { padding: 30px 25px; line-height: 1.6; color: #334155; }
        .details-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px 20px; margin: 20px 0; }
        .details-box table { width: 100%; border-collapse: collapse; }
        .details-box td { padding: 6px 0; font-size: 14px; }
        .details-box td.label { font-weight: 600; color: #166534; width: 40%; }
        .btn { display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 30px; font-weight: 600; margin-top: 15px; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h2>New Public Job Application</h2>
        </div>
        <div class="content">
            <p style="font-size: 16px; font-weight: 600; color: #065f46;">Hello {{ $ownerName }},</p>
            <p>A new public candidate has just submitted an application for your position: <strong>{{ $opportunity->title }}</strong>.</p>
            
            <div class="details-box">
                <table>
                    <tr>
                        <td class="label">Candidate Name:</td>
                        <td>{{ $application->applicant_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email Address:</td>
                        <td>{{ $application->applicant_email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Contact Phone:</td>
                        <td>{{ $application->applicant_phone }}</td>
                    </tr>
                    <tr>
                        <td class="label">Currently Employed:</td>
                        <td>{{ $application->is_currently_employed ? 'Yes' : 'No' }}</td>
                    </tr>
                    @if($application->is_currently_employed)
                    <tr>
                        <td class="label">Current Role:</td>
                        <td>{{ $application->current_designation }} at {{ $application->current_organization_name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="text-align: center;">
                <a href="{{ route('applications.show', $application->id) }}" class="btn">View & Evaluate Candidate</a>
            </p>
        </div>
        <div class="footer">
            MyResume.cloud ATS Platform &bull; &copy; {{ date('Y') }} All rights reserved.
        </div>
    </div>
</body>
</html>
