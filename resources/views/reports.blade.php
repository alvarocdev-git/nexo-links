<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status') === 'report-resolved')
                <p class="text-sm text-green-600">{{ __('Report marked as resolved.') }}</p>
            @endif

            @if ($reports->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    {{ __('No reports. All good!') }}
                </div>
            @else
                @foreach ($reports as $report)
                    <div @class(['bg-white shadow-sm sm:rounded-lg p-6', 'opacity-60' => $report->status === 'resolved'])>
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ __($reasons[$report->reason] ?? $report->reason) }}
                                    @if ($report->status === 'resolved')
                                        <span class="ms-1 rounded bg-green-50 px-1.5 py-0.5 text-xs text-green-700">{{ __('Resolved') }}</span>
                                    @else
                                        <span class="ms-1 rounded bg-amber-50 px-1.5 py-0.5 text-xs text-amber-700">{{ __('Open') }}</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $report->link?->title ?? __('The whole page') }}
                                    · {{ $report->created_at->diffForHumans() }}
                                </p>
                                @if ($report->details)
                                    <p class="mt-2 text-sm text-gray-700">{{ $report->details }}</p>
                                @endif
                            </div>

                            @if ($report->status !== 'resolved')
                                <form method="POST" action="{{ route('reports.update', $report) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="rounded-md bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                        {{ __('Mark as resolved') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
