<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contact $contact)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Nuevo mensaje de contacto: ' . ($this->contact->subject ?: 'Sin asunto'))
            ->replyTo($this->contact->email, $this->contact->full_name)
            ->view('emails.new-contact-message');
    }
}
