<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\NewContactMessage;
use App\Models\Contact;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(ContactRequest $request)
    {
        // Honeypot: campo oculto para humanos, que los bots simples suelen
        // completar automáticamente. Si viene con algo, fingimos éxito sin
        // guardar nada — evita que el bot sepa que fue detectado.
        if ($request->filled('website')) {
            return back()->with('success', '¡Gracias! Tu mensaje fue enviado correctamente. Te responderemos a la brevedad.');
        }

        $contact = Contact::create([
            'first_name'         => $request->first_name,
            'last_name'          => $request->last_name,
            'phone'              => $request->phone,
            'email'              => $request->email,
            'subject'            => $request->subject,
            'message'            => $request->message,
            'newsletter_consent' => $request->boolean('newsletter_consent'),
            'ip_address'         => $request->ip(),
            'status'             => 'new',
        ]);

        $this->notifyByEmail($contact);

        return back()->with('success', '¡Gracias! Tu mensaje fue enviado correctamente. Te responderemos a la brevedad.');
    }

    /**
     * El mensaje ya quedó guardado en /admin/contacts sin importar esto —
     * si el email falla (SMTP mal configurado, etc.) no debe romper la
     * experiencia del visitante, solo queda registrado en el log.
     */
    private function notifyByEmail(Contact $contact): void
    {
        $to = SiteSetting::get('contact_email') ?: SiteSetting::get('email');

        if (!$to) {
            return;
        }

        try {
            Mail::to($to)->send(new NewContactMessage($contact));
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el email de notificación de contacto: ' . $e->getMessage(), [
                'contact_id' => $contact->id,
            ]);
        }
    }
}
