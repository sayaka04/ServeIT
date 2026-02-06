<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSenderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $mailMessage;
    public $subject;
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $message, array $details, string $view)
    {
        $this->subject = $subject;
        $this->mailMessage = $message;
        $this->details = $details;
        $this->view = $view;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
            with: [
                'subject' => $this->subject,
                'mailMessage' => $this->mailMessage,
                'details' => $this->details
            ],
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
