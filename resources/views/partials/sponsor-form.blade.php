{{-- "Become a sponsor" form, shown in the sponsor modal. --}}
<form class="form" action="{{ route('sponsor') }}" method="POST" data-ajax-form novalidate>
    @csrf
    <div class="hp" aria-hidden="true">
        <label for="sp-company_url">Leave this empty</label>
        <input type="text" id="sp-company_url" name="company_url" tabindex="-1" autocomplete="off">
    </div>

    <div class="form__grid">
        <div class="field">
            <label for="sp-name">Full name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="sp-name" name="name" autocomplete="name" maxlength="120" required>
        </div>
        <div class="field">
            <label for="sp-email">Work email <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="sp-email" name="email" autocomplete="email" maxlength="190" required>
        </div>
        <div class="field">
            <label for="sp-company">Company / organization <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="sp-company" name="company" autocomplete="organization" maxlength="160" required>
        </div>
        <div class="field">
            <label for="sp-job_title">Job title</label>
            <input type="text" id="sp-job_title" name="job_title" autocomplete="organization-title" maxlength="120">
        </div>
        <div class="field">
            <label for="sp-phone">Phone / WhatsApp</label>
            <input type="tel" id="sp-phone" name="phone" autocomplete="tel" maxlength="50">
        </div>
        <div class="field">
            <label for="sp-country">Country</label>
            <input type="text" id="sp-country" name="country" autocomplete="country-name" maxlength="100">
        </div>
        <div class="field">
            <label for="sp-website">Website</label>
            <input type="text" id="sp-website" name="website" inputmode="url" autocomplete="url" maxlength="190" placeholder="company.com">
        </div>
        <div class="field">
            <label for="sp-interest">Interested in</label>
            <select id="sp-interest" name="interest">
                <option value="">Select an option</option>
                @foreach ($interests as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field field--full">
            <label for="sp-message">Tell us about your goals</label>
            <textarea id="sp-message" name="message" rows="3" maxlength="3000" placeholder="Audience you want to reach, type of participation, questions…"></textarea>
        </div>
    </div>

    <label class="check">
        <input type="checkbox" name="consent" value="1" required>
        <span>I agree to be contacted by the EQUINOX team (Fundación Sideralis) about partnership opportunities.</span>
    </label>

    <div class="form__actions">
        <button type="submit" class="btn btn--primary btn--lg">
            <span class="btn__label">Send inquiry</span>
            <x-icon name="arrow-right" />
        </button>
        <p class="form__status" role="status" aria-live="polite"></p>
    </div>
</form>
