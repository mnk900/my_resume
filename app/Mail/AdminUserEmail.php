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
    public $recipientName;

    /**
     * Create a new message instance.
     *
     * @param string $subjectLine
     * @param string $messageContent
     * @param string|null $recipientName
     */
    public function __construct($subjectLine, $messageContent, $recipientName = null)
    {
        $this->subjectLine = $subjectLine;
        $this->messageContent = $messageContent;
        $this->recipientName = $recipientName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address', 'info@myresume.cloud'), config('mail.from.name', 'My Resume Cloud')),
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
