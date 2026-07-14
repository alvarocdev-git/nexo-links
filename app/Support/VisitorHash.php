<?php

namespace App\Support;

use Illuminate\Http\Request;

class VisitorHash
{
    /**
     * Anonymous, daily-rotating visitor fingerprint. Derived from the app
     * key, the current date, the IP and the user agent — none of which are
     * stored. The date in the payload makes it impossible to link the same
     * visitor across days.
     */
    public static function make(Request $request): string
    {
        return hash('sha256', implode('|', [
            (string) config('app.key'),
            now()->toDateString(),
            (string) $request->ip(),
            (string) $request->userAgent(),
        ]));
    }
}
