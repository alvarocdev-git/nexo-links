{{-- Stamp <html data-theme> before first paint (no FOUC). Must precede the
     stylesheet; every full-page <head> pulls this partial in before @vite. --}}
@include('partials.theme-init')

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<meta name="theme-color" content="#6366f1">
