<?php

namespace App\Mail;

use App\Models\School;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $school;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, School $school, $password)
    {
        $this->user = $user;
        $this->school = $school;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ElafSchool - Your Registration is Complete',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.school-welcome',
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
