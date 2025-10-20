<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class EmailPengumuman extends Mailable
{
    use Queueable, SerializesModels;        

    public $user;
    public $emailSubject;
    public $emailMessage;
    public $attachmentPath;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $subject, string $message, ?string $attachmentPath = null)
    {
        $this->user = $user;
        $this->emailSubject = $subject;
        $this->emailMessage = $message;
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.email-pengumuman',
            with: [
                'user' => $this->user,
                'emailMessage' => $this->emailMessage,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->attachmentPath && Storage::disk('public')->exists($this->attachmentPath)) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->attachmentPath);
        }

        return $attachments;
    }
}
