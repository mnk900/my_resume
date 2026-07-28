<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ClientInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    protected $pdfData;
    protected $filename;

    /**
     * Create a new message instance.
     *
     * @param Invoice $invoice
     * @param string $pdfData
     */
    public function __construct(Invoice $invoice, $pdfData)
    {
        $this->invoice = $invoice;
        $this->pdfData = $pdfData;
        $this->filename = $invoice->invoice_no . '.pdf';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@myresume.cloud', 'My Resume Cloud'),
            subject: 'Invoice for Portfolio Website - ' . $this->invoice->invoice_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.invoice_email',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfData, $this->filename)
                ->withMime('application/pdf'),
        ];
    }
}
