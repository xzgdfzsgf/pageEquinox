{{-- Illustrated placeholder for the Conference card: a stage with the orbital visual on the LED wall, audience in front. --}}
<svg class="illus" viewBox="0 0 800 500" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="cf-bg" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" style="stop-color: var(--sv-sky-0)"/>
            <stop offset="1" style="stop-color: var(--sv-bg-1)"/>
        </linearGradient>
        <linearGradient id="cf-beam" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#E9C4F4" stop-opacity=".28"/>
            <stop offset="1" stop-color="#E9C4F4" stop-opacity="0"/>
        </linearGradient>
        <radialGradient id="cf-screen" cx=".5" cy=".5" r=".7">
            <stop offset="0" style="stop-color: var(--sv-screen-0)"/>
            <stop offset="1" style="stop-color: var(--sv-screen-1)"/>
        </radialGradient>
        <radialGradient id="cf-glow" cx=".5" cy=".5" r=".5">
            <stop offset="0" stop-color="#A979D9" stop-opacity=".5"/>
            <stop offset="1" stop-color="#A979D9" stop-opacity="0"/>
        </radialGradient>
    </defs>

    <rect width="800" height="500" fill="url(#cf-bg)"/>

    {{-- Stage lights --}}
    <g class="illus__beams">
        <path d="M160 0 L70 330 H250Z" fill="url(#cf-beam)"/>
        <path d="M640 0 L550 330 H730Z" fill="url(#cf-beam)"/>
        <path d="M400 0 L330 300 H470Z" fill="url(#cf-beam)" opacity=".6"/>
    </g>

    {{-- LED wall --}}
    <ellipse cx="400" cy="170" rx="300" ry="140" fill="url(#cf-glow)"/>
    <rect x="200" y="70" width="400" height="200" rx="6" fill="url(#cf-screen)" stroke="rgba(233,196,244,.45)"/>
    <g fill="none" stroke-width="1.5" class="illus__screen-orbits">
        <ellipse cx="400" cy="170" rx="150" ry="36" stroke="#E9C4F4" stroke-opacity=".8" transform="rotate(-12 400 170)"/>
        <ellipse cx="400" cy="170" rx="110" ry="60" stroke="#EC5FAA" stroke-opacity=".6" transform="rotate(24 400 170)"/>
        <ellipse cx="400" cy="170" rx="70" ry="70" stroke="#BBD2FF" stroke-opacity=".35"/>
    </g>
    <path d="M400 146 C402 164 406 168 424 170 C406 172 402 176 400 194 C398 176 394 172 376 170 C394 168 398 164 400 146Z" fill="#F6E6FB"/>
    <text x="400" y="252" text-anchor="middle" font-family="JetBrains Mono, monospace" font-size="11" letter-spacing="4" fill="#E9C4F4" opacity=".8">THE EQUATORIAL SPACE ECONOMY</text>

    {{-- Stage --}}
    <path d="M90 300 H710 L760 340 H40Z" style="fill: var(--sv-stage)" stroke="rgba(233,196,244,.25)"/>
    <rect x="370" y="268" width="60" height="34" rx="3" style="fill: var(--sv-metal-0)" stroke="rgba(233,196,244,.35)"/>

    {{-- Audience rows --}}
    @foreach ([[372, 34, 22, 'var(--sv-crowd-1)'], [412, 30, 26, 'var(--sv-crowd-2)'], [458, 26, 30, 'var(--sv-crowd-3)']] as $row => [$y, $gap, $r, $fill])
        <g style="fill: {{ $fill }}" stroke="rgba(187,210,255,.10)">
            @for ($x = ($row % 2 ? 10 : 26); $x < 820; $x += $gap + $r)
                <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $r * 0.42 }}"/>
                <path d="M{{ $x - $r * 0.8 }} {{ $y + $r * 1.3 }} Q{{ $x }} {{ $y + $r * 0.2 }} {{ $x + $r * 0.8 }} {{ $y + $r * 1.3 }}Z"/>
            @endfor
        </g>
    @endforeach
</svg>
