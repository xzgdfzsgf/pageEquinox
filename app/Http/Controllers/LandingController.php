<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SponsorInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $title = 'EQUINOX 2027 — Where Space Meets at the Equator';
        $description = 'EQUINOX: The Equatorial Space Economy. A global gathering on investment, business and new opportunities in space. March 16–19, 2027 · Quito, Ecuador.';

        // Built here (plain PHP) rather than inline in the Blade view: a literal "@context"
        // string in a .blade.php file gets mistaken for Laravel's @context directive by the
        // Blade compiler, which mangles the output instead of emitting valid JSON-LD.
        $jsonLd = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => 'EQUINOX 2027 — The Equatorial Space Economy',
            'description' => $description,
            'startDate' => '2027-03-16',
            'endDate' => '2027-03-19',
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'location' => [
                ['@type' => 'Place', 'name' => 'UDLA Park', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Quito', 'addressCountry' => 'EC']],
                ['@type' => 'Place', 'name' => 'Astralintu Stargate', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Urcuquí', 'addressCountry' => 'EC']],
            ],
            'organizer' => ['@type' => 'Organization', 'name' => 'Fundación Sideralis'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return view('landing', [
            'title' => $title,
            'description' => $description,
            'jsonLd' => $jsonLd,
            'profiles' => Lead::PROFILES,
            'interests' => SponsorInquiry::INTERESTS,
        ]);
    }

    public function theEvent(): View
    {
        return view('the-event', [
            'title' => 'The Event — EQUINOX 2027 · Quito, Ecuador',
            'description' => 'The Event: one gathering, two experiences. The Conference at UDLA Park and the Stargate Visit in Urcuquí. March 16–19, 2027 · Quito, Ecuador.',
            'profiles' => Lead::PROFILES,
            'interests' => SponsorInquiry::INTERESTS,
        ]);
    }

    public function planYourVisit(): View
    {
        return view('plan-your-visit', [
            'title' => 'Plan your visit — EQUINOX 2027 · Quito, Ecuador',
            'description' => 'Plan your trip to EQUINOX 2027: flights, visa and entry requirements, partner hotels, airport transfers and conference shuttles. March 16–19, 2027 · Quito, Ecuador.',
            'profiles' => Lead::PROFILES,
            'interests' => SponsorInquiry::INTERESTS,
        ]);
    }

    public function joinList(Request $request): JsonResponse|RedirectResponse
    {
        // Honeypot: bots fill every field; humans never see this one.
        if ($request->filled('company_url')) {
            return $this->done($request, 'join', "You're on the list.");
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'organization' => ['nullable', 'string', 'max:160'],
            'country' => ['nullable', 'string', 'max:100'],
            'profile' => ['nullable', Rule::in(array_keys(Lead::PROFILES))],
            'consent' => ['accepted'],
            'source' => ['nullable', 'string', 'max:50'],
        ], [
            'consent.accepted' => 'Please confirm you agree to receive EQUINOX updates.',
        ]);

        $email = Str::lower(trim($data['email']));
        $emailHash = Lead::hashEmail($email);

        // Re-submitting updates the existing entry instead of failing on the unique email.
        $lead = Lead::firstOrNew(['email_hash' => $emailHash]);
        $lead->fill(array_filter([
            'name' => $data['name'] ?? null,
            'organization' => $data['organization'] ?? null,
            'country' => $data['country'] ?? null,
            'profile' => $data['profile'] ?? null,
        ]) + [
            'email' => $email,
            'email_hash' => $emailHash,
            'consent' => true,
            'source' => $lead->source ?? ($data['source'] ?? 'landing'),
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);
        $lead->save();

        $this->notifyNewLead($lead);

        return $this->done($request, 'join', "You're on the list. We'll let you know as soon as registration opens.");
    }

    private function notifyNewLead(Lead $lead): void
    {
        $recipients = config('equinox.notify_emails');
        if (empty($recipients)) {
            return;
        }

        $profile = Lead::PROFILES[$lead->profile] ?? '—';
        $body = implode("\n", [
            'New registration on the EQUINOX list:',
            '',
            'Name: '.($lead->name ?: '—'),
            "Email: {$lead->email}",
            'Organization: '.($lead->organization ?: '—'),
            'Country: '.($lead->country ?: '—'),
            "Profile: {$profile}",
            'Source: '.($lead->source ?: '—'),
        ]);

        // A mail failure must never lose the lead, which is already saved.
        try {
            Mail::raw($body, function ($mail) use ($recipients, $lead) {
                $mail->to($recipients)
                    ->replyTo($lead->email, $lead->name ?: $lead->email)
                    ->subject('EQUINOX · New list registration'.($lead->organization ? ": {$lead->organization}" : ''));
            });
        } catch (\Throwable $e) {
            Log::warning('Lead notification failed', ['id' => $lead->id, 'error' => $e->getMessage()]);
        }
    }

    public function sponsor(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->filled('company_url')) {
            return $this->done($request, 'sponsor', 'Thank you. Our team will contact you shortly.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'company' => ['required', 'string', 'max:160'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'string', 'max:190'],
            'interest' => ['nullable', Rule::in(array_keys(SponsorInquiry::INTERESTS))],
            'message' => ['nullable', 'string', 'max:3000'],
            'consent' => ['accepted'],
        ], [
            'consent.accepted' => 'Please confirm we can contact you about EQUINOX partnerships.',
        ]);

        $email = Str::lower(trim($data['email']));

        $inquiry = SponsorInquiry::create($data + [
            'email' => $email,
            'email_hash' => SponsorInquiry::hashEmail($email),
            'consent' => true,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        $this->notifySponsorInquiry($inquiry);

        return $this->done($request, 'sponsor', 'Thank you for your interest in partnering with EQUINOX. Our team will contact you shortly.');
    }

    private function notifySponsorInquiry(SponsorInquiry $inquiry): void
    {
        $recipients = config('equinox.notify_emails');
        if (empty($recipients)) {
            return;
        }

        $interest = SponsorInquiry::INTERESTS[$inquiry->interest] ?? '—';
        $body = implode("\n", [
            'New sponsorship inquiry from the EQUINOX landing page:',
            '',
            "Name: {$inquiry->name}",
            "Email: {$inquiry->email}",
            "Company: {$inquiry->company}",
            'Job title: '.($inquiry->job_title ?: '—'),
            'Phone: '.($inquiry->phone ?: '—'),
            'Country: '.($inquiry->country ?: '—'),
            'Website: '.($inquiry->website ?: '—'),
            "Interest: {$interest}",
            '',
            'Message:',
            $inquiry->message ?: '—',
        ]);

        // A mail failure must never lose the inquiry, which is already saved.
        try {
            Mail::raw($body, function ($mail) use ($recipients, $inquiry) {
                $mail->to($recipients)
                    ->replyTo($inquiry->email, $inquiry->name)
                    ->subject("EQUINOX · Sponsorship inquiry: {$inquiry->company}");
            });
        } catch (\Throwable $e) {
            Log::warning('Sponsor inquiry notification failed', ['id' => $inquiry->id, 'error' => $e->getMessage()]);
        }
    }

    private function done(Request $request, string $form, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        // Without JS both forms fall back to the "Join the list" section, where the flash message is shown.
        return redirect()->to(route('home').'#join')->with("{$form}_success", $message);
    }
}
