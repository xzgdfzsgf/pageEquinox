{{-- Hero "orbital loop": tilted orbits around the EQUINOX star, with satellites travelling along them. --}}
@php
    $cx = 400; $cy = 400;
    $orbits = [
        ['rx' => 370, 'ry' => 92,  'rot' => -14, 'dur' => 26, 'stroke' => 'url(#orb-a)', 'w' => 1.4],
        ['rx' => 300, 'ry' => 150, 'rot' => 28,  'dur' => 21, 'stroke' => 'url(#orb-b)', 'w' => 1],
        ['rx' => 250, 'ry' => 70,  'rot' => 72,  'dur' => 17, 'stroke' => 'url(#orb-c)', 'w' => 1],
        ['rx' => 190, 'ry' => 190, 'rot' => 0,   'dur' => 32, 'stroke' => 'var(--line-strong)', 'w' => .8],
        ['rx' => 150, 'ry' => 44,  'rot' => -38, 'dur' => 12, 'stroke' => 'url(#orb-a)', 'w' => .8],
    ];
    $ellipse = fn ($o) => sprintf('M %1$s %2$s a %3$s %4$s 0 1 0 %5$s 0 a %3$s %4$s 0 1 0 -%5$s 0',
        $cx - $o['rx'], $cy, $o['rx'], $o['ry'], 2 * $o['rx']);
@endphp
<svg class="orbits" viewBox="0 0 800 800" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="orb-a" x1="0" y1="0" x2="1" y2="0">
            <stop offset="0" style="stop-color: var(--orb-lila)" stop-opacity="0"/>
            <stop offset=".45" style="stop-color: var(--orb-lila)" stop-opacity=".85"/>
            <stop offset="1" style="stop-color: var(--orb-violet)" stop-opacity=".1"/>
        </linearGradient>
        <linearGradient id="orb-b" x1="1" y1="0" x2="0" y2="1">
            <stop offset="0" style="stop-color: var(--orb-violet)" stop-opacity=".9"/>
            <stop offset=".6" style="stop-color: var(--orb-rosa)" stop-opacity=".35"/>
            <stop offset="1" style="stop-color: var(--orb-rosa)" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="orb-c" x1="0" y1="1" x2="1" y2="0">
            <stop offset="0" style="stop-color: var(--orb-ice)" stop-opacity="0"/>
            <stop offset=".5" style="stop-color: var(--orb-ice)" stop-opacity=".7"/>
            <stop offset="1" style="stop-color: var(--orb-lila)" stop-opacity=".2"/>
        </linearGradient>
        <radialGradient id="orb-core">
            <stop offset="0" style="stop-color: var(--orb-lila)" stop-opacity=".45"/>
            <stop offset=".35" style="stop-color: var(--orb-violet)" stop-opacity=".14"/>
            <stop offset="1" style="stop-color: var(--orb-violet)" stop-opacity="0"/>
        </radialGradient>
        <filter id="orb-glow" x="-50%" y="-50%" width="200%" height="200%">
            <feGaussianBlur stdDeviation="3" result="b"/>
            <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
    </defs>

    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="260" fill="url(#orb-core)" class="orbits__core"/>
    {{-- Equator line --}}
    <line x1="0" y1="{{ $cy }}" x2="800" y2="{{ $cy }}" stroke="url(#orb-a)" stroke-width="1" pathLength="1" class="orbits__path orbits__equator" style="--i: 0"/>

    <g class="orbits__spin">
        @foreach ($orbits as $i => $o)
            <g transform="rotate({{ $o['rot'] }} {{ $cx }} {{ $cy }})">
                <path id="orbit-{{ $i }}" d="{{ $ellipse($o) }}" pathLength="1" class="orbits__path" style="--i: {{ $i }}; stroke: {{ $o['stroke'] }}" fill="none" stroke-width="{{ $o['w'] }}"/>
                <circle r="{{ $i === 0 ? 4 : 3 }}" class="orbits__sat" style="--i: {{ $i }}; fill: var(--orb-{{ ['lila', 'rosa', 'ice', 'lila', 'violet'][$i] }})" filter="url(#orb-glow)">
                    <animateMotion dur="{{ $o['dur'] }}s" repeatCount="indefinite" begin="-{{ $i * 3 }}s">
                        <mpath href="#orbit-{{ $i }}"/>
                    </animateMotion>
                </circle>
            </g>
        @endforeach
    </g>

    {{-- Four-point star from the EQUINOX mark --}}
    <g class="orbits__star-in">
        <g class="orbits__star" filter="url(#orb-glow)">
            <path d="M400 352 C404 388 412 396 448 400 C412 404 404 412 400 448 C396 412 388 404 352 400 C388 396 396 388 400 352 Z" style="fill: var(--orb-star)"/>
        </g>
    </g>
</svg>
