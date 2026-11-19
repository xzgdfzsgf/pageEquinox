@extends('layouts.app')

@section('body-class', 'page-ecuador')

@section('content')

@php
    // Resolves a Plan-page image if it exists, otherwise null (graceful fallback).
    $planImg = fn (string $name) => file_exists(public_path("img/plan/{$name}.jpg")) ? asset("img/plan/{$name}.jpg") : null;
    // Hero slideshow: whichever of these exist, in order.
    $heroSlides = collect(['hero-quito', 'hero-2', 'hero-3', 'hero-4'])
        ->map(fn ($n) => $planImg($n))->filter()->values();
@endphp

    {{-- ============ QUITO LOADER ============ --}}
    <div class="quito-loader" data-quito-loader role="presentation">
        <div class="quito-loader__inner">
            <svg class="quito-loader__sky" viewBox="0 44 260 126" preserveAspectRatio="xMidYMax meet" aria-hidden="true">
                <defs>
                    <linearGradient id="ql-grad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0" stop-color="#ef2a48"/>
                        <stop offset="1" stop-color="#2f6be0"/>
                    </linearGradient>
                    {{-- Quito skyline silhouette (Virgen del Panecillo, Basílica and city landmarks).
                         Used as a mask so the red→blue flag gradient fills it left→right like a progress bar. --}}
                    <mask id="ql-sky" maskUnits="userSpaceOnUse" x="0" y="0" width="260" height="280">
                        <g fill="#fff" fill-rule="nonzero">
                            <path fill-rule="nonzero" d="m218.36 221.08c0 5.36 0.03 5.59 0.68 5.59 0.61 0 0.67-0.23 0.67-2.37 0-1.31 0.12-2.37 0.26-2.38 0.14 0 1.09 1.07 2.11 2.38 1.51 1.91 2.04 2.37 2.8 2.37h0.93l-3.66-4.91c1.87-1.21 2.16-1.67 2.24-2.55 0.06-0.65 0.02-1.52-0.08-1.94-0.11-0.42-0.65-0.99-1.21-1.27-0.56-0.28-1.85-0.51-2.88-0.51h-1.86z"/>
                            <path fill-rule="nonzero" d="m219.88 217.07c-0.15 0.13-0.25 0.89-0.22 1.7 0.05 1.43 0.08 1.46 1.21 1.46 0.64 0 1.4-0.22 1.7-0.5 0.29-0.28 0.53-0.82 0.53-1.19 0-0.37-0.34-0.91-0.76-1.19-0.42-0.28-1.08-0.51-1.47-0.5-0.39 0-0.83 0.1-0.99 0.22z"/>
                            <path fill-rule="nonzero" d="m154.76 221.04l-0.05 5.63c4.2 0 5.92-0.2 6.52-0.45 0.61-0.24 1.48-0.97 1.95-1.61 0.7-0.96 0.84-1.54 0.84-3.42 0-1.73-0.16-2.47-0.67-3.17-0.38-0.51-1.11-1.24-1.64-1.64-0.76-0.57-1.56-0.74-3.93-0.84l-2.98-0.13z"/>
                            <path fill-rule="nonzero" d="m156.28 217.26c-0.11 0.26-0.16 2.14-0.12 4.18l0.08 3.7c4.18-0.12 4.49-0.23 5.33-1.16 0.75-0.84 0.93-1.36 0.93-2.73 0-1.48-0.15-1.84-1.18-2.88-0.96-0.96-1.45-1.18-2.54-1.18-0.75 0-1.57-0.09-1.83-0.21-0.26-0.11-0.56 0.01-0.67 0.28z"/>
                            <path fill-rule="nonzero" d="m124.81 221.08c-2.55 5.49-2.58 5.59-1.75 5.59 0.68 0 1.02-0.34 1.69-1.7l0.85-1.69h4.4c1.52 3.05 1.86 3.39 2.54 3.39 0.84 0 0.84-0.02-1.05-3.98-1.05-2.19-2.23-4.71-2.63-5.6-0.4-0.89-0.89-1.61-1.09-1.61-0.2 0.01-1.53 2.53-2.96 5.6z"/>
                            <path fill-rule="nonzero" d="m126.96 220.23l-0.68 1.36c2.1 0 2.7-0.15 2.7-0.34 0-0.19-0.22-0.8-0.5-1.35-0.28-0.56-0.58-1.02-0.68-1.02-0.09 0-0.47 0.61-0.84 1.35z"/>
                            <path fill-rule="nonzero" d="m93.83 220.26c0.11 4.72 0.13 4.78 1.05 5.59 0.51 0.45 1.42 0.91 2.03 1.02 0.65 0.13 1.5 0.02 2.08-0.25 0.54-0.25 1.31-0.89 1.7-1.42 0.63-0.85 0.7-1.43 0.71-5.33 0.01-4.32 0-4.38-0.76-4.38-0.76 0-0.77 0.07-0.77 4.15 0 3.14-0.12 4.33-0.51 4.91-0.35 0.52-0.85 0.77-1.61 0.77-0.62 0.01-1.35-0.25-1.68-0.59-0.49-0.51-0.59-1.3-0.59-4.92l-0.01-4.32h-1.75z"/>
                            <path fill-rule="nonzero" d="m34.19 221.08v5.59c6.21 0 6.44-0.05 6.44-0.68 0-0.61-0.26-0.69-2.46-0.76l-2.45-0.09-0.17-3.55c5.02 0 5.08-0.02 5.08-0.85 0-0.81-0.09-0.85-2.46-0.93l-2.45-0.08v-2.66c4.15 0.11 4.63 0.01 4.9-0.47 0.18-0.33 0.21-0.72 0.08-0.86-0.13-0.15-1.65-0.26-3.38-0.25h-3.13z"/>
                            <path fill-rule="nonzero" d="m188.4 215.83c-0.75 0.37-1.7 1.17-2.13 1.78-0.43 0.6-0.87 1.79-0.98 2.62-0.11 0.84-0.03 2.06 0.17 2.71 0.2 0.65 0.9 1.68 1.56 2.29 0.67 0.6 1.86 1.26 2.65 1.46 0.79 0.2 1.89 0.25 2.45 0.13 0.56-0.13 1.44-0.45 1.95-0.7 0.51-0.26 1.31-0.96 1.77-1.57 0.46-0.6 0.94-1.63 1.06-2.28 0.12-0.66 0.12-1.76 0-2.46-0.12-0.73-0.68-1.8-1.31-2.51-0.6-0.68-1.59-1.44-2.2-1.69-0.6-0.25-1.67-0.45-2.37-0.45-0.7 0-1.88 0.3-2.62 0.67z"/>
                            <path fill-rule="nonzero" d="m189.32 217.2c-0.86 0.4-1.63 1.07-1.94 1.68-0.28 0.56-0.5 1.55-0.5 2.2 0 0.65 0.22 1.64 0.5 2.2 0.28 0.56 0.97 1.25 1.53 1.52 0.55 0.28 1.58 0.51 2.28 0.5 0.7-0.01 1.61-0.2 2.03-0.42 0.42-0.23 1.07-0.87 1.44-1.43 0.37-0.56 0.68-1.58 0.68-2.28 0-0.7-0.23-1.73-0.51-2.29-0.28-0.56-0.93-1.22-1.44-1.48-0.51-0.25-1.31-0.55-1.78-0.66-0.52-0.12-1.42 0.06-2.29 0.46z"/>
                            <path fill-rule="nonzero" d="m65.01 215.63c-0.65 0.24-1.57 0.96-2.06 1.59-0.48 0.64-0.99 1.76-1.13 2.51-0.14 0.75-0.1 1.95 0.09 2.7 0.19 0.75 0.66 1.78 1.04 2.29 0.38 0.51 1.19 1.18 1.8 1.48 0.6 0.31 1.93 0.56 2.96 0.56 1.04 0.01 2.31-0.22 2.88-0.51 0.55-0.29 1.28-0.85 1.6-1.24 0.57-0.68 0.57-0.73 0-1.01-0.42-0.21-0.93-0.05-1.77 0.52-0.82 0.56-1.66 0.81-2.71 0.81q-1.52 0-2.46-0.68c-0.51-0.38-1.17-1.19-1.48-1.79-0.37-0.75-0.48-1.53-0.34-2.46 0.12-0.83 0.56-1.71 1.14-2.29 0.52-0.52 1.39-1.1 1.95-1.29q1.02-0.35 2.37-0.01c0.75 0.19 1.66 0.65 2.03 1.03 0.51 0.51 0.83 0.6 1.27 0.36 0.47-0.26 0.52-0.49 0.26-1.01-0.19-0.37-0.99-0.98-1.78-1.35-0.79-0.37-2.12-0.67-2.96-0.66-0.84 0-2.05 0.2-2.7 0.45z"/>
                            <path fill-rule="nonzero" d="m128.13 172.92c-0.09 0.24-0.13 7.89-0.08 17.01l0.09 16.59h5.41l0.18-34.02c-4.58 0-5.49 0.13-5.6 0.42z"/>
                            <path fill-rule="nonzero" d="m156.07 174.53v2.37l11.34 0.17 0.17 29.28h5.41v-29.45l11.25-0.17 0.1-4.57h-28.27z"/>
                            <path fill-rule="nonzero" d="m78.27 185.11c0.12 12.28 0.17 13.05 0.86 14.81 0.45 1.17 1.27 2.37 2.18 3.23 0.81 0.75 2 1.68 2.65 2.08 0.65 0.4 2.1 0.99 3.21 1.3 1.52 0.44 2.94 0.55 5.59 0.45 3.01-0.1 3.84-0.26 5.42-1.04 1.02-0.5 2.51-1.52 3.3-2.28 0.79-0.75 1.72-1.94 2.07-2.64 0.35-0.7 0.81-2.07 1.02-3.04 0.24-1.13 0.38-6.19 0.38-13.8v-12.02h-5.42c0.01 20.36-0.11 24.58-0.38 25.39-0.21 0.65-0.72 1.64-1.13 2.2-0.4 0.56-1.49 1.39-2.4 1.85-1.34 0.66-2.15 0.82-4.04 0.79-1.9-0.03-2.74-0.23-4.21-1-1.4-0.73-2.02-1.32-2.63-2.48-0.79-1.52-0.8-1.55-0.79-14.14l0.01-12.61h-5.81z"/>
                            <path fill-rule="nonzero" d="m218.7 172.17c-1.21 0.35-3.19 1.14-4.4 1.75-1.2 0.62-3.18 2.05-4.4 3.19-1.27 1.19-2.65 2.91-3.27 4.06-0.59 1.1-1.32 2.83-1.61 3.86-0.3 1.02-0.55 3.04-0.54 4.48 0 1.55 0.25 3.42 0.6 4.57 0.33 1.07 1.13 2.77 1.78 3.78 0.65 1.01 2.11 2.69 3.24 3.73 1.14 1.04 3.2 2.44 4.57 3.13 1.38 0.68 3.72 1.5 5.21 1.82 2.02 0.43 3.7 0.54 6.6 0.42 3.09-0.12 4.43-0.34 6.44-1.06 1.39-0.5 3.51-1.55 4.7-2.34 1.2-0.79 2.95-2.28 3.9-3.32 0.95-1.03 2.13-2.7 2.63-3.72 0.5-1.01 1.06-2.53 1.26-3.37 0.19-0.84 0.36-2.66 0.37-4.06 0.01-1.6-0.23-3.35-0.65-4.74-0.37-1.21-1.12-2.89-1.67-3.72-0.55-0.84-2-2.48-3.23-3.64-1.42-1.35-3.13-2.55-4.69-3.29-1.35-0.64-3.44-1.4-4.65-1.69-1.35-0.31-3.72-0.51-6.1-0.5-2.75 0.02-4.53 0.21-6.09 0.66z"/>
                            <path fill-rule="nonzero" d="m222.68 176.27c-1.26 0.16-3.24 0.72-4.41 1.23-1.22 0.55-2.94 1.72-4.08 2.8-1.26 1.17-2.33 2.59-2.95 3.88-0.53 1.12-1.07 2.87-1.19 3.89-0.15 1.27-0.05 2.57 0.33 4.07 0.31 1.21 0.93 2.77 1.39 3.47 0.46 0.69 1.48 1.89 2.27 2.66 0.79 0.76 2.2 1.78 3.13 2.24 0.94 0.47 2.38 1.06 3.22 1.32 0.84 0.25 2.9 0.46 4.57 0.46 1.84 0.01 3.79-0.21 4.91-0.54 1.02-0.3 2.55-0.89 3.39-1.32 0.83-0.42 2.24-1.47 3.13-2.33 0.88-0.86 1.97-2.29 2.41-3.17 0.45-0.89 0.98-2.45 1.18-3.47 0.26-1.29 0.27-2.54 0.04-4.06-0.18-1.21-0.62-2.81-0.98-3.56-0.36-0.74-1.28-2-2.04-2.79-0.76-0.79-2.1-1.9-2.98-2.46-0.89-0.56-2.53-1.31-3.64-1.67-1.12-0.35-2.8-0.71-3.73-0.79-0.93-0.09-2.72-0.03-3.97 0.14z"/>
                            <path fill-rule="nonzero" d="m30.55 171.5c-0.97 0.18-2.73 0.68-3.89 1.11-1.16 0.44-2.99 1.35-4.06 2.04-1.07 0.68-2.58 1.88-3.35 2.67-0.78 0.8-1.9 2.2-2.51 3.14-0.6 0.93-1.43 2.68-1.84 3.89-0.64 1.86-0.73 2.73-0.61 5.58 0.12 2.79 0.31 3.75 1.08 5.42 0.51 1.12 1.44 2.72 2.05 3.56 0.62 0.83 2.02 2.21 3.11 3.06 1.09 0.85 2.67 1.94 3.51 2.42 0.83 0.48 2.66 1.23 4.06 1.67 2.09 0.65 3.28 0.79 6.77 0.79 3.78 0 4.57-0.1 7.36-1.01l3.12-1.01c1.48 1.47 1.81 1.52 4.83 1.44l3.29-0.09c-2.21-2.41-3.05-3.36-3.28-3.67-0.36-0.47-0.17-0.87 1.31-2.67 0.96-1.17 2.01-2.65 2.34-3.3 0.32-0.66 0.84-2.03 1.14-3.05 0.3-1.02 0.55-3 0.55-4.4 0-1.56-0.25-3.39-0.66-4.74-0.36-1.21-1.26-3.12-2-4.23-0.73-1.12-1.92-2.56-2.64-3.2-0.72-0.64-2.22-1.75-3.34-2.47-1.12-0.72-3.25-1.69-4.74-2.16-1.97-0.62-3.68-0.89-6.26-0.98-1.96-0.07-4.36 0.01-5.34 0.19z"/>
                            <path fill-rule="nonzero" d="m30.47 176.5c-1.21 0.32-2.96 1.02-3.89 1.57-0.94 0.55-2.23 1.48-2.88 2.07-0.65 0.59-1.67 1.97-2.26 3.06-0.63 1.16-1.23 2.92-1.43 4.2-0.23 1.45-0.23 2.77-0.02 3.89 0.18 0.93 0.7 2.45 1.15 3.38 0.45 0.94 1.55 2.4 2.45 3.25 0.9 0.85 2.55 2.02 3.66 2.59 1.12 0.57 2.95 1.2 4.07 1.41 1.11 0.21 2.79 0.37 3.72 0.36 0.93-0.01 2.61-0.21 3.72-0.44 1.12-0.24 2.26-0.56 2.54-0.71 0.4-0.21-0.13-0.9-2.37-3.11l-2.88-2.84 6.78 0.02c2.23 2.09 3.03 2.69 3.21 2.69 0.19 0 0.74-0.49 1.23-1.1 0.49-0.6 1.16-1.63 1.49-2.28 0.33-0.66 0.79-2.18 1.02-3.39 0.32-1.69 0.32-2.63 0.03-4.06-0.22-1.03-0.76-2.63-1.2-3.56-0.45-0.93-1.63-2.44-2.62-3.35-1-0.91-2.72-2.08-3.84-2.58-1.12-0.51-2.83-1.1-3.81-1.31-0.98-0.2-2.65-0.37-3.72-0.36-1.07 0.02-2.94 0.28-4.15 0.6z"/>
                            <path fill-rule="nonzero" d="m106.3 48.42c0 0.63-0.18 0.85-0.67 0.85-0.38 0-0.68 0.15-0.68 0.34 0 0.19 0.3 0.34 0.68 0.34 0.58 0 0.67 0.22 0.67 1.69 0 1.12-0.45 2.93-1.32 5.33-0.72 2-1.75 5.74-2.28 8.3-0.53 2.56-1.08 4.66-1.22 4.66-0.14-0.01-0.43-0.58-0.64-1.28-0.21-0.7-0.47-1.42-0.57-1.61-0.1-0.18-0.45 0.43-0.78 1.36-0.43 1.25-0.59 2.85-0.61 6.09-0.03 3.8-0.12 4.54-0.7 5.42-0.58 0.87-0.68 1.59-0.67 4.91 0 2.14-0.14 4.04-0.32 4.23-0.18 0.2-0.41 11.59-0.55 27.76-0.13 15.08-0.32 27.57-0.42 27.76-0.1 0.19-0.14 3.46-0.1 7.28 0.07 6.04 0.01 7.01-0.47 7.45-0.3 0.28-0.54 1-0.53 1.6 0.01 0.95-0.1 1.09-0.75 1.02-0.57-0.06-0.79-0.34-0.88-1.1-0.06-0.56-0.25-1.17-0.42-1.35-0.16-0.19-0.29-4.91-0.29-10.5 0.01-8.02-0.08-10.19-0.43-10.32-0.24-0.1-0.44-0.4-0.44-0.68 0-0.28-0.22-0.52-0.5-0.54-0.28-0.02-0.7-0.06-0.93-0.09-0.26-0.03-0.51-0.81-0.62-1.99-0.11-1.08-0.27-2.22-0.34-2.54-0.08-0.33-0.26-0.6-0.4-0.6-0.14 0-0.44 1.14-0.68 2.54-0.25 1.53-0.59 2.56-0.84 2.58-0.24 0.02-0.73 0.01-1.1-0.03-0.37-0.04-0.98-0.46-1.36-0.93-0.51-0.65-0.88-0.82-1.52-0.67-0.47 0.1-0.96 0.5-1.1 0.89-0.21 0.59-0.53 0.7-2.03 0.69-1.58-0.01-1.9-0.14-2.88-1.19-0.63-0.67-1.32-1.92-1.61-2.95-0.4-1.38-0.44-2.18-0.17-3.63q0.34-1.87 0-2.21-0.34-0.33-1.86 0l-1.51 0.34c-0.02-5.02-0.19-6.1-0.68-7.11-0.36-0.74-1.12-1.62-1.69-1.94-0.56-0.33-1.1-0.6-1.2-0.6-0.09 0-0.17 0.39-0.17 0.85 0 0.68-0.17 0.85-0.84 0.85q-0.85-0.01-1.16-0.76c-0.17-0.42-0.3-1.22-0.29-1.78 0-0.56-0.22-1.55-0.5-2.2l-0.51-1.19c-0.93 2.16-1.08 3.09-0.96 3.64 0.11 0.53 0.06 0.93-0.14 0.93-0.18 0-0.34 0.31-0.34 0.68 0 0.53-0.22 0.68-1.01 0.68-0.86 0-1.02-0.13-1.02-0.85 0-0.72-0.11-0.81-0.73-0.59-0.4 0.14-1.09 0.67-1.52 1.18-0.44 0.52-0.95 1.51-1.13 2.2-0.19 0.7-0.34 2.38-0.34 3.73v2.45c-1.31-0.52-2.07-0.67-2.54-0.67-0.47 0-0.92 0.22-1.02 0.5-0.09 0.28-0.04 0.7 0.1 0.93 0.15 0.24 0.3 1.15 0.34 2.04 0.05 1.05-0.19 2.21-0.69 3.35-0.42 0.96-1.18 2.1-1.69 2.54-0.68 0.57-1.35 0.79-2.46 0.79-1.26 0-1.62-0.15-2.11-0.87-0.43-0.63-0.83-0.84-1.44-0.76-0.48 0.06-0.99 0.44-1.19 0.87-0.25 0.57-0.62 0.76-1.43 0.76-0.95 0-1.15-0.15-1.45-1.1-0.19-0.6-0.34-1.67-0.34-2.37 0.01-0.7-0.14-1.34-0.33-1.44-0.19-0.09-0.41-0.13-0.5-0.08-0.08 0.04-0.24 0.84-0.36 1.78-0.11 0.93-0.27 2.03-0.35 2.45-0.1 0.54-0.42 0.79-1.08 0.85-0.66 0.06-0.9 0.25-0.84 0.67 0.04 0.33-0.15 0.67-0.43 0.77-0.43 0.14-0.5 1.59-0.5 10.32 0 6.43-0.13 10.22-0.34 10.33-0.19 0.09-0.34 0.7-0.34 1.35 0 1.04-0.11 1.19-0.85 1.19-0.46 0-0.84-0.16-0.84-0.34 0-0.19-0.31-3.81-0.68-8.04-0.37-4.24-0.75-8.28-0.84-8.98-0.16-1.16-0.09-1.3 0.85-1.69 0.56-0.23 1.09-0.69 1.18-1.01 0.09-0.33 0.01-0.94-0.18-1.36-0.19-0.42-1.33-1.41-2.54-2.2-1.81-1.18-2.2-1.6-2.21-2.37 0-0.51-0.23-1.31-0.49-1.78-0.27-0.46-0.49-1.49-0.5-2.28 0-0.79-0.16-1.52-0.35-1.61-0.21-0.1-0.25-1.03-0.11-2.45l0.23-2.29c-1.62 1.84-2.19 2.3-2.31 2.2-0.12-0.09-0.06-2.99 0.14-6.43 0.2-3.44 0.4-7.57 0.45-9.17 0.06-2.41-0.05-3.18-0.69-4.57l-0.77-1.66 1.59-5.08c1.07 2.1 1.76 3.16 2.22 3.72 0.46 0.56 1.62 1.51 2.58 2.12 0.96 0.6 1.98 1.02 2.26 0.93 0.28-0.09 0.51-0.47 0.51-0.85 0-0.37-0.41-1.32-0.9-2.11-0.49-0.79-1.09-1.97-1.31-2.63-0.23-0.65-0.63-2.92-0.88-5.04-0.26-2.12-0.62-3.92-0.81-4-0.18-0.09-0.98 0.24-1.78 0.72-0.79 0.48-1.7 0.84-2.03 0.79-0.44-0.07-0.61-0.46-0.67-1.53-0.06-0.97 0.17-1.95 0.72-3.04 0.45-0.88 1.14-1.91 1.54-2.28 0.52-0.5 0.72-1.08 0.71-2.12-0.01-0.79-0.24-1.9-0.52-2.46-0.28-0.55-0.81-1.16-1.18-1.35-0.38-0.19-1.33-0.34-2.12-0.34-0.84 0-1.89 0.28-2.54 0.68-0.6 0.37-1.25 1.06-1.44 1.52q-0.34 0.85 0 1.7c0.23 0.57 0.22 1.59-0.02 3.13-0.2 1.25-0.43 2.4-0.51 2.54-0.08 0.14-0.72 0.42-1.41 0.62-0.7 0.2-1.43 0.3-1.61 0.22-0.19-0.08-0.57-0.88-0.85-1.77-0.28-0.88-0.66-1.74-0.85-1.89-0.19-0.15-1.14 0.27-2.2 0.98-1.64 1.1-2.1 1.24-3.89 1.23-1.12-0.01-2.76-0.16-3.64-0.34-1.35-0.27-1.62-0.24-1.63 0.18 0 0.28 0.8 1.13 1.79 1.89 0.98 0.76 2.4 1.56 3.14 1.76 0.75 0.21 1.6 0.65 1.9 0.99 0.3 0.34 0.66 1.07 0.79 1.63 0.13 0.56 0.85 2.31 1.6 3.89 1.21 2.57 1.34 3.08 1.18 4.74-0.1 1.02-0.07 2.79 0.05 3.93 0.21 1.8 0.42 2.26 1.68 3.64l1.44 1.57c0 8.4-0.12 10.83-0.26 10.83-0.14 0-0.42-0.12-0.63-0.25-0.21-0.14-0.62-0.75-0.93-1.35-0.3-0.61-0.56-1.37-0.58-1.7q-0.01-0.62-0.35 0.26c-0.2 0.49-0.24 1.92-0.11 3.38 0.2 2.21 0.13 2.72-0.49 3.9-0.4 0.74-0.72 1.73-0.71 2.2 0.01 0.46 0.23 1.07 0.5 1.35 0.38 0.39 0.42 0.78 0.16 1.69-0.18 0.66-0.33 1.61-0.33 2.12 0 0.51-0.11 0.93-0.25 0.92-0.14 0-0.98 0.46-1.86 1.02-1.05 0.66-1.61 1.26-1.61 1.7 0 0.37 0.15 0.98 0.34 1.35 0.18 0.38 0.75 0.89 1.27 1.15l0.93 0.48c-0.89 14.35-1.16 18.55-1.17 18.6-0.01 0.05-0.78 0.09-1.71 0.09h-1.69v3.04h244.08c0-2.85-0.09-3.06-0.76-3.13-0.69-0.08-0.77-0.29-0.84-2.2l-0.09-2.12-3.06-1.52c0.01-6.07-0.03-6.28-0.75-6.56-0.42-0.16-1.06-0.39-1.44-0.5-0.51-0.17-0.81 0.02-1.26 0.8-0.45 0.76-0.6 1.77-0.6 4.06v3.05c-2.02 0-2.54-0.23-2.96-0.79-0.51-0.67-0.7-2.78-1.37-15.48-0.42-8.09-0.84-15.51-0.93-16.49l-0.15-1.77c-2.58 0-2.71-0.09-2.71-0.85q0-0.85-0.68-1.02c-0.39-0.09-0.68-0.45-0.68-0.84 0-0.37-0.15-0.68-0.34-0.68-0.18 0-0.33-0.38-0.33-0.85 0-0.46-0.19-0.84-0.43-0.84-0.23 0 0-0.38 0.51-0.85 0.51-0.46 1.08-1.23 1.27-1.69 0.19-0.47 0.34-1.23 0.34-1.69 0-0.47-0.23-1.31-0.51-1.87-0.28-0.55-0.96-1.24-1.52-1.52-0.56-0.28-1.4-0.51-1.86-0.51-0.47 0-1.27 0.21-1.78 0.46-0.55 0.28-1.17 1.03-1.52 1.86-0.48 1.12-0.53 1.66-0.25 2.68 0.18 0.69 0.75 1.69 1.26 2.2 0.76 0.77 0.82 0.94 0.34 0.93-0.43 0-0.55 0.19-0.42 0.67 0.1 0.42-0.03 0.75-0.34 0.85-0.28 0.09-0.51 0.48-0.51 0.85 0 0.39-0.28 0.75-0.68 0.84-0.43 0.11-0.7 0.5-0.76 1.09-0.07 0.8-0.23 0.92-1.25 0.93-1.06 0.01-1.19 0.12-1.4 1.2-0.13 0.65-0.58 7.89-0.99 16.08-0.53 10.34-0.91 15.23-1.23 16-0.39 0.94-0.63 1.1-1.65 1.1h-1.18c-0.01-5.47-0.13-6.1-0.77-7.02-0.57-0.82-0.92-1.04-1.43-0.89-0.37 0.12-1.02 0.34-1.44 0.5-0.71 0.28-0.76 0.49-0.76 3.43l0.01 3.13-3.06 1.52c0 4.33-0.03 4.41-0.85 4.41h-0.84c-0.01-6.17 0.13-8.11 0.3-8.3 0.18-0.19 0.37-0.72 0.43-1.18 0.11-0.83 0.06-0.85-2.09-0.87l-2.2-0.02c-0.19-10.83-0.16-11.17 0.51-11.81 0.38-0.37 0.7-0.79 0.69-0.93-0.01-0.14-0.47-0.25-1.03-0.25h-1.01c0.02-8.87 0.13-10.14 0.52-10.67 0.28-0.37 0.5-0.79 0.5-0.93 0-0.14-0.31-0.25-0.68-0.25-0.63 0-0.67-0.19-0.55-2.29 0.07-1.26 0.24-2.74 0.37-3.3 0.19-0.78 0.07-1.25-0.52-2.03-0.72-0.95-0.76-1.28-0.63-4.82 0.13-3.46 0.09-3.81-0.45-3.81q-0.59 0-0.68-0.91c-0.06-0.7-0.32-0.98-1.1-1.18-0.87-0.23-1.19-0.67-2.25-3.07-0.68-1.54-1.59-4.44-2.03-6.44-0.44-2-0.91-3.64-1.05-3.64-0.14 0-0.25 0.27-0.25 0.59 0.01 0.33-0.32 1.97-0.72 3.64-0.41 1.68-1.29 4.31-1.96 5.86-1.05 2.42-1.36 2.84-2.23 3.07-0.78 0.19-1.04 0.47-1.1 1.17q-0.09 0.91-0.68 0.91c-0.53 0-0.59 0.42-0.59 4.14 0 3.14-0.11 4.13-0.43 4.07-0.23-0.05-0.65 0.06-0.93 0.25q-0.51 0.34-1.01 0-0.51-0.34-1.02 0-0.51 0.34-1.01 0-0.51-0.34-1.02 0-0.51 0.34-1.02 0-0.5-0.34-1.01 0-0.51 0.34-1.02 0-0.5-0.34-1.01 0c-0.35 0.23-0.68 0.21-1.04-0.08-0.44-0.35-0.6-0.33-0.86 0.08-0.27 0.44-0.39 0.41-0.85-0.17-0.45-0.56-0.5-1.31-0.33-4.48 0.19-3.66 0.17-3.81-0.48-3.81-0.52 0-0.67-0.23-0.67-1.02 0-0.86-0.15-1.03-0.94-1.1-0.75-0.07-1.13-0.46-2.01-2.11-0.62-1.16-1.56-3.96-2.2-6.51-0.61-2.46-1.2-4.37-1.31-4.23-0.1 0.13-0.54 1.76-0.97 3.63-0.43 1.86-1.33 4.65-2.01 6.2-1.06 2.43-1.37 2.85-2.24 3.06-0.79 0.2-1.04 0.46-1.1 1.17q-0.09 0.91-0.68 0.91c-0.54 0-0.58 0.36-0.46 3.97 0.13 3.71 0.09 4.03-0.59 4.74-0.4 0.42-0.73 1.15-0.73 1.61 0 0.47 0.15 0.92 0.34 1.02 0.19 0.09 0.34 1.23 0.34 2.54 0 2.14-0.07 2.37-0.68 2.37-0.64 0-0.65 0.05-0.17 1.01 0.38 0.77 0.51 2.24 0.51 5.93v4.91c-1.57 0-2.03 0.15-2.03 0.33 0 0.19 0.3 0.53 0.68 0.77 0.63 0.39 0.68 0.78 0.67 6.26v5.84c-2.75-0.04-3.74-0.04-3.97-0.02-0.24 0.01-0.43 0.4-0.43 0.87 0 0.46 0.15 0.92 0.34 1.01 0.2 0.1 0.34 1.86 0.34 4.23 0 3.09-0.1 4.04-0.42 3.95-0.24-0.06-0.48-0.55-0.54-1.1-0.06-0.54-0.33-1.21-0.6-1.49-0.38-0.4-0.49-2-0.54-7.62-0.03-3.91-0.14-19.45-0.25-34.53-0.14-21.01-0.29-27.6-0.61-28.19-0.27-0.47-0.43-2.12-0.43-4.4 0-2.97-0.12-3.82-0.64-4.65-0.52-0.84-0.65-1.86-0.76-5.93-0.09-3.47-0.29-5.25-0.66-6.09l-0.53-1.18c-0.74 2.3-1.11 3.03-1.3 3.1-0.19 0.07-0.46-0.52-0.6-1.32-0.14-0.79-0.64-3.12-1.1-5.17-0.47-2.05-1.38-5.13-2.03-6.86-0.77-2.05-1.18-3.71-1.18-4.82 0-1.47 0.09-1.69 0.68-1.69 0.37 0 0.68-0.15 0.68-0.34 0-0.19-0.31-0.34-0.68-0.34-0.5 0-0.68-0.22-0.68-0.85 0-0.46-0.15-0.84-0.34-0.84-0.18 0-0.34 0.38-0.34 0.84 0 0.63-0.18 0.85-0.67 0.85-0.38 0-0.68 0.15-0.68 0.34 0 0.19 0.3 0.34 0.68 0.34 0.58 0 0.67 0.22 0.67 1.69q0 1.7-1.36 5.5c-0.76 2.1-1.78 5.83-2.29 8.3-0.5 2.47-1.03 4.49-1.17 4.49-0.14-0.01-0.51-0.81-0.82-1.79l-0.56-1.77c-1.04 2.96-1.2 4.52-1.22 7.95-0.02 3.97-0.12 4.71-0.7 5.59-0.57 0.85-0.67 1.59-0.68 4.65 0 2.56-0.15 3.95-0.51 4.66-0.39 0.79-0.48 2.11-0.42 5.92 0.05 2.7 0.01 4.91-0.08 4.91-0.1 0-2.15-1.96-4.57-4.36-2.81-2.77-4.25-4.43-3.98-4.57 0.23-0.11 0.43-0.48 0.44-0.8 0.01-0.33-0.23-0.82-0.53-1.1-0.32-0.31-0.55-1.11-0.56-2.03l-0.03-1.53c-0.35 2.37-0.67 3.39-0.95 3.81-0.38 0.59-0.4 0.9-0.1 1.36 0.34 0.51-0.2 1.17-3.97 4.91l-4.37 4.31c0.01-8.89-0.1-10.13-0.49-10.66-0.38-0.5-0.51-1.66-0.52-4.49-0.01-3.11-0.13-4.05-0.69-5.16-0.56-1.13-0.67-2.04-0.68-5.5 0-3.01-0.16-4.65-0.59-5.96l-0.59-1.81c-0.84 2.54-1.22 3.32-1.39 3.37-0.16 0.04-0.79-2.24-1.4-5.08-0.6-2.84-1.55-6.38-2.11-7.87-0.74-1.95-1.02-3.3-1.02-4.83 0-1.65 0.11-2.11 0.51-2.11 0.27 0 0.5-0.15 0.5-0.34 0-0.19-0.23-0.34-0.5-0.34-0.32 0-0.51-0.32-0.51-0.85 0-0.52-0.19-0.84-0.51-0.84-0.32 0-0.51 0.32-0.51 0.84z"/>
                            <path fill-rule="nonzero" d="m25.51 82.6c-0.58 0.25-1.31 0.81-1.61 1.25-0.3 0.44-0.54 1.14-0.54 1.56v0.76c1.57-1.31 2.26-1.69 2.54-1.69 0.28 0 0.85 0.17 1.27 0.38 0.42 0.21 1.11 0.7 1.53 1.1l0.77 0.72c-0.01-1.44-0.25-2.32-0.52-2.88-0.28-0.56-0.82-1.17-1.19-1.35-0.37-0.19-0.79-0.33-0.93-0.33-0.14 0.01-0.73 0.22-1.32 0.48z"/>
                        </g>
                    </mask>
                </defs>
                <g mask="url(#ql-sky)">
                    {{-- Full red→blue gradient, static; the dark cover retreats to the
                         right so the silhouette fills left→right. --}}
                    <rect x="0" y="0" width="260" height="280" fill="url(#ql-grad)"/>
                    <rect class="ql-cover" x="0" y="0" width="260" height="280" fill="#08080A" fill-opacity=".82"/>
                </g>
            </svg>
            <p class="quito-loader__text">EQUINOX · Quito 0°</p>
        </div>
    </div>

    {{-- ============ PAGE HERO ============ --}}
    <section class="plan-hero" id="top">
        @if ($heroSlides->isNotEmpty())
            <div class="plan-hero__slides" data-hero-slides aria-hidden="true">
                @foreach ($heroSlides as $i => $src)
                    <img class="plan-hero__slide @if ($i === 0) is-active @endif" src="{{ $src }}" alt=""
                         @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif>
                @endforeach
            </div>
        @endif
        <div class="container plan-hero__content">
            <div class="plan-hero__panel">
                <p class="eyebrow intro" style="--d: .05s">Plan your visit · Quito, Ecuador</p>
                <h1 class="plan-hero__title intro" style="--d: .12s">Welcome to Ecuador,<br><em>the middle of the world.</em></h1>
                <p class="lead intro" style="--d: .2s">
                    EQUINOX 2027 takes you to one of the most beautiful and biodiverse countries on Earth. From flights and
                    visas to where to stay and how to move around Quito, here is everything you need to plan an unforgettable
                    trip to 0° latitude.
                </p>
                <ul class="plan-hero__facts intro" style="--d: .28s">
                    <li><x-icon name="calendar" /> March 16–19, 2027</li>
                    <li><x-icon name="pin" /> Quito, Ecuador</li>
                    <li><x-icon name="globe" /> 0° latitude</li>
                </ul>
                <div class="hero__ctas intro" style="--d: .34s">
                    <a href="#discover" class="btn btn--primary">Discover Ecuador <x-icon name="arrow-right" /></a>
                    <a href="#flights" class="btn btn--ghost">Find your flight <x-icon name="plane" /></a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ IN-PAGE SUBNAV ============ --}}
    <nav class="plan-subnav" aria-label="Sections">
        <div class="container plan-subnav__inner">
            <a href="#discover"><x-icon name="compass" /> Discover Ecuador</a>
            <a href="#flights"><x-icon name="plane" /> Flights</a>
            <a href="#visa"><x-icon name="passport" /> Visa</a>
            <a href="#accommodation"><x-icon name="bed" /> Accommodation</a>
            <a href="#getting-around"><x-icon name="car" /> Getting around</a>
        </div>
    </nav>

    {{-- ============ DISCOVER ECUADOR ============ --}}
    <section class="section plan-section discover" id="discover">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="compass" /></span> Discover Ecuador</p>
            <h2 class="display reveal">Four worlds, <em>one country.</em></h2>
            <p class="lead reveal">
                Few countries pack so much into so little distance. Ecuador is one of the most biodiverse places on
                Earth, and from Quito you can reach the Andes, the Amazon, the Pacific coast and the Galápagos Islands,
                with the line of the equator running right through it. Come for EQUINOX, and stay to discover why this
                small country at latitude 0° leaves such a lasting impression.
            </p>

            <div class="discover-grid">
                @foreach ([
                    ['discover-quito', 'landmark', 'Historic Quito', "One of the best-preserved colonial centres in the Americas and among the very first UNESCO World Heritage Sites — a maze of plazas, domes and cobbled streets at 2,850 metres."],
                    ['discover-mitad', 'globe', 'The Middle of the World', "Just north of the city you can stand with one foot in each hemisphere at latitude 0°0'0'' — the very reason EQUINOX takes place here."],
                    ['discover-cotopaxi', 'mountain', 'The Avenue of the Volcanoes', 'Snow-capped Andean giants such as Cotopaxi and Cayambe rise within a short drive of the capital, framing some of the most dramatic highland scenery in the world.'],
                    ['discover-nature', 'leaf', 'Nature within reach', 'From Amazon rainforest and cloud forests to the Pacific coast and the legendary Galápagos Islands, extraordinary nature is never far away.'],
                    ['discover-food', 'dish', 'Ecuadorian flavours', 'Fresh coastal ceviche, comforting locro de papa, bean-to-bar chocolate and some of the finest coffee and cacao on the planet.'],
                    ['discover-climate', 'sun', 'Eternal spring', 'Quito enjoys mild, spring-like weather all year round. Pack light layers for cool evenings and remember that the equatorial sun is strong by day.'],
                ] as [$img, $icon, $heading, $text])
                    @php ($photo = $planImg($img))
                    <article class="card discover-card reveal">
                        <div class="discover-card__photo @unless ($photo) discover-card__photo--placeholder @endunless">
                            @if ($photo)
                                <img src="{{ $photo }}" alt="{{ $heading }}" loading="lazy">
                            @endif
                            <div class="discover-card__head">
                                <span class="icon-ring"><x-icon :name="$icon" /></span>
                                <h3>{{ $heading }}</h3>
                            </div>
                        </div>
                        <div class="discover-card__body">
                            <p>{{ $text }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="callout callout--tip reveal">
                <span class="icon-ring"><x-icon name="compass" /></span>
                <p><strong>Make the most of your trip.</strong> Many delegates add a few days before or after the conference to explore Quito and beyond. We will share curated travel ideas and trusted local operators with registered delegates.</p>
            </div>
        </div>
    </section>

    {{-- ============ FLIGHTS ============ --}}
    <section class="section plan-section" id="flights">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="plane" /></span> Flights</p>
            <h2 class="display reveal">Getting to Quito.</h2>
            <p class="lead reveal">
                Quito's Mariscal Sucre International Airport (UIO) is one of the highest-capacity airports in the region,
                with frequent connections from across the Americas and Europe, many of them direct.
            </p>

            <div class="cards cards--2 reveal">
                <article class="card">
                    <span class="icon-ring icon-ring--lg"><x-icon name="plane" /></span>
                    <h3>Direct connections</h3>
                    <p>
                        Nonstop flights link Quito with regional hubs such as Panama, Bogotá, Lima and Guayaquil, plus
                        long-haul routes from Europe, with onward connections to the Galápagos.
                    </p>
                </article>
                <article class="card">
                    <span class="icon-ring icon-ring--lg"><x-icon name="globe" /></span>
                    <h3>Airlines serving Quito</h3>
                    <ul class="flight-airlines__list">
                        @foreach (['Avianca', 'LATAM', 'Copa', 'American', 'United', 'KLM', 'Iberia', 'Aeroméxico'] as $airline)
                            <li>{{ $airline }}</li>
                        @endforeach
                    </ul>
                </article>
            </div>

            <div class="callout reveal">
                <span class="icon-ring"><x-icon name="clock" /></span>
                <p><strong>Live search coming to this page.</strong> A real-time flight and hotel search with current fares and availability is being connected, so you will be able to compare and book right here, without leaving the site.</p>
            </div>
        </div>
    </section>

    {{-- ============ VISA AND ENTRY REQUIREMENTS ============ --}}
    <section class="section plan-section" id="visa">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="passport" /></span> Before you travel</p>
            <h2 class="display reveal">Visa &amp; <em>entry requirements.</em></h2>
            <div class="cards cards--2 reveal">
                <article class="card">
                    <span class="icon-ring icon-ring--lg"><x-icon name="shield" /></span>
                    <h3>Visa assistance</h3>
                    <p>
                        The EQUINOX team is available to provide supporting documentation and guidance to registered
                        delegates who require a visa to attend the conference. Please contact our team in advance to
                        request assistance.
                    </p>
                </article>
                <article class="card">
                    <span class="icon-ring icon-ring--lg"><x-icon name="globe" /></span>
                    <h3>Visa-free entry</h3>
                    <p>
                        Citizens of many countries, including most countries in the Americas and Europe, can enter
                        Ecuador without a visa for tourism purposes for stays of up to 90 days.
                    </p>
                </article>
            </div>
            <div class="callout reveal">
                <span class="icon-ring"><x-icon name="passport" /></span>
                <p>Your passport must generally be valid for at least <strong>six months</strong> from your date of entry into Ecuador.</p>
            </div>
        </div>
    </section>

    {{-- ============ ACCOMMODATION ============ --}}
    <section class="section plan-section" id="accommodation">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="bed" /></span> Accommodation</p>
            <h2 class="display reveal">Book your hotel.</h2>
            <p class="lead reveal">
                Reserve your room now and save with the special rates negotiated at our partner hotels. Accommodation
                is not included in the registration fee, and these rates are only available to registered EQUINOX 2027
                delegates.
            </p>
            <p class="reveal">
                Partner hotels are in the Av. República de El Salvador and La Carolina area, Quito's business
                district, with banks, embassies, shopping centres, parks and restaurants within walking distance.
                The venue is about 15 to 25 minutes away by road, and event shuttles run from every partner hotel.
            </p>

            <div class="hotel-grid">
                @foreach ([
                    ['Sheraton Quito', 'Av. República de El Salvador N36-212 y Naciones Unidas', '5★', 'hotel-sheraton', 'https://www.marriott.com/en-us/hotels/uiosi-sheraton-quito-hotel/overview/'],
                    ['Hotel Dann Carlton Quito', 'Av. República de El Salvador N34-377 e Irlanda', '5★', 'hotel-dann-carlton', 'https://hotelesdann.com/dann-carlton-quito/en/'],
                    ['Le Parc Hotel', 'Av. República de El Salvador, Quito', 'Boutique', 'hotel-le-parc', 'https://www.leparc.com.ec/en/'],
                    ['GoHotel', 'Av. Eloy Alfaro, Quito', '5★', 'hotel-gohotel', 'https://goquitohotel.com/en/'],
                    ['Hotel Finlandia', 'Finlandia 35-129 y Suecia, Quito', '4★', 'hotel-finlandia', 'https://hotelfinlandiaquito.com/'],
                ] as [$hotel, $address, $category, $slug, $url])
                    @php ($hphoto = $planImg($slug))
                    <article class="card hotel-card reveal">
                        <div class="hotel-card__photo @unless ($hphoto) hotel-card__photo--placeholder @endunless">
                            @if ($hphoto)
                                <img src="{{ $hphoto }}" alt="{{ $hotel }}" loading="lazy">
                            @else
                                <x-icon name="bed" />
                            @endif
                            <span class="hotel-card__cat">{{ $category }}</span>
                        </div>
                        <div class="hotel-card__body">
                            <h3>{{ $hotel }}</h3>
                            <p class="hotel-card__addr"><x-icon name="pin" /> {{ $address }}</p>
                            <span class="hotel-card__rate"><x-icon name="clock" /> Rate &amp; booking code coming soon</span>
                            @if ($url !== '#')
                                <a href="{{ $url }}" class="btn btn--ghost btn--sm hotel-card__link" target="_blank" rel="noopener">View &amp; book <x-icon name="arrow-right" /></a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="callout reveal">
                <span class="icon-ring"><x-icon name="calendar" /></span>
                <p>Rooms are held for the nights of <strong>15 to 19 March 2027</strong>. To get the event rate, use each hotel's booking code and follow the instructions on its booking link.</p>
            </div>

            <div class="plan-sub reveal">
                <h3>Included in the rate</h3>
                <ul class="rate-list">
                    @foreach ([
                        ['coffee', 'Breakfast'],
                        ['check', 'Local accommodation taxes and service charges'],
                        ['car', "Airport transfers on arrival and departure at most partner hotels — add your flight details to your booking so the hotel can arrange it"],
                    ] as [$icon, $text])
                        <li><span class="icon-ring"><x-icon :name="$icon" /></span> {{ $text }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="plan-sub reveal">
                <h3>Other accommodation options</h3>
                <p class="lead">
                    For those who prefer alternative accommodation, the La Carolina area offers a wide selection of
                    Airbnb apartments and short-term rentals. Located near the conference area, it is a convenient
                    option for visitors looking for more flexibility and a local experience during their stay in Quito.
                </p>
                <p>We recommend exploring available listings in the La Carolina neighbourhood and its surroundings.</p>
            </div>
        </div>
    </section>

    {{-- ============ GETTING AROUND ============ --}}
    <section class="section plan-section" id="getting-around">
        <div class="container">
            <p class="eyebrow reveal"><span class="icon-ring"><x-icon name="car" /></span> Getting around</p>
            <h2 class="display reveal">Moving around <em>Quito.</em></h2>

            <div class="plan-sub reveal">
                <h3>From the airport</h3>
                <p class="lead">The airport is about 40 to 60 minutes from the hotel area, depending on traffic.</p>
                <div class="option-grid">
                    @foreach ([
                        ['car', 'Hotel transfer', 'Included at most hotels', 'Add your flight details when booking'],
                        ['car', 'Official airport taxi', 'USD 25 – 30', 'Fixed fare, 24/7 at arrivals'],
                        ['car', 'Uber · DiDi · InDriver', 'Similar to taxi', 'Pick-up at the airport zone'],
                    ] as [$icon, $name, $cost, $note])
                        <article class="card option-card reveal">
                            <span class="icon-ring"><x-icon :name="$icon" /></span>
                            <h4>{{ $name }}</h4>
                            <p class="option-card__cost">{{ $cost }}</p>
                            <p class="option-card__note">{{ $note }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="plan-sub reveal">
                <h3>In the city</h3>
                <div class="option-grid">
                    @foreach ([
                        ['car', 'Uber · DiDi · InDriver', '$2 – 3 per ride', 'Our recommended way around town'],
                        ['car', 'Hotel taxi', 'From $1.50', 'Metered yellow taxis; ask your hotel'],
                        ['train', 'Quito Metro', '$0.45', 'North–south line to the Historic Centre'],
                    ] as [$icon, $name, $cost, $note])
                        <article class="card option-card reveal">
                            <span class="icon-ring"><x-icon :name="$icon" /></span>
                            <h4>{{ $name }}</h4>
                            <p class="option-card__cost">{{ $cost }}</p>
                            <p class="option-card__note">{{ $note }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="callout callout--tip reveal">
                <span class="icon-ring"><x-icon name="shield" /></span>
                <p><strong>Tip.</strong> Use ride-hailing apps or hotel-called taxis rather than hailing cars on the street, especially at night.</p>
            </div>

            <div class="plan-sub reveal">
                <h3><span class="icon-ring"><x-icon name="bus" /></span> Conference shuttles</h3>
                <p class="lead">
                    Free shuttles run between the partner hotels and the venue (Auditorio 2, UDLA Park) every conference
                    day, 16 to 19 March.
                </p>
                <ol class="shuttle-list">
                    @foreach ([
                        'Morning and evening runs between the hotels and the venue, timed around the programme.',
                        'On 19 March, buses head north to Urcuquí (about two hours) for the closing visit.',
                        'Staff wait in each hotel lobby at shuttle times; exact times go to confirmed delegates.',
                    ] as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- ============ CLOSING CTA ============ --}}
    <section class="section plan-section plan-section--cta">
        <div class="container">
            <div class="plan-cta reveal">
                <p class="eyebrow">Stay informed</p>
                <h2 class="plan-cta__title">Get travel updates before anyone else.</h2>
                <p class="more__text">
                    Join the EQUINOX list and we'll send booking codes, flight recommendations and shuttle schedules
                    as soon as they are confirmed.
                </p>
                <div class="hero__ctas hero__ctas--center">
                    <a href="{{ route('home') }}#join" class="btn btn--primary btn--lg" data-open="join">Join the list <x-icon name="arrow-right" /></a>
                    <a href="{{ route('home') }}#join" class="btn btn--ghost btn--lg" data-open="sponsor">Become a sponsor</a>
                </div>
            </div>
        </div>
    </section>
@endsection
