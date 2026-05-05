<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class PortfolioMessageReply extends Mailable
{
    use Queueable, SerializesModels;

    public $replyMessage;
    public $senderName;
    public $originalMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($replyMessage, $senderName, $originalMessage)
    {
        $this->replyMessage = $replyMessage;
        $this->senderName = $senderName;
        $this->originalMessage = $originalMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), $this->senderName),
            subject: 'Re: Your message to ' . $this->senderName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.portfolio.reply',
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
