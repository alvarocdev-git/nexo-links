<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Links') }}
            </h2>
            <a href="{{ url('/'.$page->username) }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                {{ __('View my page') }} ↗
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'link-created')
                <p class="text-sm text-green-600">{{ __('Link created.') }}</p>
            @elseif (session('status') === 'link-updated')
                <p class="text-sm text-green-600">{{ __('Link updated.') }}</p>
            @elseif (session('status') === 'link-deleted')
                <p class="text-sm text-green-600">{{ __('Link deleted.') }}</p>
            @endif

            <!-- Add link -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="{ open: {{ $errors->hasAny(['title', 'url']) && ! old('editing') ? 'true' : 'false' }} }">
                <button type="button" x-show="! open" @click="open = true" class="w-full text-center text-sm font-medium text-indigo-600 hover:text-indigo-900">
                    + {{ __('Add link') }}
                </button>

                <form method="POST" action="{{ route('links.store') }}" x-show="open" x-cloak class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required maxlength="120" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="url" :value="__('URL')" />
                        <x-text-input id="url" name="url" type="text" class="mt-1 block w-full" :value="old('url')" required maxlength="2048" placeholder="https://…" />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                        <button type="button" @click="open = false" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>

            <!-- Links list -->
            @if ($links->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    {{ __('No links yet. Add your first one!') }}
                </div>
            @else
                <ul id="links-list" data-reorder-url="{{ route('links.reorder') }}" class="space-y-3">
                    @foreach ($links as $link)
                        <li data-link-id="{{ $link->id }}" class="bg-white shadow-sm sm:rounded-lg p-4" x-data="{ editing: {{ $errors->hasAny(['title', 'url']) && old('editing') == $link->id ? 'true' : 'false' }} }">
                            <div class="flex items-center gap-3">
                                <button type="button" data-drag-handle class="cursor-grab text-gray-400 hover:text-gray-600 touch-none" aria-label="{{ __('Drag to reorder') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm0 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm9-13a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm-1 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm1 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                    </svg>
                                </button>

                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">
                                        {{ $link->title }}
                                        @unless ($link->is_visible)
                                            <span class="ms-1 inline-block rounded bg-gray-100 px-1.5 py-0.5 text-xs text-gray-500">{{ __('Hidden') }}</span>
                                        @endunless
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">{{ $link->url }}</p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-sm text-gray-500 tabular-nums" title="{{ __('Total clicks') }}">{{ $link->clicks_count }} ⟶</span>

                                    <form method="POST" action="{{ route('links.update', $link) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_visible" value="{{ $link->is_visible ? 0 : 1 }}">
                                        <button class="text-sm text-gray-600 hover:text-gray-900 underline">
                                            {{ $link->is_visible ? __('Hide') : __('Show') }}
                                        </button>
                                    </form>

                                    <button type="button" @click="editing = ! editing" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Edit') }}</button>

                                    <form method="POST" action="{{ route('links.destroy', $link) }}" onsubmit="return confirm('{{ __('Delete this link?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm text-red-600 hover:text-red-900 underline">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('links.update', $link) }}" x-show="editing" x-cloak class="mt-4 space-y-4 border-t pt-4">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="editing" value="{{ $link->id }}">
                                <div>
                                    <x-input-label :value="__('Title')" />
                                    <x-text-input name="title" type="text" class="mt-1 block w-full" :value="old('editing') == $link->id ? old('title') : $link->title" required maxlength="120" />
                                </div>
                                <div>
                                    <x-input-label :value="__('URL')" />
                                    <x-text-input name="url" type="text" class="mt-1 block w-full" :value="old('editing') == $link->id ? old('url') : $link->url" required maxlength="2048" />
                                </div>
                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                                    <button type="button" @click="editing = false" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</button>
                                </div>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
