<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $userName;
    public $spaces;
    public $loginUrl;
    public $logoUrl;
    /**
     * Create a new message instance.
     */
    public function __construct($userName, $spaces, $loginUrl, $logoUrl)
    {
        $this->userName = $userName;
        $this->spaces = $spaces;
        $this->loginUrl = $loginUrl;
        $this->logoUrl = $logoUrl;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Register Confirmed Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'RegisterConfirmedView',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
