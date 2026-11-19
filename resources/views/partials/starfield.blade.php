@php
    // Starfield: three tileable layers (far → near) that drift at different speeds on scroll,
    // plus a handful of twinkling stars that stay fixed. Shared by every page.
    mt_srand(2027);
    $starTile = function (int $count, array $radius, array $opacity) {
        $circles = '';
        for ($i = 0; $i < $count; $i++) {
            $circles .= sprintf('<circle cx="%d" cy="%d" r="%.1f" fill="#fff" fill-opacity="%.2f"/>',
                mt_rand(0, 1200), mt_rand(0, 1000), mt_rand(...$radius) / 10, mt_rand(...$opacity) / 100);
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="1000">'.$circles.'</svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    };
    $starLayers = [
        ['speed' => 0.03, 'src' => $starTile(110, [3, 8], [15, 55])],
        ['speed' => 0.07, 'src' => $starTile(45, [6, 12], [30, 75])],
        ['speed' => 0.13, 'src' => $starTile(16, [10, 17], [45, 90])],
    ];
    $twinkles = collect(range(1, 28))->map(fn () => [
        'x' => mt_rand(0, 1600), 'y' => mt_rand(0, 1000), 'r' => mt_rand(8, 16) / 10, 'd' => mt_rand(0, 60) / 10,
    ]);
@endphp
<div class="starfield" aria-hidden="true">
    @foreach ($starLayers as $layer)
        <div class="starfield__layer" data-speed="{{ $layer['speed'] }}" style="background-image: url('{{ $layer['src'] }}')"></div>
    @endforeach
    <svg class="starfield__twinkles" viewBox="0 0 1600 1000" preserveAspectRatio="xMidYMid slice" focusable="false">
        @foreach ($twinkles as $s)
            <circle cx="{{ $s['x'] }}" cy="{{ $s['y'] }}" r="{{ $s['r'] }}" fill="#fff" style="animation-delay: -{{ $s['d'] }}s"/>
        @endforeach
    </svg>
    <span class="shooting-star"></span>
</div>
