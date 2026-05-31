@php
use App\Models\TraineePedaStatus;
use Carbon\Carbon;

$statusCfg = [
    'nt'                    => ['label' => 'NT',                    'color' => '#cbd5e1', 'pill' => 'bg-slate-50 text-slate-500 border-slate-200'],
    'observation'           => ['label' => 'Observation',           'color' => '#fbbf24', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200'],
    'supervision_directe'   => ['label' => 'Supervision directe',   'color' => '#fb923c', 'pill' => 'bg-orange-50 text-orange-700 border-orange-200'],
    'supervision_indirecte' => ['label' => 'Supervision indirecte', 'color' => '#8b5cf6', 'pill' => 'bg-violet-50 text-violet-700 border-violet-200'],
    'autonomie'             => ['label' => 'Autonomie',             'color' => '#10b981', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
];

$sitShort = [
    'observation'           => 'Obs',
    'supervision_directe'   => 'SD',
    'supervision_indirecte' => 'SI',
    'autonomie'             => 'Auto',
];

$statuses  = TraineePedaStatus::STATUSES;  // ['nt','observation','supervision_directe','supervision_indirecte','autonomie']
$levels    = TraineePedaStatus::LEVELS;    // ['bapteme','n1','n2','n3']
$situations = ['observation','supervision_directe','supervision_indirecte','autonomie'];
@endphp

{{-- ── Peda subtab bar ─────────────────────────────────────────────── --}}
<div class="flex gap-1 bg-slate-100 rounded-lg p-1 mb-6">
    <button type="button" class="peda-subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="peda-pratique">
        Pédagogie Pratique
    </button>
    <button type="button" class="peda-subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="peda-theorique">
        Pédagogie Théorique
    </button>
</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- SUBTAB: Peda Pratique                                             --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div id="subtab-peda-pratique" class="peda-subtab-panel">

    @if(session('peda_success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
            {{ session('peda_success') }}
        </div>
    @endif

    {{-- ── Timeline ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-2">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Calendrier de progression</h3>
            @php
                $examDate = $pedaData['exam_date'];
                $daysToExam = Carbon::today()->diffInDays(Carbon::parse($examDate), false);
            @endphp
            <span class="text-[10px] {{ $daysToExam <= 14 ? 'text-red-500 font-semibold' : 'text-slate-400' }}">
                Examen pédagogie pratique :
                {{ Carbon::parse($examDate)->locale('fr')->isoFormat('D MMM YYYY') }}
                @if($daysToExam >= 0)
                    · {{ $daysToExam }}j
                @else
                    · passé
                @endif
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2.5 px-4 text-slate-400 font-medium w-24">Niveau</th>
                        @foreach($situations as $sit)
                            <th class="text-center py-2.5 px-3 font-medium">
                                <div class="flex items-center justify-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color:{{ $statusCfg[$sit]['color'] }}"></span>
                                    <span class="text-slate-600">{{ $statusCfg[$sit]['label'] }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedaData['levels'] as $lk => $lv)
                    <tr class="border-b border-slate-50 last:border-0">
                        <td class="py-2.5 px-4">
                            <div class="font-semibold text-slate-700">{{ $lv['label'] }}</div>
                            @php $totalSeances = array_sum(array_column($lv['counts'], 'total')); @endphp
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $totalSeances }} séance{{ $totalSeances !== 1 ? 's' : '' }}</div>
                        </td>
                        @foreach($situations as $sit)
                        @php
                            $nextSit = ['observation'=>'supervision_directe','supervision_directe'=>'supervision_indirecte','supervision_indirecte'=>'autonomie','autonomie'=>null][$sit];
                            $m = $nextSit ? ($lv['timeline'][$nextSit] ?? null) : null;
                            $isCurrent = $sit === $lv['status'];
                        @endphp
                        <td class="py-2 px-3 text-center {{ $isCurrent ? 'bg-slate-50' : '' }}"
                            @if($isCurrent) style="box-shadow:inset 0 0 0 1px {{ $statusCfg[$sit]['color'] }}20" @endif>
                            @if(!$m)
                                <span class="text-slate-200">—</span>
                            @elseif($m['achieved'])
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    <span class="text-[10px] text-emerald-600 font-medium">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
                                </div>
                            @elseif($m['at_risk'])
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center animate-pulse">
                                        <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                    </span>
                                    <span class="text-[10px] text-red-600 font-semibold">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
                                    @if($m['days_left'] !== null)
                                    <span class="text-[9px] text-red-400">
                                        {{ $m['days_left'] >= 0 ? $m['days_left'].'j' : 'dépassé' }}
                                    </span>
                                    @endif
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="text-[11px] text-slate-500">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
                                    @if($m['days_left'] !== null && $m['days_left'] >= 0)
                                    <span class="text-[9px] text-slate-300">{{ $m['days_left'] }}j</span>
                                    @elseif($m['days_left'] !== null && $m['days_left'] < 0)
                                    <span class="text-[9px] text-slate-400 font-medium">dépassé</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-[10px] text-slate-400 px-1 mb-8">
        ✓ Étape atteinte &nbsp;·&nbsp;
        <span class="text-red-400">⚠</span> À risque (échéance dans ≤ 3 jours ou dépassée) &nbsp;·&nbsp;
        — Non prévu à ce niveau
    </p>

    {{-- ── Status cards ─────────────────────────────────────────────── --}}
    <div class="space-y-3 mb-8">
        @foreach($pedaData['levels'] as $levelKey => $level)
        @php
            $currentStatus = $level['status'];
            $currentIdx    = TraineePedaStatus::statusIndex($currentStatus);
            $cfg           = $statusCfg[$currentStatus];
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 p-4">

            {{-- Pending automation prompt ──────────────────────────── --}}
            @if($level['pending_auto'])
            <div class="flex items-center gap-3 mb-3 px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-lg">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-amber-700 flex-1">
                    L'automatisation suggère de passer à
                    <strong>{{ $statusCfg[$level['pending_auto']]['label'] }}</strong>.
                    Le statut actuel a été défini manuellement.
                </p>
                <div class="flex gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('instructor.peda.status.save', $trainee) }}">
                        @csrf
                        <input type="hidden" name="level"  value="{{ $levelKey }}">
                        <input type="hidden" name="status" value="{{ $level['pending_auto'] }}">
                        <button type="submit"
                                class="text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 px-2.5 py-1 rounded-md transition-colors">
                            Accepter
                        </button>
                    </form>
                    <form method="POST" action="{{ route('instructor.peda.status.save', $trainee) }}">
                        @csrf
                        <input type="hidden" name="level"  value="{{ $levelKey }}">
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                        <button type="submit"
                                class="text-xs font-semibold text-slate-500 bg-white hover:bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-md transition-colors">
                            Garder
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="flex items-start gap-4">

                {{-- Level name ─────────────────────────────────────── --}}
                <div class="w-16 flex-shrink-0 pt-0.5">
                    <span class="text-base font-bold text-slate-800">{{ $level['label'] }}</span>
                </div>

                {{-- Status pill + progress stepper ─────────────────── --}}
                <div class="flex-1 min-w-0">

                    {{-- Status pill --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $cfg['pill'] }}">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:{{ $cfg['color'] }}"></span>
                            {{ $cfg['label'] }}
                        </span>
                        @if($level['is_manual'])
                            <span class="text-[10px] text-slate-400 font-medium">Manuel</span>
                        @endif
                    </div>

                    {{-- Progress stepper: NT ──●──●──○──○ ─────────── --}}
                    <div class="flex items-center gap-0">
                        @foreach($statuses as $i => $st)
                        @php $filled = $i <= $currentIdx; $sc = $statusCfg[$st]; @endphp
                        {{-- Connecting line before (skip for first) --}}
                        @if($i > 0)
                            <div class="h-px flex-1 transition-colors"
                                 style="background:{{ $filled ? $sc['color'] : '#e2e8f0' }}"></div>
                        @endif
                        {{-- Dot --}}
                        <div class="relative flex-shrink-0" title="{{ $sc['label'] }}">
                            @if($filled)
                                <div class="w-3 h-3 rounded-full" style="background-color:{{ $sc['color'] }}"></div>
                            @else
                                <div class="w-3 h-3 rounded-full bg-white border-2 border-slate-200"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Stage labels + séance counts ───────────────── --}}
                    <div class="flex items-start mt-1" style="gap:0">
                        @foreach($statuses as $i => $st)
                        @php
                            $labelActive  = $i <= $currentIdx;
                            $c            = $level['counts'][$st] ?? null;
                            $last         = $i === count($statuses) - 1;
                            $alignItems   = $i === 0 ? 'flex-start' : ($last ? 'flex-end' : 'center');
                            $ml           = $i === 0 ? '0' : '-14px';
                            $mr           = $last ? '0' : '-14px';
                            $stepLabel    = $i === 0 ? '1' : ($sitShort[$st] ?? '');
                            $aCount       = $c['3'] ?? 0;
                            $ecaCount     = $c['2'] ?? 0;
                            $hasCount     = ($c['total'] ?? 0) > 0;
                        @endphp
                        @if($i > 0)<div class="flex-1"></div>@endif
                        <div class="flex-shrink-0 flex flex-col leading-none"
                             style="width:28px;align-items:{{ $alignItems }};margin-left:{{ $ml }};margin-right:{{ $mr }}">
                            <span class="text-[9px] {{ $labelActive ? 'text-slate-500 font-medium' : 'text-slate-300' }}">{{ $stepLabel }}</span>
                            @if($hasCount && $aCount > 0)
                            <span class="text-[8px] font-semibold mt-0.5 tabular-nums" style="color:#10b981">{{ $aCount }}×3</span>
                            @endif
                            @if($hasCount && $ecaCount > 0)
                            <span class="text-[8px] font-medium tabular-nums" style="color:#f59e0b">{{ $ecaCount }}×2</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Manual override ─────────────────────────────────── --}}
                <div class="flex-shrink-0">
                    <button type="button"
                            onclick="openPedaOverride('{{ $levelKey }}','{{ $currentStatus }}')"
                            class="text-[10px] text-slate-400 hover:text-slate-600 border border-slate-200 hover:border-slate-300 rounded-md px-2 py-1 transition-colors">
                        Modifier
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- SUBTAB: Peda Théorique                                            --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div id="subtab-peda-theorique" class="peda-subtab-panel" style="display:none">
@php
use App\Models\TraineePedaTheoStatus;

$theoCfg = [
    'nt'       => ['label' => 'NT',       'color' => '#cbd5e1', 'pill' => 'bg-slate-50 text-slate-500 border-slate-200'],
    'en_cours' => ['label' => 'En cours', 'color' => '#fbbf24', 'pill' => 'bg-amber-50 text-amber-700 border-amber-200'],
    'valide'   => ['label' => 'Validé',   'color' => '#10b981', 'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
];
$theoStatuses = TraineePedaTheoStatus::STATUSES;
$coverage = $pedaTheoData['coverage'];
$coveragePct = $coverage['total'] > 0 ? round($coverage['covered'] / $coverage['total'] * 100) : 0;
$theoExamDate = $pedaTheoData['exam_date'];
$daysToTheoExam = Carbon::today()->diffInDays(Carbon::parse($theoExamDate), false);
@endphp

    {{-- ── Global coverage indicator ───────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 p-4 mb-6">
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

    {{-- ── Timeline ─────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Calendrier de progression</h3>
            <span class="text-[10px] {{ $daysToTheoExam <= 14 ? 'text-red-500 font-semibold' : 'text-slate-400' }}">
                Examen pédagogie théorique :
                {{ Carbon::parse($theoExamDate)->locale('fr')->isoFormat('D MMM YYYY') }}
                @if($daysToTheoExam >= 0)
                    · {{ $daysToTheoExam }}j
                @else
                    · passé
                @endif
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-2.5 px-4 text-slate-400 font-medium w-28">Niveau</th>
                        <th class="text-center py-2.5 px-4 text-slate-400 font-medium">Statut</th>
                        <th class="text-center py-2.5 px-4 text-slate-400 font-medium">Sujets</th>
                        <th class="text-center py-2.5 px-4 text-slate-400 font-medium">Échéance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedaTheoData['levels'] as $lk => $lv)
                    @php
                        $tCfg  = $theoCfg[$lv['status']];
                        $tIdx  = TraineePedaTheoStatus::statusIndex($lv['status']);
                        $totalTopics = count($lv['topics']);
                        $ratedA   = count(array_filter($lv['topics'], fn($t) => $t['rating'] === '3'));
                        $ratedAny = count(array_filter($lv['topics'], fn($t) => $t['rating'] !== null));
                        $m = $lv['timeline'];
                    @endphp
                    <tr class="border-b border-slate-50 last:border-0">
                        {{-- Level --}}
                        <td class="py-2.5 px-4">
                            <div class="font-semibold text-slate-700">{{ $lv['label'] }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $totalTopics }} sujet{{ $totalTopics !== 1 ? 's' : '' }}</div>
                        </td>
                        {{-- Status pill + stepper --}}
                        <td class="py-2 px-4">
                            <div class="flex flex-col items-center gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $tCfg['pill'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:{{ $tCfg['color'] }}"></span>
                                    {{ $tCfg['label'] }}
                                </span>
                                {{-- 3-step progress dots --}}
                                <div class="flex items-center gap-0">
                                    @foreach($theoStatuses as $i => $st)
                                    @php $filled = $i <= $tIdx; @endphp
                                    @if($i > 0)
                                        <div class="h-px w-5" style="background:{{ $filled ? $theoCfg[$st]['color'] : '#e2e8f0' }}"></div>
                                    @endif
                                    <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" title="{{ $theoCfg[$st]['label'] }}"
                                         style="{{ $filled ? 'background-color:'.$theoCfg[$st]['color'] : 'background-color:#fff;border:2px solid #e2e8f0' }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        {{-- Topics list --}}
                        <td class="py-2 px-4">
                            @php
                                $workedTopics = array_filter($lv['topics'], fn($t) => $t['rating'] !== null || $t['date'] !== null);
                            @endphp
                            <div class="space-y-1">
                                @forelse($workedTopics as $slug => $topic)
                                @php
                                    $r = $topic['rating'];
                                    $rColor = in_array($r, ['3','A']) ? 'text-emerald-600' : (in_array($r, ['2','ECA']) ? 'text-amber-600' : 'text-slate-400');
                                    $rBg    = in_array($r, ['3','A']) ? '#d1fae5' : (in_array($r, ['2','ECA']) ? '#fef3c7' : '#f1f5f9');
                                @endphp
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $rColor }}"
                                          style="background:{{ $rBg }};min-width:2rem;text-align:center">
                                        {{ $r ?? '1' }}
                                    </span>
                                    <span class="text-[11px] text-slate-600 leading-snug">{{ $topic['label'] }}</span>
                                </div>
                                @empty
                                <span class="text-[11px] text-slate-300 italic">Aucune séance</span>
                                @endforelse
                                @if(count($lv['autre']) > 0)
                                <div class="mt-1 pt-1 border-t border-slate-100">
                                    @foreach($lv['autre'] as $slug => $autre)
                                    <div class="flex items-center gap-1.5">
                                        @php $ar = $autre['rating']; @endphp
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded text-slate-400"
                                              style="background:#f1f5f9;min-width:2rem;text-align:center">
                                            {{ $ar ?? '1' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 italic leading-snug">{{ $autre['label'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </td>
                        {{-- Timeline --}}
                        <td class="py-2 px-4 text-center">
                            @if(!$m)
                                <span class="text-slate-200 text-xs">—</span>
                            @elseif($m['achieved'])
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    <span class="text-[10px] text-emerald-600 font-medium">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
                                </div>
                            @elseif($m['at_risk'])
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center animate-pulse">
                                        <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                    </span>
                                    <span class="text-[10px] text-red-600 font-semibold">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
                                    <span class="text-[9px] text-red-400">
                                        {{ $m['days_left'] >= 0 ? $m['days_left'].'j' : 'dépassé' }}
                                    </span>
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center gap-0.5">
                                    <span class="text-[11px] text-slate-500">
                                        {{ Carbon::parse($m['due'])->locale('fr')->isoFormat('D MMM') }}
                                    </span>
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

    <p class="text-[10px] text-slate-400 px-1">
        ✓ Niveau validé &nbsp;·&nbsp;
        <span class="text-red-400">⚠</span> À risque (échéance dans ≤ 3 jours ou dépassée) &nbsp;·&nbsp;
        Sujets en italique : hors programme DEJEPS (non comptabilisés)
    </p>
</div>

{{-- ── Manual override modal ───────────────────────────────────────── --}}
<div id="peda-override-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
     style="display:none !important">
    <div class="bg-white rounded-2xl shadow-xl mx-4 p-6" style="width:100%;max-width:24rem">
        <h3 class="text-base font-bold text-slate-800 mb-1">Modifier le statut</h3>
        <p id="peda-override-level-label" class="text-xs text-slate-400 mb-4"></p>
        <form id="peda-override-form" method="POST" action="{{ route('instructor.peda.status.save', $trainee) }}">
            @csrf
            <input type="hidden" id="peda-override-level"  name="level"  value="">
            <input type="hidden" id="peda-override-status" name="status" value="">
            <div class="space-y-2 mb-5">
                @foreach(TraineePedaStatus::STATUSES as $st)
                <label class="flex items-center gap-3 p-2.5 rounded-lg border-2 cursor-pointer transition-colors
                              border-slate-100 hover:border-slate-200 peda-override-option" data-status="{{ $st }}">
                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color:{{ $statusCfg[$st]['color'] }}"></span>
                    <span class="text-sm font-medium text-slate-700">{{ $statusCfg[$st]['label'] }}</span>
                    <span class="ml-auto w-4 h-4 rounded-full border-2 border-slate-300 peda-override-radio flex-shrink-0"></span>
                </label>
                @endforeach
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closePedaOverride()"
                        class="flex-1 py-2 text-sm text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 py-2 text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-lg transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
