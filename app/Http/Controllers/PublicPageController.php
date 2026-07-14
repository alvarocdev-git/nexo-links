<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Page;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function show(string $username): View
    {
        $page = Page::query()
            ->where('username', $username)
            ->with('links')
            ->firstOrFail();

        return view('pages.show', [
            'page' => $page,
            'links' => $page->links->filter(fn (Link $link): bool => $link->isPublished())->values(),
        ]);
    }
}
