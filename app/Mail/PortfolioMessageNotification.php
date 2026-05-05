<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PortfolioMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $visitorName;
    public $visitorEmail;
    public $visitorMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($visitorName, $visitorEmail, $visitorMessage)
    {
        $this->visitorName = $visitorName;
        $this->visitorEmail = $visitorEmail;
        $this->visitorMessage = $visitorMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Message from your Portfolio: ' . $this->visitorName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.portfolio.notification',
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
