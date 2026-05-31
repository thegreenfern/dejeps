<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class CalendarController extends Controller
{
    // Timeline: Jan 1 → Oct 31 2026
    private const PERIOD_START = '2026-01-01';
    private const MONTH_DAYS   = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31];
    private const MONTH_NAMES  = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre'];
    private const TOTAL_DAYS   = 304;
    private const DAY_PX       = 10;   // pixels per day
    private const LABEL_PX     = 220;  // label column width

    public function index()
    {
        $role = session('role');
        if (!$role) return redirect()->route('home');

        $periodStart = Carbon::parse(self::PERIOD_START);
        $periodEnd   = Carbon::parse('2026-10-31');

        // Pixel width of each month column
        $monthPxWidths = array_map(fn($d) => $d * self::DAY_PX, self::MONTH_DAYS);

        // Cumulative month-end pixel positions (within the 3040px chart area)
        $cumPx = [0];
        foreach ($monthPxWidths as $w) {
            $cumPx[] = end($cumPx) + $w;
        }

        // Today position in the chart area (px)
        $todayDays = max(0, min(self::TOTAL_DAYS, $periodStart->diffInDays(Carbon::today(), false)));
        $todayPx   = $todayDays * self::DAY_PX;

        // Total chart width (px) — excludes label column
        $totalChartPx = self::TOTAL_DAYS * self::DAY_PX; // 3040

        // Type colours (600-weight for white-text contrast)
        $typeColors = CalendarEvent::typeColors();

        // Fetch & annotate events with pixel geometry
        $events = CalendarEvent::orderBy('start_on')->orderBy('due_on')->get()
            ->map(function ($event) use ($periodStart, $periodEnd, $typeColors) {
                $s = ($event->start_on ?? $event->due_on)?->copy();
                $e = ($event->due_on   ?? $s)?->copy();
                if (!$s) return null;

                if ($s->lt($periodStart)) $s = $periodStart->copy();
                if ($e->lt($periodStart)) return null;
                if ($e->gt($periodEnd))   $e = $periodEnd->copy();

                $leftDays  = $periodStart->diffInDays($s);
                $widthDays = max(1, $s->diffInDays($e) + 1);

                $c = $typeColors[$event->event_type ?? ''] ?? $typeColors[''];

                $event->bar_left_px  = $leftDays  * self::DAY_PX;
                $event->bar_width_px = $widthDays * self::DAY_PX;
                $event->bar_left_days  = $leftDays;   // for data-attr zoom
                $event->bar_width_days = $widthDays;
                $event->bar_hex      = $c['hex'];

                return $event;
            })
            ->filter();

        $uc12Events = $events->filter(fn($e) => $e->section === 'UC1 / UC2')->values();
        $uc34Events = $events->filter(fn($e) => $e->section === 'UC3 / UC4')->values();
        $lastSync   = CalendarEvent::max('synced_at');

        return view('calendar.index', [
            'uc12Events'     => $uc12Events,
            'uc34Events'     => $uc34Events,
            'monthNames'     => self::MONTH_NAMES,
            'monthDays'      => self::MONTH_DAYS,
            'monthPxWidths'  => $monthPxWidths,
            'cumPx'          => $cumPx,
            'typeColors'     => $typeColors,
            'todayPx'        => $todayPx,
            'todayDays'      => $todayDays,
            'totalDays'      => self::TOTAL_DAYS,
            'totalChartPx'   => $totalChartPx,
            'labelPx'        => self::LABEL_PX,
            'dayPx'          => self::DAY_PX,
            'lastSync'       => $lastSync ? Carbon::parse($lastSync) : null,
            'role'           => $role,
        ]);
    }

    public function sync()
    {
        Artisan::call('asana:sync');
        return back()->with('success', 'Calendrier synchronisé avec Asana.');
    }
}
