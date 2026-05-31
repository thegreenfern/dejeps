@extends('layouts.app')
@section('title', 'Calendrier de formation')

@section('content')
@php
    // CSS week-grid: first Monday of 2026 is Jan 5 (day 4 = 40px from chart start)
    $weekStartPx   = 4 * $dayPx; // 40px
    $weekPeriodPx  = 7 * $dayPx; // 70px

    // Month separator positions (within the 3040px chart, not counting label column)
    $monthSepPx = array_slice($cumPx, 1, -1); // exclude 0 and last (3040)

    // Legend types
    $typeLegend = array_filter($typeColors, fn($k) => $k !== '', ARRAY_FILTER_USE_KEY);
    $typeLegend[''] = $typeColors[''];
    $legendLabels = [
        'Formation'             => 'Formation',
        'Observation'           => 'Observation',
        'Supervision directe'   => 'Sup. directe',
        'Autonomie'             => 'Autonomie',
        'Supervision indirecte' => 'Sup. indirecte',
        'Examen'                => 'Examen',
        ''                      => 'Autre',
    ];

    $currentMonthIdx = 4; // May = index 4 (0-based)
@endphp

{{-- ── Page header ─────────────────────────────────────────── --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800 leading-tight">Calendrier de formation</h1>
        <p class="text-sm text-slate-400 mt-0.5">DEJEPS Moniteur de plongée &middot; Janvier → Octobre 2026</p>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
        {{-- Zoom --}}
        <div class="inline-flex items-center gap-0.5 bg-slate-100 rounded-lg p-1">
            <button id="zoom-out" class="px-2.5 py-1 rounded-md text-xs font-semibold text-slate-500 hover:bg-white hover:text-slate-700 transition-colors">−</button>
            <span id="zoom-label" class="px-2 text-xs font-semibold text-slate-600 tabular-nums w-10 text-center">100%</span>
            <button id="zoom-in"  class="px-2.5 py-1 rounded-md text-xs font-semibold text-slate-500 hover:bg-white hover:text-slate-700 transition-colors">+</button>
        </div>
        {{-- Today button --}}
        <button id="btn-today"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block"></span>
            Aujourd'hui
        </button>
        {{-- Sync (instructor only) --}}
        @if($role === 'instructor')
        <form method="POST" action="{{ route('calendar.sync') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:border-slate-300 shadow-sm transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Synchroniser
            </button>
        </form>
        @endif
    </div>
</div>

{{-- ── Section tabs ─────────────────────────────────────────── --}}
<div class="flex justify-end mb-4">
    <div class="inline-flex gap-0.5 bg-slate-100 rounded-lg p-1 text-xs font-semibold flex-shrink-0">
        <button class="cal-tab-btn px-3.5 py-1.5 rounded-md transition-all" data-tab="all">Tout</button>
        <button class="cal-tab-btn px-3.5 py-1.5 rounded-md transition-all" data-tab="uc12">UC1 &amp; UC2</button>
        <button class="cal-tab-btn px-3.5 py-1.5 rounded-md transition-all" data-tab="uc34">UC3 &amp; UC4</button>
    </div>
</div>

{{-- ── Gantt card ───────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm" style="overflow: clip;">

    {{-- ── Scrollable container ──────────────────────────────── --}}
    {{-- overflow-y clip prevents the sticky-top/sticky-left conflict --}}
    <div id="gantt-scroll" style="overflow-x: auto; overflow-y: clip;">
        <div id="gantt-inner" style="min-width: {{ $labelPx + $totalChartPx }}px; position: relative;">

            {{-- ── Month header (sticky top) ──────────────────── --}}
            <div class="flex border-b border-slate-200"
                 style="position: sticky; top: 0; z-index: 30; background: #f8fafc;">
                {{-- Label corner cell --}}
                <div style="width: {{ $labelPx }}px; flex-shrink: 0; position: sticky; left: 0; z-index: 40; background: #f8fafc; border-right: 1px solid #e2e8f0;">
                    <div class="flex items-center h-full px-4 py-3">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tâche</span>
                    </div>
                </div>
                {{-- Month cells + today marker --}}
                <div style="width: {{ $totalChartPx }}px; flex-shrink: 0; display: flex; position: relative;">
                    @foreach($monthNames as $i => $name)
                    @php
                        $isNow = ($i === $currentMonthIdx);
                    @endphp
                    <div data-month-days="{{ $monthDays[$i] }}"
                         style="width: {{ $monthPxWidths[$i] }}px; flex-shrink: 0; border-right: 1px solid #e2e8f0; position: relative;"
                         class="{{ $isNow ? 'bg-violet-50/60' : '' }}">
                        <div class="flex flex-col items-center justify-center py-2.5 gap-0.5 h-full">
                            <span class="text-[11px] font-bold {{ $isNow ? 'text-violet-600' : 'text-slate-500' }}">{{ $name }}</span>
                            <span class="text-[9px] {{ $isNow ? 'text-violet-400' : 'text-slate-300' }} tabular-nums">2026</span>
                        </div>
                        @if($isNow)
                        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-violet-400 opacity-50"></div>
                        @endif
                    </div>
                    @endforeach

                    {{-- Today dot + line in header --}}
                    <div style="position: absolute; top: 0; bottom: 0; left: {{ $todayPx }}px; transform: translateX(-50%); pointer-events: none; z-index: 10;">
                        <div style="display: flex; flex-direction: column; align-items: center; height: 100%;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: #f43f5e; margin-top: 10px; flex-shrink: 0; box-shadow: 0 0 0 2px #ffe4e6;"></div>
                            <div style="flex: 1; width: 2px; background: linear-gradient(to bottom, #f43f5e, rgba(244,63,94,0.2));"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Chart body: shared grid overlay ──────────────── --}}
            {{-- This absolute div spans all rows and draws grid lines --}}
            <div id="chart-grid-overlay" style="position: absolute; top: 0; bottom: 0; left: {{ $labelPx }}px; width: {{ $totalChartPx }}px; pointer-events: none; z-index: 1;">
                {{-- Week grid lines (CSS repeating gradient) --}}
                <div style="position: absolute; inset: 0;
                            background-image: repeating-linear-gradient(
                                to right,
                                rgba(226,232,240,0.35) 0px,
                                rgba(226,232,240,0.35) 1px,
                                transparent 1px,
                                transparent {{ $weekPeriodPx }}px
                            );
                            background-position: {{ $weekStartPx }}px 0;
                            background-size: {{ $weekPeriodPx }}px 100%;"></div>
                {{-- Month separator lines --}}
                @foreach($monthSepPx as $px)
                <div style="position: absolute; top: 0; bottom: 0; left: {{ $px }}px; width: 1px; background: #cbd5e1; opacity: 0.7;"></div>
                @endforeach
                {{-- Today line --}}
                <div data-today-line style="position: absolute; top: 0; bottom: 0; left: {{ $todayPx }}px; transform: translateX(-50%); width: 2px; background: linear-gradient(to bottom, #f43f5e, rgba(244,63,94,0.3)); z-index: 5;"></div>
            </div>

            {{-- ══════════════════════════════════════════════════ --}}
            {{-- UC1 / UC2 Section                                 --}}
            {{-- ══════════════════════════════════════════════════ --}}
            <div id="section-uc12" class="cal-section">
                {{-- Section header row --}}
                <div class="flex items-center border-b border-slate-100" style="background: #eff6ff; position: sticky; top: 48px; z-index: 25;">
                    <div style="width: {{ $labelPx }}px; flex-shrink: 0; position: sticky; left: 0; z-index: 26; background: #eff6ff; border-right: 1px solid #bfdbfe; padding: 8px 16px;">
                        <div class="flex items-center gap-2">
                            <span style="width:8px; height:8px; border-radius:50%; background:#2563eb; flex-shrink:0;"></span>
                            <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">UC1 / UC2</span>
                            <span class="text-[10px] text-blue-400 font-medium">{{ $uc12Events->count() }} tâches</span>
                        </div>
                    </div>
                    <div style="width: {{ $totalChartPx }}px; flex-shrink: 0; height: 32px;"></div>
                </div>

                {{-- Event rows --}}
                @foreach($uc12Events as $event)
                @php
                    $hex      = $event->bar_hex;
                    $leftPx   = $event->bar_left_px;
                    $widthPx  = $event->bar_width_px;
                    $isShort  = $widthPx < 40;  // < 4 days: dot only
                    $noText   = $widthPx < 70;   // < 7 days: bar but no text
                    $dateStr  = ($event->start_on ? $event->start_on->format('d/m') : '—') . ' → ' . ($event->due_on ? $event->due_on->format('d/m') : '—');
                    $opacity  = $event->completed ? '0.4' : '1';
                    $typeKey  = $event->event_type ?? '';
                @endphp
                <div class="flex items-stretch cal-row group" data-type="{{ $typeKey }}"
                     style="opacity: {{ $opacity }}; border-bottom: 1px solid #f1f5f9;">
                    {{-- Label cell --}}
                    <div style="width: {{ $labelPx }}px; flex-shrink: 0; position: sticky; left: 0; z-index: 15; background: white; border-right: 1px solid #e2e8f0; padding: 0 16px; min-height: 48px; display: flex; flex-direction: column; justify-content: center; transition: background 0.1s;"
                         class="group-hover:bg-slate-50/90">
                        <span class="block text-[10px] font-semibold text-slate-700 leading-snug"
                              style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: {{ $labelPx - 32 }}px;"
                              title="{{ $event->name }}">{{ $event->name }}</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5 tabular-nums">{{ $dateStr }}</span>
                    </div>
                    {{-- Chart track --}}
                    <div data-chart-track style="width: {{ $totalChartPx }}px; flex-shrink: 0; position: relative; min-height: 48px;"
                         class="group-hover:bg-slate-50/30">
                        @if($isShort)
                        {{-- Milestone dot --}}
                        <div data-left="{{ $event->bar_left_days }}"
                             style="position: absolute; top: 50%; left: {{ $leftPx }}px; transform: translate(-50%, -50%);
                                    width: 12px; height: 12px; border-radius: 50%; background: {{ $hex }};
                                    border: 2px solid white; box-shadow: 0 0 0 2px {{ $hex }}55; z-index: 10;"
                             title="{{ $event->name }} ({{ $dateStr }})"></div>
                        @else
                        {{-- Full bar --}}
                        <div data-left="{{ $event->bar_left_days }}" data-width="{{ $event->bar_width_days }}"
                             style="position: absolute; top: 10px; bottom: 10px;
                                    left: {{ $leftPx }}px; width: {{ $widthPx }}px;
                                    background: {{ $hex }};
                                    border-radius: 5px;
                                    box-shadow: 0 1px 3px rgba(0,0,0,0.18);
                                    display: flex; align-items: center;
                                    overflow: hidden; z-index: 10;
                                    transition: filter 0.15s;"
                             class="gantt-bar"
                             title="{{ $event->name }} ({{ $dateStr }})"
                             onmouseenter="this.style.filter='brightness(1.1)'"
                             onmouseleave="this.style.filter=''">
                            @if(!$noText)
                            <span style="padding: 0 8px; font-size: 10.5px; font-weight: 700; color: white;
                                         white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                         letter-spacing: 0.01em; text-shadow: 0 1px 2px rgba(0,0,0,0.25);">
                                @if($event->asana_url)
                                <a href="{{ $event->asana_url }}" target="_blank" style="color:inherit; text-decoration:none;"
                                   onclick="event.stopPropagation()">{{ $event->name }}</a>
                                @else
                                {{ $event->name }}
                                @endif
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Section divider --}}
            <div id="section-spacer" style="height: 4px; background: #e2e8f0; border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1;"></div>

            {{-- ══════════════════════════════════════════════════ --}}
            {{-- UC3 / UC4 Section                                 --}}
            {{-- ══════════════════════════════════════════════════ --}}
            <div id="section-uc34" class="cal-section">
                <div class="flex items-center border-b border-slate-100" style="background: #f0f9ff; position: sticky; top: 48px; z-index: 25;">
                    <div style="width: {{ $labelPx }}px; flex-shrink: 0; position: sticky; left: 0; z-index: 26; background: #f0f9ff; border-right: 1px solid #bae6fd; padding: 8px 16px;">
                        <div class="flex items-center gap-2">
                            <span style="width:8px; height:8px; border-radius:50%; background:#0284c7; flex-shrink:0;"></span>
                            <span class="text-[11px] font-bold text-sky-700 uppercase tracking-wider">UC3 / UC4</span>
                            <span class="text-[10px] text-sky-400 font-medium">{{ $uc34Events->count() }} tâches</span>
                        </div>
                    </div>
                    <div style="width: {{ $totalChartPx }}px; flex-shrink: 0; height: 32px;"></div>
                </div>

                @foreach($uc34Events as $event)
                @php
                    $hex      = $event->bar_hex;
                    $leftPx   = $event->bar_left_px;
                    $widthPx  = $event->bar_width_px;
                    $isShort  = $widthPx < 40;
                    $noText   = $widthPx < 70;
                    $dateStr  = ($event->start_on ? $event->start_on->format('d/m') : '—') . ' → ' . ($event->due_on ? $event->due_on->format('d/m') : '—');
                    $opacity  = $event->completed ? '0.4' : '1';
                    $typeKey  = $event->event_type ?? '';
                @endphp
                <div class="flex items-stretch cal-row group" data-type="{{ $typeKey }}"
                     style="opacity: {{ $opacity }}; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: {{ $labelPx }}px; flex-shrink: 0; position: sticky; left: 0; z-index: 15; background: white; border-right: 1px solid #e2e8f0; padding: 0 16px; min-height: 48px; display: flex; flex-direction: column; justify-content: center; transition: background 0.1s;"
                         class="group-hover:bg-slate-50/90">
                        <span class="block text-[10px] font-semibold text-slate-700 leading-snug"
                              style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: {{ $labelPx - 32 }}px;"
                              title="{{ $event->name }}">{{ $event->name }}</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5 tabular-nums">{{ $dateStr }}</span>
                    </div>
                    <div data-chart-track style="width: {{ $totalChartPx }}px; flex-shrink: 0; position: relative; min-height: 48px;"
                         class="group-hover:bg-slate-50/30">
                        @if($isShort)
                        <div data-left="{{ $event->bar_left_days }}"
                             style="position: absolute; top: 50%; left: {{ $leftPx }}px; transform: translate(-50%, -50%);
                                    width: 12px; height: 12px; border-radius: 50%; background: {{ $hex }};
                                    border: 2px solid white; box-shadow: 0 0 0 2px {{ $hex }}55; z-index: 10;"
                             title="{{ $event->name }} ({{ $dateStr }})"></div>
                        @else
                        <div data-left="{{ $event->bar_left_days }}" data-width="{{ $event->bar_width_days }}"
                             style="position: absolute; top: 10px; bottom: 10px;
                                    left: {{ $leftPx }}px; width: {{ $widthPx }}px;
                                    background: {{ $hex }};
                                    border-radius: 5px;
                                    box-shadow: 0 1px 3px rgba(0,0,0,0.18);
                                    display: flex; align-items: center;
                                    overflow: hidden; z-index: 10;
                                    transition: filter 0.15s;"
                             class="gantt-bar"
                             title="{{ $event->name }} ({{ $dateStr }})"
                             onmouseenter="this.style.filter='brightness(1.1)'"
                             onmouseleave="this.style.filter=''">
                            @if(!$noText)
                            <span style="padding: 0 8px; font-size: 10.5px; font-weight: 700; color: white;
                                         white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                         letter-spacing: 0.01em; text-shadow: 0  1px 2px rgba(0,0,0,0.25);">
                                @if($event->asana_url)
                                <a href="{{ $event->asana_url }}" target="_blank" style="color:inherit; text-decoration:none;"
                                   onclick="event.stopPropagation()">{{ $event->name }}</a>
                                @else
                                {{ $event->name }}
                                @endif
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ── Footer bar ──────────────────────────────────── --}}
            <div class="flex items-center justify-between px-5 py-3 border-t border-slate-200 bg-slate-50/80">
                <div class="flex items-center gap-4" style="position: sticky; left: 0; z-index: 15;">
                    <span class="text-[11px] text-slate-400 inline-flex items-center gap-1.5">
                        <span style="width:14px; height:2px; background:#f43f5e; border-radius:2px; display:inline-block;"></span>
                        Aujourd'hui ({{ \Carbon\Carbon::today()->format('d/m/Y') }})
                    </span>
                    <span class="text-[11px] text-slate-400">{{ $uc12Events->count() + $uc34Events->count() }} tâches · Jan–Oct 2026</span>
                </div>
                @if($lastSync)
                <span class="text-[11px] text-slate-400 flex-shrink-0">
                    Asana · {{ $lastSync->diffForHumans() }}
                </span>
                @endif
            </div>

        </div>{{-- /gantt-inner --}}
    </div>{{-- /gantt-scroll --}}
</div>{{-- /card --}}

<script>
(function () {
    var scroll     = document.getElementById('gantt-scroll');
    var inner      = document.getElementById('gantt-inner');
    var todayDays  = {{ $todayDays }};
    var todayPx    = {{ $todayPx }};
    var labelPx    = {{ $labelPx }};
    var baseDayPx  = {{ $dayPx }};
    var totalDays  = {{ $totalDays }};
    var currentDpx = baseDayPx;

    // ── Scroll to today ──────────────────────────────────────
    function scrollToToday() {
        var visibleChart = scroll.clientWidth - labelPx;
        var target = Math.max(0, todayPx - visibleChart / 2);
        scroll.scrollTo({ left: target, behavior: 'smooth' });
    }

    document.getElementById('btn-today').addEventListener('click', scrollToToday);

    // Auto-scroll on load
    window.addEventListener('load', function () {
        var visibleChart = scroll.clientWidth - labelPx;
        var target = Math.max(0, todayPx - visibleChart / 2);
        scroll.scrollLeft = target;
    });

    // ── Zoom ────────────────────────────────────────────────
    var zoomLabel = document.getElementById('zoom-label');
    var zoomLevels = [4, 6, 8, 10, 12, 14, 18, 24];
    var zoomIdx = zoomLevels.indexOf(baseDayPx);
    if (zoomIdx < 0) zoomIdx = 3;

    function applyZoom(dpx) {
        currentDpx = dpx;
        var ratio = dpx / baseDayPx;
        zoomLabel.textContent = Math.round(ratio * 100) + '%';

        // Resize inner
        var newChartWidth = totalDays * dpx;
        inner.style.minWidth = (labelPx + newChartWidth) + 'px';

        // Reposition bars (data-left + data-width) and dots (data-left only)
        document.querySelectorAll('[data-left]').forEach(function (el) {
            el.style.left = (parseInt(el.dataset.left) * dpx) + 'px';
            if (el.dataset.width !== undefined) {
                el.style.width = (parseInt(el.dataset.width) * dpx) + 'px';
            }
        });

        // Resize month header cells
        document.querySelectorAll('[data-month-days]').forEach(function (el) {
            el.style.width = (parseInt(el.dataset.monthDays) * dpx) + 'px';
        });

        // Resize chart track cells
        document.querySelectorAll('[data-chart-track]').forEach(function (el) {
            el.style.width = newChartWidth + 'px';
        });

        // Update today line position and grid overlay width
        var overlay = document.getElementById('chart-grid-overlay');
        if (overlay) {
            overlay.style.width = newChartWidth + 'px';
            var todayLine = overlay.querySelector('[data-today-line]');
            if (todayLine) todayLine.style.left = (todayDays * dpx) + 'px';
        }

        // Re-center today
        var visibleChart = scroll.clientWidth - labelPx;
        scroll.scrollLeft = Math.max(0, todayDays * dpx - visibleChart / 2);

        todayPx = todayDays * dpx;
    }

    document.getElementById('zoom-out').addEventListener('click', function () {
        zoomIdx = Math.max(0, zoomIdx - 1);
        applyZoom(zoomLevels[zoomIdx]);
    });

    document.getElementById('zoom-in').addEventListener('click', function () {
        zoomIdx = Math.min(zoomLevels.length - 1, zoomIdx + 1);
        applyZoom(zoomLevels[zoomIdx]);
    });

    // ── Section tabs ────────────────────────────────────────
    var tabs    = document.querySelectorAll('.cal-tab-btn');
    var sec12   = document.getElementById('section-uc12');
    var sec34   = document.getElementById('section-uc34');
    var spacer  = document.getElementById('section-spacer');

    function activateTab(id) {
        tabs.forEach(function (btn) {
            var on = btn.dataset.tab === id;
            btn.style.background = on ? '#fff' : '';
            btn.style.color      = on ? '#1e293b' : '';
            btn.style.fontWeight = on ? '700' : '';
            btn.style.boxShadow  = on ? '0 1px 3px rgba(0,0,0,.08)' : '';
        });
        sec12.style.display  = (id === 'all' || id === 'uc12') ? '' : 'none';
        sec34.style.display  = (id === 'all' || id === 'uc34') ? '' : 'none';
        spacer.style.display = (id === 'all') ? '' : 'none';
    }

    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () { activateTab(this.dataset.tab); });
    });
    activateTab('all');

    // ── Legend type filter ───────────────────────────────────
    var activeFilter = null;

    document.querySelectorAll('.legend-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var t = this.dataset.type;
            activeFilter = (activeFilter === t) ? null : t;
            applyFilter();

            // Toggle active style on legend pills
            document.querySelectorAll('.legend-btn').forEach(function (b) {
                var isActive = !activeFilter || b.dataset.type === activeFilter;
                b.style.opacity = isActive ? '1' : '0.35';
                b.style.transform = (b.dataset.type === activeFilter) ? 'scale(1.05)' : '';
            });
        });
    });

    function applyFilter() {
        document.querySelectorAll('.cal-row').forEach(function (row) {
            var match = !activeFilter || row.dataset.type === activeFilter
                        || (activeFilter === '' && !row.dataset.type);
            row.style.display = match ? '' : 'none';
        });
    }
})();
</script>
@endsection
