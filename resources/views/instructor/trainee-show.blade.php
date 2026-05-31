@extends('layouts.app')
@section('title', $trainee->name . ' · Dossier stagiaire')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('instructor.dashboard') }}"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-5">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Tableau de bord
    </a>

    {{-- ── Trainee header (always visible) ────────────────────────────── --}}
    <div class="flex items-center gap-4 mb-6">
        @if($trainee->photo_path)
            <img src="{{ Storage::url($trainee->photo_path) }}"
                 class="w-14 h-14 rounded-full object-cover border-2 border-slate-100 shadow-sm flex-shrink-0">
        @else
            <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold text-slate-800">{{ $trainee->name }}</h1>
                @if($trainee->hasCompletedOnboarding())
                    <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Dossier complet
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3 mt-0.5">
                @if($trainee->email)
                    <span class="text-xs text-slate-400">{{ $trainee->email }}</span>
                @endif
                @if($trainee->phone)
                    <span class="text-xs text-slate-300">·</span>
                    <span class="text-xs text-slate-400">{{ $trainee->phone }}</span>
                @endif
            </div>
        </div>
        <a href="{{ route('instructor.session.add', $trainee) }}"
           class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter une séance
        </a>
    </div>

    {{-- ── Tab navigation ───────────────────────────────────────────────── --}}
    <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6" role="tablist">
        @foreach([
            'profil'    => 'Profil',
            'epmsp'     => 'EPMSP',
            'uc12'      => 'UC1 / UC2',
            'seances'   => 'Séances',
            'peda'      => 'Péda',
            'parcours'  => 'Parcours',
        ] as $id => $label)
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
    {{-- TAB: Profil                                                       --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-profil" class="tab-panel">

        {{-- Subtab bar --}}
        <div class="flex gap-1 bg-slate-100 rounded-lg p-1 mb-5">
            <button type="button" class="subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="profil-info">Profil</button>
            <button type="button" class="subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="profil-autoeval">Auto-éval.</button>
            <button type="button" class="subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="profil-personnalite">Personnalité</button>
        </div>

        {{-- Subtab: Profil info --}}
        <div id="subtab-profil-info" class="subtab-panel space-y-5">

        {{-- Identity --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Informations personnelles</h2>

            <div class="flex items-start gap-6">
            {{-- Info grid --}}
            <div class="flex-1 grid grid-cols-2 gap-x-8 gap-y-3">
                @if($trainee->date_of_birth)
                    <div>
                        <p class="text-xs font-medium text-slate-400 mb-0.5">Date de naissance</p>
                        <p class="text-sm text-slate-700">{{ $trainee->date_of_birth->format('d/m/Y') }}</p>
                    </div>
                @endif
                @if($trainee->email)
                    <div>
                        <p class="text-xs font-medium text-slate-400 mb-0.5">E-mail</p>
                        <p class="text-sm text-slate-700">{{ $trainee->email }}</p>
                    </div>
                @endif
                @if($trainee->phone)
                    <div>
                        <p class="text-xs font-medium text-slate-400 mb-0.5">Téléphone</p>
                        <p class="text-sm text-slate-700">{{ $trainee->phone }}</p>
                    </div>
                @endif
                @if($trainee->cv_path)
                    <div>
                        <p class="text-xs font-medium text-slate-400 mb-0.5">CV</p>
                        <a href="{{ Storage::url($trainee->cv_path) }}" target="_blank"
                           class="text-sm text-sky-600 hover:text-sky-700 transition-colors">
                            Télécharger le CV →
                        </a>
                    </div>
                @endif
            </div>
            {{-- Avatar --}}
            <div class="flex-shrink-0 pb-4">
                @if($trainee->profile?->photo_path)
                    <img src="{{ Storage::url($trainee->profile->photo_path) }}"
                         alt="Photo de {{ $trainee->name }}"
                         class="w-24 h-24 rounded-full object-cover border-2 border-slate-200">
                @else
                    <div class="w-24 h-24 rounded-full border-2 border-slate-200 bg-slate-100 flex items-end justify-center overflow-hidden">
                        <svg viewBox="0 0 80 90" class="w-20 h-20 text-slate-300" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="40" cy="28" rx="18" ry="20"/>
                            <path d="M4 85 C4 60 76 60 76 85" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>
        </div>

        {{-- Prior experiences --}}
        @if($trainee->profile?->prior_experiences)
            @php $prior = $trainee->profile->prior_experiences; @endphp
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Expériences antérieures</h2>
                <div class="space-y-4">
                    @if(!empty($prior['diving_level']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Niveau de plongée</p>
                            <p class="text-sm text-slate-700">{{ $prior['diving_level'] }}</p>
                        </div>
                    @endif
                    @if(!empty($prior['teaching']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Expériences d'enseignement</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $prior['teaching'] }}</p>
                        </div>
                    @endif
                    @if(!empty($prior['other']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Autres expériences</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $prior['other'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Ice-breaking --}}
        @if($trainee->profile?->ice_breaking)
            @php $ice = $trainee->profile->ice_breaking; @endphp
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Pour mieux le/la connaître</h2>
                <div class="space-y-5">
                    @if(!empty($ice['motivation']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Motivation pour le DEJEPS</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $ice['motivation'] }}</p>
                        </div>
                    @endif
                    @if(!empty($ice['strengths']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Points forts</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $ice['strengths'] }}</p>
                        </div>
                    @endif
                    @if(!empty($ice['challenges']))
                        <div>
                            <p class="text-xs font-medium text-slate-400 mb-1">Aspects difficiles ou incertains</p>
                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $ice['challenges'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Comments --}}
        @if($trainee->profile?->trainee_comments)
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Commentaires du stagiaire</h2>
                <p class="text-sm text-slate-700 whitespace-pre-line">{{ $trainee->profile->trainee_comments }}</p>
            </div>
        @endif

        </div>{{-- /subtab-profil-info --}}

        {{-- Subtab: Auto-évaluation --}}
        <div id="subtab-profil-autoeval" class="subtab-panel" style="display:none">
        @if($assessments->isNotEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-1">Auto-évaluation initiale</h2>
                <p class="text-xs text-slate-400 mb-6">1 = aucune notion · 2 = avec aide · 3 = autonome</p>

                <div class="space-y-6">
                    @foreach($assessments as $category => $items)
                        @php
                            $catScore = $items->avg(fn($i) => match((int)$i->trainee_score) {
                                1 => 0, 2 => 50, 3 => 100, default => 0,
                            });
                            $catScore = round($catScore);
                            $barColor = $catScore >= 66 ? 'bg-emerald-400' : ($catScore >= 34 ? 'bg-amber-400' : 'bg-red-400');
                            $grade    = $catScore >= 66 ? '3' : ($catScore >= 34 ? '2' : '1');
                        @endphp
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-semibold text-slate-600 w-52 flex-shrink-0 leading-tight">{{ $category }}</span>
                                <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $catScore }}%"></div>
                                </div>
                                <span class="text-xs font-bold w-6 text-right flex-shrink-0
                                    {{ $catScore >= 66 ? 'text-emerald-600' : ($catScore >= 34 ? 'text-amber-600' : 'text-red-500') }}">
                                    {{ $grade }}
                                </span>
                            </div>
                            <details class="ml-52 pl-3">
                                <summary class="text-xs text-slate-400 hover:text-slate-600 cursor-pointer select-none mb-2">
                                    Détail ({{ $items->count() }} compétences)
                                </summary>
                                <div class="space-y-1.5 mt-2">
                                    @foreach($items as $item)
                                        @php
                                            $s   = (int)$item->trainee_score;
                                            $lbl = match($s) { 1 => '1', 2 => '2', 3 => '3', default => '—' };
                                        @endphp
                                        <div class="flex items-start gap-2">
                                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold mt-0.5
                                                {{ $s === 1 ? 'bg-red-50 text-red-600 border border-red-200'
                                                 : ($s === 2 ? 'bg-amber-50 text-amber-600 border border-amber-200'
                                                 : ($s === 3 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200'
                                                 : 'bg-slate-100 text-slate-400')) }}">
                                                {{ $lbl }}
                                            </span>
                                            <div class="flex-1">
                                                <p class="text-xs text-slate-600 leading-snug">{{ $item->competency->label }}</p>
                                                @if($item->trainee_evidence)
                                                    <p class="text-xs text-slate-400 italic mt-0.5">« {{ $item->trainee_evidence }} »</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <p class="text-sm text-slate-400">L'auto-évaluation n'a pas encore été complétée.</p>
            </div>
        @endif
        </div>{{-- /subtab-profil-autoeval --}}

        {{-- Subtab: Personnalité --}}
        <div id="subtab-profil-personnalite" class="subtab-panel space-y-4" style="display:none">
        @if($trainee->profile?->big5_scores)
            @php
                $scores = $trainee->profile->big5_scores;
                $traits = [
                    'O' => [
                        'label' => 'Ouverture',
                        'bar'   => 'bg-sky-400',
                        'badge' => 'bg-sky-50 text-sky-700',
                        'bands' => [
                            'Élevé'  => [
                                'desc' => "Grande curiosité intellectuelle et créativité. Adapte spontanément ses méthodes pédagogiques et apprécie l'innovation dans l'enseignement.",
                                'tip'  => "Peut manquer de structure dans ses séances. Encourager une préparation rigoureuse et un fil directeur clair.",
                            ],
                            'Modéré' => [
                                'desc' => "Équilibre entre méthodes éprouvées et ouverture à l'innovation. S'adapte bien aux différents contextes d'enseignement.",
                                'tip'  => "Profil polyvalent. À l'aise dans la plupart des situations pédagogiques standard.",
                            ],
                            'Faible' => [
                                'desc' => "Préfère des approches concrètes et des procédures établies. Fiable et prévisible dans un cadre bien défini.",
                                'tip'  => "Peut avoir du mal à adapter son approche face à des publics atypiques. Travailler la flexibilité pédagogique et la différenciation.",
                            ],
                        ],
                    ],
                    'C' => [
                        'label' => 'Conscienciosité',
                        'bar'   => 'bg-violet-400',
                        'badge' => 'bg-violet-50 text-violet-700',
                        'bands' => [
                            'Élevé'  => [
                                'desc' => "Organisation irréprochable, sens du détail et fiabilité. Planifie ses séances avec soin et respecte les protocoles.",
                                'tip'  => "Peut avoir des difficultés à improviser. Travailler la gestion des imprévus et la tolérance à l'ambiguïté.",
                            ],
                            'Modéré' => [
                                'desc' => "Bonne organisation générale avec une capacité à s'adapter aux imprévus. Équilibre préparation et flexibilité.",
                                'tip'  => "Profil solide. Veiller à maintenir la rigueur sur les éléments de sécurité plongée.",
                            ],
                            'Faible' => [
                                'desc' => "Spontané et flexible, préfère agir dans l'instant plutôt que de planifier à l'avance.",
                                'tip'  => "À accompagner sur la planification pédagogique et le strict respect des protocoles de sécurité en milieu aquatique.",
                            ],
                        ],
                    ],
                    'E' => [
                        'label' => 'Extraversion',
                        'bar'   => 'bg-amber-400',
                        'badge' => 'bg-amber-50 text-amber-700',
                        'bands' => [
                            'Élevé'  => [
                                'desc' => "Naturellement à l'aise devant un groupe, dynamise les séances et crée facilement du lien avec les apprenants.",
                                'tip'  => "Peut monopoliser l'espace de parole. Travailler l'écoute active et les temps de silence pédagogique.",
                            ],
                            'Modéré' => [
                                'desc' => "S'adapte aux contextes collectifs comme individuels. Bon équilibre entre présence et écoute.",
                                'tip'  => "À l'aise aussi bien en grand groupe qu'en suivi individuel. Profil polyvalent.",
                            ],
                            'Faible' => [
                                'desc' => "Plus à l'aise en accompagnement individuel ou en petits groupes. Peut paraître réservé devant un large public.",
                                'tip'  => "Travailler la prise de parole en groupe, l'affirmation de soi devant un public et l'animation de séances collectives.",
                            ],
                        ],
                    ],
                    'A' => [
                        'label' => 'Agréabilité',
                        'bar'   => 'bg-emerald-400',
                        'badge' => 'bg-emerald-50 text-emerald-700',
                        'bands' => [
                            'Élevé'  => [
                                'desc' => "Très empathique et bienveillant. Crée un climat de confiance propice à l'apprentissage et à la prise de risque.",
                                'tip'  => "Peut avoir du mal à formuler des feedbacks négatifs ou à maintenir l'exigence. Travailler l'assertivité et le feedback constructif.",
                            ],
                            'Modéré' => [
                                'desc' => "Allie coopération et assertivité. Capable d'un feedback équilibré, à la fois bienveillant et direct.",
                                'tip'  => "Bon équilibre pédagogique. Peut s'adapter à des apprenants aux profils variés.",
                            ],
                            'Faible' => [
                                'desc' => "Direct et objectif dans ses évaluations. Peut paraître exigeant ou froid pour certains apprenants.",
                                'tip'  => "Travailler la chaleur relationnelle et l'adaptation du discours selon la sensibilité de l'apprenant.",
                            ],
                        ],
                    ],
                    'N' => [
                        'label' => 'Stabilité émo.',
                        'bar'   => 'bg-rose-400',
                        'badge' => 'bg-rose-50 text-rose-700',
                        'bands' => [
                            'Élevé'  => [
                                'desc' => "Grande stabilité émotionnelle, calme sous pression. Ressource précieuse pour la gestion de la sécurité en plongée.",
                                'tip'  => "Veiller à rester attentif aux signaux émotionnels des apprenants — la stabilité ne doit pas devenir froideur.",
                            ],
                            'Modéré' => [
                                'desc' => "Réactivité émotionnelle dans la norme. Garde généralement son calme dans les situations standard.",
                                'tip'  => "Peut être affecté par les situations de forte pression. Accompagner sur la gestion du stress en conditions réelles.",
                            ],
                            'Faible' => [
                                'desc' => "Sensible au stress et aux imprévus. Peut être affecté par les conflits ou la pression des évaluations.",
                                'tip'  => "Priorité : développer les stratégies de régulation émotionnelle, en particulier dans les contextes de sécurité aquatique.",
                            ],
                        ],
                    ],
                ];
            @endphp

            <p class="text-xs text-slate-400">Il n'y a pas de profil idéal — ces tendances guident l'accompagnement individuel.</p>

            @foreach($traits as $key => $trait)
                @php
                    $raw  = $scores[$key] ?? 50;
                    $disp = $key === 'N' ? (100 - $raw) : $raw;
                    $band = $disp < 35 ? 'Faible' : ($disp < 65 ? 'Modéré' : 'Élevé');
                    $info = $trait['bands'][$band];
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    {{-- Header row --}}
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-sm font-semibold text-slate-700 w-32 flex-shrink-0">{{ $trait['label'] }}</span>
                        <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $trait['bar'] }} rounded-full" style="width: {{ $disp }}%"></div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0 {{ $trait['badge'] }}">{{ $band }}</span>
                        <span class="text-xs text-slate-400 w-8 text-right flex-shrink-0 tabular-nums">{{ $disp }}%</span>
                    </div>
                    {{-- Description --}}
                    <p class="text-sm text-slate-600 leading-relaxed mb-3">{{ $info['desc'] }}</p>
                    {{-- Instructor tip --}}
                    <div class="flex gap-2.5 bg-slate-50 rounded-lg px-3 py-2.5">
                        <span class="text-slate-400 flex-shrink-0 mt-px">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347A3.5 3.5 0 0114.5 20h-5a3.5 3.5 0 01-2.475-1.025l-.347-.347z"/>
                            </svg>
                        </span>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $info['tip'] }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
                <p class="text-sm text-slate-400">Le test de personnalité n'a pas encore été complété.</p>
            </div>
        @endif
        </div>{{-- /subtab-profil-personnalite --}}

    </div>{{-- /tab-profil --}}

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: UC1 / UC2                                                    --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-uc12" class="tab-panel space-y-5">
        @php $uc = $trainee->ucProgress->firstWhere('uc', 'uc1'); @endphp

        {{-- ── Dossier projet ────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">UC1 / UC2 · Dossier projet</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Projet de développement et gestion d'une structure sportive</p>
                </div>
                <button type="button"
                        onclick="document.getElementById('modal-suivi-etapes').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition-colors flex-shrink-0 ml-4">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Envoyer une évaluation
                </button>
            </div>
            @if($uc?->dossier_url)
                <a href="{{ $uc->dossier_url }}" target="_blank"
                   class="inline-flex items-center gap-2 text-sm text-sky-600 hover:text-sky-700 transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Ouvrir le dossier
                </a>
            @else
                <p class="text-sm text-slate-400 italic">Aucun lien renseigné par le stagiaire.</p>
            @endif
        </div>

        {{-- ── Calendrier du projet ──────────────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Calendrier du projet</h2>
                <a href="{{ route('instructor.uc12') }}"
                   class="text-xs text-slate-400 hover:text-slate-600 transition-colors">
                    Modifier les dates →
                </a>
            </div>

            {{--
                Tailwind safelist (milestone status badges):
                bg-emerald-100 text-emerald-700 border-emerald-300
                bg-amber-100 text-amber-700 border-amber-300
                bg-slate-100 text-slate-500 border-slate-200
                peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-700
                peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-700
                peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-600
            --}}
            @php
                $submissionDate = $settings->uc1_submission_deadline ?? \Carbon\Carbon::parse('2026-06-12');
                $juryDate       = $settings->uc1_jury_date            ?? \Carbon\Carbon::parse('2026-06-18');
                $rattrapageDate = $settings->uc2_jury_date             ?? \Carbon\Carbon::parse('2026-10-23');
                $frMonths       = ['jan.','fév.','mars','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
                $fmtDate        = fn($d) => $d->day . ' ' . $frMonths[$d->month - 1];

                // 6th element: milestone slug (null = not tracked)
                $milestones = [
                    ['2026-01-05', '5 jan.',                      'Diagnostic de la structure',      'slate',   false, 'diagnostic'],
                    ['2026-02-27', '27 fév.',                     'Validation de la problématique',  'violet',  true,  'validation_problematique'],
                    ['2026-03-02', '2 mars',                      'Conception et planification',     'slate',   false, 'conception_planification'],
                    ['2026-05-11', '11 mai',                      'Phase de test & analyse',         'slate',   false, 'phase_test_analyse'],
                    ['2026-05-30', '30 mai',                      'Rédaction du dossier',            'amber',   false, 'redaction_dossier'],
                    [$submissionDate->format('Y-m-d'), $fmtDate($submissionDate), 'Dépôt du dossier', 'red',   true,  'depot_dossier'],
                    ['2026-06-13', '13 juin',                     'Oral blanc (préparation)',        'slate',   false, 'oral_blanc'],
                    [$juryDate->format('Y-m-d'),       $fmtDate($juryDate),       'Oral final — soutenance',   'emerald', true,  null],
                    ['2026-10-09', '9 oct.',                      'Remédiation',                     'slate',   false, null],
                    [$rattrapageDate->format('Y-m-d'), $fmtDate($rattrapageDate), 'Rattrapage',      'amber',   true,  null],
                ];
                $colors = [
                    'slate'   => ['dot' => 'bg-slate-300',   'text' => 'text-slate-500'],
                    'violet'  => ['dot' => 'bg-violet-400',  'text' => 'text-violet-600'],
                    'amber'   => ['dot' => 'bg-amber-400',   'text' => 'text-amber-600'],
                    'red'     => ['dot' => 'bg-red-400',     'text' => 'text-red-600'],
                    'emerald' => ['dot' => 'bg-emerald-400', 'text' => 'text-emerald-600'],
                ];
                $msBadge = [
                    'done'        => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                    'in_progress' => 'bg-amber-100 text-amber-700 border border-amber-300',
                    'not_done'    => 'bg-slate-100 text-slate-500 border border-slate-200',
                ];
                $today = now()->startOfDay();
            @endphp

            <div class="relative pl-6 border-l-2 border-slate-100 space-y-4">
                @foreach($milestones as [$dateStr, $displayDate, $label, $color, $isKey, $msSlug])
                    @php
                        $date      = \Carbon\Carbon::parse($dateStr);
                        $isPast    = $date->lt($today);
                        $c         = $colors[$color];
                        $msStatus  = $msSlug ? ($milestoneProgress[$msSlug] ?? 'not_done') : null;
                        $isOverdue = $isPast && $msSlug && $msStatus !== 'done';
                    @endphp
                    <div class="flex items-start gap-3 relative {{ $isOverdue ? 'rounded-lg bg-amber-50 -mx-2 px-2 py-1' : '' }}">
                        <div class="absolute -left-[25px] mt-1 w-4 h-4 rounded-full border-2 border-white
                                    {{ $isKey ? $c['dot'] : 'bg-slate-200' }}
                                    {{ $isPast && !$isOverdue ? 'opacity-50' : '' }}"></div>
                        <div class="flex-1 flex items-center justify-between gap-2 {{ $isPast && !$isOverdue && !$msSlug ? 'opacity-50' : '' }}">
                            <div class="flex items-baseline gap-2 min-w-0">
                                <span class="text-xs font-semibold {{ $isKey ? $c['text'] : 'text-slate-400' }} w-16 flex-shrink-0">
                                    {{ $displayDate }}
                                </span>
                                <span class="text-sm {{ $isKey ? 'font-semibold text-slate-800' : 'text-slate-500' }} truncate">
                                    {{ $label }}
                                </span>
                            </div>
                            @if($isOverdue)
                                <span class="text-[10px] font-semibold text-amber-600 flex-shrink-0 ml-auto">⚠ Retard</span>
                            @endif
                            @if($msSlug)
                                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded flex-shrink-0 {{ $msBadge[$msStatus] }}">
                                    {{ \App\Models\TraineeUcProgress::milestoneStatusLabel($msStatus) }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Suivi des étapes modal ────────────────────────────────────── --}}
        <div id="modal-suivi-etapes" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
                 onclick="document.getElementById('modal-suivi-etapes').classList.add('hidden')"></div>

            {{-- Panel --}}
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
                {{-- Header --}}
                <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">Suivi des étapes</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Avancement du projet · retour au stagiaire</p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                        @if($pendingReviewRequest)
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-3 py-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                Retour demandé · {{ $pendingReviewRequest->created_at->diffForHumans() }}
                            </span>
                        @endif
                        <button type="button"
                                onclick="document.getElementById('modal-suivi-etapes').classList.add('hidden')"
                                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Scrollable body --}}
                <div class="overflow-y-auto flex-1 px-6 py-5">
                    @if(session('success') && request()->routeIs('instructor.trainee.show'))
                        <p class="text-xs text-emerald-600 mb-4">{{ session('success') }}</p>
                    @endif

                    <form id="form-suivi-etapes" method="POST" action="{{ route('instructor.milestones.save', $trainee) }}" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            @foreach($trackedMilestones as $ms)
                                @php
                                    $currentStatus = $milestoneProgress[$ms['slug']] ?? 'not_done';
                                    $peerCheckedClasses = match(true) {
                                        true => [
                                            'not_done'    => 'peer-checked:border-slate-400 peer-checked:bg-slate-100 peer-checked:text-slate-600',
                                            'in_progress' => 'peer-checked:border-amber-400 peer-checked:bg-amber-50 peer-checked:text-amber-700',
                                            'done'        => 'peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-700',
                                        ],
                                    };
                                @endphp
                                <div class="flex items-center justify-between rounded-lg px-2 py-1.5">
                                    <span class="text-sm text-slate-700 leading-snug">{{ $ms['label'] }}</span>
                                    <div class="flex gap-1 flex-shrink-0 ml-4">
                                        @foreach(['not_done' => 'Non fait', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $val => $lbl)
                                            <label class="cursor-pointer">
                                                <input type="radio"
                                                       name="milestone_statuses[{{ $ms['slug'] }}]"
                                                       value="{{ $val }}"
                                                       class="sr-only peer"
                                                       {{ $currentStatus === $val ? 'checked' : '' }}>
                                                <div class="text-[11px] font-semibold px-2.5 py-1 rounded-md border-2 transition-colors cursor-pointer
                                                            border-slate-200 text-slate-400 hover:border-slate-300
                                                            {{ $peerCheckedClasses[$val] }}">
                                                    {{ $lbl }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">
                                Message au stagiaire <span class="font-normal text-slate-400">(optionnel — déclenche une notification)</span>
                            </label>
                            <textarea name="feedback_text" rows="3"
                                      placeholder="Retour sur l'avancement, points d'attention, félicitations…"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none"></textarea>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 flex-shrink-0">
                    <button type="submit" form="form-suivi-etapes" name="action" value="save"
                            class="px-4 py-2 border border-slate-300 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors">
                        Enregistrer
                    </button>
                    <button type="submit" form="form-suivi-etapes" name="action" value="notify"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Enregistrer et notifier
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Feedback history ───────────────────────────────────────────── --}}
        @if($feedbacks->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Historique des retours</h2>

            <div class="space-y-4">
                @foreach($feedbacks as $fb)
                <div class="group relative" data-feedback-id="{{ $fb->id }}">
                    {{-- Read view --}}
                    <div class="feedback-read-view">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-semibold text-slate-400 mb-1">
                                    {{ $fb->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH[h]mm') }}
                                </p>
                                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $fb->data['feedback_text'] ?? '' }}</p>
                            </div>
                            <button type="button"
                                    onclick="openFeedbackEdit({{ $fb->id }})"
                                    class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded text-slate-400 hover:text-slate-600 hover:bg-slate-100"
                                    title="Modifier">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    {{-- Edit view (hidden by default) --}}
                    <div class="feedback-edit-view hidden">
                        <p class="text-[11px] font-semibold text-slate-400 mb-1">
                            {{ $fb->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH[h]mm') }}
                        </p>
                        <textarea rows="3"
                                  class="w-full rounded-lg border border-sky-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none"
                                  data-original="{{ htmlspecialchars($fb->data['feedback_text'] ?? '', ENT_QUOTES) }}">{{ $fb->data['feedback_text'] ?? '' }}</textarea>
                        <div class="flex items-center gap-2 mt-2">
                            <button type="button"
                                    onclick="saveFeedbackEdit({{ $fb->id }}, '{{ route('instructor.feedback.update', $fb) }}')"
                                    class="px-3 py-1 text-xs font-semibold bg-sky-600 hover:bg-sky-700 text-white rounded-md transition-colors">
                                Enregistrer
                            </button>
                            <button type="button"
                                    onclick="cancelFeedbackEdit({{ $fb->id }})"
                                    class="px-3 py-1 text-xs text-slate-500 hover:text-slate-700 border border-slate-200 rounded-md transition-colors">
                                Annuler
                            </button>
                            <span class="feedback-save-error text-xs text-red-500 hidden ml-2">Erreur — réessayez.</span>
                        </div>
                    </div>
                    @if(!$loop->last)
                    <div class="border-t border-slate-100 mt-4"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif


    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: SÉANCES                                                      --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-seances" class="tab-panel">

        {{-- Subtab bar --}}
        <div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6">
            <button type="button" class="seances-subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="seances-theorique">Théorique</button>
            <button type="button" class="seances-subtab-btn flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-colors" data-subtab="seances-pratique">Pratique</button>
        </div>

    <div id="subtab-seances-theorique" class="seances-subtab-panel space-y-5">

        @php
            $uc3                  = $trainee->uc3;
            $topics               = \App\Models\TraineeUc3::topics();
            $compPoints           = \App\Models\TraineeUc3::competencyPoints();
            $topicProgress        = $uc3?->topic_progress ?? [];
            $traineeTopicProgress = $uc3?->trainee_topic_progress ?? [];
            $allKeys              = \App\Models\TraineeUc3::allPointKeys();
            $notationStyles       = [
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
            $completeCount = collect($topics)->filter(
                fn($t) => collect($allKeys)->filter(fn($k) => ($topicProgress[$t['slug']][$k] ?? null) === '3')->count() === 8
            )->count();

            // Unified théorique list (union of both sources)
            $theoSlugs = array_unique(array_merge(
                array_filter(array_keys($topicProgress),        fn($s) => !str_starts_with($s, 'pratique_') && $hasData($topicProgress[$s])),
                array_filter(array_keys($traineeTopicProgress), fn($s) => !str_starts_with($s, 'pratique_') && $hasData($traineeTopicProgress[$s]))
            ));
            $theoSessions = [];
            foreach ($theoSlugs as $slug) {
                $iP = $topicProgress[$slug] ?? null;
                $sP = $traineeTopicProgress[$slug] ?? null;
                $ref = $iP ?? $sP;
                $topicInfo  = collect($topics)->firstWhere('slug', $slug);
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
        @endphp

        <div class="flex items-baseline gap-3">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">UC3</h2>
            <span class="text-xs text-slate-400">Pédagogie Théorique · Épreuve de pédagogie théorique de la plongée</span>
        </div>

        {{-- Unified théorique table --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances théoriques</h2>
                    <p class="text-xs text-slate-400 mt-0.5">{{ count($theoSessions) }} séance{{ count($theoSessions) !== 1 ? 's' : '' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-lg font-bold {{ $completeCount === 14 ? 'text-emerald-600' : 'text-slate-700' }}">{{ $completeCount }}</span>
                        <span class="text-xs text-slate-400">/ 14 complets</span>
                    </div>
                    <a href="{{ route('instructor.session.add', $trainee) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Nouvelle séance
                    </a>
                </div>
            </div>

            @if(count($theoSessions) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-sm">Aucune séance enregistrée</p>
                </div>
            @else
                <div class="rounded-lg overflow-hidden border border-slate-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5 w-24">Date</th>
                                <th class="text-left text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-4 py-2.5">Thème</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Mon éval.</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Stagiaire</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($theoSessions as $s)
                                <tr class="instr-theo-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $s['slug'] }}">
                                    <td class="px-4 py-3.5">
                                        @if($s['session_date'])
                                            <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($s['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($s['session_date'])->year }}</span>
                                        @else
                                            <span class="text-sm text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <button type="button" class="instr-theo-row-btn text-left w-full" data-slug="{{ $s['slug'] }}">
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
        <div id="instr-theo-placeholder" class="bg-white rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
            </svg>
            <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
        </div>

        @foreach($theoSessions as $s)
            @php
                $iP              = $topicProgress[$s['slug']] ?? null;
                $sP              = $traineeTopicProgress[$s['slug']] ?? null;
                $theoKeys        = array_filter($allKeys, fn($k) => $k !== 'r_securite');
                $iA              = $iP ? collect($theoKeys)->filter(fn($k) => ($iP[$k] ?? null) === '3')->count() : 0;
                $sA              = $sP ? collect($theoKeys)->filter(fn($k) => ($sP[$k] ?? null) === '3')->count() : 0;
                $maxA            = 8;
                $isInstrPratique = false;
            @endphp
            <div data-instr-theo-panel="{{ $s['slug'] }}" style="display:none" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">

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
                            <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Mon éval.</span>
                            @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                            @endif
                            <span class="text-[9px] text-slate-300 font-bold">VS</span>
                            <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Stagiaire</span>
                            @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} · {{ $sA }}/{{ $maxA }} A</span>
                            <a href="{{ route('instructor.session.edit', [$trainee, $s['slug']]) }}"
                               class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-violet-500 hover:bg-violet-50 transition-colors">
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
                                <th class="text-center text-[10px] font-semibold text-sky-500 uppercase tracking-wide px-3 py-2 w-24">Stagiaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compPoints as $gpKey => $gpGroup)
                            <tr class="border-t border-slate-100 bg-slate-50/50">
                                <td colspan="3" class="px-4 py-1.5"><span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $gpGroup['label'] }}</span></td>
                            </tr>
                            @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Note</p>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                            @endif
                        </div>
                        <div class="px-4 py-3">
                            @if($sP['session_note'] ?? null)
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Notes personnelles</p>
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
                        <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Mon évaluation</span>
                        <div class="flex items-center gap-2">
                            @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                            @endif
                            <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} A</span>
                            <a href="{{ route('instructor.session.edit', [$trainee, $s['slug']]) }}"
                               class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-violet-500 hover:bg-violet-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
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
                                    @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                        <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Auto-évaluation du stagiaire</span>
                        <div class="flex items-center gap-2">
                            @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                            @endif
                            <span class="text-xs tabular-nums text-slate-400">{{ $sA }}/{{ $maxA }} A</span>
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
                                    @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                <div class="rounded-xl border border-dashed border-slate-200 p-4">
                    <p class="text-sm text-slate-400">Le stagiaire n'a pas encore auto-évalué cette séance.</p>
                </div>
                @endif
                @endif

            </div>
        @endforeach

    </div>{{-- /subtab-seances-theorique --}}

    <div id="subtab-seances-pratique" class="seances-subtab-panel space-y-5" style="display:none">
        @php
            $pratiqueComps = \App\Models\TraineeUc3::pratiqueCompetencies();
            $pratSlugs = array_unique(array_merge(
                array_filter(array_keys($topicProgress),        fn($s) => str_starts_with($s, 'pratique_') && $hasData($topicProgress[$s])),
                array_filter(array_keys($traineeTopicProgress), fn($s) => str_starts_with($s, 'pratique_') && $hasData($traineeTopicProgress[$s]))
            ));
            $pratSessions = [];
            foreach ($pratSlugs as $slug) {
                $iP = $topicProgress[$slug] ?? null;
                $sP = $traineeTopicProgress[$slug] ?? null;
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

        <div class="flex items-baseline gap-3">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Pédagogie Pratique</h2>
            <span class="text-xs text-slate-400">Conduite de séance d'apprentissage pratique</span>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Séances pratiques</h2>
                    <p class="text-xs text-slate-400 mt-0.5">{{ count($pratSessions) }} séance{{ count($pratSessions) !== 1 ? 's' : '' }}</p>
                </div>
                <a href="{{ route('instructor.session.add', $trainee) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Nouvelle séance
                </a>
            </div>

            @if(count($pratSessions) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
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
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Mon éval.</th>
                                <th class="text-center text-[10px] font-semibold text-slate-400 uppercase tracking-wide px-3 py-2.5 w-20">Stagiaire</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pratSessions as $ps)
                                <tr class="instr-prat-row border-l-4 border-transparent hover:bg-slate-50 transition-colors cursor-pointer" data-slug="{{ $ps['slug'] }}">
                                    <td class="px-4 py-3.5">
                                        @if($ps['session_date'])
                                            <span class="text-sm text-slate-600 block leading-tight whitespace-nowrap">{{ \Carbon\Carbon::parse($ps['session_date'])->locale('fr')->isoFormat('D MMM') }}</span>
                                            <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($ps['session_date'])->year }}</span>
                                        @else
                                            <span class="text-sm text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <button type="button" class="instr-prat-row-btn text-left w-full" data-slug="{{ $ps['slug'] }}">
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
        <div id="instr-prat-placeholder" class="bg-white rounded-xl border border-slate-200 p-10 flex flex-col items-center justify-center text-slate-300">
            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
            </svg>
            <p class="text-sm">Cliquez sur une séance pour voir le détail</p>
        </div>

        @foreach($pratSessions as $ps)
            @php
                $iP              = $topicProgress[$ps['slug']] ?? null;
                $sP              = $traineeTopicProgress[$ps['slug']] ?? null;
                $iA              = $iP ? collect($allKeys)->filter(fn($k) => ($iP[$k] ?? null) === '3')->count() : 0;
                $sA              = $sP ? collect($allKeys)->filter(fn($k) => ($sP[$k] ?? null) === '3')->count() : 0;
                $maxA            = 9;
                $isInstrPratique = true;
                $pLvComp         = $pratiqueComps[$ps['level']] ?? null;
            @endphp
            <div data-instr-prat-panel="{{ $ps['slug'] }}" style="display:none" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">

                {{-- Panel header --}}
                <div>
                    <h3 class="text-base font-bold text-slate-800">{{ $ps['level'] }} · Séance {{ $ps['session_num'] }} <span class="text-slate-400 font-normal text-sm">· {{ $ps['level_label'] }}</span></h3>
                    @if($ps['session_date'])
                        <p class="text-sm text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($ps['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                    @endif
                </div>

                {{-- Evaluations --}}
                @if($iP && $sP)
                {{-- Combined table when both exist --}}
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50/70">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Mon éval.</span>
                            @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                            @endif
                            <span class="text-[9px] text-slate-300 font-bold">VS</span>
                            <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Stagiaire</span>
                            @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} · {{ $sA }}/{{ $maxA }} A</span>
                            <a href="{{ route('instructor.session.edit', [$trainee, $ps['slug']]) }}"
                               class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-violet-500 hover:bg-violet-50 transition-colors">
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
                                <th class="text-center text-[10px] font-semibold text-sky-500 uppercase tracking-wide px-3 py-2 w-24">Stagiaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compPoints as $gpKey => $gpGroup)
                            <tr class="border-t border-slate-100 bg-slate-50/50">
                                <td colspan="3" class="px-4 py-1.5"><span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $gpGroup['label'] }}</span></td>
                            </tr>
                            @foreach($gpGroup['items'] as $gpItemKey => $gpPoint)
                                @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                    @if($pLvComp && (!empty($iP['exercises_done']) || !empty($sP['exercises_done'])))
                    <div class="border-t border-slate-100 px-4 py-3">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Exercices abordés</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-violet-500 uppercase tracking-wide mb-2">Formateur</p>
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
                            <div>
                                <p class="text-[10px] font-bold text-sky-500 uppercase tracking-wide mb-2">Stagiaire</p>
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
                        </div>
                    </div>
                    @endif
                    @if(($iP['session_note'] ?? null) || ($sP['session_note'] ?? null))
                    <div class="grid grid-cols-2 divide-x divide-slate-100 border-t border-slate-200">
                        <div class="px-4 py-3">
                            @if($iP['session_note'] ?? null)
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Note</p>
                                <p class="text-sm text-slate-500 leading-relaxed">{{ $iP['session_note'] }}</p>
                            @endif
                        </div>
                        <div class="px-4 py-3">
                            @if($sP['session_note'] ?? null)
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Notes personnelles</p>
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
                        <span class="text-[10px] font-bold uppercase tracking-wide text-violet-600 bg-violet-50 border border-violet-200 rounded px-2 py-0.5">Mon évaluation</span>
                        <div class="flex items-center gap-2">
                            @if(($iP['global_rating'] ?? null) && $iP['global_rating'] !== null)
                                <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$iP['global_rating']]['badge'] }}">{{ $iP['global_rating'] }}</span>
                            @endif
                            <span class="text-xs tabular-nums text-slate-400">{{ $iA }}/{{ $maxA }} A</span>
                            <a href="{{ route('instructor.session.edit', [$trainee, $ps['slug']]) }}"
                               class="flex items-center justify-center w-7 h-7 rounded text-slate-300 hover:text-violet-500 hover:bg-violet-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
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
                                    @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                @if($sP)
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-bold uppercase tracking-wide text-sky-600 bg-sky-50 border border-sky-200 rounded px-2 py-0.5">Auto-évaluation du stagiaire</span>
                        <div class="flex items-center gap-2">
                            @if(($sP['global_rating'] ?? null) && $sP['global_rating'] !== null)
                                <span class="text-sm font-bold px-2 py-0.5 rounded {{ $notationStyles[$sP['global_rating']]['badge'] }}">{{ $sP['global_rating'] }}</span>
                            @endif
                            <span class="text-xs tabular-nums text-slate-400">{{ $sA }}/{{ $maxA }} A</span>
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
                                    @continue(($gpPoint['pratique_only'] ?? false) && !$isInstrPratique)
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
                <div class="rounded-xl border border-dashed border-slate-200 p-4">
                    <p class="text-sm text-slate-400">Le stagiaire n'a pas encore auto-évalué cette séance.</p>
                </div>
                @endif
                @endif

            </div>
        @endforeach

    </div>{{-- /subtab-seances-pratique --}}

    </div>{{-- /tab-seances --}}

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: PÉDA                                                         --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-peda" class="tab-panel">
        @include('instructor.partials.peda-tab')
    </div>{{-- /tab-peda --}}

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: EPMSP                                                        --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-epmsp" class="tab-panel space-y-5">

        <div class="flex items-center justify-between mb-1">
            <div class="flex items-baseline gap-3">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">EPMSP</h2>
                <span class="text-xs text-slate-400">Épreuves de mise en situation professionnelle</span>
            </div>
            <span class="inline-flex items-center gap-2 text-sm font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                12 mai 2026
            </span>
        </div>

        @php
            $epmspTypes = [
                '25m'       => ['title' => 'Sauvetage 25m',      'subtitle' => "Intervention sur un plongeur en difficulté"],
                'pedagogie' => ['title' => 'Pédagogie Pratique', 'subtitle' => "Conduite de séance d'apprentissage 0/20m"],
            ];
        @endphp

        @foreach($epmspTypes as $type => $meta)
            @php
                $rec           = $trainee->epmsp->firstWhere('type', $type);
                $competencies  = \App\Models\TraineeEpmsp::competencies($type);
                $ratings       = $rec?->ratings ?? [];
                $currentStatus = $rec?->status ?? 'in_progress';
            @endphp

            <div class="bg-white rounded-xl border border-slate-200 p-5">

                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-700">{{ $meta['title'] }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $meta['subtitle'] }}</p>
                    </div>
                    @if($rec && $rec->status !== 'not_started')
                        <span class="inline-flex items-center text-xs font-semibold border rounded-full px-2.5 py-1 {{ \App\Models\TraineeEpmsp::statusColor($rec->status) }}">
                            {{ \App\Models\TraineeEpmsp::statusLabel($rec->status) }}
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('instructor.epmsp.save', [$trainee, $type]) }}" class="space-y-4">
                    @csrf

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Statut</label>
                        <select name="status" class="w-52 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                            @foreach(['in_progress' => 'En préparation', 'ready' => "Prêt pour l'évaluation", 'evaluated' => 'Évalué'] as $val => $lbl)
                                <option value="{{ $val }}" {{ $currentStatus === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Competency ratings --}}
                    <div>
                        <p class="text-xs text-slate-400 mb-3">
                            <span class="text-amber-500 font-bold">★</span> requis · 1 Insuffisant · 2 Satisfaisant · 3 Maîtrisé
                        </p>
                        <div class="space-y-2">
                            @foreach($competencies as $key => $comp)
                                @php $rating = (string)($ratings[$key] ?? ''); @endphp
                                <div class="flex items-center gap-3">
                                    <div class="flex items-start gap-1 flex-1 min-w-0">
                                        @if($comp['mandatory'])
                                            <span class="text-amber-500 text-xs font-bold mt-0.5 flex-shrink-0">★</span>
                                        @else
                                            <span class="w-3 flex-shrink-0"></span>
                                        @endif
                                        <span class="text-xs text-slate-600 leading-snug">{{ $comp['label'] }}</span>
                                    </div>
                                    <div class="flex gap-1 flex-shrink-0">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="ratings[{{ $key }}]" value="1" class="sr-only peer" {{ $rating === '1' ? 'checked' : '' }}>
                                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-red-400 peer-checked:bg-red-50 hover:border-slate-300 transition-colors cursor-pointer">
                                                <span class="text-xs font-bold text-slate-400 peer-checked:text-red-600">1</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="ratings[{{ $key }}]" value="2" class="sr-only peer" {{ $rating === '2' ? 'checked' : '' }}>
                                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-amber-400 peer-checked:bg-amber-50 hover:border-slate-300 transition-colors cursor-pointer">
                                                <span class="text-xs font-bold text-slate-400 peer-checked:text-amber-600">2</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="ratings[{{ $key }}]" value="3" class="sr-only peer" {{ $rating === '3' ? 'checked' : '' }}>
                                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-emerald-400 peer-checked:bg-emerald-50 hover:border-slate-300 transition-colors cursor-pointer">
                                                <span class="text-xs font-bold text-slate-400 peer-checked:text-emerald-600">3</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer" title="Effacer">
                                            <input type="radio" name="ratings[{{ $key }}]" value="" class="sr-only peer" {{ $rating === '' ? 'checked' : '' }}>
                                            <div class="w-7 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-slate-300 peer-checked:bg-slate-100 hover:border-slate-300 transition-colors cursor-pointer">
                                                <span class="text-xs text-slate-400">—</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Notes formateur</label>
                        <textarea name="instructor_notes" rows="3"
                                  placeholder="Observations sur la préparation ou les résultats…"
                                  class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ $rec?->instructor_notes }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        @endforeach

    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- TAB: PARCOURS (Timeline)                                          --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div id="tab-parcours" class="tab-panel">
    @php
        $today        = \Carbon\Carbon::today();
        $tlEvents     = $timeline['events'];
        $tlOngoing    = $timeline['ongoing'];
        $sessionCount = collect($tlEvents)->where('type', 'session')->count();
        // Group events by month
        $grouped = [];
        foreach ($tlEvents as $event) {
            $monthKey = \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('MMMM YYYY');
            $grouped[$monthKey][] = $event;
        }
        // Rating helpers
        $ratingLabel = ['A' => 'Acquis', 'ECA' => 'En cours', 'NT' => 'Non traité'];
        $ratingColor = [
            'A'   => ['bg' => '#f0fdf4', 'border' => '#86efac', 'text' => '#15803d', 'dot' => '#22c55e'],
            'ECA' => ['bg' => '#fffbeb', 'border' => '#fcd34d', 'text' => '#92400e', 'dot' => '#f59e0b'],
            'NT'  => ['bg' => '#f8fafc', 'border' => '#cbd5e1', 'text' => '#64748b', 'dot' => '#94a3b8'],
        ];
        $levelColor = ['N1' => '#0ea5e9', 'N2' => '#8b5cf6', 'N3' => '#f97316', 'N4' => '#ec4899'];
    @endphp

        {{-- Summary bar --}}
        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
            {{-- Session count --}}
            <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:10px 16px; flex-shrink:0;">
                <svg style="width:16px;height:16px;color:#0ea5e9;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ $sessionCount }} séance{{ $sessionCount > 1 ? 's' : '' }} réalisée{{ $sessionCount > 1 ? 's' : '' }}</span>
            </div>
            {{-- Ongoing calendar tasks --}}
            @if(!empty($tlOngoing))
                <div style="flex:1; min-width:0; background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:10px 14px;">
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                        <span style="width:7px;height:7px;border-radius:50%;background:#0ea5e9;flex-shrink:0;animation:pulse 2s infinite;"></span>
                        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#0284c7;">En cours</span>
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @foreach($tlOngoing as $task)
                            @php $urgent = $task['days_left'] <= 7; @endphp
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:#1e293b;background:#fff;border:1px solid {{ $urgent ? '#fed7aa' : '#e2e8f0' }};border-radius:8px;padding:3px 10px;">
                                {{ $task['name'] }}
                                <span style="font-size:10px;font-weight:700;color:{{ $urgent ? '#ea580c' : '#64748b' }};">J-{{ $task['days_left'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>

        @if(empty($tlEvents))
            <div style="text-align:center; padding:48px 24px; color:#94a3b8;">
                <svg style="width:40px;height:40px;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/>
                </svg>
                <p style="font-size:14px;">Aucun événement à afficher</p>
            </div>
        @else
        {{-- Timeline body --}}
        <div style="position:relative; padding-left:88px;">
            {{-- Vertical line --}}
            <div style="position:absolute; left:43px; top:0; bottom:0; width:2px; background:#e2e8f0; border-radius:1px;"></div>

            @foreach($grouped as $monthKey => $events)
                {{-- Month marker --}}
                <div style="position:relative; margin-bottom:6px; margin-top:20px;">
                    <div style="position:absolute; left:-52px; width:18px; height:18px; border-radius:50%; background:#e2e8f0; border:2px solid #fff; top:1px;"></div>
                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">{{ $monthKey }}</span>
                </div>

                @foreach($events as $event)
                    @if($event['type'] === 'deadline')
                        @php
                            $isPast = $event['past'];
                            $isToday = $event['days'] === 0;
                            $isUrgent = !$isPast && $event['days'] <= 14;
                        @endphp
                        <div style="position:relative; margin-bottom:10px;">
                            {{-- Dot --}}
                            <div style="position:absolute; left:-50px; top:14px; width:14px; height:14px; border-radius:50%; background:{{ $isPast ? '#cbd5e1' : ($isUrgent ? '#ea580c' : '#0284c7') }}; border:2px solid #fff; box-shadow:0 0 0 3px {{ $isPast ? '#f1f5f9' : ($isUrgent ? '#fff7ed' : '#f0f9ff') }};"></div>
                            {{-- Date --}}
                            <div style="position:absolute; left:-86px; top:13px; width:28px; text-align:right; font-size:10px; font-weight:600; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                            </div>
                            {{-- Card --}}
                            <div style="background:{{ $isPast ? '#f8fafc' : ($isUrgent ? '#fff7ed' : '#f0f9ff') }}; border:1px solid {{ $isPast ? '#e2e8f0' : ($isUrgent ? '#fed7aa' : '#bae6fd') }}; border-radius:12px; padding:12px 16px; {{ $isPast ? 'opacity:0.75;' : '' }} display:flex; align-items:center; gap:12px;">
                                <div style="flex-shrink:0; width:32px; height:32px; border-radius:8px; background:{{ $isPast ? '#e2e8f0' : ($isUrgent ? '#ea580c' : '#0284c7') }}; display:flex; align-items:center; justify-content:center;">
                                    @if($event['icon'] === 'document')
                                        <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @elseif($event['icon'] === 'star')
                                        <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    @else
                                        <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21l1.653-3.306A9.974 9.974 0 013 12C3 6.477 7.477 2 13 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 01-5.824-1.881L3 21z" />
                                        </svg>
                                    @endif
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-size:13px; font-weight:600; color:{{ $isPast ? '#64748b' : '#1e293b' }};">{{ $event['label'] }}</div>
                                    <div style="font-size:11px; color:#94a3b8; margin-top:1px;">{{ \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
                                </div>
                                @if(!$isPast)
                                    <span style="flex-shrink:0; font-size:11px; font-weight:700; padding:3px 9px; border-radius:999px; background:{{ $isUrgent ? '#ea580c' : '#0284c7' }}; color:#fff;">
                                        {{ $event['days'] === 0 ? "Aujourd'hui" : 'J-' . $event['days'] }}
                                    </span>
                                @else
                                    <span style="flex-shrink:0; font-size:11px; font-weight:600; padding:3px 9px; border-radius:999px; background:#e2e8f0; color:#64748b;">Passé</span>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Session event --}}
                        @php
                            $r  = $event['rating'];
                            $rc = $ratingColor[$r] ?? $ratingColor['NT'];
                            $lc = $levelColor[$event['level']] ?? '#64748b';
                        @endphp
                        <div style="position:relative; margin-bottom:10px;">
                            {{-- Dot --}}
                            <div style="position:absolute; left:-50px; top:14px; width:10px; height:10px; border-radius:50%; background:{{ $rc['dot'] }}; border:2px solid #fff; box-shadow:0 0 0 2px {{ $rc['border'] }};"></div>
                            {{-- Day --}}
                            <div style="position:absolute; left:-86px; top:13px; width:28px; text-align:right; font-size:10px; font-weight:600; color:#94a3b8;">
                                {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                            </div>
                            {{-- Card --}}
                            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 16px; display:flex; align-items:flex-start; gap:10px;">
                                {{-- Left accent --}}
                                <div style="flex-shrink:0; width:3px; align-self:stretch; border-radius:2px; background:{{ $lc }}; min-height:32px;"></div>
                                <div style="flex:1; min-width:0;">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                        <span style="font-size:13px; font-weight:600; color:#1e293b;">{{ $event['label'] }}</span>
                                        @if($event['level'])
                                            <span style="font-size:10px; font-weight:700; padding:1px 7px; border-radius:999px; background:{{ $lc }}1a; color:{{ $lc }}; border:1px solid {{ $lc }}40;">{{ $event['level'] }}</span>
                                        @endif
                                    </div>
                                    @if($event['situation'])
                                        <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $event['situation'] }}</div>
                                    @endif
                                    <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
                                </div>
                                <div style="flex-shrink:0; align-self:flex-start; display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                                    @if($r)
                                        <span style="font-size:11px; font-weight:600; padding:3px 9px; border-radius:999px; background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}; border:1px solid {{ $rc['border'] }};">{{ $r }}</span>
                                    @endif
                                    <a href="{{ route('instructor.session.edit', [$trainee, $event['slug']]) }}"
                                       style="display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:#0284c7; padding:3px 9px; border-radius:999px; border:1px solid #bae6fd; background:#f0f9ff; text-decoration:none; white-space:nowrap; transition:background .15s;"
                                       onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                        <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2.414a2 2 0 01.586-1.414z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
        @endif

    </div>{{-- /tab-parcours --}}

    <div class="pb-10"></div>

</div>

<script>
(function () {
    const tabs    = document.querySelectorAll('.tab-btn');
    const panels  = document.querySelectorAll('.tab-panel');
    const active  = 'bg-white text-slate-800 shadow-sm';
    const inactive = 'text-slate-500 hover:text-slate-700';

    function activate(id) {
        tabs.forEach(btn => {
            const isActive = btn.dataset.tab === id;
            btn.classList.toggle('bg-white',    isActive);
            btn.classList.toggle('shadow-sm',   isActive);
            btn.classList.toggle('text-slate-800', isActive);
            btn.classList.toggle('text-slate-500', !isActive);
        });
        panels.forEach(panel => {
            panel.style.display = panel.id === 'tab-' + id ? '' : 'none';
        });
        history.replaceState(null, '', '#' + id);
    }

    tabs.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));

    // Restore from hash or default to first tab
    const hash = location.hash.replace('#', '');
    const validTabs = [...tabs].map(b => b.dataset.tab);
    activate(validTabs.includes(hash) ? hash : validTabs[0]);
})();

// Profil subtabs
(function () {
    const btns   = document.querySelectorAll('.subtab-btn');
    const panels = document.querySelectorAll('.subtab-panel');

    function activateSubtab(id) {
        btns.forEach(btn => {
            const on = btn.dataset.subtab === id;
            btn.classList.toggle('bg-white',      on);
            btn.classList.toggle('shadow-sm',     on);
            btn.classList.toggle('text-slate-800', on);
            btn.classList.toggle('text-slate-500', !on);
        });
        panels.forEach(p => { p.style.display = p.id === 'subtab-' + id ? '' : 'none'; });
    }

    btns.forEach(btn => btn.addEventListener('click', () => activateSubtab(btn.dataset.subtab)));
    activateSubtab('profil-info');
})();

// Séances subtabs
(function () {
    const btns   = document.querySelectorAll('.seances-subtab-btn');
    const panels = document.querySelectorAll('.seances-subtab-panel');

    function activate(id) {
        btns.forEach(btn => {
            const on = btn.dataset.subtab === id;
            btn.classList.toggle('bg-white',       on);
            btn.classList.toggle('shadow-sm',      on);
            btn.classList.toggle('text-slate-800', on);
            btn.classList.toggle('text-slate-500', !on);
        });
        panels.forEach(p => { p.style.display = p.id === 'subtab-' + id ? '' : 'none'; });
    }

    btns.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.subtab)));
    activate('seances-theorique');
})();

// Théorique sessions click-to-expand
(function () {
    const placeholder = document.getElementById('instr-theo-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-instr-theo-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.instr-theo-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-instr-theo-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.instr-theo-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.instr-theo-row-btn, .instr-theo-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();

// Pratique sessions click-to-expand
(function () {
    const placeholder = document.getElementById('instr-prat-placeholder');
    if (!placeholder) return;
    function show(slug) {
        document.querySelectorAll('[data-instr-prat-panel]').forEach(p => { p.style.display = 'none'; });
        document.querySelectorAll('.instr-prat-row').forEach(r => { r.style.borderLeftColor = ''; r.style.backgroundColor = ''; });
        if (slug) {
            placeholder.style.display = 'none';
            const panel = document.querySelector('[data-instr-prat-panel="' + slug + '"]');
            if (panel) panel.style.display = '';
            const row = document.querySelector('.instr-prat-row[data-slug="' + slug + '"]');
            if (row) { row.style.borderLeftColor = '#8b5cf6'; row.style.backgroundColor = '#faf5ff'; }
        } else { placeholder.style.display = ''; }
    }
    document.querySelectorAll('.instr-prat-row-btn, .instr-prat-row').forEach(el => {
        el.addEventListener('click', function (e) { if (e.target.closest('a')) return; show(this.dataset.slug); });
    });
    show('');
})();

// Peda subtabs
(function () {
    const btns   = document.querySelectorAll('.peda-subtab-btn');
    const panels = document.querySelectorAll('.peda-subtab-panel');
    function activate(id) {
        btns.forEach(btn => {
            const on = btn.dataset.subtab === id;
            btn.classList.toggle('bg-white',       on);
            btn.classList.toggle('shadow-sm',      on);
            btn.classList.toggle('text-slate-800', on);
            btn.classList.toggle('text-slate-500', !on);
        });
        panels.forEach(p => { p.style.display = p.id === 'subtab-' + id ? '' : 'none'; });
    }
    btns.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.subtab)));
    activate('peda-pratique');
})();

// Peda manual override modal
const pedaModal = document.getElementById('peda-override-modal');

function openPedaOverride(level, currentStatus) {
    document.getElementById('peda-override-level').value = level;
    document.getElementById('peda-override-status').value = currentStatus;
    const levelLabels = { bapteme: 'Baptême', n1: 'N1', n2: 'N2', n3: 'N3' };
    document.getElementById('peda-override-level-label').textContent = 'Niveau : ' + (levelLabels[level] || level);

    document.querySelectorAll('.peda-override-option').forEach(opt => {
        const st = opt.dataset.status;
        const isActive = st === currentStatus;
        opt.style.borderColor    = isActive ? '#8b5cf6' : '';
        opt.style.backgroundColor = isActive ? '#f5f3ff' : '';
        const radio = opt.querySelector('.peda-override-radio');
        radio.style.borderColor     = isActive ? '#8b5cf6' : '';
        radio.style.backgroundColor = isActive ? '#8b5cf6' : '';
    });

    pedaModal.style.removeProperty('display');
    pedaModal.style.display = 'flex';
}

function closePedaOverride() {
    pedaModal.style.display = 'none';
}

document.querySelectorAll('.peda-override-option').forEach(opt => {
    opt.addEventListener('click', function () {
        const st = this.dataset.status;
        document.getElementById('peda-override-status').value = st;
        document.querySelectorAll('.peda-override-option').forEach(o => {
            const on = o.dataset.status === st;
            o.style.borderColor     = on ? '#8b5cf6' : '';
            o.style.backgroundColor = on ? '#f5f3ff' : '';
            const r = o.querySelector('.peda-override-radio');
            r.style.borderColor     = on ? '#8b5cf6' : '';
            r.style.backgroundColor = on ? '#8b5cf6' : '';
        });
    });
});

if (pedaModal) {
    pedaModal.addEventListener('click', function (e) {
        if (e.target === pedaModal) closePedaOverride();
    });
}

// ── Feedback inline edit ─────────────────────────────────────────────────
function feedbackBlock(id) {
    return document.querySelector('[data-feedback-id="' + id + '"]');
}

function openFeedbackEdit(id) {
    const block = feedbackBlock(id);
    block.querySelector('.feedback-read-view').classList.add('hidden');
    block.querySelector('.feedback-edit-view').classList.remove('hidden');
    block.querySelector('textarea').focus();
}

function cancelFeedbackEdit(id) {
    const block = feedbackBlock(id);
    const ta = block.querySelector('textarea');
    ta.value = ta.dataset.original;
    block.querySelector('.feedback-edit-view').classList.add('hidden');
    block.querySelector('.feedback-read-view').classList.remove('hidden');
    block.querySelector('.feedback-save-error').classList.add('hidden');
}

function saveFeedbackEdit(id, url) {
    const block    = feedbackBlock(id);
    const textarea = block.querySelector('textarea');
    const errEl    = block.querySelector('.feedback-save-error');
    const text     = textarea.value.trim();

    if (!text) return;

    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ feedback_text: text }),
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(() => {
        // Update the read view text and the textarea's original value
        block.querySelector('.feedback-read-view p:last-child').textContent = text;
        textarea.dataset.original = text;
        errEl.classList.add('hidden');
        block.querySelector('.feedback-edit-view').classList.add('hidden');
        block.querySelector('.feedback-read-view').classList.remove('hidden');
    })
    .catch(() => errEl.classList.remove('hidden'));
}

</script>

@endsection
