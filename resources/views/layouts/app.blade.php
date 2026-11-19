@php
    $ogImage = file_exists(public_path('img/og.jpg')) ? asset('img/og.jpg') : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            // The page style is fixed to black across the whole site.
            var d = document.documentElement;
            d.classList.add('js');
            d.dataset.theme = 'black';
        })();
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#08080A">
    <link rel="icon" href="{{ asset('img/favicon.svg') }}" type="image/svg+xml">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/motion.css', 'resources/css/depth.css', 'resources/js/app.js'])

    @isset($jsonLd)
        <script type="application/ld+json">
        {!! $jsonLd !!}
        </script>
    @endisset
</head>
<body class="@yield('body-class')">
<a class="skip-link" href="#main">Skip to content</a>

{{-- Fixed starfield behind everything (hidden on pages with their own backdrop) --}}
@include('partials.starfield')
<div class="scroll-progress" aria-hidden="true"></div>

@include('partials.site-header')

<main id="main">
    @yield('content')
</main>

@include('partials.site-footer')

@include('partials.companion')
@include('partials.dock')
@include('partials.modals')

@yield('page-modals')
</body>
</html>
