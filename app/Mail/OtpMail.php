<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $otp,
        public string $type = 'reset_password'
    ) {}

    public function build()
    {
        $subject = $this->type === 'register'
            ? 'Verifikasi Email - PRJ'
            : 'Reset Password - PRJ';

        return $this->subject($subject)
            ->view('emails.otp')
            ->with(['type' => $this->type]);
    }
}
