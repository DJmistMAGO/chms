<?php

namespace App\Mail;

use App\Models\IdVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IdVerificationStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public IdVerification $verification;

    public function __construct(IdVerification $verification)
    {
        $this->verification = $verification;
    }

    public function build()
    {
        $subject = $this->verification->valid_id_status === 'verified'
            ? 'Your ID has been verified'
            : 'Your ID submission needs attention';

        return $this->subject($subject)
            ->view('emails.id-verification-status');
    }
}
