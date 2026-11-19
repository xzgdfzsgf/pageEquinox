{{-- Review dock: cursor companion only. The hummingbird starts off; the page style is fixed to black. --}}
<div class="dock" data-dock>
    <div class="dock__panel" id="dock-panel">
        <div class="theme-switch companion-switch" role="group" aria-label="Cursor companion">
            <span class="theme-switch__label">Cursor</span>
            <button type="button" data-companion-set="hummingbird" aria-pressed="false" aria-label="Hummingbird follows the cursor">
                <svg class="dock__glyph" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M23 6.2 16.6 7a3 3 0 0 0-5.4 1.3L3.5 3c1 3.9 2.8 6.6 5.2 8-1.8 1.8-3.9 4.9-5.6 9.8 3-2.4 5.8-3.9 8-4.6 2.8-1 4.6-3.3 4.7-6.1l.1-1.2Z"/></svg>
                Hummingbird
            </button>
            <button type="button" data-companion-set="off" aria-pressed="true" aria-label="No cursor companion">
                <svg class="dock__glyph" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><path d="m6.5 6.5 11 11"/></svg>
                Off
            </button>
        </div>
    </div>
    <button type="button" class="dock__toggle" aria-controls="dock-panel" aria-expanded="true" aria-label="Hide cursor options" data-dock-toggle>
        <svg class="icon dock__icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M4 7h10M18 7h2M4 17h4M12 17h8"/><circle cx="16" cy="7" r="2"/><circle cx="10" cy="17" r="2"/></svg>
        <svg class="icon dock__icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>
