<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $messageContent;

    /**
     * Create a new message instance.
     *
     * @param string $subjectLine
     * @param string $messageContent
     */
    public function __construct($subjectLine, $messageContent)
    {
        $this->subjectLine = $subjectLine;
        $this->messageContent = $messageContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@myresume.cloud', 'My Resume Cloud'),
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.user_email',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
