<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} — {{ __('Your links. Your domain. Your data.') }}</title>
    <meta name="description" content="{{ __('Open-source link-in-bio page you host yourself, with visitor analytics that don\'t spy on anyone.') }}">
    <link rel="canonical" href="{{ url('/') }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name') }} — {{ __('Your links. Your domain. Your data.') }}">
    <meta property="og:description" content="{{ __('Open-source link-in-bio page you host yourself, with visitor analytics that don\'t spy on anyone.') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('og-image.png') }}">
    <meta name="twitter:card" content="summary_large_image">

    @include('partials.brand-head')

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-50" style="font-family: ui-sans-serif, system-ui, -apple-system, sans-serif">
    <div class="mx-auto flex min-h-screen w-full max-w-4xl flex-col px-5">

        <!-- Nav -->
        <nav class="flex items-center justify-between py-6">
            <span class="flex items-center gap-2 text-lg font-bold tracking-tight">
                <x-application-logo class="h-6 w-6" />
                {{ config('app.name') }}
            </span>
            <div class="flex items-center gap-4 text-sm">
                <x-language-switcher />
                @auth
                    <a href="{{ route('dashboard') }}" class="font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">{{ __('Dashboard') }} →</a>
                @else
                    <a href="{{ route('login') }}" class="text-neutral-600 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-100">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-neutral-900 px-4 py-2 font-medium text-white transition hover:bg-neutral-700 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">{{ __('Create your page') }}</a>
                @endauth
            </div>
        </nav>

        <!-- Hero -->
        <header class="py-16 text-center sm:py-24">
            <h1 class="mx-auto max-w-2xl text-balance text-4xl font-bold tracking-tight sm:text-6xl">
                {{ __('Your links.') }}
                <span class="bg-gradient-to-r from-brand-500 to-brand-600 bg-clip-text text-transparent">{{ __('Your domain.') }}</span>
                {{ __('Your data.') }}
            </h1>
            <p class="mx-auto mt-6 max-w-xl text-balance text-lg text-neutral-600 dark:text-neutral-400">
                {{ __('An open-source link-in-bio page you host yourself — with visitor analytics that don\'t spy on anyone.') }}
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="rounded-full bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-3 font-semibold text-white shadow-lg shadow-brand-500/20 transition hover:-translate-y-0.5 hover:shadow-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 motion-reduce:transition-none">
                    {{ __('Create your page — it\'s free') }}
                </a>
                @if ($exampleUsername !== null)
                    <a href="{{ url('/'.$exampleUsername) }}"
                       class="rounded-full border border-neutral-300 px-6 py-3 font-medium transition hover:border-neutral-400 hover:bg-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 dark:border-neutral-700 dark:hover:border-neutral-500 dark:hover:bg-neutral-900 motion-reduce:transition-none">
                        {{ __('See a live example') }} ↗
                    </a>
                @endif
            </div>
        </header>

        <!-- Features -->
        <section aria-label="{{ __('Features') }}" class="grid gap-4 pb-16 sm:grid-cols-2">
            @foreach ([
                [__('No vendor lock-in'), __('Your page lives on your own domain and server. No platform can paywall it, break your URL or shut it down.')],
                [__('Analytics without spying'), __('Click stats with zero cookies and zero personal data stored. No consent banner needed — private by design.')],
                [__('Fast and lightweight'), __('Server-rendered pages with no trackers and no external requests. Built mobile-first, because that\'s where your visitors are.')],
                [__('Links with superpowers'), __('Schedule links by date, highlight what\'s live right now, and tease launches with a countdown.')],
                [__('Your look'), __('Avatar, banner, color palettes, solid or gradient backgrounds — with automatic dark mode and readable text guaranteed.')],
                [__('Open source'), __('MIT licensed, self-hostable on cheap shared hosting (PHP + MySQL). Read the code, run your own, contribute.')],
            ] as [$title, $text])
                <article class="rounded-2xl border border-neutral-200 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900">
                    <h2 class="flex items-center gap-2 font-semibold">
                        <span class="h-2 w-2 shrink-0 rounded-full bg-gradient-to-r from-brand-500 to-brand-600" aria-hidden="true"></span>
                        {{ $title }}
                    </h2>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">{{ $text }}</p>
                </article>
            @endforeach
        </section>

        <!-- Footer -->
        <footer class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-neutral-200 py-8 text-sm text-neutral-500 dark:border-neutral-800">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('help') }}" class="hover:text-neutral-700 dark:hover:text-neutral-300">{{ __('Help') }}</a>
                <a href="{{ config('nexo.repository_url') }}" rel="noopener" class="hover:text-neutral-700 dark:hover:text-neutral-300">
                    {{ __('Open source on GitHub') }} ↗
                </a>
            </div>
            <a href="{{ config('nexo.attribution.url') }}" rel="noopener" class="hover:text-neutral-700 dark:hover:text-neutral-300">
                {{ config('nexo.attribution.label') }}
            </a>
        </footer>
    </div>
</body>
</html>
