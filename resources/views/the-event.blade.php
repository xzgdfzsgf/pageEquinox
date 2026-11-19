@extends('layouts.app')

@section('content')

    {{-- ============ PAGE HERO ============ --}}
    <section class="plan-hero" id="top">
        <div class="plan-hero__glow" aria-hidden="true"></div>
        <div class="sky sky--plan" data-depth=".3" aria-hidden="true">@include('partials.sky', ['kind' => 'ringed'])</div>
        <div class="container plan-hero__content">
            <p class="eyebrow intro" style="--d: .05s">The Event</p>
            <h1 class="plan-hero__title intro" style="--d: .12s">One event.<br><em>Two experiences.</em></h1>
            <p class="lead intro" style="--d: .2s">
                Three days of high-level conversations in Quito, followed by a technical visit to real equatorial
                infrastructure at 0° latitude.
            </p>
            <ul class="plan-hero__facts intro" style="--d: .28s">
                <li><x-icon name="calendar" /> March 16–19, 2027</li>
                <li><x-icon name="pin" /> Quito &amp; Urcuquí, Ecuador</li>
                <li><x-icon name="globe" /> 0° latitude</li>
            </ul>
        </div>
    </section>

    {{-- ============ THE VENUES · where it happens (moved from the homepage) ============ --}}
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
                        <span class="venue-frame__caption">Auditorium</span>
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-udla-3.jpg') }}" alt="UDLA Park glass architecture" loading="lazy">
                        <span class="venue-frame__caption">Campus</span>
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
                        <span class="venue-frame__caption">Under construction</span>
                        <span class="venue-frame__shine" aria-hidden="true"></span>
                    </div>
                    <div class="venue-frame">
                        <img class="venue-photo" src="{{ asset('img/venue-stargate-3.jpg') }}" alt="Stargate site foundation, aerial view" loading="lazy">
                        <span class="venue-frame__caption">Site · aerial view</span>
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

    {{-- ============ CONFERENCE THEMES (moved from the homepage) ============ --}}
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

    {{-- ============ THE CONFERENCE PROGRAMME (Canva slides 11–12) ============ --}}
    <section class="section plan-section" id="details">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="orbit" /></span> The conference</p>
            <h2 class="display reveal">The EQUINOX <em>programme.</em></h2>
            <p class="lead reveal">
                Three days of high-level conversations bringing together international, local and regional industry
                leaders, space agencies, investors, companies, government representatives, academics and emerging talent.
            </p>
            <ul class="plan-hero__facts reveal" style="margin-top: 20px;">
                <li><x-icon name="calendar" /> March 16–18, 2027</li>
                <li><x-icon name="pin" /> UDLA Park, Quito</li>
            </ul>

            <div class="cards programme-grid">
                @foreach ([
                    ['mic', 'Keynote speakers', 'Leading voices setting the agenda for the equatorial space era.'],
                    ['users', 'Expert panels', 'Cross-sector discussions on the industry\'s most pressing questions.'],
                    ['satellite', 'Industry sessions', 'Focused sessions across the key segments of the space economy.'],
                    ['coffee', 'Fireside conversations', 'Candid, in-depth exchanges with the people shaping the field.'],
                    ['briefcase', 'Business meetings', 'Curated spaces to build partnerships and open new markets.'],
                    ['trending', 'Networking', 'Connections across the global and equatorial space community.'],
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

    {{-- ============ WHO WILL BE IN THE ROOM (moved from the homepage) ============ --}}
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

    {{-- ============ CLOSING CTA ============ --}}
    <section class="section plan-section plan-section--cta">
        <div class="container">
            <div class="plan-cta reveal">
                <p class="eyebrow">Be part of it</p>
                <h2 class="plan-cta__title">Join us at EQUINOX 2027.</h2>
                <p class="more__text">
                    Join the EQUINOX list to get registration updates, speaker announcements and programme releases
                    before anyone else.
                </p>
                <div class="hero__ctas hero__ctas--center">
                    <a href="{{ route('home') }}#join" class="btn btn--primary btn--lg" data-open="join">Join the list <x-icon name="arrow-right" /></a>
                    <a href="{{ route('plan') }}" class="btn btn--ghost btn--lg">Plan your visit <x-icon name="arrow-right" /></a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page-modals')
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
                <li><span class="map-legend__dot map-legend__dot--stargate"></span><span><strong>The Stargate Visit</strong> Urcuquí, ≈ 75 km north-east of Quito · March 19</span></li>
                <li><span class="map-legend__line"></span><span><strong>0° latitude</strong> The equator crosses Ecuador just north of Quito</span></li>
            </ul>
        </div>
    </dialog>
@endsection
