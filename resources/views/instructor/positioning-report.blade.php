@extends('layouts.app')
@section('title', 'Rapport de positionnement · ' . $trainee->name)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Nav --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('instructor.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Tableau de bord
        </a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('instructor.positioning', $trainee) }}"
           class="text-xs text-slate-400 hover:text-slate-600 transition-colors">
            Contre-évaluation
        </a>
    </div>

    {{-- Trainee card --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 mb-8 flex items-center gap-5">
        @if($trainee->photo_path)
            <img src="{{ Storage::url($trainee->photo_path) }}"
                 class="w-14 h-14 rounded-full object-cover border border-slate-200 flex-shrink-0">
        @else
            <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif
        <div class="flex-1">
            <h1 class="text-lg font-bold text-slate-800">{{ $trainee->name }}</h1>
            @if($trainee->email)
                <p class="text-xs text-slate-400 mt-0.5">{{ $trainee->email }}</p>
            @endif
        </div>
        <div>
            <a href="{{ route('instructor.positioning', $trainee) }}"
               class="text-xs px-3 py-1.5 rounded-lg border border-slate-300 text-slate-600 hover:border-violet-400 hover:text-violet-700 hover:bg-violet-50 transition-colors">
                Modifier
            </a>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-6 mb-6 text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-sky-400"></div>
            <span>Auto-évaluation stagiaire</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-violet-400"></div>
            <span>Contre-évaluation formateur</span>
        </div>
        <div class="ml-auto flex items-center gap-4 text-slate-400">
            <span>A = 0%</span>
            <span>B = 50%</span>
            <span>C = 100%</span>
        </div>
    </div>

    {{-- Per-category comparison --}}
    <div class="space-y-6">
        @foreach($assessments as $category => $items)
            @php
                $traineeAvg = $items->whereNotNull('trainee_score')->avg(fn($i) => match((int)$i->trainee_score) {
                    1 => 0, 2 => 50, 3 => 100, default => 0,
                });
                $tutorAvg = $items->whereNotNull('tutor_score')->avg(fn($i) => match((int)$i->tutor_score) {
                    1 => 0, 2 => 50, 3 => 100, default => 0,
                });
                $traineeAvg = round($traineeAvg ?? 0);
                $tutorAvg   = round($tutorAvg ?? 0);
                $gap        = abs($traineeAvg - $tutorAvg);

                $traineeGrade = $traineeAvg >= 66 ? 'C' : ($traineeAvg >= 34 ? 'B' : 'A');
                $tutorGrade   = $tutorAvg   >= 66 ? 'C' : ($tutorAvg   >= 34 ? 'B' : 'A');
            @endphp

            <div class="bg-white rounded-xl border {{ $gap >= 30 ? 'border-amber-300' : 'border-slate-200' }} p-5">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-slate-700">{{ $category }}</h2>
                    <div class="flex items-center gap-3">
                        @if($gap >= 30)
                            <span class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2 py-0.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Écart significatif
                            </span>
                        @endif
                        <span class="text-xs text-slate-400">{{ $items->count() }} compétences</span>
                    </div>
                </div>

                {{-- Stagiaire bar --}}
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs text-slate-500 w-32 flex-shrink-0">Stagiaire</span>
                    <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-sky-400 rounded-full transition-all" style="width: {{ $traineeAvg }}%"></div>
                    </div>
                    <span class="text-xs font-bold w-10 text-right flex-shrink-0
                        {{ $traineeAvg >= 66 ? 'text-emerald-600' : ($traineeAvg >= 34 ? 'text-amber-600' : 'text-red-500') }}">
                        {{ $traineeGrade }} · {{ $traineeAvg }}
                    </span>
                </div>

                {{-- Formateur bar --}}
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-500 w-32 flex-shrink-0">Formateur</span>
                    <div class="flex-1 h-4 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-violet-400 rounded-full transition-all" style="width: {{ $tutorAvg }}%"></div>
                    </div>
                    <span class="text-xs font-bold w-10 text-right flex-shrink-0
                        {{ $tutorAvg >= 66 ? 'text-emerald-600' : ($tutorAvg >= 34 ? 'text-amber-600' : 'text-red-500') }}">
                        {{ $tutorGrade }} · {{ $tutorAvg }}
                    </span>
                </div>

                {{-- Divergences detail (only when gap ≥ 30) --}}
                @if($gap >= 30)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wide">Détail des écarts</p>
                        <div class="space-y-1.5">
                            @foreach($items as $item)
                                @php
                                    $ts = (int)($item->trainee_score ?? 0);
                                    $fs = (int)($item->tutor_score   ?? 0);
                                    $itemGap = abs($ts - $fs);
                                    if ($itemGap < 1) continue;
                                    $traineeLabel = match($ts) { 1 => 'A', 2 => 'B', 3 => 'C', default => '—' };
                                    $tutorLabel   = match($fs) { 1 => 'A', 2 => 'B', 3 => 'C', default => '—' };
                                @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="flex-1 text-slate-600 truncate">{{ $item->competency->label }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-bold
                                        {{ $ts === 1 ? 'bg-red-50 text-red-600' : ($ts === 2 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                        {{ $traineeLabel }}
                                    </span>
                                    <svg class="w-3 h-3 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-bold
                                        {{ $fs === 1 ? 'bg-red-50 text-red-600' : ($fs === 2 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                        {{ $tutorLabel }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Full competency table --}}
    <div class="mt-10 bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-semibold text-slate-700">Tableau complet</h2>
            <p class="text-xs text-slate-400 mt-0.5">Toutes les compétences avec notes du formateur</p>
        </div>

        @foreach($assessments as $category => $items)
            <div class="border-b border-slate-100 last:border-b-0">
                <div class="px-6 py-2 bg-slate-50">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">{{ $category }}</span>
                </div>
                @foreach($items as $item)
                    @php
                        $ts = (int)($item->trainee_score ?? 0);
                        $fs = (int)($item->tutor_score   ?? 0);
                        $tLabel = match($ts) { 1 => 'A', 2 => 'B', 3 => 'C', default => '—' };
                        $fLabel = match($fs) { 1 => 'A', 2 => 'B', 3 => 'C', default => '—' };
                    @endphp
                    <div class="px-6 py-3 flex items-start gap-4 border-t border-slate-50">
                        <p class="flex-1 text-xs text-slate-700 leading-snug">{{ $item->competency->label }}</p>

                        {{-- Trainee score --}}
                        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-full text-xs font-bold
                            {{ $ts === 1 ? 'bg-red-50 text-red-600 border border-red-200' : ($ts === 2 ? 'bg-amber-50 text-amber-600 border border-amber-200' : ($ts === 3 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400')) }}">
                            {{ $tLabel }}
                        </span>

                        {{-- Arrow --}}
                        <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0 mt-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>

                        {{-- Tutor score --}}
                        <span class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-full text-xs font-bold
                            {{ $fs === 1 ? 'bg-red-50 text-red-600 border border-red-200' : ($fs === 2 ? 'bg-amber-50 text-amber-600 border border-amber-200' : ($fs === 3 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-slate-100 text-slate-400')) }}">
                            {{ $fLabel }}
                        </span>

                        {{-- Notes --}}
                        @if($item->tutor_notes)
                            <div class="w-48 flex-shrink-0">
                                <p class="text-xs text-slate-400 italic leading-snug">{{ $item->tutor_notes }}</p>
                            </div>
                        @else
                            <div class="w-48 flex-shrink-0"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="mt-8 pb-10 flex justify-center">
        <a href="{{ route('instructor.dashboard') }}"
           class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition-colors">
            Retour au tableau de bord
        </a>
    </div>

</div>
@endsection
