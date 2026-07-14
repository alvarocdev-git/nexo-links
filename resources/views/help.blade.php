<!DOCTYPE html>
@php($topics = [
    'account' => [
        'title' => __('Create your account'),
        'steps' => [
            __('Click "Create your page" and choose a username — it becomes your public URL.'),
            __('Verify your email address with the link we send you.'),
            __('Your page is immediately live at /your-username.'),
        ],
    ],
    'links' => [
        'title' => __('Add and organize your links'),
        'steps' => [
            __('In the dashboard, press "+ Add link" and give it a title and a URL.'),
            __('Drag the handle on the left of each card to reorder.'),
            __('Use Hide to keep a link without showing it, and Edit to change it anytime.'),
        ],
    ],
    'dynamic' => [
        'title' => __('Schedule, highlight and countdowns'),
        'steps' => [
            __('When creating or editing a link, set optional start and end dates — it publishes and unpublishes itself.'),
            __('Mark "Highlight this link" to give it the accent color treatment.'),
            __('Enable the countdown to tease a launch: visitors see a live timer until it starts.'),
        ],
    ],
    'contact' => [
        'title' => __('Contact buttons and WhatsApp'),
        'steps' => [
            __('Links accept https://, mailto: and tel: — so a button can send an email or call you.'),
            __('Use "Build a WhatsApp link" to generate a wa.me link with a prefilled message.'),
        ],
    ],
    'social' => [
        'title' => __('Social icons'),
        'steps' => [
            __('In "Social icons", pick a platform and enter your handle, email or phone.'),
            __('Shown as icons at the bottom of your page. Prefer a big button? Add it as a regular link instead.'),
        ],
    ],
    'design' => [
        'title' => __('Make it yours'),
        'steps' => [
            __('In Design, upload an avatar and a banner, and write your bio.'),
            __('Pick an accent palette and a background: default, solid color or gradient.'),
            __('Text color adapts automatically so your page stays readable.'),
        ],
    ],
    'analytics' => [
        'title' => __('Understand your analytics'),
        'steps' => [
            __('Analytics shows total clicks, unique visitors, clicks per day and your top referrers.'),
            __('Numbers are collected without cookies and without storing personal data — visitors are never tracked across days.'),
        ],
    ],
    'share' => [
        'title' => __('Share your page'),
        'steps' => [
            __('Copy your URL from "Share your page" in the dashboard.'),
            __('Download your QR code as SVG — print it anywhere, it always points to your page.'),
        ],
    ],
])
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Help') }} · {{ config('app.name') }}</title>
    <meta name="description" content="{{ __('What can I do in :app?', ['app' => config('app.name')]) }}">
    <link rel="canonical" href="{{ route('help') }}">

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-50" style="font-family: ui-sans-serif, system-ui, -apple-system, sans-serif">
    <div class="mx-auto flex min-h-screen w-full max-w-2xl flex-col px-5">

        <nav class="flex items-center justify-between py-6">
            <a href="{{ route('home') }}" class="text-lg font-bold tracking-tight">{{ config('app.name') }}</a>
            <div class="flex items-center gap-4 text-sm">
                <x-language-switcher />
                @auth
                    <a href="{{ route('dashboard') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Dashboard') }} →</a>
                @else
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Create your page') }}</a>
                @endauth
            </div>
        </nav>

        <header class="py-10">
            <h1 class="text-3xl font-bold tracking-tight">{{ __('What can I do in :app?', ['app' => config('app.name')]) }}</h1>

            <nav aria-label="{{ __('Help topics') }}" class="mt-6 flex flex-wrap gap-2">
                @foreach ($topics as $anchor => $topic)
                    <a href="#{{ $anchor }}"
                       class="rounded-full border border-neutral-200 bg-white px-3 py-1 text-sm text-neutral-700 transition hover:border-neutral-400 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:border-neutral-600">
                        {{ $topic['title'] }}
                    </a>
                @endforeach
            </nav>
        </header>

        <main class="space-y-10 pb-16">
            @foreach ($topics as $anchor => $topic)
                <section id="{{ $anchor }}" class="scroll-mt-6">
                    <h2 class="flex items-center gap-2 text-xl font-semibold">
                        <span class="h-2 w-2 shrink-0 rounded-full bg-gradient-to-r from-indigo-500 to-fuchsia-500" aria-hidden="true"></span>
                        {{ $topic['title'] }}
                    </h2>
                    <ol class="mt-3 list-decimal space-y-2 ps-8 text-neutral-700 dark:text-neutral-300">
                        @foreach ($topic['steps'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </section>
            @endforeach
        </main>

        <footer class="mt-auto flex flex-wrap items-center justify-between gap-2 border-t border-neutral-200 py-8 text-sm text-neutral-500 dark:border-neutral-800">
            <a href="{{ route('home') }}" class="hover:text-neutral-700 dark:hover:text-neutral-300">← {{ config('app.name') }}</a>
            <a href="{{ config('nexo.repository_url') }}" rel="noopener" class="hover:text-neutral-700 dark:hover:text-neutral-300">
                {{ __('Open source on GitHub') }} ↗
            </a>
        </footer>
    </div>
</body>
</html>
