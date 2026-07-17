<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_loads(): void
    {
        $this->get(route('frontend.contact'))->assertStatus(200);
    }

    public function test_valid_submission_creates_a_contact(): void
    {
        $response = $this->post(route('frontend.contact.store'), [
            'first_name' => 'Juan',
            'last_name'  => 'Pérez',
            'email'      => 'juan@example.com',
            'message'    => 'Este es un mensaje de prueba con más de diez caracteres.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'email'  => 'juan@example.com',
            'status' => 'new',
        ]);
    }

    public function test_submission_without_required_fields_fails_validation(): void
    {
        $response = $this->post(route('frontend.contact.store'), [
            'first_name' => '',
            'email'      => 'no-es-un-email',
            'message'    => 'corto',
        ]);

        $response->assertSessionHasErrors(['first_name', 'email', 'message']);
        $this->assertDatabaseCount('contacts', 0);
    }
}
