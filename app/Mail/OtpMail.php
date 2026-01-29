<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $userName;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $userNameOrType, $type = null)
    {
        $this->otp = $otp;
        if ($type) {
            $this->userName = $userNameOrType;
            $this->type = $type;
        } else {
            $this->userName = $userNameOrType;
            $this->type = 'login';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'reset' 
            ? 'Kode OTP Reset Password - Gawean'
            : 'Kode OTP Verifikasi - Gawean';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
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
