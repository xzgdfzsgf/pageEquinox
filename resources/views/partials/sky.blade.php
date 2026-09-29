{{-- Line-art celestial bodies, in the same language as the globe and the orbital "atom".
     Each has its own character and tint (--sky-tint, set per placement in depth.css):
     ringed (lilac, tilted, Saturn-like rings) · moon (ice blue full moon) · gas (pink giant with drifting bands and a storm). --}}
@php
    $id = 'sky-'.$kind;
    $c = 100;
    $R = ['ringed' => 48, 'moon' => 46, 'gas' => 52][$kind];
    // Meridians "rotate" like the globe's: each ellipse's rx cycles through |cos| with a phase offset.
    $meridians = [];
    foreach ([0, 45, 90, 135] as $phase) {
        $vals = [];
        for ($s = 0; $s <= 24; $s++) {
            $vals[] = round($R * abs(cos(deg2rad($phase + $s * 7.5))), 1);
        }
        $meridians[] = $vals;
    }
    // A wavy cloud band, two periods wider than the planet so it can drift seamlessly.
    $wave = function (float $y, float $amp, float $len) use ($c, $R) {
        $x = $c - $R - 2 * $len;
        $d = "M{$x} {$y}";
        for ($i = 0; $i < 8; $i++) {
            $d .= sprintf(' q%.1f %.1f %.1f 0', $len / 2, ($i % 2 ? $amp : -$amp) * 2, $len);
            $x += $len;
        }
        return $d;
    };
@endphp
<svg class="sky__svg" viewBox="0 0 200 200" focusable="false">
    <defs>
        <radialGradient id="{{ $id }}-fill" cx=".34" cy=".3" r=".85">
            <stop offset="0" style="stop-color: var(--sky-tint)" stop-opacity=".26"/>
            <stop offset=".6" style="stop-color: var(--sky-tint)" stop-opacity=".06"/>
            <stop offset="1" style="stop-color: var(--sky-tint)" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="{{ $id }}-halo">
            <stop offset=".5" style="stop-color: var(--sky-tint)" stop-opacity=".3"/>
            <stop offset="1" style="stop-color: var(--sky-tint)" stop-opacity="0"/>
        </radialGradient>
        <linearGradient id="{{ $id }}-ring" x1="0" x2="1">
            <stop offset="0" style="stop-color: var(--grad-b)" stop-opacity=".2"/>
            <stop offset=".4" style="stop-color: var(--grad-a)"/>
            <stop offset="1" style="stop-color: var(--grad-c)" stop-opacity=".45"/>
        </linearGradient>
        <clipPath id="{{ $id }}-clip"><circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}"/></clipPath>
        <clipPath id="{{ $id }}-front"><rect x="-50" y="{{ $c }}" width="300" height="150"/></clipPath>
        <filter id="{{ $id }}-glow" x="-40%" y="-40%" width="180%" height="180%">
            <feGaussianBlur stdDeviation="3" result="b"/>
            <feMerge><feMergeNode in="b"/><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
    </defs>

    <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R + 34 }}" fill="url(#{{ $id }}-halo)"/>

    @if ($kind === 'ringed')
        {{-- Tilted Saturn: the whole system leans on its axis --}}
        <g transform="rotate(-22 {{ $c }} {{ $c }})">
            {{-- rings, back half (hidden by the body) --}}
            <g fill="none" stroke="url(#{{ $id }}-ring)">
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="98" ry="23" stroke-width="1"/>
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="89" ry="21" stroke-width="5" stroke-opacity=".35"/>
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="74" ry="17" stroke-width="2.4" stroke-opacity=".55"/>
            </g>
            <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" style="fill: var(--navy)"/>
            <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="url(#{{ $id }}-fill)"/>
            <g clip-path="url(#{{ $id }}-clip)" fill="none" style="stroke: var(--sky-tint)">
                <g stroke-width=".8" opacity=".5">
                    @foreach ([-32, -16, 16, 32] as $lat)
                        <ellipse cx="{{ $c }}" cy="{{ $c + $lat }}" rx="{{ round(sqrt($R * $R - $lat * $lat), 1) }}" ry="6"/>
                    @endforeach
                </g>
                <g stroke-width=".7" opacity=".42">
                    @foreach ($meridians as $vals)
                        <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="{{ $vals[0] }}" ry="{{ $R }}">
                            <animate attributeName="rx" values="{{ implode(';', $vals) }}" dur="30s" repeatCount="indefinite"/>
                        </ellipse>
                    @endforeach
                </g>
                {{-- the rings' faint shadow across the planet --}}
                <ellipse cx="{{ $c }}" cy="{{ $c + 12 }}" rx="89" ry="21" stroke-width="1" stroke-dasharray="1 3" opacity=".55"/>
            </g>
            <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="none" style="stroke: var(--sky-tint)" stroke-width="1.6" filter="url(#{{ $id }}-glow)"/>
            {{-- rings, front half --}}
            <g fill="none" stroke="url(#{{ $id }}-ring)" clip-path="url(#{{ $id }}-front)">
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="98" ry="23" stroke-width="1"/>
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="89" ry="21" stroke-width="5" stroke-opacity=".35"/>
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="89" ry="21" stroke-width="1.4" filter="url(#{{ $id }}-glow)"/>
                <ellipse cx="{{ $c }}" cy="{{ $c }}" rx="74" ry="17" stroke-width="2.4" stroke-opacity=".55"/>
            </g>
        </g>

    @elseif ($kind === 'moon')
        {{-- Full moon: a glowing rim, soft maria and craters across the whole face --}}
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" style="fill: var(--navy)"/>
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="url(#{{ $id }}-fill)"/>
        <g clip-path="url(#{{ $id }}-clip)">
            {{-- maria (the darker "seas") --}}
            <g style="fill: var(--sky-tint)" fill-opacity=".1">
                <path d="M78 76 C86 68 102 70 106 80 C110 90 98 98 88 96 C78 94 72 84 78 76Z"/>
                <path d="M108 96 C116 90 130 94 132 104 C134 114 122 120 114 116 C106 112 102 102 108 96Z"/>
                <path d="M84 110 C90 106 100 110 98 118 C96 126 86 126 82 120 C80 116 80 112 84 110Z"/>
            </g>
            {{-- craters, each with a small rim highlight --}}
            <g fill="none" style="stroke: var(--sky-tint)" stroke-width=".9" opacity=".6">
                <circle cx="118" cy="72" r="6"/><circle cx="72" cy="100" r="4.2"/><circle cx="104" cy="128" r="5"/>
                <circle cx="128" cy="120" r="2.8"/><circle cx="92" cy="86" r="2.4"/><circle cx="66" cy="80" r="2"/>
                <circle cx="112" cy="104" r="1.8"/><circle cx="86" cy="134" r="1.6"/>
            </g>
            <g fill="none" style="stroke: var(--sky-tint)" stroke-width=".6" opacity=".35">
                <path d="M113 69 A6 6 0 0 1 121 67"/><path d="M69 97 A4.2 4.2 0 0 1 75 96"/><path d="M100 124 A5 5 0 0 1 107 124"/>
                {{-- rays from the brightest crater --}}
                <path d="M118 72 L138 60 M118 72 L104 56 M118 72 L136 84" stroke-dasharray="1 3"/>
            </g>
        </g>
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="none" style="stroke: var(--sky-tint)" stroke-width="2" filter="url(#{{ $id }}-glow)"/>
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R + 9 }}" fill="none" style="stroke: var(--sky-tint)" stroke-width=".7" stroke-opacity=".3" stroke-dasharray="1.5 5"/>
        {{-- two twinkling four-point stars beside it --}}
        @foreach ([[160, 58, 7, 0], [150, 150, 4.5, 1.6]] as [$sx, $sy, $sr, $delay])
            <path class="sky__twinkle" style="fill: var(--sky-tint); animation-delay: -{{ $delay }}s"
                  d="M{{ $sx }} {{ $sy - $sr }} C{{ $sx + $sr * .12 }} {{ $sy - $sr * .12 }} {{ $sx + $sr * .12 }} {{ $sy - $sr * .12 }} {{ $sx + $sr }} {{ $sy }} C{{ $sx + $sr * .12 }} {{ $sy + $sr * .12 }} {{ $sx + $sr * .12 }} {{ $sy + $sr * .12 }} {{ $sx }} {{ $sy + $sr }} C{{ $sx - $sr * .12 }} {{ $sy + $sr * .12 }} {{ $sx - $sr * .12 }} {{ $sy + $sr * .12 }} {{ $sx - $sr }} {{ $sy }} C{{ $sx - $sr * .12 }} {{ $sy - $sr * .12 }} {{ $sx - $sr * .12 }} {{ $sy - $sr * .12 }} {{ $sx }} {{ $sy - $sr }}Z"/>
        @endforeach

    @else
        {{-- Gas giant: wavy cloud bands drifting sideways, a great storm, two moons on different orbits --}}
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" style="fill: var(--navy)"/>
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="url(#{{ $id }}-fill)"/>
        <g clip-path="url(#{{ $id }}-clip)" fill="none" style="stroke: var(--sky-tint)" stroke-linecap="round">
            <g>
                <animateTransform attributeName="transform" type="translate" from="0 0" to="52 0" dur="14s" repeatCount="indefinite"/>
                @foreach ([[-34, 2.2, .45, 1], [-20, 3, .7, 1.4], [-6, 2.4, .5, 1], [8, 3.4, .8, 1.6], [22, 2.6, .55, 1], [36, 2, .4, 1]] as [$dy, $amp, $op, $w])
                    <path d="{{ $wave($c + $dy, $amp, 26) }}" stroke-width="{{ $w }}" opacity="{{ $op }}"/>
                @endforeach
            </g>
            {{-- the great storm --}}
            <g transform="translate(118 114)">
                <ellipse rx="13" ry="7.5" stroke-width="1.3"/>
                <ellipse rx="8" ry="4.4" stroke-width=".9" opacity=".75"/>
                <ellipse rx="3.4" ry="1.8" stroke-width=".8" opacity=".6">
                    <animateTransform attributeName="transform" type="rotate" from="0" to="360" dur="9s" repeatCount="indefinite"/>
                </ellipse>
            </g>
        </g>
        <circle cx="{{ $c }}" cy="{{ $c }}" r="{{ $R }}" fill="none" style="stroke: var(--sky-tint)" stroke-width="1.6" filter="url(#{{ $id }}-glow)"/>
        @foreach ([['a', 82, 18, -18, 13, 3.2, 'var(--sky-tint)'], ['b', 94, 32, 24, 22, 2.2, 'var(--accent)']] as [$k, $rx, $ry, $tilt, $dur, $r, $fill])
            <g transform="rotate({{ $tilt }} {{ $c }} {{ $c }})">
                <path id="{{ $id }}-orbit-{{ $k }}" d="M{{ $c - $rx }} {{ $c }} a{{ $rx }} {{ $ry }} 0 1 0 {{ 2 * $rx }} 0 a{{ $rx }} {{ $ry }} 0 1 0 -{{ 2 * $rx }} 0"
                      fill="none" style="stroke: var(--sky-tint)" stroke-opacity=".28" stroke-dasharray="1 5"/>
                <circle r="{{ $r }}" style="fill: {{ $fill }}" filter="url(#{{ $id }}-glow)">
                    <animateMotion dur="{{ $dur }}s" repeatCount="indefinite"><mpath href="#{{ $id }}-orbit-{{ $k }}"/></animateMotion>
                </circle>
            </g>
        @endforeach
    @endif
</svg>
