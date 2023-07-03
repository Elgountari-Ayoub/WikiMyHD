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
    public $email;
    public $pass;
    public $post;
    public $spaces;
    public $loginUrl;
    public $logoUrl;
    /**
     * Create a new message instance.
     */
    public function __construct($userName, $email, $pass, $post, $spaces, $loginUrl, $logoUrl)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->pass = $pass;
        $this->post = $post;
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
            subject: 'Demande d\'adhésion à WikiMyHD approuvée',
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
