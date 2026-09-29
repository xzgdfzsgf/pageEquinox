{{-- Wireframe globe (echoes the EQUINOX mark) with the equator highlighted and a marker on Quito. --}}
@php
    $R = 160; $c = 200;
    // Meridians "rotate" by animating each ellipse's rx through |cos| with a phase offset.
    $meridians = [];
    foreach ([0, 30, 60, 90, 120, 150] as $phase) {
        $vals = [];
        for ($s = 0; $s <= 24; $s++) {
            $vals[] = round($R * abs(cos(deg2rad($phase + $s * 7.5))), 1);
        }
        $meridians[] = implode(';', $vals);
    }
    // Latitudes seen with a slight tilt.
    $lats = [];
    foreach ([-60, -30, 30, 60] as $lat) {
        $lats[] = [
            'cy' => round($c - $R * sin(deg2rad($lat)), 1),
            'rx' => round($R * cos(deg2rad($lat)), 1),
            'ry' => round(28 * cos(deg2rad($lat)), 1),
        ];
    }
@endphp
<svg class="globe" viewBox="0 0 400 400" role="img" aria-labelledby="globe-title">
    <title id="globe-title">Globe showing the equator at 0° latitude, with Quito, Ecuador marked on it</title>
    <defs>
        <radialGradient id="globe-fill" cx=".38" cy=".35" r=".75">
            <stop offset="0" style="stop-color: var(--sv-globe-0)" stop-opacity=".9"/>
            <stop offset="1" style="stop-color: var(--sv-globe-1)" stop-opacity=".95"/>
        </radialGradient>
        <linearGradient id="globe-eq" x1="0" x2="1">
            <stop offset="0" stop-color="#A979D9"/>
            <stop offset=".5" stop-color="#E9C4F4"/>
            <stop offset="1" stop-color="#EC5FAA"/>
        </linearGradient>
        <radialGradient id="globe-halo">
            <stop offset=".7" stop-color="#A979D9" stop-opacity=".22"/>
            <stop offset="1" stop-color="#A979D9" stop-opacity="0"/>
        </radialGradient>
        <filter id="globe-glow" x="-30%" y="-30%" width="160%" height="160%">
            <feGaussianBlur stdDeviation="4" result="b"/>
            <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
    </defs>

    <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R + 40 }}" fill="url(#globe-halo)"/>
    <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R + 26 }}" fill="none" stroke="rgba(187,210,255,.14)" stroke-dasharray="2 6" class="globe__dash"/>
    <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="url(#globe-fill)" stroke="rgba(187,210,255,.35)" stroke-width="1.2"/>

    <g fill="none" stroke="rgba(187,210,255,.22)" stroke-width="1">
        @foreach ($lats as $l)
            <ellipse cx="{{ $c }}" cy="{{ $l['cy'] }}" rx="{{ $l['rx'] }}" ry="{{ $l['ry'] }}"/>
        @endforeach
        @foreach ($meridians as $vals)
            <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="{{ explode(';', $vals)[0] }}" ry="{{ $R }}">
                <animate attributeName="rx" values="{{ $vals }}" dur="18s" repeatCount="indefinite"/>
            </ellipse>
        @endforeach
    </g>

    {{-- Equator --}}
    <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="{{ $R }}" ry="28" fill="none" stroke="url(#globe-eq)" stroke-width="2.5" filter="url(#globe-glow)"/>
    <line x1="4" y1="{{ $c }}" x2="36" y2="{{ $c }}" stroke="#E9C4F4" stroke-opacity=".5"/>
    <line x1="364" y1="{{ $c }}" x2="396" y2="{{ $c }}" stroke="#E9C4F4" stroke-opacity=".5"/>

    {{-- Satellite in a tilted orbit around the globe --}}
    <g transform="rotate(-18 {{ $c }} {{ $c }})">
        <path id="globe-orbit" d="M {{ $c - 196 }} {{ $c }} a 196 58 0 1 0 392 0 a 196 58 0 1 0 -392 0" fill="none" stroke="rgba(233,196,244,.28)" stroke-dasharray="1 5"/>
        <g>
            <circle r="4.5" fill="#EC5FAA" filter="url(#globe-glow)"/>
            <animateMotion dur="11s" repeatCount="indefinite"><mpath href="#globe-orbit"/></animateMotion>
        </g>
    </g>

    {{-- Quito marker on the front of the equator --}}
    <g class="globe__marker" transform="translate(162 227)">
        <circle r="16" fill="#EC5FAA" opacity=".25" class="globe__pulse"/>
        <circle r="5" fill="#E9C4F4" filter="url(#globe-glow)"/>
        <line x1="0" y1="-6" x2="-38" y2="-72" stroke="#E9C4F4" stroke-opacity=".7"/>
    </g>
    <g font-family="JetBrains Mono, ui-monospace, monospace" fill="#E9C4F4">
        <text x="84" y="142" font-size="13" font-weight="600" letter-spacing="1.5">QUITO</text>
        <text x="84" y="158" font-size="10" fill="#BBD2FF" letter-spacing="1">0°13′S · 78°31′W</text>
    </g>
    <text x="300" y="248" font-family="JetBrains Mono, ui-monospace, monospace" font-size="10" fill="#BBD2FF" letter-spacing="2" opacity=".85">0° LAT</text>
</svg>
