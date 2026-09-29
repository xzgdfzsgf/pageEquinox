{{-- Cursor companion. Positioned and animated by resources/js/companion.js; purely decorative. --}}
<div class="companion" data-companion aria-hidden="true">

    {{-- Hummingbird, after Ecuador's violet-tailed sylph. Faces right; the beak tip is the anchor. --}}
    <svg class="bird bird--hummingbird" data-bird="hummingbird" viewBox="0 0 120 80" width="68" height="45" focusable="false">
        <defs>
            <linearGradient id="hb-body" x1="1" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#D6E3FF"/>
                <stop offset=".45" stop-color="#C69BEA"/>
                <stop offset="1" stop-color="#8B57C9"/>
            </linearGradient>
            <linearGradient id="hb-head" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#8FA8F0"/>
                <stop offset="1" stop-color="#A979D9"/>
            </linearGradient>
            <linearGradient id="hb-tail" x1="1" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#A979D9"/>
                <stop offset=".7" stop-color="#7B45B8"/>
                <stop offset="1" stop-color="#EC5FAA"/>
            </linearGradient>
            <linearGradient id="hb-wing" x1="1" y1="1" x2="0" y2="0">
                <stop offset="0" stop-color="#E9C4F4" stop-opacity=".95"/>
                <stop offset="1" stop-color="#E9C4F4" stop-opacity=".25"/>
            </linearGradient>
        </defs>
        <g class="bird__tail">
            <path d="M47 48 C35 58 21 66 3 72 C20 70 34 63 49 52Z" fill="url(#hb-tail)"/>
            <path d="M47 50 C37 62 26 72 13 79 C28 73 39 65 51 53Z" fill="url(#hb-tail)" opacity=".8"/>
        </g>
        <path d="M62 36 C50 10 36 -2 18 -4 C22 16 34 36 58 42Z" fill="#E9C4F4" opacity=".14"/>
        <g class="bird__wing bird__wing--far"><path d="M60 36 C52 18 40 6 22 2 C34 12 44 24 54 40Z" fill="url(#hb-wing)" opacity=".55"/></g>
        <path d="M86 26 C84 20 76 18 70 22 C62 27 54 34 46 46 C44 50 46 54 50 53 C62 50 74 44 82 36 C86 32 87 29 86 26Z" fill="url(#hb-body)"/>
        <path d="M80 36 C72 44 62 49 51 52 C60 46 70 40 76 34Z" fill="#fff" opacity=".22"/>
        <path d="M85 30 C83 35 79 38 74 40 C77 36 80 33 81 29Z" fill="#EC5FAA"/>
        <circle cx="79" cy="25" r="8" fill="url(#hb-head)"/>
        <circle cx="82" cy="23.5" r="1.9" fill="#0A0F33"/>
        <circle cx="82.7" cy="22.8" r=".65" fill="#fff"/>
        <path d="M86 25.2 L118 27 L86 28.4Z" fill="#2B2F55" stroke="#E9C4F4" stroke-opacity=".5" stroke-width=".5"/>
        <g class="bird__wing bird__wing--near"><path d="M64 34 C56 14 44 0 26 -4 C38 8 48 22 58 40Z" fill="url(#hb-wing)"/></g>
    </svg>

    {{-- Sparkles the hummingbird leaves when it darts --}}
    @for ($i = 0; $i < 14; $i++)
        <span class="companion__spark"></span>
    @endfor
</div>
