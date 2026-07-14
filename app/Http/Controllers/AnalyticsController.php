<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Page;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    private const array RANGES = [7, 30, 90];

    public function index(Request $request): View
    {
        $page = $request->user()?->page;
        abort_if($page === null, 404);

        $days = (int) $request->query('range', '30');
        if (! in_array($days, self::RANGES, true)) {
            $days = 30;
        }

        $from = CarbonImmutable::today()->subDays($days - 1);

        $totals = $this->clicksOf($page, $from)
            ->selectRaw('count(*) as total, count(distinct visitor_hash) as uniques')
            ->first();

        $perDay = $this->clicksOf($page, $from)
            ->selectRaw('date(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $series = collect(range(0, $days - 1))
            ->map(fn (int $offset): array => [
                'day' => $day = $from->addDays($offset)->toDateString(),
                'total' => (int) ($perDay[$day] ?? 0),
            ]);

        $perLink = $this->clicksOf($page, $from)
            ->selectRaw('link_id, count(*) as total, count(distinct visitor_hash) as uniques')
            ->groupBy('link_id')
            ->get()
            ->keyBy('link_id');

        $referrers = $this->clicksOf($page, $from)
            ->whereNotNull('referrer_host')
            ->selectRaw('referrer_host, count(*) as total')
            ->groupBy('referrer_host')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('analytics', [
            'days' => $days,
            'ranges' => self::RANGES,
            'totalClicks' => (int) ($totals->total ?? 0),
            'uniqueVisitors' => (int) ($totals->uniques ?? 0),
            'series' => $series,
            'maxPerDay' => max(1, (int) $series->max('total')),
            'links' => $page->links,
            'perLink' => $perLink,
            'referrers' => $referrers,
        ]);
    }

    /**
     * @return Builder<Click>
     */
    private function clicksOf(Page $page, CarbonImmutable $from): Builder
    {
        return Click::query()
            ->whereIn('link_id', $page->links()->select('id'))
            ->where('created_at', '>=', $from->startOfDay());
    }
}
