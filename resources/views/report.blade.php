<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>{{ __('Report this page') }} · {{ config('app.name') }}</title>

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-50" style="font-family: ui-sans-serif, system-ui, -apple-system, sans-serif">
    <main class="mx-auto flex min-h-screen w-full max-w-md flex-col px-5 py-14">
        <a href="{{ route('page.show', $page->username) }}" class="text-sm text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">
            ← {{ '@'.$page->username }}
        </a>

        <h1 class="mt-4 text-2xl font-bold tracking-tight">{{ __('Report this page') }}</h1>

        @if (session('status') === 'report-sent')
            <div class="mt-8 rounded-2xl border border-green-200 bg-green-50 p-6 text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
                {{ __('Thanks — your report was sent.') }}
            </div>
        @else
            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                {{ __('Something broken, misleading or abusive? Let the page owner know. Reports are anonymous.') }}
            </p>

            <form method="POST" action="{{ route('report.store', $page->username) }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="reason" class="block text-sm font-medium">{{ __('What\'s wrong?') }}</label>
                    <select id="reason" name="reason" required
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-900">
                        @foreach ($reasons as $value => $label)
                            <option value="{{ $value }}" @selected(old('reason') === $value)>{{ __($label) }}</option>
                        @endforeach
                    </select>
                    @error('reason')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="link_id" class="block text-sm font-medium">{{ __('Which link? (optional)') }}</label>
                    <select id="link_id" name="link_id"
                            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-900">
                        <option value="">{{ __('The whole page') }}</option>
                        @foreach ($links as $link)
                            <option value="{{ $link->id }}" @selected(old('link_id') == $link->id)>{{ $link->title }}</option>
                        @endforeach
                    </select>
                    @error('link_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="details" class="block text-sm font-medium">{{ __('Details (optional)') }}</label>
                    <textarea id="details" name="details" rows="3" maxlength="500"
                              class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-700 dark:bg-neutral-900">{{ old('details') }}</textarea>
                    @error('details')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button class="rounded-full bg-neutral-900 px-6 py-2.5 font-medium text-white transition hover:bg-neutral-700 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200">
                    {{ __('Send report') }}
                </button>
            </form>
        @endif
    </main>
</body>
</html>
