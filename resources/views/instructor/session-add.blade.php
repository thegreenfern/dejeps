@extends('layouts.app')
@section('title', 'Nouvelle séance · ' . $trainee->name)

@section('content')
@php
    $dejepsTopics   = \App\Models\TraineeUc3::topics();
    $compPoints     = \App\Models\TraineeUc3::competencyPoints();
    $allKeys        = \App\Models\TraineeUc3::allPointKeys();
@endphp

<div class="max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('instructor.trainee.show', $trainee) }}"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour au dossier
    </a>

    {{-- Trainee bar --}}
    <div class="flex items-center gap-3 mb-8 p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
        @if($trainee->photo_path)
            <img src="{{ Storage::url($trainee->photo_path) }}"
                 class="w-10 h-10 rounded-full object-cover border-2 border-slate-100 flex-shrink-0">
        @else
            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-800">{{ $trainee->name }}</p>
            <p id="page-subtitle" class="text-xs text-slate-400">Nouvelle séance</p>
        </div>
        {{-- Step indicator --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            <div id="ind-1" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-sky-600 text-white transition-all">1</div>
            <div id="ind-line-1" class="w-6 h-px bg-slate-200"></div>
            <div id="ind-2" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-slate-100 text-slate-400 transition-all">2</div>
            <div id="ind-line-2" class="w-6 h-px bg-slate-200"></div>
            <div id="ind-3" class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-slate-100 text-slate-400 transition-all">3</div>
        </div>
    </div>

    {{-- ── Step 1 : type ──────────────────────────────────────────────────── --}}
    <div id="step-1">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Type de séance</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4 mb-4">
            <label for="session-date-input" class="block text-xs font-medium text-slate-600 mb-1.5">Date de la séance</label>
            <input type="date" id="session-date-input" value="{{ date('Y-m-d') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <button type="button" id="btn-type-pratique"
                    class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl border-2 border-slate-200 hover:border-sky-400 hover:bg-sky-50 transition-all group">
                <div class="w-12 h-12 rounded-full bg-sky-100 flex items-center justify-center group-hover:bg-sky-200 transition-colors">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-sky-700">Pratique</span>
            </button>
            <button type="button" id="btn-type-theorique"
                    class="flex flex-col items-center gap-3 p-6 bg-white rounded-xl border-2 border-slate-200 hover:border-violet-400 hover:bg-violet-50 transition-all group">
                <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-violet-700">Théorique</span>
            </button>
        </div>
    </div>

    {{-- ── Step 2 (théorique) : sujet ─────────────────────────────────────── --}}
    <div id="step-2-theorique" style="display:none">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Sujet de la séance</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">

            {{-- Toggle DEJEPS / Autre --}}
            <div class="flex gap-2">
                <button type="button" id="toggle-dejeps"
                        class="flex-1 py-2 px-3 rounded-lg border-2 border-violet-400 bg-violet-50 text-violet-700 text-sm font-semibold transition-all">
                    Sujet DEJEPS
                </button>
                <button type="button" id="toggle-autre"
                        class="flex-1 py-2 px-3 rounded-lg border-2 border-slate-200 text-slate-500 text-sm font-semibold transition-all hover:border-slate-300">
                    Autre thème
                </button>
            </div>

            {{-- Section DEJEPS --}}
            <div id="section-dejeps">
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Sujet DEJEPS</label>
                <select id="dejeps-topic-select"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                    @foreach($dejepsTopics as $topic)
                        <option value="{{ $topic['slug'] }}">{{ $topic['label'] }} · {{ $topic['level'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Section Autre thème --}}
            <div id="section-autre" style="display:none" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Niveau</label>
                    <select id="level-select"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                        <option value="PA12">PA12 · Plongeur autonome 12m</option>
                        <option value="N1 · PE20">N1 · Plongeur encadré 20m</option>
                        <option value="N2 · PA20">N2 · Plongeur autonome 20m</option>
                        <option value="N2 · PE40">N2 · Plongeur encadré 40m</option>
                        <option value="N3 · PA40">N3 · Plongeur autonome 40m</option>
                        <option value="N3 · PE60">N3 · Plongeur encadré 60m</option>
                        <option value="N3 · PA60">N3 · Plongeur autonome 60m</option>
                        <option value="N4">N4 · Thème N4</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Thème théorique</label>
                    <select id="topic-autre-select"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
                    </select>
                </div>
            </div>

            <button type="button" id="btn-next"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Suivant →
            </button>
        </div>
    </div>

    {{-- ── Step 2 (pratique) : niveau + séance ──────────────────────────────── --}}
    <div id="step-2-pratique" style="display:none">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Séance pratique</h2>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">

            {{-- Level selector --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-2">Niveau</label>
                <div class="grid grid-cols-3 gap-2" id="pratique-level-btns">
                    @foreach([
                        ['BAPT','Baptême','Baptême'],
                        ['PE20','N1','Plongeur Encadré 20m'],
                        ['PA20','N2','Plongeur Autonome 20m'],
                        ['PE40','N2','Plongeur Encadré 40m'],
                        ['PA40','N3','Plongeur Autonome 40m'],
                        ['PE60','N3','Plongeur Encadré 60m'],
                        ['PA60','N3','Plongeur Autonome 60m'],
                    ] as [$lv,$nl,$ll])
                    <button type="button" data-level="{{ $lv }}" data-level-label="{{ $ll }}" data-level-n="{{ $nl }}"
                            class="pratique-level-btn py-2.5 px-2 rounded-lg border-2 border-slate-200 text-center transition-all hover:border-sky-300">
                        <span class="block text-sm font-bold text-slate-700">{{ $lv }}</span>
                        <span class="block text-[10px] text-slate-400">{{ $nl }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Session number --}}
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-2">Numéro de séance</label>
                <div id="pratique-session-nums" class="flex gap-2 flex-wrap"></div>
            </div>

            <button type="button" id="btn-pratique-next"
                    class="w-full py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Suivant →
            </button>
        </div>
    </div>

    {{-- ── Step 3 : notation ───────────────────────────────────────────────── --}}
    <form id="step-3" method="POST" action="{{ route('instructor.uc3.seance.save', $trainee) }}" style="display:none">
        @csrf
        <input type="hidden" id="form-slug" name="slug" value="">
        <input type="hidden" id="form-session-date" name="session_date" value="{{ date('Y-m-d') }}">
        <input type="hidden" id="form-session-label" name="session_label" value="">
        <input type="hidden" id="form-session-level" name="session_level" value="">

        {{-- Situation pédagogique --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-slate-700 mb-3">Situation pédagogique</h2>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    ['observation',           'Observation'],
                    ['supervision_directe',   'Supervision directe'],
                    ['supervision_indirecte', 'Supervision indirecte'],
                    ['autonomie',             'Autonomie'],
                ] as [$val, $lbl])
                <label class="cursor-pointer">
                    <input type="radio" name="situation" value="{{ $val }}" {{ $loop->first ? 'checked' : '' }} class="sr-only situation-radio">
                    <span class="flex items-center justify-center py-2.5 px-3 text-sm font-medium rounded-lg border-2 transition-colors text-center leading-tight border-slate-200 text-slate-500 bg-slate-50">{{ $lbl }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Compétences détaillées (same for both types) --}}
        <div id="section-competences" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-4 space-y-6">
            <h2 class="text-base font-semibold text-slate-700">Détail par compétence</h2>
            @foreach($compPoints as $groupKey => $group)
            <div class="mb-4 last:mb-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">{{ $group['label'] }}</p>
                <div class="space-y-4">
                    @foreach($group['items'] as $key => $point)
                    @if($point['pratique_only'] ?? false)
                    <div id="r-securite-row" style="display:none">
                    @else
                    <div>
                    @endif
                        <p class="text-sm text-slate-600 leading-snug mb-2">{{ $point['label'] }}</p>
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1.5 flex-shrink-0">
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="1" checked class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-slate-200 text-slate-500 bg-slate-50 transition-colors">1</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="2" class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-amber-200 text-amber-500 bg-amber-50 transition-colors">2</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="3" class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-emerald-200 text-emerald-500 bg-emerald-50 transition-colors">3</span>
                                </label>
                            </div>
                            <input type="text" name="notes[{{ $key }}]" placeholder="Commentaire…"
                                class="flex-1 min-w-0 text-sm rounded border border-slate-200 px-3 py-1.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-1 focus:ring-violet-400">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Notes de séance --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6 space-y-4">
            <h2 class="text-base font-semibold text-slate-700">Notes</h2>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Commentaire sur la séance</label>
                <textarea name="session_note" rows="3" placeholder="Notes spécifiques à cette séance…"
                    class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-400 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1.5">Notes globales stagiaire</label>
                <textarea name="session_notes_global" rows="3" placeholder="Progression générale, points de vigilance…"
                    class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-400 resize-none"></textarea>
            </div>
        </div>

        <button type="submit"
            class="w-full py-3 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            Enregistrer la séance
        </button>
    </form>

</div>

<script>
(function () {
    var TOPICS = {
        'PA12':     ['Accidents (barotraumatismes, essoufflement, malaises)', 'Procédures de désaturation (durée, profondeur, vitesse)', 'Réglementation et prérogatives', 'Milieu et environnement'],
        'N1 · PE20':['Notions de physique (flottabilité, pression, volume, couleurs, sons)', 'Barotraumatismes et prévention (Valsalva, BTV, Frenzel, surpression pulmonaire)', 'Procédures de désaturation (ordinateur, table fédérale, remontée anormale)', 'Froid et dangers du milieu', 'Réglementation (prérogatives, FFESSM, carnet numérique)', 'Milieu et environnement'],
        'N2 · PA20':['Théorie de l\'activité (flottabilité, consommation, autonomie)', 'Accidents (désaturation, barotraumatismes, essoufflement, froid)', 'Procédures de désaturation (tables, ordinateur, cohabitation)', 'Matériel — fonctionnement du détendeur', 'Réglementation (prérogatives, matériel obligatoire, responsabilité)', 'Milieu et environnement'],
        'N2 · PE40':['Théorie de l\'activité (flottabilité, consommation, pressions partielles)', 'Accidents de profondeur (désaturation, essoufflement, narcose, froid)', 'Procédures de désaturation (courbe sans palier, ordinateur)', 'Réglementation', 'Milieu et environnement'],
        'N3 · PA40':['Théorie de l\'activité (physique, calculs consommation et autonomie)', 'Accidents (narcose, froid, essoufflement, désaturation)', 'Procédures de désaturation (Haldane, M-values, GF, Bühlmann ZHL-16C, RGBM)', 'Gestion de l\'ordinateur (planification, GF, paliers)', 'Réglementation (prérogatives, autonomie avec/sans DP)', 'Milieu et environnement'],
        'N3 · PE60':['Accident de désaturation (mécanismes, cutis marmorata, prévention, RIFAP)', 'Risques liés à la profondeur (consommation, essoufflement, narcose, froid)'],
        'N3 · PA60':['Gestion de l\'ordinateur (GF bas/haut, mode planification)', 'Consommation en profondeur et au palier (L/min et bar/min)', 'Réglementation (autonomie 0-40m et 40-60m avec DP)', 'Accident de désaturation (mécanismes, prévention)'],
        'N4':       ['Risques de l\'activité, mesures de prévention et bonnes pratiques', 'Outils et procédures de désaturation', 'Règlementation relative à l\'activité', 'Connaissance du matériel de plongée'],
    };

    var PRATIQUE_COMPS = {
        'BAPT': { label: 'Baptême', level: 'Baptême', sessions: 4, keys: [
            ['bapt_accueil',     "Accueillir, équiper et préparer le baptisé"],
            ['bapt_mise_a_leau', "Mettre à l'eau et accompagner l'immersion"],
            ['bapt_guidage',     "Guider et communiquer en immersion (signaux, réassurance)"],
            ['bapt_securite',    "Sécuriser le baptisé et gérer les imprévus"],
            ['bapt_surface',     "Retourner en surface en sécurité avec le baptisé"],
        ]},
        'PE20': { label: 'Plongeur Encadré 20m', level: 'N1', sessions: 4, keys: [
            ['pe20_equipement',    "S'équiper et se déséquiper avec aide"],
            ['pe20_mise_a_leau',   "Mise à l'eau depuis le bord ou l'embarcation"],
            ['pe20_immersion',     "S'immerger (canard, palmage ventral/dorsal)"],
            ['pe20_propulsion',    "Se propulser horizontalement et verticalement"],
            ['pe20_ventilation',   "Se ventiler correctement (embout, masque)"],
            ['pe20_equilibre',     "S'équilibrer et utiliser le gilet (SGS)"],
            ['pe20_communication', "Communiquer avec le guide de palanquée"],
            ['pe20_securite',      "Évoluer en sécurité dans la zone des 20m"],
            ['pe20_milieu',        "Respecter le milieu naturel"],
            ['pe20_surface',       "Retourner en surface en sécurité"],
        ]},
        'PA20': { label: 'Plongeur Autonome 20m', level: 'N2', sessions: 4, keys: [
            ['pa20_equipement',    "S'équiper, se déséquiper et mettre à l'eau"],
            ['pa20_immersion',     "S'immerger, se propulser et se ventiler"],
            ['pa20_milieu',        "Respecter le milieu naturel"],
            ['pa20_materiel',      "Connaître et vérifier le matériel des équipiers"],
            ['pa20_autonomie',     "Évoluer en autonomie dans la zone des 20m"],
            ['pa20_planification', "Planifier une plongée PA20"],
            ['pa20_intervention',  "Intervenir et porter assistance à un équipier"],
        ]},
        'PE40': { label: 'Plongeur Encadré 40m', level: 'N2', sessions: 4, keys: [
            ['pe40_equipement',    "S'équiper et mettre à l'eau"],
            ['pe40_immersion',     "S'immerger et se propulser jusqu'à 40m"],
            ['pe40_milieu',        "Respecter le milieu naturel"],
            ['pe40_ventilation',   "Se ventiler et s'équilibrer en VDM 20m"],
            ['pe40_communication', "Communiquer avec le guide de palanquée"],
            ['pe40_surface',       "Retourner en surface avec palier de désaturation"],
            ['pe40_intervention',  "Intervenir en relai auprès d'un plongeur en difficulté"],
        ]},
        'PA40': { label: 'Plongeur Autonome 40m', level: 'N3', sessions: 4, keys: [
            ['pa40_planification', "Planifier une plongée autonome PA40 (0-40m)"],
            ['pa40_autonomie',     "Évoluer en autonomie PA40 dans la zone des 40m"],
            ['pa40_intervention',  "Intervenir et porter assistance jusqu'à 40m"],
            ['pa40_milieu',        "Respecter le milieu naturel"],
        ]},
        'PE60': { label: 'Plongeur Encadré 60m', level: 'N3', sessions: 2, keys: [
            ['pe60_profondeur', "S'adapter à la profondeur entre 40 et 60m"],
            ['pe60_milieu',     "Respecter le milieu naturel"],
        ]},
        'PA60': { label: 'Plongeur Autonome 60m', level: 'N3', sessions: 4, keys: [
            ['pa60_organisation', "Organiser et planifier la plongée (0-60m)"],
            ['pa60_autonomie',    "Évoluer en autonomie dans la zone des 60m"],
            ['pa60_rifa',         "RIFA Plongée — premiers secours en plongée"],
            ['pa60_milieu',       "Respecter le milieu naturel"],
        ]},
    };

    var currentType = 'theorique'; // 'theorique' | 'pratique'
    var activeMode  = 'dejeps';    // for théorique: 'dejeps' | 'autre'
    var selectedLevel   = 'PE20';
    var selectedSession = 1;

    var step1     = document.getElementById('step-1');
    var step2T    = document.getElementById('step-2-theorique');
    var step2P    = document.getElementById('step-2-pratique');
    var step3     = document.getElementById('step-3');
    var subtitle  = document.getElementById('page-subtitle');
    var levelSel  = document.getElementById('level-select');
    var topicAutre= document.getElementById('topic-autre-select');
    var formSlug  = document.getElementById('form-slug');

    var RATING_ON  = { '1': ['#475569','#f1f5f9'], '2': ['#d97706','#fef3c7'], '3': ['#059669','#d1fae5'] };
    var RATING_OFF = { '1': ['#e2e8f0','#f8fafc'], '2': ['#fde68a','#fffbeb'], '3': ['#a7f3d0','#ecfdf5'] };

    function refreshSituation() {
        var isObservation = false;
        step3.querySelectorAll('.situation-radio').forEach(function(radio) {
            var span = radio.nextElementSibling;
            if (radio.checked) {
                span.style.borderColor = '#8b5cf6'; span.style.backgroundColor = '#f5f3ff'; span.style.color = '#6d28d9';
                if (radio.value === 'observation') isObservation = true;
            } else {
                span.style.borderColor = ''; span.style.backgroundColor = ''; span.style.color = '';
            }
        });

        document.getElementById('section-competences').style.display = isObservation ? 'none' : '';
    }

    function setIndicator(active) {
        [1,2,3].forEach(function (n) {
            var el = document.getElementById('ind-' + n);
            if (n < active) {
                el.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-emerald-500 text-white transition-all';
                el.textContent = '✓';
            } else if (n === active) {
                el.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-sky-600 text-white transition-all';
                el.textContent = String(n);
            } else {
                el.className = 'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-slate-100 text-slate-400 transition-all';
                el.textContent = String(n);
            }
            var line = document.getElementById('ind-line-' + n);
            if (line) line.className = n < active ? 'w-6 h-px bg-emerald-400' : 'w-6 h-px bg-slate-200';
        });
    }

    function showOnly(steps) {
        [step1, step2T, step2P, step3].forEach(function (s) { s.style.display = 'none'; });
        steps.forEach(function (s) { s.style.display = ''; });
    }

    function refreshRatingGroup(name) {
        document.querySelectorAll('input[type="radio"][name="' + name + '"]').forEach(function(radio) {
            var span = radio.nextElementSibling;
            var s = radio.checked ? RATING_ON[radio.value] : RATING_OFF[radio.value];
            if (s && span) { span.style.borderColor = s[0]; span.style.backgroundColor = s[1]; }
        });
    }

    function refreshAllRatings(root) {
        var seen = {};
        (root || step3).querySelectorAll('input[type="radio"]').forEach(function(radio) {
            if (!seen[radio.name]) { seen[radio.name] = true; refreshRatingGroup(radio.name); }
        });
    }

    // ─── Théorique helpers ──────────────────────────────────────────────
    function populateTopics(level) {
        topicAutre.innerHTML = '';
        (TOPICS[level] || []).forEach(function (t) {
            var opt = document.createElement('option');
            opt.value = t; opt.textContent = t;
            topicAutre.appendChild(opt);
        });
    }

    function setToggle(mode) {
        activeMode = mode;
        var dejepsSec = document.getElementById('section-dejeps');
        var autreSec  = document.getElementById('section-autre');
        var btnD      = document.getElementById('toggle-dejeps');
        var btnA      = document.getElementById('toggle-autre');
        var ON  = 'flex-1 py-2 px-3 rounded-lg border-2 border-violet-400 bg-violet-50 text-violet-700 text-sm font-semibold transition-all';
        var OFF = 'flex-1 py-2 px-3 rounded-lg border-2 border-slate-200 text-slate-500 text-sm font-semibold transition-all hover:border-slate-300';
        if (mode === 'dejeps') {
            dejepsSec.style.display = ''; autreSec.style.display = 'none';
            btnD.className = ON; btnA.className = OFF;
        } else {
            dejepsSec.style.display = 'none'; autreSec.style.display = '';
            btnA.className = ON; btnD.className = OFF;
        }
    }

    // ─── Pratique helpers ───────────────────────────────────────────────
    function selectLevel(level) {
        selectedLevel = level;
        document.querySelectorAll('.pratique-level-btn').forEach(function(btn) {
            var on = btn.dataset.level === level;
            btn.style.borderColor      = on ? '#0ea5e9' : '';
            btn.style.backgroundColor  = on ? '#f0f9ff' : '';
            btn.querySelector('span').style.color = on ? '#0369a1' : '';
        });
        renderSessionNums(level);
    }

    function selectSession(n) {
        selectedSession = n;
        document.querySelectorAll('.pratique-session-btn').forEach(function(btn) {
            var on = parseInt(btn.dataset.n) === n;
            btn.style.borderColor     = on ? '#0ea5e9' : '';
            btn.style.backgroundColor = on ? '#f0f9ff' : '';
            btn.style.color           = on ? '#0369a1' : '';
        });
    }

    function renderSessionNums(level) {
        var comp = PRATIQUE_COMPS[level];
        var count = comp ? comp.sessions : 4;
        var container = document.getElementById('pratique-session-nums');
        container.innerHTML = '';
        for (var i = 1; i <= count; i++) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.dataset.n = i;
            btn.className = 'pratique-session-btn w-10 h-10 rounded-lg border-2 border-slate-200 text-sm font-bold text-slate-600 transition-all hover:border-sky-300';
            btn.textContent = i;
            btn.addEventListener('click', (function(n) { return function() { selectSession(n); }; })(i));
            container.appendChild(btn);
        }
        selectSession(1);
    }

    // ─── Navigation ─────────────────────────────────────────────────────
    function goToStep2T() {
        currentType = 'theorique';
        subtitle.textContent = 'Séance théorique';
        showOnly([step2T]);
        setIndicator(2);
        populateTopics(levelSel.value);
        window.scrollTo(0, 0);
    }

    function goToStep2P() {
        currentType = 'pratique';
        subtitle.textContent = 'Séance pratique';
        showOnly([step2P]);
        setIndicator(2);
        selectLevel(selectedLevel);
        window.scrollTo(0, 0);
    }

    function goToStep3() {
        var slug, label, level;

        var rSecuriteRow = document.getElementById('r-securite-row');
        if (currentType === 'pratique') {
            slug  = 'pratique_' + selectedLevel.toLowerCase() + '_s' + selectedSession;
            label = 'Séance ' + selectedSession;
            level = selectedLevel;
            if (rSecuriteRow) rSecuriteRow.style.display = '';
        } else {
            if (activeMode === 'dejeps') {
                var sel = document.getElementById('dejeps-topic-select');
                slug  = sel.value;
                label = sel.options[sel.selectedIndex].textContent;
                level = '';
            } else {
                var lv    = levelSel.value;
                var topic = topicAutre.value;
                slug  = 'autre__' + lv.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '__' + topic.replace(/[^a-z0-9]/gi, '_').toLowerCase().substring(0, 40);
                label = topic;
                var lvlMatch = (lv || '').match(/^(N\d)/i);
                level = lvlMatch ? lvlMatch[1].toUpperCase() : '';
            }
            if (rSecuriteRow) rSecuriteRow.style.display = 'none';
        }

        formSlug.value = slug;
        document.getElementById('form-session-date').value  = document.getElementById('session-date-input').value;
        document.getElementById('form-session-label').value = label;
        document.getElementById('form-session-level').value = level;
        subtitle.textContent = currentType === 'pratique'
            ? (PRATIQUE_COMPS[selectedLevel].label + ' · Séance ' + selectedSession)
            : label;

        showOnly([step3]);
        setIndicator(3);
        step3.querySelectorAll('input[type="radio"][value="1"]').forEach(function (r) { r.checked = true; });
        step3.querySelectorAll('input[type="text"], textarea').forEach(function (el) { el.value = ''; });
        step3.querySelectorAll('input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
        var firstSit = step3.querySelector('.situation-radio');
        if (firstSit) { firstSit.checked = true; }
        refreshAllRatings(step3);
        refreshSituation();
        window.scrollTo(0, 0);
    }

    // ─── Event listeners ────────────────────────────────────────────────
    document.getElementById('btn-type-theorique').addEventListener('click', goToStep2T);
    document.getElementById('btn-type-pratique').addEventListener('click', goToStep2P);
    document.getElementById('btn-next').addEventListener('click', goToStep3);
    document.getElementById('btn-pratique-next').addEventListener('click', goToStep3);
    document.getElementById('toggle-dejeps').addEventListener('click', function () { setToggle('dejeps'); });
    document.getElementById('toggle-autre').addEventListener('click', function () { setToggle('autre'); });
    levelSel.addEventListener('change', function () { populateTopics(this.value); });
    step3.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.classList.contains('situation-radio')) { refreshSituation(); }
            else { refreshRatingGroup(this.name); }
        });
    });
    document.querySelectorAll('.pratique-level-btn').forEach(function(btn) {
        btn.addEventListener('click', function() { selectLevel(this.dataset.level); });
    });
    // Initial highlights
    refreshAllRatings(step3);
    refreshSituation();
})();
</script>
@endsection
