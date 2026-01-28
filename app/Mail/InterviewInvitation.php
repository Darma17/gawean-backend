<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\AppliedJob;
use Illuminate\Queue\SerializesModels;


class InterviewInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $appliedJob;

    /**
     * Create a new message instance.
     */
    public function __construct(AppliedJob $appliedJob)
    {
        $this->appliedJob = $appliedJob;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Interview - ' . $this->appliedJob->job->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.interview_invitation',
            with: [
                'appliedJob' => $this->appliedJob,
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
