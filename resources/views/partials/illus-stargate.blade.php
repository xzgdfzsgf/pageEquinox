{{-- Illustrated placeholder for the Stargate card: teleport dishes on the Andean highlands at 0°, at dusk. --}}
<svg class="illus" viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="sg-sky" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" style="stop-color: var(--sv-sky-0)"/>
            <stop offset=".62" style="stop-color: var(--sv-sky-1)"/>
            <stop offset=".8" style="stop-color: var(--sv-sky-2)"/>
            <stop offset="1" stop-color="#A979D9"/>
        </linearGradient>
        <radialGradient id="sg-sun" cx=".5" cy="1" r=".6">
            <stop offset="0" stop-color="#EC5FAA" stop-opacity=".55"/>
            <stop offset="1" stop-color="#EC5FAA" stop-opacity="0"/>
        </radialGradient>
        <linearGradient id="sg-rim" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#E9C4F4"/>
            <stop offset="1" stop-color="#A979D9" stop-opacity=".2"/>
        </linearGradient>
    </defs>

    <rect width="800" height="500" fill="url(#sg-sky)"/>
    <ellipse cx="400" cy="330" rx="520" ry="160" fill="url(#sg-sun)"/>

    <g fill="#fff">
        @foreach ([[80,60,1.2],[190,110,.8],[260,40,1],[350,90,.7],[470,50,1.3],[560,120,.8],[640,70,1],[720,30,.9],[120,150,.7],[300,160,.6],[680,160,.7],[520,20,.8],[40,120,.9],[760,110,.8]] as $i => [$x, $y, $r])
            <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $r }}" class="illus__twinkle" style="animation-delay: -{{ $i * 0.7 }}s"/>
        @endforeach
    </g>

    {{-- Andes ridges --}}
    <path d="M0 330 L70 290 L130 305 L210 250 L280 290 L340 270 L420 300 L500 240 L590 285 L660 262 L740 300 L800 280 V500 H0Z" style="fill: var(--sv-ridge-1)" opacity=".85"/>
    <path d="M0 370 L90 340 L170 352 L260 318 L360 350 L450 330 L540 358 L640 326 L720 348 L800 336 V500 H0Z" style="fill: var(--sv-ridge-2)"/>
    <path d="M0 410 C200 385 600 385 800 410 V500 H0Z" style="fill: var(--sv-ground)"/>

    {{-- Equator line running into the distance --}}
    <line x1="0" y1="440" x2="800" y2="440" stroke="#E9C4F4" stroke-width="1.5" stroke-dasharray="10 8" class="illus__equator"/>
    <text x="776" y="430" text-anchor="end" font-family="JetBrains Mono, monospace" font-size="13" fill="#E9C4F4" letter-spacing="3" opacity=".85">0°00′00″</text>

    {{-- Signal waves --}}
    <g fill="none" stroke="#E9C4F4" stroke-linecap="round">
        <path d="M467 199 a30 30 0 0 1 30 -30" class="illus__wave"/>
        <path d="M442 199 a55 55 0 0 1 55 -55" class="illus__wave" style="animation-delay: .6s"/>
        <path d="M417 199 a80 80 0 0 1 80 -80" class="illus__wave" style="animation-delay: 1.2s"/>
    </g>

    {{-- Big dish --}}
    <g transform="translate(590 318) scale(1.35)">
        <path d="M-10 0 L-26 120 H26 L10 0Z" style="fill: var(--sv-metal-0)" stroke="rgba(233,196,244,.35)"/>
        <rect x="-40" y="116" width="80" height="14" rx="3" style="fill: var(--sv-metal-0)" stroke="rgba(233,196,244,.35)"/>
        <g transform="rotate(-38)">
            <path d="M-110 -8 C-100 60 100 60 110 -8 Z" style="fill: var(--sv-metal-1)" stroke="url(#sg-rim)" stroke-width="2.5"/>
            <ellipse cx="0" cy="-8" rx="110" ry="22" style="fill: var(--sv-metal-2)" stroke="url(#sg-rim)" stroke-width="2"/>
            <path d="M-60 -2 L0 -110 L60 -2" fill="none" stroke="rgba(233,196,244,.6)" stroke-width="2"/>
            <circle cx="0" cy="-112" r="7" fill="#E9C4F4"/>
        </g>
    </g>

    {{-- Small dish --}}
    <g transform="translate(330 372) scale(.75)">
        <path d="M-10 0 L-26 110 H26 L10 0Z" style="fill: var(--sv-metal-0)" stroke="rgba(233,196,244,.3)"/>
        <g transform="rotate(-24)">
            <path d="M-110 -8 C-100 60 100 60 110 -8 Z" style="fill: var(--sv-metal-1)" stroke="url(#sg-rim)" stroke-width="3"/>
            <ellipse cx="0" cy="-8" rx="110" ry="22" style="fill: var(--sv-metal-2)" stroke="url(#sg-rim)" stroke-width="3"/>
            <path d="M-60 -2 L0 -110 L60 -2" fill="none" stroke="rgba(233,196,244,.5)" stroke-width="3"/>
            <circle cx="0" cy="-112" r="8" fill="#E9C4F4"/>
        </g>
    </g>

    {{-- Satellite crossing the sky --}}
    <circle r="3" fill="#BBD2FF" class="illus__sat">
        <animateMotion dur="14s" repeatCount="indefinite" path="M-20 150 Q400 -40 820 120"/>
    </circle>
</svg>
