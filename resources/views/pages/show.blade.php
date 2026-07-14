<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ '@'.$page->username }} · {{ config('app.name') }}</title>
    <meta name="description" content="{{ $page->bio ?? '@'.$page->username.' links' }}">
    <link rel="canonical" href="{{ route('page.show', $page->username) }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ '@'.$page->username }} · {{ config('app.name') }}">
    <meta property="og:description" content="{{ $page->bio ?? '@'.$page->username.' links' }}">
    <meta property="og:url" content="{{ route('page.show', $page->username) }}">
    <meta name="twitter:card" content="summary">

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-neutral-100 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-50" style="font-family: ui-sans-serif, system-ui, -apple-system, sans-serif">
    <main class="mx-auto flex min-h-screen w-full max-w-md flex-col px-5 pb-8 pt-14 sm:pt-20">
        <header class="text-center">
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-500 via-purple-500 to-fuchsia-500 p-1">
                <div class="flex h-full w-full items-center justify-center rounded-full bg-neutral-100 text-3xl font-bold uppercase dark:bg-neutral-950">
                    {{ mb_substr($page->username, 0, 1) }}
                </div>
            </div>

            <h1 class="mt-4 text-2xl font-bold tracking-tight">{{ '@'.$page->username }}</h1>

            @if ($page->bio)
                <p class="mx-auto mt-2 max-w-xs text-balance text-neutral-600 dark:text-neutral-400">{{ $page->bio }}</p>
            @endif
        </header>

        <ul class="mt-10 space-y-4">
            @foreach ($links as $link)
                <li>
                    @if ($link->isUpcoming())
                        <div data-countdown="{{ $link->starts_at->getTimestamp() }}"
                             class="rounded-2xl border border-dashed border-neutral-300 bg-white/60 px-5 py-4 text-center dark:border-neutral-700 dark:bg-neutral-900/60">
                            <p class="font-medium text-neutral-700 dark:text-neutral-300">{{ $link->title }}</p>
                            <p class="mt-1 text-sm tabular-nums text-neutral-500" aria-live="off">
                                {{ __('starts in') }} <span data-countdown-label>…</span>
                            </p>
                        </div>
                    @elseif ($link->is_highlighted)
                        <a href="{{ route('link.visit', $link) }}" data-highlighted rel="noopener"
                           class="group relative block overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-5 py-4 text-center font-semibold text-white shadow-lg shadow-indigo-500/20 transition duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-fuchsia-500/30 motion-reduce:transition-none">
                            <span class="absolute left-4 top-1/2 -mt-1 h-2 w-2 animate-pulse rounded-full bg-white/90 motion-reduce:animate-none" aria-hidden="true"></span>
                            {{ $link->title }}
                        </a>
                    @else
                        <a href="{{ route('link.visit', $link) }}" rel="noopener"
                           class="block rounded-2xl border border-neutral-200 bg-white px-5 py-4 text-center font-medium shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-neutral-300 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700 motion-reduce:transition-none">
                            {{ $link->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($links->isEmpty())
            <p class="mt-10 text-center text-neutral-500">{{ __('Nothing here yet.') }}</p>
        @endif

        <footer class="mt-auto pt-14 text-center">
            <a href="{{ config('nexo.attribution.url') }}" rel="noopener"
               class="text-sm text-neutral-400 transition hover:text-neutral-600 dark:text-neutral-600 dark:hover:text-neutral-400">
                {{ config('nexo.attribution.label') }}
            </a>
        </footer>
    </main>

    @if ($links->contains(fn ($link) => $link->isUpcoming()))
        <script>
            document.querySelectorAll('[data-countdown]').forEach((el) => {
                const target = Number(el.dataset.countdown) * 1000;
                const label = el.querySelector('[data-countdown-label]');
                let timer;

                const tick = () => {
                    const seconds = Math.floor((target - Date.now()) / 1000);

                    if (seconds <= 0) {
                        if (timer) clearInterval(timer);
                        window.location.reload();
                        return;
                    }

                    const pad = (n) => String(n).padStart(2, '0');
                    const days = Math.floor(seconds / 86400);
                    label.textContent = (days > 0 ? days + 'd ' : '')
                        + pad(Math.floor((seconds % 86400) / 3600))
                        + ':' + pad(Math.floor((seconds % 3600) / 60))
                        + ':' + pad(seconds % 60);
                };

                tick();
                timer = setInterval(tick, 1000);
            });
        </script>
    @endif
</body>
</html>
