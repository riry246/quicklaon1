<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IdRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $baseUrl;

    public function __construct(User $user)
    {
        $this->baseUrl = url('/');
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.id-request-email')
            ->subject('ID Verification Required');
    }
}