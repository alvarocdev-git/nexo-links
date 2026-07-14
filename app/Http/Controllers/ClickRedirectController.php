<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Support\VisitorHash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClickRedirectController extends Controller
{
    public function __invoke(Request $request, Link $link): RedirectResponse
    {
        abort_unless($link->isPublished(), 404);

        $link->clicks()->create([
            'visitor_hash' => VisitorHash::make($request),
            'referrer_host' => $this->referrerHost($request),
            'created_at' => now(),
        ]);

        return redirect()
            ->away($link->url)
            ->header('X-Robots-Tag', 'noindex');
    }

    /**
     * External host the visitor came from; clicks from the page itself
     * (or with no referrer) count as direct and store null.
     */
    private function referrerHost(Request $request): ?string
    {
        $referer = $request->headers->get('referer');

        if (! is_string($referer)) {
            return null;
        }

        $host = strtolower((string) parse_url($referer, PHP_URL_HOST));
        $host = (string) preg_replace('/^www\./', '', $host);

        if ($host === '' || $host === $request->getHost()) {
            return null;
        }

        return $host;
    }
}
