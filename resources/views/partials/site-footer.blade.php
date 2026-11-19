@php
    $home = request()->routeIs('home');
    $to = fn (string $hash) => $home ? $hash : route('home').$hash;
@endphp
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div>
            <a href="{{ $home ? '#top' : route('home') }}" class="brand brand--footer" aria-label="EQUINOX — back to top">
                <span class="brand__mark">{!! file_get_contents(resource_path('svg/logo-mark.svg')) !!}</span>
                <span class="brand__word">{!! file_get_contents(resource_path('svg/logo-word.svg')) !!}</span>
            </a>
            <p class="site-footer__tag">Where Space Meets at the Equator</p>
            <p class="site-footer__org">An event by <strong>Fundación Sideralis</strong>, Quito, Ecuador</p>
        </div>

        <nav aria-label="Footer">
            <p class="site-footer__heading">Explore</p>
            <ul>
                <li><a href="{{ route('event') }}">The Event</a></li>
                <li><a href="{{ $to('#themes') }}">Themes</a></li>
                <li><a href="{{ $to('#speakers') }}">Speakers</a></li>
                <li><a href="{{ route('plan') }}">Plan your visit</a></li>
                <li><a href="{{ $to('#join') }}" data-open="sponsor">Become a sponsor</a></li>
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
