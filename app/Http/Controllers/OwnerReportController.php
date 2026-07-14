<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OwnerReportController extends Controller
{
    public function index(Request $request): View
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        return view('reports', [
            'reports' => $page->reports()->with('link')->latest()->get(),
            'reasons' => config('nexo.report_reasons'),
        ]);
    }

    public function update(Report $report): RedirectResponse
    {
        Gate::authorize('update', $report);

        $report->update(['status' => 'resolved']);

        return redirect()->route('reports.index')->with('status', 'report-resolved');
    }
}
