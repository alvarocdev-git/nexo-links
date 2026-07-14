<nav aria-label="{{ __('Language') }}" {{ $attributes->class(['flex items-center gap-2 text-sm']) }}>
    @foreach (config('nexo.locales') as $code => $label)
        <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
           lang="{{ str_replace('_', '-', $code) }}"
           title="{{ $label }}"
           @if (app()->getLocale() === $code) aria-current="true" @endif
           @class([
               'transition hover:opacity-100',
               'font-semibold underline underline-offset-4' => app()->getLocale() === $code,
               'opacity-60' => app()->getLocale() !== $code,
           ])>{{ strtoupper(explode('_', $code)[0]) }}</a>
    @endforeach
</nav>
