<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Link;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $page = $this->pageOf($request);

        return view('dashboard', [
            'page' => $page,
            'links' => $page->links()->withCount('clicks')->get(),
        ]);
    }

    public function store(StoreLinkRequest $request): RedirectResponse
    {
        $page = $this->pageOf($request);

        $page->links()->create([
            ...$request->validated(),
            'position' => (int) $page->links()->max('position') + 1,
        ]);

        return redirect()->route('dashboard')->with('status', 'link-created');
    }

    public function update(UpdateLinkRequest $request, Link $link): RedirectResponse
    {
        $link->update($request->validated());

        return redirect()->route('dashboard')->with('status', 'link-updated');
    }

    public function destroy(Link $link): RedirectResponse
    {
        Gate::authorize('delete', $link);

        $link->delete();

        return redirect()->route('dashboard')->with('status', 'link-deleted');
    }

    /**
     * Persist a new ordering: an array of link ids in their new order.
     */
    public function reorder(Request $request): Response
    {
        $validated = $request->validate([
            'links' => ['required', 'array'],
            'links.*' => ['integer'],
        ]);

        $page = $this->pageOf($request);

        /** @var array<int, int> $ids */
        $ids = $validated['links'];
        $owned = $page->links()->pluck('id');

        if ($owned->diff($ids)->isNotEmpty() || collect($ids)->diff($owned)->isNotEmpty()) {
            throw ValidationException::withMessages([
                'links' => 'The link list does not match your links.',
            ]);
        }

        DB::transaction(function () use ($ids) {
            foreach (array_values($ids) as $position => $id) {
                Link::whereKey($id)->update(['position' => $position]);
            }
        });

        return response()->noContent();
    }

    private function pageOf(Request $request): Page
    {
        $page = $request->user()?->page;

        abort_if($page === null, 404);

        return $page;
    }
}
