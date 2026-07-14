<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Range filter -->
            <div class="flex gap-2">
                @foreach ($ranges as $range)
                    <a href="{{ route('analytics', ['range' => $range]) }}"
                       @class([
                           'rounded-full px-3 py-1 text-sm transition',
                           'bg-indigo-600 text-white' => $days === $range,
                           'bg-white text-gray-600 hover:bg-gray-50 shadow-sm' => $days !== $range,
                       ])>
                        {{ __(':days days', ['days' => $range]) }}
                    </a>
                @endforeach
            </div>

            <!-- Stat tiles -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Total clicks') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums">{{ number_format($totalClicks) }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Unique visitors') }}</p>
                    <p class="mt-1 text-3xl font-bold tabular-nums">{{ number_format($uniqueVisitors) }}</p>
                </div>
            </div>

            <!-- Clicks per day -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-900">{{ __('Clicks per day') }}</h3>

                <div class="mt-4 flex h-32 items-end gap-0.5 border-b border-gray-200" role="img" aria-label="{{ __('Clicks per day, last :days days', ['days' => $days]) }}">
                    @foreach ($series as $point)
                        <div class="group relative flex-1">
                            <span class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-1 hidden -translate-x-1/2 whitespace-nowrap rounded bg-gray-900 px-2 py-1 text-xs text-white group-hover:block">
                                {{ $point['day'] }} · {{ trans_choice(':count click|:count clicks', $point['total']) }}
                            </span>
                            <div class="w-full rounded-t bg-indigo-500 transition group-hover:bg-indigo-600"
                                 @style(['height: '.($point['total'] > 0 ? max(4, round($point['total'] / $maxPerDay * 120)) : 0).'px'])></div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-1 flex justify-between text-xs text-gray-400">
                    <span>{{ $series->first()['day'] }}</span>
                    <span>{{ $series->last()['day'] }}</span>
                </div>
            </div>

            <!-- Per link -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <h3 class="font-medium text-gray-900">{{ __('Per link') }}</h3>

                @if ($links->isEmpty())
                    <p class="mt-4 text-sm text-gray-500">{{ __('No links yet.') }}</p>
                @else
                    <table class="mt-4 w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th scope="col" class="pb-2 font-normal">{{ __('Link') }}</th>
                                <th scope="col" class="pb-2 font-normal text-right">{{ __('Clicks') }}</th>
                                <th scope="col" class="pb-2 font-normal text-right">{{ __('Unique') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($links as $link)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2 pr-4">
                                        <span class="text-gray-900">{{ $link->title }}</span>
                                        @unless ($link->is_visible)
                                            <span class="ms-1 rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-500">{{ __('Hidden') }}</span>
                                        @endunless
                                    </td>
                                    <td class="py-2 text-right tabular-nums">{{ number_format($perLink[$link->id]->total ?? 0) }}</td>
                                    <td class="py-2 text-right tabular-nums">{{ number_format($perLink[$link->id]->uniques ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Top referrers -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-900">{{ __('Top referrers') }}</h3>

                @if ($referrers->isEmpty())
                    <p class="mt-4 text-sm text-gray-500">{{ __('No external referrers yet — clicks from your page or shared links without a referrer count as direct.') }}</p>
                @else
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach ($referrers as $referrer)
                            <li class="flex justify-between">
                                <span class="text-gray-900">{{ $referrer->referrer_host }}</span>
                                <span class="tabular-nums text-gray-500">{{ number_format($referrer->total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
