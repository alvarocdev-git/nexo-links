<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class SocialLinkController extends Controller
{
    public function store(StoreSocialLinkRequest $request): RedirectResponse
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        $page->socialLinks()->create([
            ...$request->validated(),
            'position' => (int) $page->socialLinks()->max('position') + 1,
        ]);

        return redirect()->route('dashboard')->with('status', 'social-created');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        Gate::authorize('delete', $socialLink);

        $socialLink->delete();

        return redirect()->route('dashboard')->with('status', 'social-deleted');
    }
}
