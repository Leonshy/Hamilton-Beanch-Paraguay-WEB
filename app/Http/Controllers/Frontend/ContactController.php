<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(ContactRequest $request)
    {
        Contact::create([
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

        return back()->with('success', '¡Gracias! Tu mensaje fue enviado correctamente. Te responderemos a la brevedad.');
    }
}
