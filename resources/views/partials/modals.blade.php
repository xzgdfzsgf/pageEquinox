{{-- Shared modals: Join the list and Become a sponsor (available on every page). --}}
<dialog class="modal" id="modal-join" aria-labelledby="modal-join-title">
    <div class="modal__inner">
        <button type="button" class="modal__close" data-close aria-label="Close"><x-icon name="close" /></button>
        <p class="eyebrow">Registration opening soon</p>
        <h2 id="modal-join-title" class="modal__title">Join the EQUINOX list</h2>
        <p class="modal__lead">Be the first to know when registration opens, plus speaker announcements and program releases.</p>
        @include('partials.join-form', ['prefix' => 'mj', 'source' => 'modal'])
    </div>
</dialog>

<dialog class="modal" id="modal-sponsor" aria-labelledby="modal-sponsor-title">
    <div class="modal__inner">
        <button type="button" class="modal__close" data-close aria-label="Close"><x-icon name="close" /></button>
        <p class="eyebrow">Partnerships</p>
        <h2 id="modal-sponsor-title" class="modal__title">Become a sponsor</h2>
        <p class="modal__lead">Tell us about your organization and our team will contact you with sponsorship and partnership opportunities.</p>
        @include('partials.sponsor-form')
    </div>
</dialog>
