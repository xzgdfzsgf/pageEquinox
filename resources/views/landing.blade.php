@php
    $title = 'EQUINOX 2027 — Where Space Meets at the Equator';
    $description = 'EQUINOX: The Equatorial Space Economy. A global gathering on investment, business and new opportunities in space. March 16–19, 2027 · Quito, Ecuador.';

    // Photos are optional: drop them in public/img and they replace the illustrated placeholders.
    $photo = fn (string $name) => file_exists(public_path("img/{$name}")) ? asset("img/{$name}") : null;
    $heroPhoto = $photo('hero.jpg');
    $conferencePhoto = $photo('conference.jpg');
    $stargatePhoto = $photo('stargate.jpg');

    // Starfield: three tileable layers (far → near) that drift at different speeds on scroll,
    // plus a handful of twinkling stars that stay fixed.
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

    // Hero wordmark with each letter wrapped in its own group so they can rise in one by one.
    [$wmHead, $wmBody] = explode('</defs>', file_get_contents(resource_path('svg/logo-word.svg')), 2);
    $heroWordmark = $wmHead.'</defs>'.preg_replace('/<path ([^>]*?)\/>/', '<g class="wm-l"><path $1/></g>', $wmBody);

    $marquee = ['March 16–19, 2027', 'Quito · Ecuador', '0° Latitude', 'The Equatorial Space Economy', 'Conference + Stargate', 'Investment · Business · New Opportunities'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            var d = document.documentElement;
            d.classList.add('js');
            // Style: ?theme=black|violet in the URL wins (handy for sharing), otherwise the last choice. Black is the default.
            try {
                var styles = ['black', 'violet'];
                var q = new URLSearchParams(location.search).get('theme');
                if (styles.indexOf(q) < 0) q = null;
                if (q) localStorage.setItem('equinox-theme', q);
                var t = q || localStorage.getItem('equinox-theme');
                d.dataset.theme = styles.indexOf(t) >= 0 ? t : 'black';
            } catch (e) {
                d.dataset.theme = 'black';
            }
        })();
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#0E1540">
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url('/') }}">
    @if ($photo('og.jpg'))
        <meta property="og:image" content="{{ $photo('og.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/motion.css', 'resources/css/depth.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => 'EQUINOX 2027 — The Equatorial Space Economy',
        'description' => $description,
        'startDate' => '2027-03-16',
        'endDate' => '2027-03-19',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'location' => [
            ['@type' => 'Place', 'name' => 'UDLA Park', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Quito', 'addressCountry' => 'EC']],
            ['@type' => 'Place', 'name' => 'Astralintu Stargate', 'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Urcuquí', 'addressCountry' => 'EC']],
        ],
        'organizer' => ['@type' => 'Organization', 'name' => 'Fundación Sideralis'],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

{{-- Fixed starfield behind everything --}}
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
<div class="scroll-progress" aria-hidden="true"></div>

{{-- ============ HEADER ============ --}}
<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="#top" class="brand" aria-label="EQUINOX — home">
            <span class="brand__mark">{!! file_get_contents(resource_path('svg/logo-mark.svg')) !!}</span>
            <span class="brand__word">{!! file_get_contents(resource_path('svg/logo-word.svg')) !!}</span>
        </a>

        <nav class="site-nav" id="site-nav" aria-label="Main">
            <ul>
                <li><a href="#about">The Event</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#themes">Themes</a></li>
            </ul>
            <div class="site-nav__ctas">
                <a href="#join" class="btn btn--primary btn--sm" data-open="join">Join the list</a>
                <a href="#join" class="btn btn--ghost btn--sm" data-open="sponsor">Become a sponsor</a>
            </div>
        </nav>

        <a href="#join" class="btn btn--primary btn--sm header-cta" data-open="join">Join<span class="header-cta__more">&nbsp;the list</span></a>

        <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" data-nav-toggle>
            <span class="sr-only">Open menu</span>
            <x-icon name="menu" class="nav-toggle__open" />
            <x-icon name="close" class="nav-toggle__close" />
        </button>
    </div>
</header>

<main id="main">

    {{-- ============ 01 · HERO ============ --}}
    <section class="hero hero--center" id="top" data-hero @if ($heroPhoto) style="--hero-photo: url('{{ $heroPhoto }}')" @endif>
        {{-- Planet limb on the horizon, with dawn light rising behind it --}}
        <div class="hero__horizon" aria-hidden="true">
            <div class="sunrise">
                <span class="sunrise__bloom"></span>
            </div>
        </div>

        <div class="container hero__content">
            <p class="hero__meta intro" style="--d: .05s">
                <span><x-icon name="calendar" /> March 16–19, 2027</span>
                <span><x-icon name="pin" /> Quito, Ecuador</span>
            </p>

            <h1 class="hero__title">
                <span class="sr-only">EQUINOX</span>
                <span class="hero__mark" aria-hidden="true">{!! file_get_contents(resource_path('svg/logo-mark.svg')) !!}</span>
                <span class="hero__wordmark" aria-hidden="true">{!! $heroWordmark !!}</span>
            </h1>
            <p class="hero__tagline intro" style="--d: .35s">Where space meets at the equator</p>

            <p class="hero__subtitle intro" style="--d: .42s">
                <strong>The Equatorial Space Economy</strong>
                <span>Investment, Business and New Opportunities</span>
            </p>

            <p class="hero__lead intro" style="--d: .48s">
                A global gathering connecting the space industry, governments, investors, companies, academia and
                emerging talent around the opportunities shaping the next generation of the space economy.
            </p>

            <div class="hero__ctas intro" style="--d: .52s">
                <a href="#join" class="btn btn--primary btn--lg" data-open="join">Join the list <x-icon name="arrow-right" /></a>
                <a href="#join" class="btn btn--ghost btn--lg" data-open="sponsor">Become a sponsor</a>
            </div>
            <p class="status-pill intro" style="--d: .6s"><span class="status-pill__dot"></span> Registration opening soon</p>
        </div>

        <a href="#about" class="hero__scroll intro" style="--d: .9s" aria-label="Scroll to learn more"><span></span></a>
    </section>

    {{-- Marquee band --}}
    <div class="marquee" data-marquee>
        <div class="marquee__track" aria-hidden="true">
            @foreach ([1, 2] as $copy)
                @foreach ($marquee as $i => $item)
                    <span @class(['marquee__item', 'marquee__item--outline' => $i % 2])>{{ $item }}</span>
                    <svg class="marquee__star" viewBox="0 0 24 24"><path d="M12 0C12.6 8.4 15.6 11.4 24 12 15.6 12.6 12.6 15.6 12 24 11.4 15.6 8.4 12.6 0 12 8.4 11.4 11.4 8.4 12 0Z" fill="currentColor"/></svg>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ============ 02 · THE IDEA ============ --}}
    <section class="section idea" id="about">
        <div class="container idea__grid">
            <div class="idea__text">
            <p class="eyebrow reveal">The idea</p>
            <h2 class="display reveal">The space economy is entering a <em>new orbit.</em></h2>
            <div class="idea__cols">
                <p class="reveal">
                    Space is evolving beyond traditional markets and established centers. New satellite architectures,
                    commercial infrastructure, emerging markets, investment and growing demand for connectivity are
                    creating new opportunities across the global space ecosystem.
                </p>
                <p class="reveal">
                    <strong>EQUINOX brings these conversations to the equator</strong> — from Quito, leaders from
                    industry, government, investment, academia and the emerging space ecosystem explore how geography,
                    infrastructure, technology and talent are shaping what comes next.
                </p>
            </div>
            </div>
            <div class="idea__visual" data-orbits data-depth=".08" aria-hidden="true">
                <div class="idea__visual-inner">
                    @include('partials.orbits')
                </div>
            </div>
        </div>
    </section>

    {{-- ============ 03 · WHY THE EQUATOR ============ --}}
    <section class="section equator" id="why-equator">
        <div class="container equator__grid">
            <div>
                <p class="eyebrow reveal">Why the equator</p>
                <h2 class="display reveal">Why the equator?</h2>
                <p class="section-sub reveal">A new perspective on the space economy.</p>
                <p class="lead reveal">
                    At 0° latitude, Ecuador offers a unique geographic position from which to explore the future of
                    space infrastructure, connectivity and access.
                </p>
                <ul class="chips reveal">
                    @foreach ([
                        ['satellite', 'Satellite communications'],
                        ['dish', 'Ground infrastructure'],
                        ['wifi', 'Connectivity'],
                        ['rocket', 'Launch and access to space'],
                        ['globe', 'Emerging markets'],
                        ['trending', 'Investment and business'],
                    ] as [$icon, $label])
                        <li class="chip"><span class="icon-ring"><x-icon :name="$icon" /></span>{{ $label }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="equator__visual reveal" data-globe>
                @include('partials.globe')
                {{-- Invisible hit area exactly over the Quito marker: opens the map of Ecuador --}}
                <button type="button" class="globe-pin" data-open-map aria-haspopup="dialog" aria-controls="modal-map">
                    <span class="globe-pin__hint" aria-hidden="true">Explore the map</span>
                    <span class="sr-only">Open the map of Ecuador with the EQUINOX venues</span>
                </button>
            </div>
        </div>
    </section>

    {{-- ============ 04 · AT A GLANCE ============ --}}
    <section class="section glance" id="glance">
        <div class="container">
            <p class="eyebrow reveal">EQUINOX at a glance</p>
            <h2 class="sr-only">EQUINOX at a glance</h2>
            <dl class="stats">
                <div class="stat reveal">
                    <dt class="stat__label">March 16–19, 2027</dt>
                    <dd class="stat__value"><span data-count="4">4</span> <small>Days</small></dd>
                </div>
                <div class="stat reveal">
                    <dt class="stat__label">International and regional speakers</dt>
                    <dd class="stat__value"><span data-count="30">30</span>–<span data-count="40">40</span></dd>
                </div>
                <div class="stat reveal">
                    <dt class="stat__label">Experiences: Conference and Stargate</dt>
                    <dd class="stat__value"><span data-count="2">2</span></dd>
                </div>
                <div class="stat reveal">
                    <dt class="stat__label">Latitude, Quito and Urcuquí</dt>
                    <dd class="stat__value">0°</dd>
                </div>
            </dl>
        </div>
    </section>

    {{-- ============ 05 · WHAT TO EXPECT ============ --}}
    <section class="section expect" id="expect">
        <div class="container">
            <p class="eyebrow reveal">What to expect</p>
            <h2 class="display reveal">What to expect</h2>
            <div class="cards cards--4">
                @foreach ([
                    ['mic', 'High-level conversations', 'Keynotes, expert panels, industry sessions and fireside conversations.'],
                    ['users', 'Business & networking', 'Connect with companies, investors, governments, agencies and emerging players.'],
                    ['globe', 'International & regional voices', "Leaders from the global space industry and LatAm's space ecosystem."],
                    ['dish', 'A technical experience at 0°', "Real equatorial infrastructure at Astralintu's Stargate."],
                ] as [$icon, $heading, $text])
                    <article class="card reveal">
                        <span class="icon-ring icon-ring--lg"><x-icon :name="$icon" /></span>
                        <h3>{{ $heading }}</h3>
                        <p>{{ $text }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 06 · THE EQUINOX EXPERIENCE ============ --}}
    <section class="section experience" id="experience">
        <div class="container">
            <p class="eyebrow reveal">The EQUINOX experience</p>
            <h2 class="display reveal">One event. <em>Two experiences.</em></h2>

            <div class="experiences">
                <article class="exp reveal">
                    <div class="exp__media @unless ($conferencePhoto) exp__media--placeholder exp__media--conference @endunless">
                        @if ($conferencePhoto)
                            <img src="{{ $conferencePhoto }}" alt="UDLA Park venue in Quito" loading="lazy">
                        @else
                            @include('partials.illus-conference')
                        @endif
                        <span class="exp__num">01</span>
                    </div>
                    <div class="exp__body">
                        <h3>The Conference</h3>
                        <p class="exp__meta"><span><x-icon name="calendar" /> March 16–18</span> <span><x-icon name="pin" /> UDLA Park, Quito</span></p>
                        <p>
                            Three days of high-level conversations: keynotes, panels, industry sessions, fireside
                            conversations, business meetings and networking.
                        </p>
                        <a href="#join" class="link-arrow" data-open="join">Get conference updates <x-icon name="arrow-right" /></a>
                    </div>
                </article>

                <article class="exp exp--stargate reveal">
                    <div class="exp__media @unless ($stargatePhoto) exp__media--placeholder exp__media--stargate @endunless">
                        @if ($stargatePhoto)
                            <img src="{{ $stargatePhoto }}" alt="Astralintu's Stargate equatorial teleport under construction in Urcuquí" loading="lazy">
                        @else
                            @include('partials.illus-stargate')
                        @endif
                        <span class="exp__num">02</span>
                    </div>
                    <div class="exp__body">
                        <h3>The Stargate Inauguration</h3>
                        <p class="exp__meta"><span><x-icon name="calendar" /> March 19</span> <span><x-icon name="pin" /> Urcuquí, 0°00′ latitude</span></p>
                        <p class="exp__kicker">From conversation to infrastructure.</p>
                        <p>
                            Technical visit and inauguration of Astralintu's first high-capacity equatorial teleport.
                        </p>
                        <a href="#join" class="link-arrow" data-open="join">Get Stargate updates <x-icon name="arrow-right" /></a>
                    </div>
                </article>
            </div>
        </div>
    </section>

    {{-- ============ 06B · THE VENUES ============ --}}
    <section class="section venue" id="venue">
        <div class="container">
            <p class="eyebrow reveal">Where it happens</p>
            <h2 class="display reveal">Two places. <em>Zero latitude.</em></h2>

            <div class="venue-group reveal">
                <div class="venue-group__head">
                    <div>
                        <p class="exp__kicker">Venue 01</p>
                        <h3>UDLA Park</h3>
                    </div>
                    <p class="exp__meta"><span><x-icon name="pin" /> Quito, Ecuador</span> <span><x-icon name="calendar" /> March 16–18</span> <span class="venue-coord">0°13′S · 78°31′W</span></p>
                </div>
                <div class="venue-photos">
                    <div class="venue-frame venue-frame--hero">
                        <img class="venue-photo" src="{{ asset('img/venue-udla-1.jpg') }}" alt="UDLA Park campus in Quito, aerial view" loading="lazy">
                        <span class="venue-photos__num" aria-hidden="true">01</span>
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-udla-2.jpg') }}" alt="Auditorium at UDLA Park during a conference" loading="lazy">
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-udla-3.jpg') }}" alt="UDLA Park glass architecture" loading="lazy">
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                </div>
            </div>

            <div class="venue-group reveal">
                <div class="venue-group__head">
                    <div>
                        <p class="exp__kicker">Venue 02</p>
                        <h3>The Stargate</h3>
                    </div>
                    <p class="exp__meta"><span><x-icon name="pin" /> Urcuquí, Ecuador</span> <span><x-icon name="calendar" /> March 19</span> <span class="venue-coord">0°23′N · 78°11′W</span></p>
                </div>
                <div class="venue-photos">
                    <div class="venue-frame venue-frame--hero">
                        <img class="venue-photo" src="{{ asset('img/venue-stargate-1.jpg') }}" alt="Astralintu team on site at the Stargate construction" loading="lazy">
                        <span class="venue-photos__num" aria-hidden="true">02</span>
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-stargate-2.jpg') }}" alt="Stargate teleport structure under construction" loading="lazy">
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-stargate-3.jpg') }}" alt="Stargate site foundation, aerial view" loading="lazy">
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                </div>
            </div>

            <div class="venue__cta reveal">
                <button type="button" class="btn btn--ghost btn--lg" data-open-map aria-haspopup="dialog" aria-controls="modal-map">
                    <x-icon name="pin" /> See both venues on the map
                </button>
            </div>
        </div>
    </section>

    {{-- ============ 07 · CONFERENCE THEMES ============ --}}
    <section class="section themes" id="themes">
        <div class="sky sky--themes" data-depth=".3" aria-hidden="true">@include('partials.sky', ['kind' => 'ringed'])</div>
        <div class="container">
            <p class="eyebrow reveal">Conference themes</p>
            <h2 class="display reveal">Five conversations shaping <em>the next orbit.</em></h2>
            <ol class="theme-list">
                @foreach ([
                    ['The Equatorial Advantage', 'Unlocking opportunities for satellite operations, communications, launch, ground infrastructure and access to space.'],
                    ['The Orbital Shift', 'Polar, mid-inclination and equatorial orbits transforming coverage and connectivity.'],
                    ['Space for Defense & Security', 'Communications, situational awareness, resilience and security.'],
                    ["Space Governance & Int'l Cooperation", 'Policies, partnerships and frameworks for a connected space ecosystem.'],
                    ['The Business of Space & Building the Space Future', 'Markets, investment models, infrastructure and talent for the next generation.'],
                ] as $i => [$heading, $text])
                    <li class="theme reveal">
                        <span class="theme__num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="theme__title">{{ $heading }}</h3>
                        <p class="theme__text">{{ $text }}</p>
                        <span class="theme__arrow" aria-hidden="true"><x-icon name="arrow-right" /></span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ 08 · WHO WILL BE IN THE ROOM ============ --}}
    <section class="section audience" id="audience">
        <div class="sky sky--audience" data-depth=".4" aria-hidden="true">@include('partials.sky', ['kind' => 'moon'])</div>
        <div class="container">
            <p class="eyebrow reveal">Who will be in the room</p>
            <h2 class="display reveal">A curated global &amp; <em>equatorial</em> audience.</h2>
            <div class="cards cards--audience">
                @foreach ([
                    ['orbit', 'Global Space Industry', 'International companies and decision-makers from leading and emerging markets.'],
                    ['globe', 'Equatorial Players', 'Companies and institutions connecting Latin America, Africa and Asia.'],
                    ['landmark', 'Governments & Space Agencies', 'Policy-makers shaping national and regional strategies.'],
                    ['briefcase', 'Investors & Business Leaders', 'Exploring new markets, infrastructure and commercial opportunities.'],
                    ['cap', 'Academia & Emerging Talent', 'Universities, researchers and next-generation professionals.'],
                ] as [$icon, $heading, $text])
                    <article class="card card--row reveal">
                        <span class="icon-ring"><x-icon :name="$icon" /></span>
                        <div>
                            <h3>{{ $heading }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ 09 · MORE INFORMATION COMING SOON ============ --}}
    <section class="section more" id="more">
        <div class="container">
            <div class="more__panel reveal">
                <p class="eyebrow">More information coming soon</p>
                <ul class="soon-list">
                    @foreach (['Speakers', 'Quito', 'The Stargate', 'Sideralis', 'Partners', 'Sponsorship details'] as $item)
                        <li>{{ $item }} <span class="soon-tag">Soon</span></li>
                    @endforeach
                </ul>
                <p class="more__text">More information coming soon — join the list below to get updates as it's announced.</p>
            </div>
        </div>
    </section>

    {{-- ============ 10 · JOIN THE LIST ============ --}}
    <section class="section join" id="join">
        <div class="sky sky--join" data-depth=".22" aria-hidden="true">@include('partials.sky', ['kind' => 'gas'])</div>
        <div class="container join__grid">
            <div class="join__intro">
                <p class="eyebrow reveal">Join the list</p>
                <h2 class="display reveal">Registration opening soon. <em>Be the first to know.</em></h2>
                <p class="lead reveal">
                    Join the EQUINOX list and receive registration updates, speaker announcements, program releases and
                    event news before anyone else.
                </p>

                <div class="sponsor-cta reveal">
                    <span class="icon-ring"><x-icon name="orbit" /></span>
                    <div>
                        <p class="sponsor-cta__title">Interested in partnering with EQUINOX?</p>
                        <a href="#join" class="btn btn--ghost" data-open="sponsor">Become a sponsor <x-icon name="arrow-right" /></a>
                    </div>
                </div>
            </div>

            <div class="join__card reveal">
                @if (session('join_success') || session('sponsor_success'))
                    <div class="form-success" role="status">
                        <span class="icon-ring icon-ring--lg"><x-icon name="check" /></span>
                        <p>{{ session('join_success') ?? session('sponsor_success') }}</p>
                    </div>
                @else
                    @if ($errors->any())
                        <div class="form-errors" role="alert">
                            <p>Please review the form:</p>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    @include('partials.join-form', ['prefix' => 'join', 'source' => 'join-section'])
                @endif
            </div>
        </div>
    </section>

    {{-- ============ 11 · FINAL CLOSING ============ --}}
    <section class="closing" id="closing">
        <div class="closing__glow" aria-hidden="true"></div>
        <div class="closing__rings" aria-hidden="true"><span></span><span></span><span></span></div>
        <div class="container closing__inner">
            <div class="closing__logo reveal" role="img" aria-label="EQUINOX — Where space meets at the equator">
                {!! file_get_contents(resource_path('svg/logo-full.svg')) !!}
            </div>
            <p class="closing__meta reveal">March 16–19, 2027 <span aria-hidden="true">·</span> Quito, Ecuador</p>
            <div class="hero__ctas hero__ctas--center reveal">
                <a href="#join" class="btn btn--primary btn--lg" data-open="join">Join the list <x-icon name="arrow-right" /></a>
                <a href="#join" class="btn btn--ghost btn--lg" data-open="sponsor">Become a sponsor</a>
            </div>
            <p class="status-pill reveal"><span class="status-pill__dot"></span> Registration opening soon</p>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container site-footer__grid">
        <div>
            <a href="#top" class="brand brand--footer" aria-label="EQUINOX — back to top">
                <span class="brand__mark">{!! file_get_contents(resource_path('svg/logo-mark.svg')) !!}</span>
                <span class="brand__word">{!! file_get_contents(resource_path('svg/logo-word.svg')) !!}</span>
            </a>
            <p class="site-footer__tag">Where Space Meets at the Equator</p>
            <p class="site-footer__org">An event by <strong>Fundación Sideralis</strong>, Quito, Ecuador</p>
        </div>

        <nav aria-label="Footer">
            <p class="site-footer__heading">Explore</p>
            <ul>
                <li><a href="#about">The Event</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#themes">Themes</a></li>
                <li><a href="#join" data-open="sponsor">Become a sponsor</a></li>
            </ul>
        </nav>

        <div>
            <p class="site-footer__heading">Contact</p>
            <ul class="contact-list">
                <li><x-icon name="mail" /><a href="mailto:dmera@sideralisfoundation.org">dmera@sideralisfoundation.org</a></li>
                <li><x-icon name="mail" /><a href="mailto:bbustamante@sideralisfoundation.org">bbustamante@sideralisfoundation.org</a></li>
                <li><x-icon name="phone" /><a href="https://wa.me/593967116195" rel="noopener" target="_blank">+593 967 116 195</a></li>
            </ul>
            @if (config('equinox.social'))
                <ul class="social">
                    @foreach (config('equinox.social') as $network => $url)
                        <li><a href="{{ $url }}" rel="noopener" target="_blank">{{ $network }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="container site-footer__bottom">
        <p>© {{ now()->year }} EQUINOX · Fundación Sideralis</p>
        <p>0°00′00″ · Quito, Ecuador</p>
    </div>
</footer>

@include('partials.companion')

{{-- Review dock: cursor companion + page style (navy is the brand default). Collapsible to present the page clean. --}}
<div class="dock" data-dock>
    <div class="dock__panel" id="dock-panel">
        <div class="theme-switch companion-switch" role="group" aria-label="Cursor companion">
            <span class="theme-switch__label">Cursor</span>
            <button type="button" data-companion-set="hummingbird" aria-pressed="true" aria-label="Hummingbird follows the cursor">
                <svg class="dock__glyph" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M23 6.2 16.6 7a3 3 0 0 0-5.4 1.3L3.5 3c1 3.9 2.8 6.6 5.2 8-1.8 1.8-3.9 4.9-5.6 9.8 3-2.4 5.8-3.9 8-4.6 2.8-1 4.6-3.3 4.7-6.1l.1-1.2Z"/></svg>
                Hummingbird
            </button>
            <button type="button" data-companion-set="off" aria-pressed="false" aria-label="No cursor companion">
                <svg class="dock__glyph" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><path d="m6.5 6.5 11 11"/></svg>
                Off
            </button>
        </div>
        <div class="theme-switch style-switch" role="group" aria-label="Page style">
            <span class="theme-switch__label">Style</span>
            <span class="theme-switch__current" data-theme-name>Black</span>
            @foreach (['black' => 'Black', 'violet' => 'Violet'] as $key => $name)
                <button type="button" data-theme-set="{{ $key }}" data-theme-label="{{ $name }}" aria-pressed="{{ $key === 'black' ? 'true' : 'false' }}" aria-label="{{ $name }} style" title="{{ $name }}">
                    <span class="theme-switch__swatch theme-switch__swatch--{{ $key }}"></span> {{ $name }}
                </button>
            @endforeach
        </div>
    </div>
    <button type="button" class="dock__toggle" aria-controls="dock-panel" aria-expanded="true" aria-label="Hide style options" data-dock-toggle>
        <svg class="icon dock__icon-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M4 7h10M18 7h2M4 17h4M12 17h8"/><circle cx="16" cy="7" r="2"/><circle cx="10" cy="17" r="2"/></svg>
        <svg class="icon dock__icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
</div>

{{-- ============ MODALS ============ --}}
<dialog class="modal" id="modal-join" aria-labelledby="modal-join-title">
    <div class="modal__inner">
        <button type="button" class="modal__close" data-close aria-label="Close"><x-icon name="close" /></button>
        <p class="eyebrow">Registration opening soon</p>
        <h2 id="modal-join-title" class="modal__title">Join the EQUINOX list</h2>
        <p class="modal__lead">Be the first to know when registration opens, plus speaker announcements and program releases.</p>
        @include('partials.join-form', ['prefix' => 'mj', 'source' => 'modal'])
    </div>
</dialog>

<dialog class="modal modal--map" id="modal-map" aria-labelledby="modal-map-heading">
    <div class="modal__inner">
        <button type="button" class="modal__close" data-close aria-label="Close"><x-icon name="close" /></button>
        <p class="eyebrow">0° · Ecuador</p>
        <h2 id="modal-map-heading" class="modal__title">Where EQUINOX happens</h2>
        <div class="map-frame" data-map>
            @include('partials.ecuador-map')
        </div>
        <p class="map-swipe" aria-hidden="true">← Swipe to see the Galápagos Islands</p>
        <ul class="map-legend">
            <li><span class="map-legend__dot map-legend__dot--conference"></span><span><strong>The Conference</strong> UDLA Park, Quito · March 16–18</span></li>
            <li><span class="map-legend__dot map-legend__dot--stargate"></span><span><strong>The Stargate Inauguration</strong> Urcuquí, ≈ 75 km north-east of Quito · March 19</span></li>
            <li><span class="map-legend__line"></span><span><strong>0° latitude</strong> The equator crosses Ecuador just north of Quito</span></li>
        </ul>
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
</body>
</html>
