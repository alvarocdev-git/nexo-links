<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDesignRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DesignController extends Controller
{
    public function edit(Request $request): View
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        return view('design', [
            'page' => $page,
            'themes' => config('nexo.themes'),
        ]);
    }

    public function update(UpdateDesignRequest $request): RedirectResponse
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        $data = $request->safe()->only([
            'bio', 'theme', 'background_type', 'background_start', 'background_end',
        ]);

        foreach (['avatar', 'banner'] as $image) {
            $column = "{$image}_path";

            if ($request->boolean("remove_{$image}") || $request->hasFile($image)) {
                if ($page->{$column} !== null) {
                    Storage::disk('public')->delete($page->{$column});
                }

                $data[$column] = null;
            }

            if ($request->hasFile($image)) {
                $data[$column] = $request->file($image)->store("{$image}s", 'public');
            }
        }

        $page->update($data);

        return redirect()->route('design.edit')->with('status', 'design-updated');
    }
}
