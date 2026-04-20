<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationLink;

    public function __construct(User $user)
    {
        $baseUrl = url('/');
        $this->user = $user;
        $this->verificationLink = $baseUrl.'/verify/email/'.$user->email_verification_code;
    }

    public function build()
    {
        return $this->view('emails.verify-email')
            ->subject('Email Verifiction');
    }
}