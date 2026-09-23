<?php

namespace Tests\Feature;

use App\Mail\NewContactMessage;
use App\Models\Contact;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Juan',
            'last_name'  => 'Pérez',
            'email'      => 'juan@example.com',
            'message'    => 'Este es un mensaje de prueba con más de diez caracteres.',
        ], $overrides);
    }

    public function test_contact_page_loads(): void
    {
        $this->get(route('frontend.contact'))->assertStatus(200);
    }

    public function test_valid_submission_creates_a_contact(): void
    {
        $response = $this->post(route('frontend.contact.store'), $this->validPayload());

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

    public function test_honeypot_field_silently_rejects_without_creating_a_record(): void
    {
        $response = $this->post(route('frontend.contact.store'), $this->validPayload([
            'website' => 'http://spam-bot.example',
        ]));

        // Finge éxito a propósito, para no delatarle al bot que fue detectado.
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_valid_submission_sends_notification_email_to_contact_address(): void
    {
        Mail::fake();
        SiteSetting::set('contact_email', 'ventas@hamiltonbeach.com.py');

        $this->post(route('frontend.contact.store'), $this->validPayload());

        Mail::assertSent(NewContactMessage::class, function ($mail) {
            return $mail->hasTo('ventas@hamiltonbeach.com.py')
                && $mail->contact->email === 'juan@example.com';
        });
    }

    public function test_contact_still_succeeds_for_visitor_even_if_email_sending_fails(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('SMTP caído'));
        SiteSetting::set('contact_email', 'ventas@hamiltonbeach.com.py');

        $response = $this->post(route('frontend.contact.store'), $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contacts', ['email' => 'juan@example.com']);
    }

    public function test_submission_is_rate_limited_after_five_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('frontend.contact.store'), $this->validPayload([
                'email' => "juan{$i}@example.com",
            ]));
        }

        $response = $this->post(route('frontend.contact.store'), $this->validPayload([
            'email' => 'juan-extra@example.com',
        ]));

        $response->assertStatus(429);
    }
}
