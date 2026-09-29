{{-- "Join the list" form. Used inline (#join) and inside the modal; $p keeps ids unique. --}}
@php $p = $prefix ?? 'join'; @endphp
<form class="form" action="{{ route('join') }}" method="POST" data-ajax-form novalidate>
    @csrf
    <input type="hidden" name="source" value="{{ $source ?? 'landing' }}">
    <div class="hp" aria-hidden="true">
        <label for="{{ $p }}-company_url">Leave this empty</label>
        <input type="text" id="{{ $p }}-company_url" name="company_url" tabindex="-1" autocomplete="off">
    </div>

    <div class="form__grid">
        <div class="field">
            <label for="{{ $p }}-name">Full name</label>
            <input type="text" id="{{ $p }}-name" name="name" autocomplete="name" maxlength="120" placeholder="Your name">
        </div>
        <div class="field">
            <label for="{{ $p }}-email">Email address <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="{{ $p }}-email" name="email" autocomplete="email" maxlength="190" required placeholder="you@company.com">
        </div>
        <div class="field">
            <label for="{{ $p }}-organization">Organization</label>
            <input type="text" id="{{ $p }}-organization" name="organization" autocomplete="organization" maxlength="160" placeholder="Company, agency, university…">
        </div>
        <div class="field">
            <label for="{{ $p }}-country">Country</label>
            <input type="text" id="{{ $p }}-country" name="country" autocomplete="country-name" maxlength="100" placeholder="e.g. Ecuador">
        </div>
        <div class="field field--full">
            <label for="{{ $p }}-profile">I am…</label>
            <select id="{{ $p }}-profile" name="profile">
                <option value="">Select your profile (optional)</option>
                @foreach ($profiles as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <label class="check">
        <input type="checkbox" name="consent" value="1" required>
        <span>I'd like to receive EQUINOX updates on registration, speakers and program news. You can unsubscribe at any time.</span>
    </label>

    <div class="form__actions">
        <button type="submit" class="btn btn--primary btn--lg">
            <span class="btn__label">Join the list</span>
            <x-icon name="arrow-right" />
        </button>
        <p class="form__status" role="status" aria-live="polite"></p>
    </div>
</form>
