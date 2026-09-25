<?php

namespace App\Mail;

use App\Models\JobApplication;
use App\Models\Opportunity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPublicApplicationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;
    public Opportunity $opportunity;
    public string $ownerName;

    public function __construct(JobApplication $application, string $ownerName)
    {
        $this->application = $application;
        $this->opportunity = $application->opportunity;
        $this->ownerName = $ownerName;
    }

    public function build()
    {
        return $this->subject("New Public Application: " . $this->application->applicant_name . " applied for " . $this->opportunity->title)
                    ->view('emails.applications.owner_notification');
    }
}
