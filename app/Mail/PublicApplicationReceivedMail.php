<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublicApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public Opportunity $opportunity;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
        $this->opportunity = $application->opportunity;
    }

    public function build()
    {
        $companyName = $this->opportunity->company ? $this->opportunity->company->name : 'MyResume.cloud';

        return $this->subject("Application Received: " . $this->opportunity->title . " - " . $companyName)
                    ->view('emails.applications.public_received');
    }
}
