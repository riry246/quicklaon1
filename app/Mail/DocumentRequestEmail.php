<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $documentType;
    public $baseUrl;

    public function __construct(User $user, $documentType)
    {
        $this->baseUrl = url('/');
        $this->user = $user;
        $this->documentType = $documentType;
    }

    public function build()
    {
        return $this->view('emails.document-request-email')
            ->subject('Request for Documnet');
    }
}