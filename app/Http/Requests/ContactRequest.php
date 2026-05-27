<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'nullable|string|max:100',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'required|email|max:255',
            'subject'            => 'nullable|string|max:255',
            'message'            => 'required|string|min:10|max:2000',
            'newsletter_consent' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required'  => 'El apellido es obligatorio.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'Ingresá un correo electrónico válido.',
            'message.required'    => 'El mensaje es obligatorio.',
            'message.min'         => 'El mensaje debe tener al menos 10 caracteres.',
        ];
    }
}
