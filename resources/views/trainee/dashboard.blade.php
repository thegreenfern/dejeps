@extends('layouts.app')
@section('title', 'Mon espace · ' . $trainee->name)

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            @if($trainee->photo_path)
                <img src="{{ Storage::url($trainee->photo_path) }}"
                     class="w-12 h-12 rounded-full object-cover border border-slate-200 flex-shrink-0">
            @else
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-slate-800">Bonjour, {{ explode(' ', $trainee->name)[0] }}</h1>
                <p class="text-sm text-slate-400">Formation DEJEPS Plongée</p>
            </div>
        </div>
        <a href="{{ route('trainee.profile.edit') }}"
           class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
            </svg>
            Mon profil
        </a>
    </div>

    {{-- Tab navigation --}}
    <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6" role="tablist">
        @foreach(['uc12' => 'UC1 / UC2', 'seances' => 'Séances', 'epmsp' => 'EPMSP'] as $id => $label)
            <button type="button"
                    role="tab"
                    data-tab="{{ $id }}"
                    class="tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors
                           text-slate-500 hover:text-slate-700">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: UC1 / UC2                                                    --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-uc12" class="tab-panel space-y-4">

        {{-- Calendrier --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-5">Calendrier du projet</h2>

            @php
                $submissionDate = $settings->uc1_submission_deadline ?? \Carbon\Carbon::parse('2026-06-12');
                $juryDate       = $settings->uc1_jury_date            ?? \Carbon\Carbon::parse('2026-06-18');
                $rattrapageDate = $settings->uc2_jury_date             ?? \Carbon\Carbon::parse('2026-10-23');
                $frMonths       = ['jan.','fév.','mars','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
                $fmtDate        = fn($d) => $d->day . ' ' . $frMonths[$d->month - 1];

                // 6th element: milestone slug (null = not tracked)
                $calMilestones = [
                    ['2026-01-05', '5 jan.',                      'Diagnostic de la structure',      'slate',   false, 'diagnostic'],
                    ['2026-02-27', '27 fév.',                     'Validation de la problématique',  'violet',  true,  'validation_problematique'],
                    ['2026-03-02', '2 mars',                      'Conception et planification',     'slate',   false, 'conception_planification'],
                    ['2026-05-11', '11 mai',                      'Phase de test & analyse',         'slate',   false, 'phase_test_analyse'],
                    ['2026-05-30', '30 mai',                      'Rédaction du dossier',            'amber',   false, 'redaction_dossier'],
                    [$submissionDate->format('Y-m-d'), $fmtDate($submissionDate), 'Dépôt du dossier', 'red',   true,  'depot_dossier'],
                    ['2026-06-13', '13 juin',                     'Oral blanc (préparation)',        'slate',   false, 'oral_blanc'],
                    [$juryDate->format('Y-m-d'),       $fmtDate($juryDate),       'Oral final — soutenance', 'emerald', true, null],
                    ['2026-10-09', '9 oct.',                      'Remédiation',                     'slate',   false, null],
                    [$rattrapageDate->format('Y-m-d'), $fmtDate($rattrapageDate), 'Rattrapage',      'amber',   true,  null],
                ];
                $calColors = [
                    'slate'   => ['dot' => 'bg-slate-300',   'text' => 'text-slate-500'],
                    'violet'  => ['dot' => 'bg-violet-400',  'text' => 'text-violet-600'],
                    'amber'   => ['dot' => 'bg-amber-400',   'text' => 'text-amber-600'],
                    'red'     => ['dot' => 'bg-red-400',     'text' => 'text-red-600'],
                    'emerald' => ['dot' => 'bg-emerald-400', 'text' => 'text-emerald-600'],
                ];
                $calMsBadge = [
                    'done'        => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                    'in_progress' => 'bg-amber-100 text-amber-700 border border-amber-300',
                    'not_done'    => 'bg-slate-100 text-slate-500 border border-slate-200',
                ];
                $calToday = now()->startOfDay();
            @endphp

            <div class="relative pl-6 border-l-2 border-slate-100 space-y-4">
                @foreach($calMilestones as [$dateStr, $displayDate, $label, $color, $isKey, $msSlug])
                    @php
                        $date      = \Carbon\Carbon::parse($dateStr);
                        $isPast    = $date->lt($calToday);
                        $c         = $calColors[$color];
                        $msStatus  = $msSlug ? ($milestoneProgress[$msSlug] ?? 'not_done') : null;
                        $isOverdue = $isPast && $msSlug && $msStatus !== 'done';
                    @endphp
                    <div class="flex items-start gap-3 relative {{ $isOverdue ? 'rounded-lg bg-amber-50 -mx-2 px-2 py-1' : '' }}">
                        <div class="absolute -left-[25px] mt-1 w-4 h-4 rounded-full border-2 border-white
                                    {{ $isKey ? $c['dot'] : 'bg-slate-200' }}
                                    {{ $isPast && !$isOverdue && !$msSlug ? 'opacity-50' : '' }}"></div>
                        <div class="flex-1 flex items-center justify-between gap-2 {{ $isPast && !$isOverdue && !$msSlug ? 'opacity-50' : '' }}">
                            <div class="flex items-baseline gap-2 min-w-0">
                                <span class="text-xs font-semibold {{ $isKey ? $c['text'] : 'text-slate-400' }} w-16 flex-shrink-0">
                                    {{ $displayDate }}
                                </span>
                                <span class="text-sm {{ $isKey ? 'font-semibold text-slate-800' : 'text-slate-500' }} truncate">
                                    {{ $label }}
                                </span>
                                @if($isOverdue)
                                    <span class="text-[10px] font-semibold text-amber-600 flex-shrink-0">⚠ En retard</span>
                                @endif
                            </div>
                            @if($msSlug)
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded flex-shrink-0 {{ $calMsBadge[$msStatus] }}">
                                    {{ \App\Models\TraineeUcProgress::milestoneStatusLabel($msStatus) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Review request button / state --}}
            <div class="mt-6 pt-5 border-t border-slate-100">
                @if($feedbacks->isNotEmpty())
                    <div class="mb-5">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-3">Historique des retours</h3>
                        <div class="space-y-3">
                            @foreach($feedbacks as $fb)
                                <div class="rounded-lg bg-sky-50 border border-sky-200 p-3">
                                    <p class="text-[10px] font-semibold text-sky-600 uppercase tracking-wide mb-1">
                                        {{ $fb->created_at->format('d/m/Y') }}
                                        <span class="font-normal normal-case text-sky-500">· {{ $fb->created_at->diffForHumans() }}</span>
                                    </p>
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $fb->data['feedback_text'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(session('success') && request()->is('stagiaire/demander-retour'))
                    <p class="text-xs text-emerald-600 mb-3">{{ session('success') }}</p>
                @endif

                @if($pendingReviewSent)
                    <div class="flex items-center gap-2 text-sm text-amber-700">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Demande de retour envoyée — en attente de réponse du formateur.
                    </div>
                @else
                    <form method="POST" action="{{ route('trainee.review.request') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-violet-300 bg-violet-50 text-violet-700 text-sm font-semibold hover:bg-violet-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Demander un retour au formateur
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Dossier status + URL --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Mon dossier</h2>

            @php
                $statusLabels = [
                    'not_started' => 'Non commencé',
                    'in_progress' => 'En cours',
                    'submitted'   => 'Dossier déposé',
                    'evaluated'   => 'Évalué',
                ];
                $statusColors = [
                    'not_started' => 'bg-slate-100 text-slate-500 border-slate-200',
                    'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'submitted'   => 'bg-sky-50 text-sky-700 border-sky-200',
                    'evaluated'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                ];
                $currentStatus = $uc?->status ?? 'not_started';
                $ratingLabels  = ['TI' => 'Très insatisfaisant', 'I' => 'Insatisfaisant', 'S' => 'Satisfaisant', 'M' => 'Très satisfaisant'];
                $ratingColors  = ['TI' => 'text-red-600', 'I' => 'text-amber-600', 'S' => 'text-emerald-600', 'M' => 'text-emerald-700'];
            @endphp

            <div class="flex items-center gap-3 mb-5">
                <span class="inline-flex items-center text-xs font-semibold border rounded-full px-3 py-1 {{ $statusColors[$currentStatus] }}">
                    {{ $statusLabels[$currentStatus] }}
                </span>
            </div>

            @if(session('success'))
                <p class="text-xs text-emerald-600 mb-3">{{ session('success') }}</p>
            @endif
            <form method="POST" action="{{ route('trainee.uc12.dossier.save') }}">
                @csrf
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Lien vers mon dossier <span class="font-normal text-slate-400">(Google Doc ou autre)</span>
                </label>
                <div class="flex gap-2">
                    <input type="url" name="dossier_url"
                           value="{{ $uc?->dossier_url }}"
                           placeholder="https://docs.google.com/…"
                           class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @if($uc?->dossier_url)
                        <a href="{{ $uc->dossier_url }}" target="_blank"
                           class="flex-shrink-0 px-3 py-2 rounded-lg border border-slate-300 text-slate-500 hover:border-sky-400 hover:text-sky-600 transition-colors"
                           title="Ouvrir">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    @endif
                    <button type="submit"
                            class="flex-shrink-0 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        Enregistrer
                    </button>
                </div>
                @error('dossier_url')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- UC2 status --}}
        @php
            $uc2Status = $uc2?->status ?? 'not_started';
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">UC2 · Gestion de structure</h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center text-xs font-semibold border rounded-full px-3 py-1 {{ $statusColors[$uc2Status] }}">
                    {{ $statusLabels[$uc2Status] }}
                </span>
                @if($uc2?->jury_date ?? $settings->uc2_jury_date)
                    <span class="ml-auto text-xs text-slate-400">
                        Jury · {{ ($uc2?->jury_date ?? $settings->uc2_jury_date)?->format('d/m/Y') }}
                    </span>
                @endif
            </div>
            @if($uc2?->instructor_notes)
                <p class="text-xs text-slate-500 italic mt-3 leading-snug">{{ $uc2->instructor_notes }}</p>
            @endif
        </div>

{{-- mis à disposition par le formateur --}}
        @if($uc12Docs->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Documents</h2>
            <ul class="space-y-2">
                @foreach($uc12Docs as $doc)
                <li>
                    <a href="{{ route('uc12.document.download', $doc) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 hover:border-sky-300 hover:bg-sky-50 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <span class="flex-1 text-sm font-medium text-slate-700 group-hover:text-sky-700 truncate">{{ $doc->original_name }}</span>
                        <span class="text-xs text-slate-400 flex-shrink-0">{{ $doc->formattedSize() }}</span>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-sky-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: SÉANCES                                                      --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-seances" class="tab-panel">
        @php
            $trainee->loadMissing('uc3');
            $uc3self       = $trainee->uc3;
            $instrProgress = $uc3self?->topic_progress ?? [];
            $selfProgress  = $uc3self?->trainee_topic_progress ?? [];
            $allTopics     = \App\Models\TraineeUc3::topics();
            $pratiqueComps = \App\Models\TraineeUc3::pratiqueCompetencies();
            $compPoints    = \App\Models\TraineeUc3::competencyPoints();
            $notationStyles = [
                '3'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
                '2'   => ['badge' => 'bg-amber-100 text-amber-700'],
                '1'   => ['badge' => 'bg-slate-100 text-slate-400'],
                'A'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
                'ECA' => ['badge' => 'bg-amber-100 text-amber-700'],
                'NT'  => ['badge' => 'bg-slate-100 text-slate-400'],
            ];
            $selfNotationStyles = [
                '3'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
                '2'   => ['badge' => 'bg-amber-100 text-amber-700'],
                '1'   => ['badge' => 'bg-slate-100 text-slate-400'],
                'A'   => ['badge' => 'bg-emerald-100 text-emerald-700'],
                'ECA' => ['badge' => 'bg-amber-100 text-amber-700'],
                'NT'  => ['badge' => 'bg-slate-100 text-slate-400'],
            ];

            $sortByDate = fn($a, $b) => match(true) {
                (bool)$a['session_date'] && (bool)$b['session_date'] => strcmp($b['session_date'], $a['session_date']),
                (bool)$a['session_date'] => -1,
                (bool)$b['session_date'] => 1,
                default => 0,
            };
            $hasData = fn($p) => array_key_exists('global_rating', $p) || array_key_exists('session_date', $p);

            // Unified théorique list (union of both sources)
            $theoSlugs = array_unique(array_merge(
                array_filter(array_keys($instrProgress), fn($s) => !str_starts_with($s, 'pratique_') && $hasData($instrProgress[$s])),
                array_filter(array_keys($selfProgress),  fn($s) => !str_starts_with($s, 'pratique_') && $hasData($selfProgress[$s]))
            ));
            $theoSessions = [];
            foreach ($theoSlugs as $slug) {
                $iP = $instrProgress[$slug] ?? null;
                $sP = $selfProgress[$slug] ?? null;
                $ref = $iP ?? $sP;
                $topicInfo  = collect($allTopics)->firstWhere('slug', $slug);
                $rawLabel   = $topicInfo ? $topicInfo['label'] : ($ref['session_label'] ?? 'Thème libre');
                $cleanLabel = preg_replace('/^(?:N\d|Niveau\s*\d+)\s*[·\-—]\s*/iu', '', $rawLabel);
                $level      = $topicInfo ? $topicInfo['level'] : ($ref['session_level'] ?? null);
                $theoSessions[] = [
                    'slug'         => $slug,
                    'label'        => $cleanLabel,
                    'level'        => $level,
                    'session_date' => ($iP['session_date'] ?? null) ?: ($sP['session_date'] ?? null),
                    'instr_rating' => $iP['global_rating'] ?? null,
                    'self_rating'  => $sP['global_rating'] ?? null,
                ];
            }
            usort($theoSessions, $sortByDate);

            // Unified pratique list (union of both sources)
            $pratSlugs = array_unique(array_merge(
                array_filter(array_keys($instrProgress), fn($s) => str_starts_with($s, 'pratique_') && $hasData($instrProgress[$s])),
                array_filter(array_keys($selfProgress),  fn($s) => str_starts_with($s, 'pratique_') && $hasData($selfProgress[$s]))
            ));
            $pratSessions = [];
            foreach ($pratSlugs as $slug) {
                $iP = $instrProgress[$slug] ?? null;
                $sP = $selfProgress[$slug] ?? null;
                preg_match('/^pratique_([a-z0-9]+)_s(\d+)$/i', $slug, $pm);
                $plvKey  = strtoupper($pm[1] ?? '');
                $pLvInfo = $pratiqueComps[$plvKey] ?? null;
                $pratSessions[] = [
                    'slug'         => $slug,
                    'level'        => $plvKey,
                    'level_label'  => $pLvInfo['label'] ?? $plvKey,
                    'session_num'  => $pm[2] ?? '?',
                    'session_date' => ($iP['session_date'] ?? null) ?: ($sP['session_date'] ?? null),
                    'instr_rating' => $iP['global_rating'] ?? null,
                    'self_rating'  => $sP['global_rating'] ?? null,
                ];
            }
            usort($pratSessions, $sortByDate);
        @endphp

        {{-- Subtab bar --}}
        <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6">
            <button type="button" class="seances-tab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-tab="seances-theo">Théorique</button>
            <button type="button" class="seances-tab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-tab="seances-prat">Pratique</button>
        </div>

        {{-- ── Sous-onglet : Théorique ──────────────────────────────────── --}}
        <div id="subtab-seances-theo" class="seances-tab-panel space-y-5">

            {{-- Unified théorique table --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances théoriques</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($theoSessions) }} séance{{ count($theoSessions) !== 1 ? 's' : '' }}</p>
                    </div>
                    <a href="{{ route('trainee.seances.add') }}?type=theorique"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Nouvelle séance
                    </a>
                </div>

                @if(count($theoSessions) === 0)
                    <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
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
                                @foreach($theoSessions as $s)
                                    <tr class="theo-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $s['slug'] }}">
                                        <td class="px-4 py-3.5">
                                            @if($s['session_date'])
                                                <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($s['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($s['session_date'])->year }}</span>
                                            @else
                                                <span class="text-sm text-slate-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <button type="button" class="theo-row-btn text-left w-full" data-slug="{{ $s['slug'] }}">
                                                <span class="text-sm text-slate-700">{{ $s['label'] }}@if($s['level']) <span class="text-slate-400">· {{ $s['level'] }}</span>@endif</span>
                                            </button>
                                        </td>
                                        <td class="px-3 py-3.5 text-center">
                                            @php $ir = $s['instr_rating']; @endphp
                                            @if($ir && $ir !== null)
                                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ir]['badge'] }}">{{ $ir }}</span>
                                            @else
                                                <span class="text-xs text-slate-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5 text-center">
                                            @php $sr = $s['self_rating']; @endphp
                                            @if($sr && $sr !== null)
                                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sr]['badge'] }}">{{ $sr }}</span>
                                            @else
                                                <span class="text-xs text-slate-300">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Detail placeholder --}}
            <div id="theo-placeholder" class="bg-white rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
            </div>

            @foreach($theoSessions as $s)
                @php
                    $iP       = $instrProgress[$s['slug']] ?? null;
                    $sP       = $selfProgress[$s['slug']] ?? null;
                    $theoKeys = array_filter(\App\Models\TraineeUc3::allPointKeys(), fn($k) => $k !== 'r_securite');
                    $iA       = $iP ? collect($theoKeys)->filter(fn($k) => ($iP[$k] ?? null) === 'A')->count() : 0;
                    $sA       = $sP ? collect($theoKeys)->filter(fn($k) => ($sP[$k] ?? null) === 'A')->count() : 0;
                    $maxA     = 8;
                @endphp
                <div data-theo-panel="{{ $s['slug'] }}" style="display:none" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">

                    {{-- Panel header --}}
                    <div>
                        <h3 class="text-base font-bold text-slate-800">{{ $s['label'] }}@if($s['level']) <span class="text-slate-400 font-normal text-sm">· {{ $s['level'] }}</span>@endif</h3>
                        @if($s['session_date'])
                            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($s['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                        @endif
                    </div>

                    {{-- Evaluations --}}
                    @if($iP && $sP)
                    {{-- Combined table when both exist --}}
                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50/70">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Formateur</span>
                                @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                                @endif
                                <span class="text-[9px] text-slate-300 font-bold">VS</span>
                                <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon éval.</span>
                                @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} · {{ $sA }}/{{ $maxA }} A</span>
                                <a href="{{ route('trainee.seances.edit', $s['slug']) }}"
                                   class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-sky-500 hover:bg-sky-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                        @if(($iP['global_comment'] ?? null) || ($sP['global_comment'] ?? null))
                        <div class="grid grid-cols-2 divide-x divide-slate-100 border-b border-slate-100">
                            <p class="px-4 py-2.5 text-sm text-slate-500 italic leading-snug">{{ $iP['global_comment'] ?? '' }}</p>
                            <p class="px-4 py-2.5 text-sm text-slate-500 italic leading-snug">{{ $sP['global_comment'] ?? '' }}</p>
                        </div>
                        @endif
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50/40">
                                    <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2">Critère</th>
                                    <th class="text-center text-[10px] font-semibold text-violet-500 uppercase tracking-wide px-3 py-2 w-24">Formateur</th>
                                    <th class="text-center text-[10px] font-semibold text-sky-500 uppercase tracking-wide px-3 py-2 w-24">Moi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compPoints as $gpKey => $gpGroup)
                                <tr class="border-t border-slate-100 bg-slate-50/50">
                                    <td colspan="3" class="px-4 py-1.5"><span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $gpGroup['label'] }}</span></td>
                                </tr>
                                @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                    @continue($gpPoint['pratique_only'] ?? false)
                                    @php
                                        $ni = in_array($iP[$gpItemKey] ?? null, ['3','2']) ? $iP[$gpItemKey] : '1';
                                        $ns = in_array($sP[$gpItemKey] ?? null, ['3','2']) ? $sP[$gpItemKey] : '1';
                                    @endphp
                                    <tr class="border-t border-slate-50">
                                        <td class="py-2 px-4 text-xs text-slate-600 leading-snug">{{ $gpPoint['label'] }}</td>
                                        <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ni]['badge'] }}">{{ $ni }}</span></td>
                                        <td class="py-2 px-3 text-center border-l border-slate-100"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ns]['badge'] }}">{{ $ns }}</span></td>
                                    </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        @if(($iP['session_note'] ?? null) || ($sP['session_note'] ?? null))
                        <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-200">
                            <div class="px-4 py-3">
                                @if($iP['session_note'] ?? null)
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Note formateur</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                                @endif
                            </div>
                            <div class="px-4 py-3">
                                @if($sP['session_note'] ?? null)
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Mes notes</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ $sP['session_note'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    {{-- Single eval --}}
                    @if($iP)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Évaluation formateur</span>
                            <div class="flex items-center gap-2">
                                @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                    <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                                @endif
                                <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} A</span>
                            </div>
                        </div>
                        @if($iP['global_comment'] ?? null)
                            <p class="text-sm text-slate-500 italic mb-4 leading-snug">{{ $iP['global_comment'] }}</p>
                        @endif
                        @foreach($compPoints as $gpKey => $gpGroup)
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $gpGroup['label'] }}</p>
                            <table class="w-full">
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                        @continue($gpPoint['pratique_only'] ?? false)
                                        @php $n = in_array($iP[$gpItemKey] ?? null, ['3','2']) ? $iP[$gpItemKey] : '1'; @endphp
                                        <tr>
                                            <td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $gpPoint['label'] }}</td>
                                            <td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$n]['badge'] }}">{{ $n }}</span></td>
                                            <td class="py-2 text-xs text-slate-400">{{ $iP[$gpItemKey . '_note'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                        @if($iP['session_note'] ?? null)
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Note</p>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                        @endif
                    </div>
                    @endif
                    @if($sP)
                    <div class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon auto-évaluation</span>
                            <div class="flex items-center gap-2">
                                @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                    <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                                @endif
                                <span class="text-xs tabular-nums text-slate-400">{{ $sA }}/{{ $maxA }} A</span>
                                <a href="{{ route('trainee.seances.edit', $s['slug']) }}"
                                   class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-sky-500 hover:bg-sky-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                        @if($sP['global_comment'] ?? null)
                            <p class="text-sm text-slate-500 italic mb-4 leading-snug">{{ $sP['global_comment'] }}</p>
                        @endif
                        @foreach($compPoints as $gpKey => $gpGroup)
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $gpGroup['label'] }}</p>
                            <table class="w-full">
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                        @continue($gpPoint['pratique_only'] ?? false)
                                        @php $n = in_array($sP[$gpItemKey] ?? null, ['3','2']) ? $sP[$gpItemKey] : '1'; @endphp
                                        <tr>
                                            <td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $gpPoint['label'] }}</td>
                                            <td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$n]['badge'] }}">{{ $n }}</span></td>
                                            <td class="py-2 text-xs text-slate-400">{{ $sP[$gpItemKey . '_note'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                        @if($sP['session_note'] ?? null)
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Notes personnelles</p>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $sP['session_note'] }}</p>
                        @endif
                    </div>
                    @else
                    <div class="rounded-xl border border-dashed border-slate-200 p-4 flex items-center justify-between">
                        <p class="text-sm text-slate-400">Vous n'avez pas encore auto-évalué cette séance.</p>
                        <a href="{{ route('trainee.seances.add') }}?type=theorique"
                           class="text-xs font-semibold text-violet-600 hover:text-violet-700 transition-colors whitespace-nowrap ml-4">
                            + Ajouter
                        </a>
                    </div>
                    @endif
                    @endif

                </div>
            @endforeach

        </div>{{-- /subtab-seances-theo --}}

        {{-- ── Sous-onglet : Pratique ───────────────────────────────────── --}}
        <div id="subtab-seances-prat" class="seances-tab-panel space-y-5" style="display:none">

            {{-- Unified pratique table --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances pratiques</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($pratSessions) }} séance{{ count($pratSessions) !== 1 ? 's' : '' }}</p>
                    </div>
                    <a href="{{ route('trainee.seances.add') }}?type=pratique"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-violet-600 hover:bg-violet-700 text-white rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Nouvelle séance
                    </a>
                </div>

                @if(count($pratSessions) === 0)
                    <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
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
                                @foreach($pratSessions as $ps)
                                    <tr class="prat-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $ps['slug'] }}">
                                        <td class="px-4 py-3.5">
                                            @if($ps['session_date'])
                                                <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($ps['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($ps['session_date'])->year }}</span>
                                            @else
                                                <span class="text-sm text-slate-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <button type="button" class="prat-row-btn text-left w-full" data-slug="{{ $ps['slug'] }}">
                                                <span class="text-sm text-slate-700">{{ $ps['level'] }} · Séance {{ $ps['session_num'] }}</span>
                                                <span class="text-xs text-slate-400 block leading-tight">{{ $ps['level_label'] }}</span>
                                            </button>
                                        </td>
                                        <td class="px-3 py-3.5 text-center">
                                            @php $ir = $ps['instr_rating']; @endphp
                                            @if($ir && $ir !== null)
                                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ir]['badge'] }}">{{ $ir }}</span>
                                            @else
                                                <span class="text-xs text-slate-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5 text-center">
                                            @php $sr = $ps['self_rating']; @endphp
                                            @if($sr && $sr !== null)
                                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sr]['badge'] }}">{{ $sr }}</span>
                                            @else
                                                <span class="text-xs text-slate-300">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Detail placeholder --}}
            <div id="prat-placeholder" class="bg-white rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
            </div>

            @foreach($pratSessions as $ps)
                @php
                    $iP      = $instrProgress[$ps['slug']] ?? null;
                    $sP      = $selfProgress[$ps['slug']] ?? null;
                    $iA      = $iP ? collect(\App\Models\TraineeUc3::allPointKeys())->filter(fn($k) => ($iP[$k] ?? null) === 'A')->count() : 0;
                    $sA      = $sP ? collect(\App\Models\TraineeUc3::allPointKeys())->filter(fn($k) => ($sP[$k] ?? null) === 'A')->count() : 0;
                    $maxA    = 9;
                    $pLvComp = $pratiqueComps[$ps['level']] ?? null;
                @endphp
                <div data-prat-panel="{{ $ps['slug'] }}" style="display:none" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">

                    {{-- Panel header --}}
                    <div>
                        <h3 class="text-base font-bold text-slate-800">{{ $ps['level'] }} · Séance {{ $ps['session_num'] }} <span class="text-slate-400 font-normal text-sm">· {{ $ps['level_label'] }}</span></h3>
                        @if($ps['session_date'])
                            <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($ps['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                        @endif
                    </div>

                    {{-- Combined eval table when both sides exist, otherwise stacked singles --}}
                    @if($iP && $sP)
                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Formateur</span>
                                @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                                @endif
                                <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} A</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs tabular-nums text-slate-400">{{ $sA }}/{{ $maxA }} A</span>
                                @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                                @endif
                                <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon éval.</span>
                                <a href="{{ route('trainee.seances.edit', $ps['slug']) }}"
                                   class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-sky-500 hover:bg-sky-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </div>
                        {{-- Global comments --}}
                        @if(($iP['global_comment'] ?? null) || ($sP['global_comment'] ?? null))
                        <div class="grid grid-cols-2 gap-4 px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-sm text-slate-500 italic leading-snug">{{ $iP['global_comment'] ?? '' }}</p>
                            <p class="text-sm text-slate-500 italic leading-snug">{{ $sP['global_comment'] ?? '' }}</p>
                        </div>
                        @endif
                        {{-- Criteria table --}}
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2">Critère</th>
                                    <th class="text-center text-[10px] font-semibold text-violet-400 uppercase tracking-wide px-3 py-2 w-16">Formateur</th>
                                    <th class="text-center text-[10px] font-semibold text-sky-400 uppercase tracking-wide px-3 py-2 w-16">Moi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($compPoints as $gpKey => $gpGroup)
                                    <tr class="bg-slate-50/60">
                                        <td colspan="3" class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $gpGroup['label'] }}</td>
                                    </tr>
                                    @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                        @php
                                            $ni = in_array($iP[$gpItemKey] ?? null, ['3','2']) ? $iP[$gpItemKey] : '1';
                                            $ns = in_array($sP[$gpItemKey] ?? null, ['3','2']) ? $sP[$gpItemKey] : '1';
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-slate-600 leading-snug">{{ $gpPoint['label'] }}</td>
                                            <td class="px-3 py-2 text-center"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ni]['badge'] }}">{{ $ni }}</span></td>
                                            <td class="px-3 py-2 text-center"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$ns]['badge'] }}">{{ $ns }}</span></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        {{-- Exercises comparison --}}
                        @if($pLvComp && (!empty($iP['exercises_done']) || !empty($sP['exercises_done'])))
                        <div class="border-t border-slate-100 px-4 py-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Exercices abordés</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    @foreach($pLvComp['keys'] as $exKey => $exLabel)
                                        @php $done = in_array($exKey, $iP['exercises_done'] ?? []); @endphp
                                        <div class="flex items-center gap-2">
                                            @if($done)
                                                <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span class="text-xs text-slate-700">{{ $exLabel }}</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                                <span class="text-xs text-slate-300">{{ $exLabel }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="space-y-1">
                                    @foreach($pLvComp['keys'] as $exKey => $exLabel)
                                        @php $done = in_array($exKey, $sP['exercises_done'] ?? []); @endphp
                                        <div class="flex items-center gap-2">
                                            @if($done)
                                                <svg class="w-3.5 h-3.5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span class="text-xs text-slate-700">{{ $exLabel }}</span>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                                <span class="text-xs text-slate-300">{{ $exLabel }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- Notes --}}
                        @if(($iP['session_note'] ?? null) || ($sP['session_note'] ?? null))
                        <div class="border-t border-slate-100 px-4 py-3 grid grid-cols-2 gap-4">
                            <div>
                                @if($iP['session_note'] ?? null)
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Note formateur</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                                @endif
                            </div>
                            <div>
                                @if($sP['session_note'] ?? null)
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mes notes</p>
                                    <p class="text-sm text-slate-500 leading-relaxed">{{ $sP['session_note'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                        {{-- Only instructor eval --}}
                        @if($iP)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Évaluation formateur</span>
                                <div class="flex items-center gap-2">
                                    @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                        <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                                    @endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} A</span>
                                </div>
                            </div>
                            @if($iP['global_comment'] ?? null)
                                <p class="text-sm text-slate-500 italic mb-4 leading-snug">{{ $iP['global_comment'] }}</p>
                            @endif
                            @foreach($compPoints as $gpKey => $gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $gpGroup['label'] }}</p>
                                <table class="w-full">
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                            @php $n = in_array($iP[$gpItemKey] ?? null, ['3','2']) ? $iP[$gpItemKey] : '1'; @endphp
                                            <tr>
                                                <td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $gpPoint['label'] }}</td>
                                                <td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$n]['badge'] }}">{{ $n }}</span></td>
                                                <td class="py-2 text-xs text-slate-400">{{ $iP[$gpItemKey . '_note'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                            @if($pLvComp && !empty($iP['exercises_done']))
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Exercices abordés</p>
                                    <div class="space-y-1.5">
                                        @foreach($pLvComp['keys'] as $exKey => $exLabel)
                                            @php $done = in_array($exKey, $iP['exercises_done'] ?? []); @endphp
                                            <div class="flex items-center gap-2">
                                                @if($done)
                                                    <svg class="w-3.5 h-3.5 text-violet-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    <span class="text-xs text-slate-700">{{ $exLabel }}</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                                    <span class="text-xs text-slate-300">{{ $exLabel }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($iP['session_note'] ?? null)
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Note</p>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                            @endif
                        </div>
                        @endif
                        {{-- Only self-eval --}}
                        @if($sP)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Mon auto-évaluation</span>
                                <div class="flex items-center gap-2">
                                    @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                        <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                                    @endif
                                    <span class="text-xs tabular-nums text-slate-400">{{ $sA }}/{{ $maxA }} A</span>
                                    <a href="{{ route('trainee.seances.edit', $ps['slug']) }}"
                                       class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-sky-500 hover:bg-sky-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                            </div>
                            @if($sP['global_comment'] ?? null)
                                <p class="text-sm text-slate-500 italic mb-4 leading-snug">{{ $sP['global_comment'] }}</p>
                            @endif
                            @foreach($compPoints as $gpKey => $gpGroup)
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 mt-3">{{ $gpGroup['label'] }}</p>
                                <table class="w-full">
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                            @php $n = in_array($sP[$gpItemKey] ?? null, ['3','2']) ? $sP[$gpItemKey] : '1'; @endphp
                                            <tr>
                                                <td class="py-2 pr-4 text-xs text-slate-600 leading-snug w-1/2">{{ $gpPoint['label'] }}</td>
                                                <td class="py-2 pr-4 w-12"><span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$n]['badge'] }}">{{ $n }}</span></td>
                                                <td class="py-2 text-xs text-slate-400">{{ $sP[$gpItemKey . '_note'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                            @if($pLvComp && !empty($sP['exercises_done']))
                                <div class="mt-4 pt-3 border-t border-slate-100">
                                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Exercices abordés</p>
                                    <div class="space-y-1.5">
                                        @foreach($pLvComp['keys'] as $exKey => $exLabel)
                                            @php $done = in_array($exKey, $sP['exercises_done'] ?? []); @endphp
                                            <div class="flex items-center gap-2">
                                                @if($done)
                                                    <svg class="w-3.5 h-3.5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    <span class="text-xs text-slate-700">{{ $exLabel }}</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-slate-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                                                    <span class="text-xs text-slate-300">{{ $exLabel }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($sP['session_note'] ?? null)
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mt-4 mb-1">Notes personnelles</p>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $sP['session_note'] }}</p>
                            @endif
                        </div>
                        @else
                        <div class="rounded-xl border border-dashed border-slate-200 p-4 flex items-center justify-between">
                            <p class="text-sm text-slate-400">Vous n'avez pas encore auto-évalué cette séance.</p>
                            <a href="{{ route('trainee.seances.add') }}?type=pratique"
                               class="text-xs font-semibold text-violet-600 hover:text-violet-700 transition-colors whitespace-nowrap ml-4">
                                + Ajouter
                            </a>
                        </div>
                        @endif
                    @endif

                </div>
            @endforeach

        </div>{{-- /subtab-seances-prat --}}

    </div>{{-- /tab-seances --}}

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: EPMSP                                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-epmsp" class="tab-panel space-y-4">

        @if($settings->epmsp_date)
        <p class="text-xs text-slate-400 mb-4">Évaluations · <span class="font-medium text-slate-500">{{ $settings->epmsp_date->locale('fr')->isoFormat('D MMMM YYYY') }}</span></p>
        @endif

        @php
            $epmspTypes = [
                '25m'       => ['Sauvetage 25m',      "Intervention sur un plongeur en difficulté"],
                'pedagogie' => ['Pédagogie Pratique', "Conduite de séance d'apprentissage 0/20m"],
            ];
            $ratingLabels = ['1' => 'Insuffisant', '2' => 'Satisfaisant', '3' => 'Maîtrisé'];
            $ratingColors = [
                '1' => 'bg-red-50 text-red-600 border-red-200',
                '2' => 'bg-amber-50 text-amber-600 border-amber-200',
                '3' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            ];
        @endphp

        @foreach($epmspTypes as $type => [$title, $subtitle])
            @php
                $rec    = $epmspData->firstWhere('type', $type);
                $status = $rec?->status ?? 'not_started';
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">{{ $title }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    </div>
                    <span class="inline-flex items-center text-xs font-semibold border rounded-full px-2.5 py-1 {{ \App\Models\TraineeEpmsp::statusColor($status) }}">
                        {{ \App\Models\TraineeEpmsp::statusLabel($status) }}
                    </span>
                </div>

                @if($rec?->ratings && count(array_filter($rec->ratings)))
                    @php $competencies = \App\Models\TraineeEpmsp::competencies($type); @endphp
                    <div class="space-y-2">
                        @foreach($competencies as $key => $comp)
                            @php $rating = $rec->ratings[$key] ?? null; @endphp
                            @if($rating)
                                <div class="flex items-center gap-2">
                                    @if($comp['mandatory'])
                                        <span class="text-amber-500 text-xs font-bold flex-shrink-0">★</span>
                                    @else
                                        <span class="w-3.5 flex-shrink-0"></span>
                                    @endif
                                    <span class="text-xs text-slate-600 flex-1 leading-snug">{{ $comp['label'] }}</span>
                                    <span class="text-xs font-bold border rounded px-1.5 py-0.5 flex-shrink-0 {{ $ratingColors[$rating] ?? '' }}">{{ $rating }} · {{ $ratingLabels[$rating] ?? $rating }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400">Les résultats seront affichés après l'évaluation.</p>
                @endif
            </div>
        @endforeach

    </div>

    <div class="pb-10"></div>

</div>

<script>
// Théorique sessions click-to-expand
(function () {
    const placeholder = document.getElementById('theo-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-theo-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.theo-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-theo-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.theo-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.theo-row-btn, .theo-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();

// Pratique sessions click-to-expand
(function () {
    const placeholder = document.getElementById('prat-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-prat-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.prat-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-prat-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.prat-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.prat-row-btn, .prat-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();

// Séances subtab switching
(function () {
    const btns   = document.querySelectorAll('.seances-tab-btn');
    const panels = document.querySelectorAll('.seances-tab-panel');
    function activate(id) {
        btns.forEach(btn => {
            const on = btn.dataset.tab === id;
            btn.style.backgroundColor = on ? '#fff' : '';
            btn.style.color           = on ? '#1e293b' : '';
            btn.style.fontWeight      = on ? '600' : '';
            btn.style.boxShadow       = on ? '0 1px 2px rgba(0,0,0,.06)' : '';
        });
        panels.forEach(p => { p.style.display = p.id === 'subtab-' + id ? '' : 'none'; });
    }
    btns.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));
    activate('seances-theo');
})();

(function () {
    const tabs   = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');

    function activate(id) {
        tabs.forEach(btn => {
            const isActive = btn.dataset.tab === id;
            btn.classList.toggle('bg-white',      isActive);
            btn.classList.toggle('shadow-sm',     isActive);
            btn.classList.toggle('text-slate-800', isActive);
            btn.classList.toggle('text-slate-500', !isActive);
        });
        panels.forEach(panel => {
            panel.style.display = panel.id === 'tab-' + id ? '' : 'none';
        });
        history.replaceState(null, '', '#' + id);
    }

    tabs.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));

    const hash      = location.hash.replace('#', '');
    const validTabs = [...tabs].map(b => b.dataset.tab);
    activate(validTabs.includes(hash) ? hash : validTabs[0]);
})();
</script>
@endsection
