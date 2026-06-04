@extends('layouts.app')
@section('title', 'Pédagogie · ' . $trainee->name)

@section('content')
@php
use App\Models\TraineePedaStatus;
use App\Models\TraineePedaTheoStatus;
use Carbon\Carbon;

$statusCfg = [
    'nt'                    => ['label' => 'NT',                    'color' => '#cbd5e1'],
    'observation'           => ['label' => 'Observation',           'color' => '#fbbf24'],
    'supervision_directe'   => ['label' => 'Supervision directe',   'color' => '#fb923c'],
    'supervision_indirecte' => ['label' => 'Supervision indirecte', 'color' => '#8b5cf6'],
    'autonomie'             => ['label' => 'Autonomie',             'color' => '#10b981'],
];
$situations    = ['observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'];
$pratSitLabels = ['Obs', 'SD', 'SI', 'Auto'];

$notationStyles = [
    '3'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
    '2'   => ['badge' => 'bg-amber-100 text-amber-700'],
    '1'   => ['badge' => 'bg-slate-100 text-slate-400'],
    'A'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
    'ECA' => ['badge' => 'bg-amber-100 text-amber-700'],
    'NT'  => ['badge' => 'bg-slate-100 text-slate-400'],
];

$_uc3                  = $trainee->uc3;
$_compPoints           = \App\Models\TraineeUc3::competencyPoints();
$_topicProgress        = $_uc3?->topic_progress ?? [];
$_traineeTopicProgress = $_uc3?->trainee_topic_progress ?? [];
$_allKeys              = \App\Models\TraineeUc3::allPointKeys();
$_pratiqueComps        = \App\Models\TraineeUc3::pratiqueCompetencies();

$_sortByDate = fn($a, $b) => match(true) {
    (bool)$a['session_date'] && (bool)$b['session_date'] => strcmp($b['session_date'], $a['session_date']),
    (bool)$a['session_date'] => -1,
    (bool)$b['session_date'] => 1,
    default => 0,
};
$_hasData = fn($p) => array_key_exists('global_rating', $p) || array_key_exists('session_date', $p);
@endphp

<div class="max-w-3xl mx-auto">

    @include('trainee._nav')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Pédagogie</h1>
        <p class="text-sm text-slate-400 mt-0.5">Suivi de progression</p>
    </div>

    {{-- Subtab bar --}}
    <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6">
        <button type="button" class="peda-tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors" data-tab="pratique">
            Pédagogie Pratique
        </button>
        <button type="button" class="peda-tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors" data-tab="theorique">
            Pédagogie Théorique
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: Pratique                                                      --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="peda-tab-pratique" class="peda-tab-panel space-y-6">

        {{-- Progression calendar --}}
        @php
            $examDate   = $pedaData['exam_date'];
            $daysToExam = Carbon::today()->diffInDays(Carbon::parse($examDate), false);
            $nextSitMap = ['nt'=>'observation','observation'=>'supervision_directe','supervision_directe'=>'supervision_indirecte','supervision_indirecte'=>'autonomie','autonomie'=>null];
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Calendrier de progression</h3>
                <span class="text-[10px] {{ $daysToExam <= 14 ? 'text-red-500 font-semibold' : 'text-slate-400' }}">
                    Examen pédagogie pratique :
                    {{ Carbon::parse($examDate)->locale('fr')->isoFormat('D MMM YYYY') }}
                    @if($daysToExam >= 0) · {{ $daysToExam }}j @else · passé @endif
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-2.5 px-4 text-slate-400 font-medium w-14">Niveau</th>
                            <th class="text-center py-2.5 px-3 text-slate-400 font-medium w-14">Séances</th>
                            <th class="text-center py-2.5 px-4 text-slate-400 font-medium">Statut</th>
                            <th class="text-center py-2.5 px-2 text-slate-400 font-medium w-20">Échéance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedaData['levels'] as $lk => $lv)
                        @php
                            $totalSeances = array_sum(array_column($lv['counts'], 'total'));
                            $sitIdx       = array_search($lv['status'], $situations);
                            if ($sitIdx === false) $sitIdx = -1;
                            $nextSit      = $nextSitMap[$lv['status']] ?? null;
                            $m            = $nextSit ? ($lv['timeline'][$nextSit] ?? null) : ($lv['timeline']['autonomie'] ?? null);
                        @endphp
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="py-2.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-700">{{ $lv['label'] }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="inline-block text-xs font-semibold text-slate-500 bg-slate-100 rounded-full px-2 py-0.5">{{ $totalSeances }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <div style="width:100%;display:flex;flex-direction:column;align-items:stretch">
                                    <div style="display:flex;align-items:center;width:100%">
                                        @foreach($situations as $i => $sit)
                                        @php $filled = $i <= $sitIdx; $current = $i === $sitIdx; $sc = $statusCfg[$sit]; @endphp
                                        @if($i > 0)
                                            <div style="flex:1;height:2px;border-radius:1px;background:{{ $i <= $sitIdx ? $sc['color'] : '#e2e8f0' }}"></div>
                                        @endif
                                        <div title="{{ $sc['label'] }}"
                                             style="width:14px;height:14px;border-radius:50%;flex-shrink:0;{{ $filled ? 'background-color:'.$sc['color'].($current ? ';box-shadow:0 0 0 3px '.$sc['color'].'33' : '') : 'background-color:#fff;border:2px solid #e2e8f0' }}"></div>
                                        @endforeach
                                    </div>
                                    <div style="display:flex;align-items:flex-start;width:100%;margin-top:6px">
                                        @foreach($situations as $i => $sit)
                                        @php $active = $i <= $sitIdx; $sc = $statusCfg[$sit]; @endphp
                                        @if($i > 0)<div style="flex:1"></div>@endif
                                        <span style="font-size:9px;line-height:1;flex-shrink:0;color:{{ $active ? $sc['color'] : '#cbd5e1' }};font-weight:{{ $active ? '600' : '400' }}">{{ $pratSitLabels[$i] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-2 text-center">
                                @if(!$m)
                                    <span class="text-slate-200 text-xs">—</span>
                                @elseif($m['achieved'])
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                        <span class="text-[10px] text-emerald-600 font-medium">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                    </div>
                                @elseif($m['at_risk'])
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center animate-pulse">
                                            <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                            </svg>
                                        </span>
                                        <span class="text-[10px] text-red-600 font-semibold">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                        <span class="text-[9px] text-red-400">{{ $m['days_left'] >= 0 ? $m['days_left'].'j' : 'dépassé' }}</span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="text-[11px] text-slate-500">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                        @if($m['days_left'] !== null && $m['days_left'] >= 0)
                                            <span class="text-[9px] text-slate-300">{{ $m['days_left'] }}j</span>
                                        @elseif($m['days_left'] !== null && $m['days_left'] < 0)
                                            <span class="text-[9px] text-slate-400 font-medium">dépassé</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Séances pratiques --}}
        @php
            $_pratSlugs = array_unique(array_merge(
                array_filter(array_keys($_topicProgress),        fn($s) => str_starts_with($s, 'pratique_') && $_hasData($_topicProgress[$s])),
                array_filter(array_keys($_traineeTopicProgress), fn($s) => str_starts_with($s, 'pratique_') && $_hasData($_traineeTopicProgress[$s]))
            ));
            $_pratSessions = [];
            foreach ($_pratSlugs as $_slug) {
                $_iP = $_topicProgress[$_slug] ?? null;
                $_sP = $_traineeTopicProgress[$_slug] ?? null;
                preg_match('/^pratique_([a-z0-9]+)_s(\d+)$/i', $_slug, $_pm);
                $_plvKey  = strtoupper($_pm[1] ?? '');
                $_pLvInfo = $_pratiqueComps[$_plvKey] ?? null;
                $_pratSessions[] = [
                    'slug'         => $_slug,
                    'level'        => $_plvKey,
                    'level_label'  => $_pLvInfo['label'] ?? $_plvKey,
                    'session_num'  => $_pm[2] ?? '?',
                    'session_date' => ($_iP['session_date'] ?? null) ?: ($_sP['session_date'] ?? null),
                    'instr_rating' => $_iP['global_rating'] ?? null,
                    'self_rating'  => $_sP['global_rating'] ?? null,
                ];
            }
            usort($_pratSessions, $_sortByDate);
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
            <div>
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances pratiques</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ count($_pratSessions) }} séance{{ count($_pratSessions) !== 1 ? 's' : '' }}</p>
            </div>

            @if(count($_pratSessions) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                    <p class="text-sm">Aucune séance pratique enregistrée</p>
                </div>
            @else
                <div class="rounded-lg overflow-hidden border border-slate-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5 w-24">Date</th>
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5">Niveau · Séance</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Formateur</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-16">Moi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($_pratSessions as $_ps)
                                <tr class="trainee-prat-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $_ps['slug'] }}">
                                    <td class="px-4 py-3.5">
                                        @if($_ps['session_date'])
                                            <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($_ps['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($_ps['session_date'])->year }}</span>
                                        @else
                                            <span class="text-sm text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <button type="button" class="trainee-prat-row-btn text-left w-full" data-slug="{{ $_ps['slug'] }}">
                                            <span class="text-sm text-slate-700">{{ $_ps['level'] }} · Séance {{ $_ps['session_num'] }}</span>
                                            <span class="text-xs text-slate-400 block leading-tight">{{ $_ps['level_label'] }}</span>
                                        </button>
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        @if($_ps['instr_rating'])
                                            <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ps['instr_rating']]['badge'] }}">{{ $_ps['instr_rating'] }}</span>
                                        @else
                                            <span class="text-xs text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        @if($_ps['self_rating'])
                                            <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ps['self_rating']]['badge'] }}">{{ $_ps['self_rating'] }}</span>
                                        @else
                                            <span class="text-xs text-slate-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="trainee-prat-placeholder" class="rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                    </svg>
                    <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
                </div>

                @foreach($_pratSessions as $_ps)
                    @php
                        $_iP      = $_topicProgress[$_ps['slug']] ?? null;
                        $_sP      = $_traineeTopicProgress[$_ps['slug']] ?? null;
                        $_iA      = $_iP ? collect($_allKeys)->filter(fn($k) => ($_iP[$k] ?? null) === '3')->count() : 0;
                        $_sA      = $_sP ? collect($_allKeys)->filter(fn($k) => ($_sP[$k] ?? null) === '3')->count() : 0;
                        $_pLvComp = $_pratiqueComps[$_ps['level']] ?? null;
                    @endphp
                    <div data-trainee-prat-panel="{{ $_ps['slug'] }}" style="display:none" class="rounded-xl border border-slate-200 p-6 space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">{{ $_ps['level'] }} · Séance {{ $_ps['session_num'] }} <span class="text-slate-400 font-normal text-sm">· {{ $_ps['level_label'] }}</span></h3>
                            @if($_ps['session_date'])
                                <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($_ps['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                            @endif
                        </div>

                        @if($_iP && $_sP)
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50/70">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Formateur</span>
                                    @if($_iP['global_rating'] ?? null)<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_iP['global_rating']]['badge'] }}">{{ $_iP['global_rating'] }}</span>@endif
                                    <span class="text-[9px] text-slate-300 font-bold">VS</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon éval.</span>
                                    @if($_sP['global_rating'] ?? null)<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_sP['global_rating']]['badge'] }}">{{ $_sP['global_rating'] }}</span>@endif
                                </div>
                                <span class="text-xs tabular-nums text-slate-400">{{ $_iA }}/9 · {{ $_sA }}/9 A</span>
                            </div>
                            <table class="w-full">
                                <thead><tr class="border-b border-slate-100 bg-slate-50/40">
                                    <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2">Critère</th>
                                    <th class="text-center text-[10px] font-semibold text-violet-500 uppercase tracking-wide px-3 py-2 w-24">Formateur</th>
                                    <th class="text-center text-[10px] font-semibold text-sky-500 uppercase tracking-wide px-3 py-2 w-24">Moi</th>
                                </tr></thead>
                                <tbody>
                                    @foreach($_compPoints as $_gpKey => $_gpGroup)
                                    <tr class="border-t border-slate-100 bg-slate-50/50"><td colspan="3" class="px-4 py-1.5"><span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $_gpGroup['label'] }}</span></td></tr>
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @php $_ni = in_array($_iP[$_gpItemKey] ?? null, ['3','2']) ? $_iP[$_gpItemKey] : '1'; $_ns = in_array($_sP[$_gpItemKey] ?? null, ['3','2']) ? $_sP[$_gpItemKey] : '1'; @endphp
                                        <tr class="border-t border-slate-50">
                                            <td class="py-2 px-4 text-xs text-slate-600 leading-snug">{{ $_gpPoint['label'] }}</td>
                                            <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ni]['badge'] }}">{{ $_ni }}</span></td>
                                            <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ns]['badge'] }}">{{ $_ns }}</span></td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            @if($_pLvComp && (!empty($_iP['exercises_done']) || !empty($_sP['exercises_done'])))
                            <div class="border-t border-slate-100 px-4 py-3">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Exercices abordés</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] font-bold text-violet-500 uppercase tracking-wide mb-2">Formateur</p>
                                        <div class="space-y-1.5">
                                            @foreach($_pLvComp['keys'] as $_exKey => $_exLabel)
                                                @php $_done = in_array($_exKey, $_iP['exercises_done'] ?? []); @endphp
                                                <div class="flex items-center gap-2">
                                                    @if($_done)<svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-xs text-slate-700">{{ $_exLabel }}</span>
                                                    @else<svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg><span class="text-xs text-slate-300">{{ $_exLabel }}</span>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-sky-500 uppercase tracking-wide mb-2">Moi</p>
                                        <div class="space-y-1.5">
                                            @foreach($_pLvComp['keys'] as $_exKey => $_exLabel)
                                                @php $_done = in_array($_exKey, $_sP['exercises_done'] ?? []); @endphp
                                                <div class="flex items-center gap-2">
                                                    @if($_done)<svg class="w-3.5 h-3.5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-xs text-slate-700">{{ $_exLabel }}</span>
                                                    @else<svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg><span class="text-xs text-slate-300">{{ $_exLabel }}</span>@endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(($_iP['session_note'] ?? null) || ($_sP['session_note'] ?? null))
                            <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-200">
                                <div class="px-4 py-3">@if($_iP['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Note formateur</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_iP['session_note'] }}</p>@endif</div>
                                <div class="px-4 py-3">@if($_sP['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Mes notes</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_sP['session_note'] }}</p>@endif</div>
                            </div>
                            @endif
                        </div>
                        @else
                        @if($_iP)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Évaluation formateur</span>
                                <div class="flex items-center gap-2">
                                    @if($_iP['global_rating'] ?? null)<span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$_iP['global_rating']]['badge'] }}">{{ $_iP['global_rating'] }}</span>@endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $_iA }}/9 A</span>
                                </div>
                            </div>
                            @foreach($_compPoints as $_gpKey => $_gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $_gpGroup['label'] }}</p>
                                <table class="w-full"><tbody class="divide-y divide-slate-100">
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @php $_n = in_array($_iP[$_gpItemKey] ?? null, ['3','2']) ? $_iP[$_gpItemKey] : '1'; @endphp
                                        <tr><td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $_gpPoint['label'] }}</td><td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_n]['badge'] }}">{{ $_n }}</span></td><td class="py-2 text-xs text-slate-400">{{ $_iP[$_gpItemKey . '_note'] ?? '' }}</td></tr>
                                    @endforeach
                                </tbody></table>
                            @endforeach
                            @if($_pLvComp && !empty($_iP['exercises_done']))
                                <div class="mt-4 pt-3 border-t border-slate-100"><p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Exercices abordés</p><div class="space-y-1.5">
                                    @foreach($_pLvComp['keys'] as $_exKey => $_exLabel)
                                        @php $_done = in_array($_exKey, $_iP['exercises_done'] ?? []); @endphp
                                        <div class="flex items-center gap-2">@if($_done)<svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-xs text-slate-700">{{ $_exLabel }}</span>@else<svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg><span class="text-xs text-slate-300">{{ $_exLabel }}</span>@endif</div>
                                    @endforeach
                                </div></div>
                            @endif
                            @if($_iP['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Note formateur</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_iP['session_note'] }}</p>@endif
                        </div>
                        @endif
                        @if($_sP)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon auto-évaluation</span>
                                <div class="flex items-center gap-2">
                                    @if($_sP['global_rating'] ?? null)<span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$_sP['global_rating']]['badge'] }}">{{ $_sP['global_rating'] }}</span>@endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $_sA }}/9 A</span>
                                </div>
                            </div>
                            @foreach($_compPoints as $_gpKey => $_gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $_gpGroup['label'] }}</p>
                                <table class="w-full"><tbody class="divide-y divide-slate-100">
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @php $_n = in_array($_sP[$_gpItemKey] ?? null, ['3','2']) ? $_sP[$_gpItemKey] : '1'; @endphp
                                        <tr><td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $_gpPoint['label'] }}</td><td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_n]['badge'] }}">{{ $_n }}</span></td><td class="py-2 text-xs text-slate-400">{{ $_sP[$_gpItemKey . '_note'] ?? '' }}</td></tr>
                                    @endforeach
                                </tbody></table>
                            @endforeach
                            @if($_pLvComp && !empty($_sP['exercises_done']))
                                <div class="mt-4 pt-3 border-t border-slate-100"><p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Exercices abordés</p><div class="space-y-1.5">
                                    @foreach($_pLvComp['keys'] as $_exKey => $_exLabel)
                                        @php $_done = in_array($_exKey, $_sP['exercises_done'] ?? []); @endphp
                                        <div class="flex items-center gap-2">@if($_done)<svg class="w-3.5 h-3.5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg><span class="text-xs text-slate-700">{{ $_exLabel }}</span>@else<svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg><span class="text-xs text-slate-300">{{ $_exLabel }}</span>@endif</div>
                                    @endforeach
                                </div></div>
                            @endif
                            @if($_sP['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Mes notes</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_sP['session_note'] }}</p>@endif
                        </div>
                        @else
                        <div class="rounded-xl border border-dashed border-slate-200 p-4"><p class="text-sm text-slate-400">Vous n'avez pas encore auto-évalué cette séance.</p></div>
                        @endif
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>{{-- /peda-tab-pratique --}}

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: Théorique                                                     --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="peda-tab-theorique" class="peda-tab-panel space-y-6" style="display:none">
    @php
        $theoCfg = [
            'nt'       => ['label' => 'NT',       'color' => '#cbd5e1'],
            'en_cours' => ['label' => 'En cours', 'color' => '#fbbf24'],
            'valide'   => ['label' => 'Validé',   'color' => '#10b981'],
        ];
        $coverage    = $pedaTheoData['coverage'];
        $coveragePct = $coverage['total'] > 0 ? round($coverage['covered'] / $coverage['total'] * 100) : 0;
        $theoExamDate     = $pedaTheoData['exam_date'];
        $daysToTheoExam   = Carbon::today()->diffInDays(Carbon::parse($theoExamDate), false);
    @endphp

        {{-- Coverage indicator --}}
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <span class="text-xs font-semibold text-slate-600">Couverture des sujets DEJEPS</span>
                    <span class="ml-2 text-xs text-slate-400">{{ $coverage['covered'] }}/{{ $coverage['total'] }} sujets travaillés</span>
                </div>
                <span class="text-xs font-bold {{ $coveragePct === 100 ? 'text-emerald-600' : ($coveragePct >= 60 ? 'text-amber-600' : 'text-slate-400') }}">
                    {{ $coveragePct }}%
                </span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full transition-all"
                     style="width:{{ $coveragePct }}%;background-color:{{ $coveragePct === 100 ? '#10b981' : ($coveragePct >= 60 ? '#fbbf24' : '#94a3b8') }}">
                </div>
            </div>
            @if($coveragePct < 100)
                <p class="text-[10px] text-slate-400 mt-1.5">Tous les sujets doivent être travaillés au moins une fois avant l'examen.</p>
            @else
                <p class="text-[10px] text-emerald-600 mt-1.5">Tous les sujets DEJEPS ont été travaillés.</p>
            @endif
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Calendrier de progression</h3>
                <span class="text-[10px] {{ $daysToTheoExam <= 14 ? 'text-red-500 font-semibold' : 'text-slate-400' }}">
                    Examen pédagogie théorique :
                    {{ Carbon::parse($theoExamDate)->locale('fr')->isoFormat('D MMM YYYY') }}
                    @if($daysToTheoExam >= 0) · {{ $daysToTheoExam }}j @else · passé @endif
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left py-2.5 px-4 text-slate-400 font-medium w-14">Niveau</th>
                            <th class="text-center py-2.5 px-3 text-slate-400 font-medium w-14">Sujets</th>
                            <th class="text-center py-2.5 px-4 text-slate-400 font-medium">Statut</th>
                            <th class="text-center py-2.5 px-2 text-slate-400 font-medium w-20">Échéance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedaTheoData['levels'] as $lk => $lv)
                        @php
                            $sitIdx = array_search($lv['sit_status'], $situations);
                            if ($sitIdx === false) $sitIdx = -1;
                            $m = $lv['timeline'];
                        @endphp
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="py-2.5 px-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-700">{{ $lv['label'] }}</div>
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <span class="inline-block text-xs font-semibold text-slate-500 bg-slate-100 rounded-full px-2 py-0.5">{{ count($lv['topics']) }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <div style="width:100%;display:flex;flex-direction:column;align-items:stretch">
                                    <div style="display:flex;align-items:center;width:100%">
                                        @foreach($situations as $i => $sit)
                                        @php $filled = $i <= $sitIdx; $current = $i === $sitIdx; $sc = $statusCfg[$sit]; @endphp
                                        @if($i > 0)
                                            <div style="flex:1;height:2px;border-radius:1px;background:{{ $i <= $sitIdx ? $sc['color'] : '#e2e8f0' }}"></div>
                                        @endif
                                        <div title="{{ $sc['label'] }}"
                                             style="width:14px;height:14px;border-radius:50%;flex-shrink:0;{{ $filled ? 'background-color:'.$sc['color'].($current ? ';box-shadow:0 0 0 3px '.$sc['color'].'33' : '') : 'background-color:#fff;border:2px solid #e2e8f0' }}"></div>
                                        @endforeach
                                    </div>
                                    <div style="display:flex;align-items:flex-start;width:100%;margin-top:6px">
                                        @foreach($situations as $i => $sit)
                                        @php $active = $i <= $sitIdx; $sc = $statusCfg[$sit]; @endphp
                                        @if($i > 0)<div style="flex:1"></div>@endif
                                        <span style="font-size:9px;line-height:1;flex-shrink:0;color:{{ $active ? $sc['color'] : '#cbd5e1' }};font-weight:{{ $active ? '600' : '400' }}">{{ $pratSitLabels[$i] }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-2 text-center">
                                @if(!$m)
                                    <span class="text-slate-200 text-xs">—</span>
                                @elseif($m['achieved'])
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                        <span class="text-[10px] text-emerald-600 font-medium">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                    </div>
                                @elseif($m['at_risk'])
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center animate-pulse">
                                            <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                            </svg>
                                        </span>
                                        <span class="text-[10px] text-red-600 font-semibold">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                        <span class="text-[9px] text-red-400">{{ $m['days_left'] >= 0 ? $m['days_left'].'j' : 'dépassé' }}</span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center gap-0.5">
                                        <span class="text-[11px] text-slate-500">{{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}</span>
                                        @if($m['days_left'] !== null && $m['days_left'] >= 0)
                                            <span class="text-[9px] text-slate-300">{{ $m['days_left'] }}j</span>
                                        @elseif($m['days_left'] !== null && $m['days_left'] < 0)
                                            <span class="text-[9px] text-slate-400 font-medium">dépassé</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Séances théoriques --}}
        @php
            $_topics        = \App\Models\TraineeUc3::topics();
            $_theoSlugs = array_unique(array_merge(
                array_filter(array_keys($_topicProgress),        fn($s) => !str_starts_with($s, 'pratique_') && $_hasData($_topicProgress[$s])),
                array_filter(array_keys($_traineeTopicProgress), fn($s) => !str_starts_with($s, 'pratique_') && $_hasData($_traineeTopicProgress[$s]))
            ));
            $_theoSessions = [];
            foreach ($_theoSlugs as $_slug) {
                $_iP2       = $_topicProgress[$_slug] ?? null;
                $_sP2       = $_traineeTopicProgress[$_slug] ?? null;
                $_ref       = $_iP2 ?? $_sP2;
                $_topicInfo = collect($_topics)->firstWhere('slug', $_slug);
                $_rawLabel  = $_topicInfo ? $_topicInfo['label'] : ($_ref['session_label'] ?? 'Thème libre');
                $_theoSessions[] = [
                    'slug'         => $_slug,
                    'label'        => preg_replace('/^(?:N\d|Niveau\s*\d+)\s*[·\-—]\s*/iu', '', $_rawLabel),
                    'level'        => $_topicInfo ? $_topicInfo['level'] : ($_ref['session_level'] ?? null),
                    'session_date' => ($_iP2['session_date'] ?? null) ?: ($_sP2['session_date'] ?? null),
                    'instr_rating' => $_iP2['global_rating'] ?? null,
                    'self_rating'  => $_sP2['global_rating'] ?? null,
                ];
            }
            usort($_theoSessions, $_sortByDate);
            $_theoCompleteKeys = array_filter($_allKeys, fn($k) => $k !== 'r_securite');
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
            <div>
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances théoriques</h2>
                <p class="text-xs text-slate-400 mt-0.5">{{ count($_theoSessions) }} séance{{ count($_theoSessions) !== 1 ? 's' : '' }}</p>
            </div>

            @if(count($_theoSessions) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-sm">Aucune séance théorique enregistrée</p>
                </div>
            @else
                <div class="rounded-lg overflow-hidden border border-slate-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5 w-24">Date</th>
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5">Thème</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Formateur</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-16">Moi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($_theoSessions as $_s)
                                <tr class="trainee-theo-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $_s['slug'] }}">
                                    <td class="px-4 py-3.5">
                                        @if($_s['session_date'])
                                            <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($_s['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($_s['session_date'])->year }}</span>
                                        @else
                                            <span class="text-sm text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <button type="button" class="trainee-theo-row-btn text-left w-full" data-slug="{{ $_s['slug'] }}">
                                            <span class="text-sm text-slate-700">{{ $_s['label'] }}@if($_s['level']) <span class="text-slate-400">· {{ $_s['level'] }}</span>@endif</span>
                                        </button>
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        @if($_s['instr_rating'])<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_s['instr_rating']]['badge'] }}">{{ $_s['instr_rating'] }}</span>
                                        @else<span class="text-xs text-slate-300">—</span>@endif
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        @if($_s['self_rating'])<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_s['self_rating']]['badge'] }}">{{ $_s['self_rating'] }}</span>
                                        @else<span class="text-xs text-slate-300">—</span>@endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="trainee-theo-placeholder" class="rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                    </svg>
                    <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
                </div>

                @foreach($_theoSessions as $_s)
                    @php
                        $_iP2 = $_topicProgress[$_s['slug']] ?? null;
                        $_sP2 = $_traineeTopicProgress[$_s['slug']] ?? null;
                        $_iA  = $_iP2 ? collect($_theoCompleteKeys)->filter(fn($k) => ($_iP2[$k] ?? null) === '3')->count() : 0;
                        $_sA  = $_sP2 ? collect($_theoCompleteKeys)->filter(fn($k) => ($_sP2[$k] ?? null) === '3')->count() : 0;
                        $_maxA = count($_theoCompleteKeys);
                    @endphp
                    <div data-trainee-theo-panel="{{ $_s['slug'] }}" style="display:none" class="rounded-xl border border-slate-200 p-6 space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">{{ $_s['label'] }}@if($_s['level']) <span class="text-slate-400 font-normal text-sm">· {{ $_s['level'] }}</span>@endif</h3>
                            @if($_s['session_date'])<p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($_s['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>@endif
                        </div>
                        @if($_iP2 && $_sP2)
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50/70">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Formateur</span>
                                    @if($_iP2['global_rating'] ?? null)<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_iP2['global_rating']]['badge'] }}">{{ $_iP2['global_rating'] }}</span>@endif
                                    <span class="text-[9px] text-slate-300 font-bold">VS</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon éval.</span>
                                    @if($_sP2['global_rating'] ?? null)<span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_sP2['global_rating']]['badge'] }}">{{ $_sP2['global_rating'] }}</span>@endif
                                </div>
                                <span class="text-xs tabular-nums text-slate-400">{{ $_iA }}/{{ $_maxA }} · {{ $_sA }}/{{ $_maxA }} A</span>
                            </div>
                            <table class="w-full">
                                <thead><tr class="border-b border-slate-100 bg-slate-50/40">
                                    <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2">Critère</th>
                                    <th class="text-center text-[10px] font-semibold text-violet-500 uppercase tracking-wide px-3 py-2 w-24">Formateur</th>
                                    <th class="text-center text-[10px] font-semibold text-sky-500 uppercase tracking-wide px-3 py-2 w-24">Moi</th>
                                </tr></thead>
                                <tbody>
                                    @foreach($_compPoints as $_gpKey => $_gpGroup)
                                    <tr class="border-t border-slate-100 bg-slate-50/50"><td colspan="3" class="px-4 py-1.5"><span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $_gpGroup['label'] }}</span></td></tr>
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @continue($_gpPoint['pratique_only'] ?? false)
                                        @php $_ni = in_array($_iP2[$_gpItemKey] ?? null, ['3','2']) ? $_iP2[$_gpItemKey] : '1'; $_ns = in_array($_sP2[$_gpItemKey] ?? null, ['3','2']) ? $_sP2[$_gpItemKey] : '1'; @endphp
                                        <tr class="border-t border-slate-50">
                                            <td class="py-2 px-4 text-xs text-slate-600 leading-snug">{{ $_gpPoint['label'] }}</td>
                                            <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ni]['badge'] }}">{{ $_ni }}</span></td>
                                            <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_ns]['badge'] }}">{{ $_ns }}</span></td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            @if(($_iP2['session_note'] ?? null) || ($_sP2['session_note'] ?? null))
                            <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-200">
                                <div class="px-4 py-3">@if($_iP2['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Note formateur</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_iP2['session_note'] }}</p>@endif</div>
                                <div class="px-4 py-3">@if($_sP2['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Mes notes</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_sP2['session_note'] }}</p>@endif</div>
                            </div>
                            @endif
                        </div>
                        @else
                        @if($_iP2)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Évaluation formateur</span>
                                <div class="flex items-center gap-2">
                                    @if($_iP2['global_rating'] ?? null)<span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$_iP2['global_rating']]['badge'] }}">{{ $_iP2['global_rating'] }}</span>@endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $_iA }}/{{ $_maxA }} A</span>
                                </div>
                            </div>
                            @foreach($_compPoints as $_gpKey => $_gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $_gpGroup['label'] }}</p>
                                <table class="w-full"><tbody class="divide-y divide-slate-100">
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @continue($_gpPoint['pratique_only'] ?? false)
                                        @php $_n = in_array($_iP2[$_gpItemKey] ?? null, ['3','2']) ? $_iP2[$_gpItemKey] : '1'; @endphp
                                        <tr><td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $_gpPoint['label'] }}</td><td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_n]['badge'] }}">{{ $_n }}</span></td><td class="py-2 text-xs text-slate-400">{{ $_iP2[$_gpItemKey . '_note'] ?? '' }}</td></tr>
                                    @endforeach
                                </tbody></table>
                            @endforeach
                            @if($_iP2['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Note formateur</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_iP2['session_note'] }}</p>@endif
                        </div>
                        @endif
                        @if($_sP2)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon auto-évaluation</span>
                                <div class="flex items-center gap-2">
                                    @if($_sP2['global_rating'] ?? null)<span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$_sP2['global_rating']]['badge'] }}">{{ $_sP2['global_rating'] }}</span>@endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $_sA }}/{{ $_maxA }} A</span>
                                </div>
                            </div>
                            @foreach($_compPoints as $_gpKey => $_gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $_gpGroup['label'] }}</p>
                                <table class="w-full"><tbody class="divide-y divide-slate-100">
                                    @foreach($_gpGroup['items'] as $_gpItemKey => $_gpPoint)
                                        @continue($_gpPoint['pratique_only'] ?? false)
                                        @php $_n = in_array($_sP2[$_gpItemKey] ?? null, ['3','2']) ? $_sP2[$_gpItemKey] : '1'; @endphp
                                        <tr><td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $_gpPoint['label'] }}</td><td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$_n]['badge'] }}">{{ $_n }}</span></td><td class="py-2 text-xs text-slate-400">{{ $_sP2[$_gpItemKey . '_note'] ?? '' }}</td></tr>
                                    @endforeach
                                </tbody></table>
                            @endforeach
                            @if($_sP2['session_note'] ?? null)<p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Mes notes</p><p class="text-sm text-slate-500 leading-relaxed">{{ $_sP2['session_note'] }}</p>@endif
                        </div>
                        @else
                        <div class="rounded-xl border border-dashed border-slate-200 p-4"><p class="text-sm text-slate-400">Vous n'avez pas encore auto-évalué cette séance.</p></div>
                        @endif
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>{{-- /peda-tab-theorique --}}

    <div class="pb-10"></div>
</div>

<script>
// Subtab switching
(function () {
    const btns   = document.querySelectorAll('.peda-tab-btn');
    const panels = document.querySelectorAll('.peda-tab-panel');
    function activate(id) {
        btns.forEach(btn => {
            const on = btn.dataset.tab === id;
            btn.style.backgroundColor = on ? '#fff' : '';
            btn.style.color           = on ? '#1e293b' : '';
            btn.style.fontWeight      = on ? '600' : '';
            btn.style.boxShadow       = on ? '0 1px 2px rgba(0,0,0,.06)' : '';
        });
        panels.forEach(p => { p.style.display = p.id === 'peda-tab-' + id ? '' : 'none'; });
    }
    btns.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));
    const hash = location.hash.replace('#', '');
    activate(hash === 'theorique' ? 'theorique' : 'pratique');
})();

// Pratique click-to-expand
(function () {
    const placeholder = document.getElementById('trainee-prat-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-trainee-prat-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.trainee-prat-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-trainee-prat-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.trainee-prat-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.trainee-prat-row-btn, .trainee-prat-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();

// Théorique click-to-expand
(function () {
    const placeholder = document.getElementById('trainee-theo-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-trainee-theo-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.trainee-theo-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-trainee-theo-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.trainee-theo-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.trainee-theo-row-btn, .trainee-theo-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();
</script>
@endsection
