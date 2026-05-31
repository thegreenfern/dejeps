@extends('layouts.app')
@section('title', 'Onboarding — Terminé')

@section('content')
<x-wizard-progress :current="4" />

<div class="max-w-2xl mx-auto">

    {{-- Profile card --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6 flex items-center gap-6">
        {{-- Photo --}}
        <div class="flex-shrink-0">
            @if($trainee->photo_path)
                <img src="{{ Storage::url($trainee->photo_path) }}"
                     class="w-20 h-20 rounded-full object-cover border-2 border-slate-100 shadow-sm">
            @else
                <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg class="w-9 h-9 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-xl font-bold text-slate-800">{{ $trainee->name }}</h1>
                <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Dossier complété
                </span>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-1 mt-2">
                @if($trainee->date_of_birth)
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $trainee->date_of_birth->format('d/m/Y') }}
                    </div>
                @endif
                @if($trainee->email)
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 truncate">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="truncate">{{ $trainee->email }}</span>
                    </div>
                @endif
                @if($trainee->phone)
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $trainee->phone }}
                    </div>
                @endif
                @if($trainee->cv_path)
                    <div class="flex items-center gap-1.5 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <a href="{{ Storage::url($trainee->cv_path) }}" target="_blank" class="hover:text-sky-600 transition-colors">CV déposé</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Auto-évaluation recap ──────────────────────────────────────── --}}
    @if($assessments->isNotEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-1">
                Récapitulatif — Auto-évaluation des compétences
            </h2>
            <p class="text-xs text-slate-400 mb-6">A = aucune notion · B = avec aide · C = autonome</p>

            <div class="space-y-3">
                @foreach($assessments as $category => $items)
                    @php
                        // A=0%, B=50%, C=100% — average across all items in the category
                        $catScore = $items->avg(fn($i) => match((int)$i->trainee_score) {
                            1 => 0, 2 => 50, 3 => 100, default => 0,
                        });
                        $catScore = round($catScore);
                        $barColor = $catScore >= 66 ? 'bg-emerald-400'
                                  : ($catScore >= 34 ? 'bg-amber-400'
                                  : 'bg-red-400');
                        $grade = $catScore >= 66 ? 'C' : ($catScore >= 34 ? 'B' : 'A');
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-52 flex-shrink-0 leading-tight">{{ $category }}</span>
                        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $barColor }} rounded-full transition-all"
                                 style="width: {{ $catScore }}%"></div>
                        </div>
                        <span class="text-xs font-bold w-6 text-right flex-shrink-0
                            {{ $catScore >= 66 ? 'text-emerald-600' : ($catScore >= 34 ? 'text-amber-600' : 'text-red-500') }}">
                            {{ $grade }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Big Five recap ──────────────────────────────────────────────── --}}
    @if($trainee->profile?->big5_scores)
        @php
            $scores = $trainee->profile->big5_scores;
            $traits = [
                'O' => ['Ouverture',         'bg-sky-400'],
                'C' => ['Conscienciosité',   'bg-violet-400'],
                'E' => ['Extraversion',      'bg-amber-400'],
                'A' => ['Agréabilité',       'bg-emerald-400'],
                'N' => ['Stabilité émo.',    'bg-rose-400'],
            ];
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-5">
                Profil de personnalité
            </h2>
            <p class="text-xs text-slate-400 mb-5">Ces cinq dimensions (modèle OCEAN) donnent un aperçu de vos tendances naturelles. Il n'y a pas de profil idéal.</p>

            <div class="space-y-3">
                @foreach($traits as $key => [$label, $bar])
                    @php
                        $raw  = $scores[$key] ?? 50;
                        $disp = $key === 'N' ? (100 - $raw) : $raw;
                        $band = $disp < 35 ? 'Faible' : ($disp < 65 ? 'Modéré' : 'Élevé');
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-28 flex-shrink-0">{{ $label }}</span>
                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $bar }} rounded-full" style="width: {{ $disp }}%"></div>
                        </div>
                        <span class="text-xs text-slate-400 w-14 text-right flex-shrink-0">{{ $band }} · {{ $disp }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Next step --}}
    <div class="bg-sky-50 rounded-xl border border-sky-200 px-6 py-5 text-sm text-sky-700 mb-8">
        <p class="font-semibold mb-1">Prochaine étape</p>
        <p>Votre formateur va maintenant compléter sa contre-évaluation et fixer avec vous des objectifs de formation pour les prochaines séances.</p>
    </div>

    <div class="flex justify-center">
        <a href="{{ route('home') }}"
           class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition-colors">
            Retour à l'accueil
        </a>
    </div>

</div>
@endsection
