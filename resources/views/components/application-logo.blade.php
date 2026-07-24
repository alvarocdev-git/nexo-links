{{-- Nexo Links isotype: the violet tile + link-list bars. Colors come from the
     brand tokens (var(--nexo-*)), so it stays on-brand in light and dark and
     never trips the no-hardcoded-colors guardian. The public mark SVG shipped to
     favicons/app-switcher lives at public/ecosystem/nexolinks.svg (generated from
     resources/brand/mark.svg). --}}
<svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" role="img" aria-label="{{ config('app.name') }}">
    <rect x="2" y="2" width="44" height="44" rx="12" fill="var(--nexo-primary)"/>
    <g fill="var(--nexo-primary-fg)">
        <rect x="13" y="15" width="22" height="5" rx="2.5"/>
        <rect x="13" y="21.5" width="22" height="5" rx="2.5"/>
        <rect x="13" y="28" width="16" height="5" rx="2.5"/>
    </g>
</svg>
