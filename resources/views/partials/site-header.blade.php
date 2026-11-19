@php
    // On the home page the section links scroll smoothly; from any other page they jump back home first.
    $home = request()->routeIs('home');
    $to = fn (string $hash) => $home ? $hash : route('home').$hash;
@endphp
<header class="site-header" data-header>
    <div class="container site-header__inner">
        <a href="{{ $home ? '#top' : route('home') }}" class="brand" aria-label="EQUINOX — home">
            <span class="brand__mark">{!! file_get_contents(resource_path('svg/logo-mark.svg')) !!}</span>
            <span class="brand__word">{!! file_get_contents(resource_path('svg/logo-word.svg')) !!}</span>
        </a>

        <nav class="site-nav" id="site-nav" aria-label="Main">
            <ul>
                <li><a href="{{ route('event') }}">The Event</a></li>
                <li><a href="{{ route('plan') }}">Plan your visit</a></li>
            </ul>
            <div class="site-nav__ctas">
                <a href="{{ $to('#join') }}" class="btn btn--primary btn--sm" data-open="join">Join the list</a>
                <a href="{{ $to('#join') }}" class="btn btn--ghost btn--sm" data-open="sponsor">Become a sponsor</a>
            </div>
        </nav>

        <a href="{{ $to('#join') }}" class="btn btn--primary btn--sm header-cta" data-open="join">Join<span class="header-cta__more">&nbsp;the list</span></a>

        <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" data-nav-toggle>
            <span class="sr-only">Open menu</span>
            <x-icon name="menu" class="nav-toggle__open" />
            <x-icon name="close" class="nav-toggle__close" />
        </button>
    </div>
</header>
