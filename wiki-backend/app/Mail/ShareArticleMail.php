<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShareArticleMail extends Mailable
{
    use Queueable, SerializesModels;
    public $pdf;
    public $filename;
    /**
     * Create a new message instance.
     */
    public function __construct($pdf, $filename)
    {
        $this->pdf = $pdf;
        $this->filename = $filename;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'WikiMyHd a partagé un article avec vous',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'ShareArticleMailView',
            with: [$this->pdf, $this->filename]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf->output(), "$this->filename")
                ->withMime('application/pdf'),
        ];
    }
}
