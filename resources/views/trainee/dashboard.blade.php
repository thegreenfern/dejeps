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
        @foreach(['uc12' => 'UC1 / UC2', 'epmsp' => 'EPMSP'] as $id => $label)
            <button type="button"
                    role="tab"
                    data-tab="{{ $id }}"
                    class="tab-btn flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors
                           text-slate-500 hover:text-slate-700">
                {{ $label }}
            </button>
        @endforeach
        <a href="{{ route('trainee.dp') }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors text-center
                  text-slate-500 hover:text-slate-700 hover:bg-white/60">
            Dir. plongée
        </a>
        <a href="{{ route('trainee.comp-annexes') }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors text-center
                  text-slate-500 hover:text-slate-700 hover:bg-white/60">
            Annexes
        </a>
        <a href="{{ route('trainee.peda') }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors text-center
                  text-slate-500 hover:text-slate-700 hover:bg-white/60">
            Pédagogie
        </a>
        <a href="{{ route('trainee.parcours') }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors text-center
                  text-slate-500 hover:text-slate-700 hover:bg-white/60">
            Parcours
        </a>
        <a href="{{ route('trainee.ressources') }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors text-center
                  text-slate-500 hover:text-slate-700 hover:bg-white/60">
            Ressources
        </a>
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
    {{-- TAB: EPMSP                                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-epmsp" class="tab-panel space-y-8">

        @php
            $epmspTypes = [
                '25m'       => ['title' => 'Sauvetage 25m',      'subtitle' => "Intervention sur un plongeur en difficulté"],
                'pedagogie' => ['title' => 'Pédagogie Pratique', 'subtitle' => "Conduite de séance d'apprentissage 0/20m"],
            ];
        @endphp

        @foreach($epmspTypes as $typeKey => $meta)
        @php
            $typeEvals = $epmspData->where('type', $typeKey)->sortByDesc('evaluated_at');
            $comps     = \App\Models\TraineeEpmsp::competencies($typeKey);
            $nbValide  = $typeEvals->where('status', 'valide')->count();
            $nbEchec   = $typeEvals->where('status', 'echec')->count();
            $nbTotal   = $typeEvals->count();
        @endphp
        <div>
            <div class="flex items-baseline gap-3 mb-4">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">{{ $meta['title'] }}</h2>
                <span class="text-xs text-slate-400">{{ $meta['subtitle'] }}</span>
            </div>

            @if($typeEvals->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 px-6 py-8 text-center text-sm text-slate-400">
                    Les résultats seront affichés après l'évaluation.
                </div>
            @else

            <div class="flex items-center gap-3 mb-3 flex-wrap">
                <span class="text-xs font-medium text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-3 py-1">
                    {{ $nbTotal }} évaluation{{ $nbTotal > 1 ? 's' : '' }}
                </span>
                @if($nbValide > 0)
                    <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1">
                        {{ $nbValide }} validée{{ $nbValide > 1 ? 's' : '' }}
                    </span>
                @endif
                @if($nbEchec > 0)
                    <span class="text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-full px-3 py-1">
                        {{ $nbEchec }} échec{{ $nbEchec > 1 ? 's' : '' }}
                    </span>
                @endif
            </div>

            <div class="space-y-3">
                @foreach($typeEvals as $rec)
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-sm font-semibold text-slate-700">
                            {{ $rec->evaluated_at?->locale('fr')->isoFormat('D MMMM YYYY') ?? '–' }}
                        </span>
                        <span class="inline-flex items-center text-xs font-semibold border rounded-full px-2.5 py-0.5 {{ \App\Models\TraineeEpmsp::statusColor($rec->status) }}">
                            {{ \App\Models\TraineeEpmsp::statusLabel($rec->status) }}
                        </span>
                        @if($rec->note_globale !== null)
                            <span class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full px-2.5 py-0.5">
                                Note : {{ number_format($rec->note_globale, 2) }} / 3
                            </span>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @foreach($comps as $key => $comp)
                        @php $val = $rec->ratings[$key] ?? null; @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex items-start gap-1 flex-1 min-w-0">
                                <span class="text-amber-500 text-xs font-bold mt-0.5 flex-shrink-0">★</span>
                                <span class="text-xs text-slate-600 leading-snug">{{ $comp['label'] }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                @if(!$val)
                                    <span class="inline-flex items-center justify-center w-8 h-6 rounded border-2 border-slate-100 text-xs text-slate-300">—</span>
                                @else
                                    @php
                                        $valColor = match((int)$val) {
                                            1 => 'border-red-400 bg-red-50 text-red-600',
                                            2 => 'border-amber-400 bg-amber-50 text-amber-600',
                                            3 => 'border-emerald-400 bg-emerald-50 text-emerald-600',
                                            default => 'border-slate-200 bg-white text-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center justify-center w-8 h-6 rounded border-2 text-xs font-bold {{ $valColor }}">{{ $val }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($rec->instructor_notes)
                        <div class="mt-4 pt-3 border-t border-slate-100">
                            <p class="text-xs text-slate-500 italic">{{ $rec->instructor_notes }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach

    </div>

    <div class="pb-10"></div>

</div>

<script>

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
