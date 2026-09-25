<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public Opportunity $opportunity;
    public string $emailSubject;
    public string $emailBody;
    public string $applicantName;
    public string $senderName;

    public function __construct(JobApplication $application, string $subject, string $body, string $senderName)
    {
        $this->application = $application;
        $this->opportunity = $application->opportunity;
        $this->emailSubject = $subject;
        $this->emailBody = $body;
        $this->applicantName = $application->candidate_name;
        $this->senderName = $senderName;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.applications.status_email');
    }
}
