<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locale priority: explicit ?lang= choice (persisted in the session),
     * then the session, then the visitor's browser language.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('nexo.locales'));

        $requested = $request->query('lang');

        if (is_string($requested) && in_array($requested, $supported, true)) {
            $request->session()->put('locale', $requested);
        }

        $locale = $request->session()->get('locale')
            ?? $request->getPreferredLanguage($supported)
            ?? config('app.locale');

        app()->setLocale($locale);

        return $next($request);
    }
}
