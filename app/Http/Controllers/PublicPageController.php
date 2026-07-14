<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Page;
use DateTimeInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PublicPageController extends Controller
{
    public function show(string $username): Response
    {
        $page = Page::query()
            ->where('username', $username)
            ->with(['links', 'socialLinks'])
            ->firstOrFail();

        $html = cache()->remember(
            "page:{$page->id}:".app()->getLocale(),
            $this->cacheExpiry($page),
            fn (): string => view('pages.show', [
                'page' => $page,
                'links' => $page->links
                    ->filter(fn (Link $link): bool => $link->isPublished() || $link->isUpcoming())
                    ->values(),
            ])->render(),
        );

        return response($html);
    }

    /**
     * Cache for up to an hour, but never past the next scheduled link
     * transition so pages publish and expire on time.
     */
    private function cacheExpiry(Page $page): DateTimeInterface
    {
        $nextTransition = $page->links
            ->flatMap(fn (Link $link): array => [$link->starts_at, $link->ends_at])
            ->filter(fn (?Carbon $moment): bool => $moment !== null && $moment->isFuture())
            ->min();

        $default = now()->addHour();

        return $nextTransition !== null && $nextTransition->lessThan($default)
            ? $nextTransition
            : $default;
    }
}
