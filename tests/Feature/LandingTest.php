<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\SponsorInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_renders_all_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Where space meets at the equator')
            ->assertSee('Five conversations shaping')
            ->assertSee('id="modal-join"', false)
            ->assertSee('id="modal-sponsor"', false);
    }

    public function test_join_list_stores_lead_and_is_idempotent(): void
    {
        $payload = ['email' => 'Someone@Example.com', 'name' => 'Some One', 'profile' => 'investor', 'consent' => '1'];

        $this->postJson('/join', $payload)->assertOk()->assertJson(['ok' => true]);
        $this->postJson('/join', $payload + ['organization' => 'Acme'])->assertOk();

        $this->assertSame(1, Lead::count());
        $lead = Lead::first();
        $this->assertSame('someone@example.com', $lead->email);
        $this->assertSame('Acme', $lead->organization);
    }

    public function test_join_list_requires_email_and_consent(): void
    {
        $this->postJson('/join', ['email' => 'not-an-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'consent']);
    }

    public function test_honeypot_silently_drops_bots(): void
    {
        $this->postJson('/join', ['email' => 'bot@spam.test', 'consent' => '1', 'company_url' => 'http://spam'])->assertOk();
        $this->assertSame(0, Lead::count());
    }

    public function test_sponsor_inquiry_is_stored_with_notifications_enabled(): void
    {
        Mail::fake();
        config(['equinox.notify_emails' => ['team@example.org']]);

        $this->postJson('/sponsor', [
            'name' => 'Ana', 'email' => 'ana@spaceco.test', 'company' => 'SpaceCo',
            'interest' => 'sponsorship', 'consent' => '1',
        ])->assertOk();

        $this->assertDatabaseHas('sponsor_inquiries', ['company' => 'SpaceCo', 'interest' => 'sponsorship']);
    }

    public function test_sponsor_requires_company(): void
    {
        $this->postJson('/sponsor', ['name' => 'Ana', 'email' => 'ana@spaceco.test', 'consent' => '1'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['company']);
        $this->assertSame(0, SponsorInquiry::count());
    }

    public function test_non_js_submission_redirects_with_flash(): void
    {
        $this->post('/join', ['email' => 'plain@example.com', 'consent' => '1'])
            ->assertRedirect(route('home').'#join')
            ->assertSessionHas('join_success');
    }

    public function test_exports_are_hidden_without_password_and_protected_with_it(): void
    {
        config(['equinox.admin_password' => null]);
        $this->get('/admin/leads.csv')->assertNotFound();

        config(['equinox.admin_user' => 'equinox', 'equinox.admin_password' => 'secret-test']);
        $this->get('/admin/leads.csv')->assertStatus(401);

        Lead::create(['email' => 'csv@example.com', 'name' => 'José Ñandú', 'consent' => true]);
        $response = $this->withHeader('Authorization', 'Basic '.base64_encode('equinox:secret-test'))->get('/admin/leads.csv');
        $response->assertOk();
        $this->assertStringContainsString('José Ñandú', $response->streamedContent());
    }
}
