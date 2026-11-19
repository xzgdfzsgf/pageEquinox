@extends('layouts.app')

@section('content')
@php
    // Photos are optional: drop them in public/img and they replace the illustrated placeholders.
    $photo = fn (string $name) => file_exists(public_path("img/{$name}")) ? asset("img/{$name}") : null;
    $heroPhoto = $photo('hero.jpg');
    $conferencePhoto = $photo('conference.jpg');
    $stargatePhoto = $photo('stargate.jpg');

    // Hero wordmark with each letter wrapped in its own group so they can rise in one by one.
    [$wmHead, $wmBody] = explode('</defs>', file_get_contents(resource_path('svg/logo-word.svg')), 2);
    $heroWordmark = $wmHead.'</defs>'.preg_replace('/<path ([^>]*?)\/>/', '<g class="wm-l"><path $1/></g>', $wmBody);

    $marquee = ['March 16–19, 2027', 'Quito · Ecuador', '0° Latitude', 'The Equatorial Space Economy', 'Conference + Stargate', 'Investment · Business · New Opportunities'];
@endphp

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
            <p class="status-pill intro" style="--d: .3s"><span class="status-pill__dot"></span> Registration opening soon</p>
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
            <div class="equator__visual reveal">
                @include('partials.globe')
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

    {{-- ============ 06B · THE EQUINOX EXPERIENCE ============ --}}
    <section class="section experience" id="experience">
        <div class="container">
            <p class="eyebrow reveal">The EQUINOX experience</p>
            <h2 class="display reveal">One event. <em>Two experiences.</em></h2>
            <p class="lead reveal">
                Three days of high-level conversations in Quito, followed by a technical visit to real equatorial
                infrastructure at 0° latitude.
            </p>

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
                        <a href="{{ route('event') }}" class="link-arrow">See the conference <x-icon name="arrow-right" /></a>
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
                        <h3>The Stargate Visit</h3>
                        <p class="exp__meta"><span><x-icon name="calendar" /> March 19</span> <span><x-icon name="pin" /> Urcuquí, 0°00′ latitude</span></p>
                        <p class="exp__kicker">From conversation to infrastructure.</p>
                        <p>
                            Technical visit of Astralintu's first high-capacity equatorial teleport.
                        </p>
                        <a href="{{ route('event') }}" class="link-arrow">See the Stargate visit <x-icon name="arrow-right" /></a>
                    </div>
                </article>
            </div>

            <div class="hero__ctas hero__ctas--center reveal" style="margin-top: 40px;">
                <a href="{{ route('event') }}" class="btn btn--primary btn--lg">See more <x-icon name="arrow-right" /></a>
            </div>
        </div>
    </section>

    {{-- ============ 07B · SPEAKERS ============ --}}
    <section class="section speakers" id="speakers">
        <div class="container">
            <p class="eyebrow reveal">Speakers</p>
            <h2 class="display reveal">Voices shaping <em>the next orbit.</em></h2>
            <p class="lead reveal">
                Discover some of the voices that will be part of EQUINOX 2027, where global perspectives
                meet at 0° latitude.
            </p>

            @php
                // Each speaker: [name, role, photo slug]. Drop a photo named "<slug>.jpg"
                // (or .png/.webp) into public/img/speakers/ and it replaces the initials automatically.
                // Los cinco ponentes en una sola fila, sin separar nacionales de internacionales.
                $speakers = [
                    ['Roberto Kury', 'Minister of Foreign Affairs and Human Mobility', 'roberto-kury'],
                    ['Gral. Michael Carey', 'Co-Founder, Atlas Space Operations · Partner, Elara Nova', 'michael-carey'],
                    ['Madin Maseeh', 'President, Maldives Space Research Organisation (MSRO)', 'madin-maseeh'],
                    ['Jim Bingen', 'Head of Corporate & Enterprise Sales, D-Orbit', 'jim-bingen'],
                    ['Matías Campos', 'Founder, Astralintu', 'matias-campos'],
                ];
                $initials = function (string $name) {
                    $clean = trim(preg_replace('/^(Gral\.|Dr\.|Dra\.|Ing\.|Lic\.)\s*/i', '', $name));
                    $parts = preg_split('/\s+/', $clean);
                    $first = mb_substr($parts[0] ?? '', 0, 1);
                    $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';
                    return mb_strtoupper($first . $last);
                };
                $speakerPhoto = function (string $slug) {
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                        if (file_exists(public_path("img/speakers/{$slug}.{$ext}"))) {
                            return asset("img/speakers/{$slug}.{$ext}");
                        }
                    }
                    return null;
                };
            @endphp

            <div class="speakers__grid">
                @foreach ($speakers as [$name, $role, $slug])
                    @php ($photo = $speakerPhoto($slug))
                    <article class="card speaker reveal">
                        <div class="speaker__photo">
                            @if ($photo)
                                <img src="{{ $photo }}" alt="{{ $name }}" loading="lazy">
                            @else
                                <span class="speaker__initials" aria-hidden="true">{{ $initials($name) }}</span>
                            @endif
                        </div>
                        <div class="speaker__body">
                            <h4 class="speaker__name">{{ $name }}</h4>
                            <p class="speaker__role">{{ $role }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="speakers__soon reveal" style="text-align: right;">
                <span class="status-pill"><span class="status-pill__dot"></span> More speakers coming soon</span>
            </div>
        </div>
    </section>

    {{-- ============ 09 · MORE INFORMATION COMING SOON ============ --}}
    <section class="section more" id="more">
        <div class="container">
            <div class="more__panel reveal">
                <p class="eyebrow">More information coming soon</p>
                <p class="more__text">Join the list below to get updates as it's announced.</p>
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
@endsection
