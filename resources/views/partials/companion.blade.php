{{-- Cursor companions. Positioned and animated by resources/js/companion.js; purely decorative. --}}
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

    {{-- Satellite orbiting the cursor, like the Stargate's teleport targets. Anchor: body center. --}}
    <div class="sat-orbit" data-sat-orbit></div>
    <svg class="bird bird--satellite" data-bird="satellite" viewBox="0 0 80 40" width="62" height="31" focusable="false">
        <defs>
            <linearGradient id="sat-panel" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#3B4FA8"/>
                <stop offset="1" stop-color="#1B2163"/>
            </linearGradient>
            <linearGradient id="sat-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#F3DDF9"/>
                <stop offset="1" stop-color="#A979D9"/>
            </linearGradient>
        </defs>
        @foreach ([2, 52] as $px)
            <rect x="{{ $px }}" y="13" width="26" height="14" rx="1.5" fill="url(#sat-panel)" stroke="#BBD2FF" stroke-opacity=".7" stroke-width=".8"/>
            <path d="M{{ $px + 6.5 }} 13v14M{{ $px + 13 }} 13v14M{{ $px + 19.5 }} 13v14M{{ $px }} 20h26" stroke="#BBD2FF" stroke-opacity=".45" stroke-width=".6"/>
        @endforeach
        <path d="M28 20h4M48 20h4" stroke="#E9C4F4" stroke-width="1.2"/>
        <rect x="32" y="12" width="16" height="16" rx="2.5" fill="url(#sat-body)" stroke="#fff" stroke-opacity=".5" stroke-width=".6"/>
        <path d="M40 12V6" stroke="#E9C4F4" stroke-width="1"/>
        <circle class="sat__light" cx="40" cy="5" r="2" fill="#EC5FAA"/>
    </svg>

    {{-- Mini moon orbiting the cursor --}}
    <span class="mini-moon" data-bird="moon"></span>

    {{-- Comet: a small glowing head with a short, soft trail --}}
    <div class="comet" data-bird="comet">
        @for ($i = 0; $i < 16; $i++)
            <span class="comet__dot" style="--i: {{ $i }}"></span>
        @endfor
    </div>

    {{-- Blue-footed booby, the Galápagos icon. Side view in flight, facing right; anchor: body center. --}}
    <svg class="bird bird--booby" data-bird="booby" viewBox="0 0 120 70" width="66" height="39" focusable="false">
        <defs>
            <linearGradient id="bb-wing" x1="1" y1="1" x2="0" y2="0">
                <stop offset="0" stop-color="#6E5646"/>
                <stop offset="1" stop-color="#3E3029"/>
            </linearGradient>
            <linearGradient id="bb-body" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#FFFFFF"/>
                <stop offset="1" stop-color="#E6DFD6"/>
            </linearGradient>
        </defs>
        {{-- far wing (behind the body) --}}
        <g class="bird__wing bird__wing--far"><path d="M58 31 C50 15 36 5 18 3 C30 13 40 23 50 33Z" fill="url(#bb-wing)" opacity=".7"/></g>
        {{-- the famous blue feet, trailing behind --}}
        <path d="M25 40 L9 45 L11 48.5 L27 43Z M27 42.5 L14 50 L17 52.5 L29 45Z" fill="#3FB2F0" stroke="#1D7FC0" stroke-width=".6" stroke-linejoin="round"/>
        {{-- pointed brown tail --}}
        <path d="M21 33 L3 29 L7 35 L3 41 L21 38Z" fill="#5A463A"/>
        {{-- body: white below, brown back --}}
        <path d="M20 34 C28 26 52 24 70 27 C80 29 87 32 89 35 C85 42 70 46 52 46 C36 46 24 42 20 34Z" fill="url(#bb-body)"/>
        <path d="M21 33 C31 27 54 24 71 27 C63 31 44 34 21 33Z" fill="#6A5243"/>
        {{-- head and neck, streaked --}}
        <path d="M79 29 C85 24 96 24 101 29 C99 37 89 40 82 38Z" fill="#F1EBE2"/>
        <path d="M84 28 L88 31 M89 27 L92 30 M93 27.5 L95 30.5" stroke="#9C8573" stroke-width=".8" stroke-linecap="round"/>
        {{-- long conical bill --}}
        <path d="M99 29.5 L119 33.5 L99 36Z" fill="#7C93B6" stroke="#556D92" stroke-width=".6" stroke-linejoin="round"/>
        {{-- yellow eye --}}
        <circle cx="96" cy="30.5" r="2.1" fill="#F2D14B"/>
        <circle cx="96.3" cy="30.5" r="1" fill="#1A1A1A"/>
        {{-- near wing: long and pointed, like a seabird's --}}
        <g class="bird__wing bird__wing--near"><path d="M57 32 C47 16 31 7 7 7 C25 16 40 27 50 37Z" fill="url(#bb-wing)"/></g>
    </svg>

    {{-- Sparkles the hummingbird leaves when it darts --}}
    @for ($i = 0; $i < 14; $i++)
        <span class="companion__spark"></span>
    @endfor
</div>
