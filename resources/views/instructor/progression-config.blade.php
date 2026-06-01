@extends('layouts.app')
@section('title', 'Configuration – Progression pédagogique')

@section('content')
@php
    $transitions = [
        ['key' => 'obs_sd',   'global_key' => 'threshold_obs_sd',   'trainee_key' => 'peda_threshold_obs_sd',
         'from' => 'Observation', 'to' => 'Supervision directe',
         'from_color' => '#d97706', 'to_color' => '#ea580c',
         'from_bg' => 'bg-amber-50', 'to_bg' => 'bg-orange-50',
         'from_text' => 'text-amber-700', 'to_text' => 'text-orange-700',
         'from_border' => 'border-amber-200', 'to_border' => 'border-orange-200'],
        ['key' => 'sd_si',    'global_key' => 'threshold_sd_si',    'trainee_key' => 'peda_threshold_sd_si',
         'from' => 'Supervision directe', 'to' => 'Supervision indirecte',
         'from_color' => '#ea580c', 'to_color' => '#7c3aed',
         'from_bg' => 'bg-orange-50', 'to_bg' => 'bg-violet-50',
         'from_text' => 'text-orange-700', 'to_text' => 'text-violet-700',
         'from_border' => 'border-orange-200', 'to_border' => 'border-violet-200'],
        ['key' => 'si_auto',  'global_key' => 'threshold_si_auto',  'trainee_key' => 'peda_threshold_si_auto',
         'from' => 'Supervision indirecte', 'to' => 'Autonomie',
         'from_color' => '#7c3aed', 'to_color' => '#059669',
         'from_bg' => 'bg-violet-50', 'to_bg' => 'bg-emerald-50',
         'from_text' => 'text-violet-700', 'to_text' => 'text-emerald-700',
         'from_border' => 'border-violet-200', 'to_border' => 'border-emerald-200'],
    ];
@endphp

<div class="max-w-3xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('instructor.dashboard') }}"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Tableau de bord
    </a>

    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-xl font-bold text-slate-800 leading-tight">Progression pédagogique</h1>
        <p class="text-sm text-slate-400 mt-1">
            Nombre de séances notées <span class="font-semibold text-emerald-600">A</span> nécessaires pour passer à la phase suivante.
            Les seuils par défaut s'appliquent à tous les stagiaires, sauf si vous les personnalisez individuellement.
        </p>
    </div>

    {{-- ── Section 1: Global defaults ──────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">
        <div class="px-6 pt-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Seuils par défaut</h2>
                <span class="text-xs text-slate-400 font-normal">appliqués à tous les stagiaires sans personnalisation</span>
            </div>
        </div>

        <form method="POST" action="{{ route('instructor.progression-config.save') }}" class="p-6">
            @csrf

            <div class="flex flex-col items-center gap-4">
                @foreach($transitions as $t)
                @php $val = (int) ($settings->{$t['global_key']} ?? 2); @endphp
                <div class="flex items-center justify-center gap-4">
                    {{-- Transition label --}}
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $t['from_bg'] }} {{ $t['from_text'] }} {{ $t['from_border'] }}">
                            {{ $t['from'] }}
                        </span>
                        <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $t['to_bg'] }} {{ $t['to_text'] }} {{ $t['to_border'] }}">
                            {{ $t['to'] }}
                        </span>
                    </div>

                    {{-- Stepper --}}
                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="stepValue('global_{{ $t['key'] }}', -1)"
                                class="w-7 h-7 rounded-full border border-slate-200 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:border-slate-300 transition-colors flex items-center justify-center text-base font-bold leading-none">−</button>
                        <input type="number" name="{{ $t['global_key'] }}" id="global_{{ $t['key'] }}"
                               value="{{ $val }}" min="1" max="10" required
                               class="w-14 text-center text-lg font-bold text-slate-800 rounded-xl border-2 border-slate-200 py-1.5 focus:outline-none focus:border-sky-400 focus:ring-0 tabular-nums">
                        <button type="button"
                                onclick="stepValue('global_{{ $t['key'] }}', 1)"
                                class="w-7 h-7 rounded-full border border-slate-200 bg-slate-50 text-slate-500 hover:bg-slate-100 hover:border-slate-300 transition-colors flex items-center justify-center text-base font-bold leading-none">+</button>
                        <span class="text-xs text-slate-400">séances&nbsp;A</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-6 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-lg transition-colors">
                    Enregistrer les seuils par défaut
                </button>
            </div>
        </form>
    </div>

    {{-- ── Section 2: Per-trainee overrides ─────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 pt-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Personnalisation par stagiaire</h2>
                <span class="text-xs text-slate-400 font-normal">laisser vide = utiliser le seuil par défaut</span>
            </div>
        </div>

        {{-- Trainee cards --}}
        <div class="divide-y divide-slate-100">
            @forelse($trainees as $trainee)
            @php
                $profile  = $trainee->profile;
                $isCustom = $profile && (
                    $profile->peda_threshold_obs_sd  !== null ||
                    $profile->peda_threshold_sd_si   !== null ||
                    $profile->peda_threshold_si_auto !== null
                );
            @endphp
            <form method="POST"
                  action="{{ route('instructor.progression-config.trainee.save', $trainee) }}"
                  class="px-6 py-4 hover:bg-slate-50/60 transition-colors group">
                @csrf

                {{-- Trainee name row --}}
                <div class="flex items-center gap-2 mb-3">
                    @if($trainee->photo_path)
                        <img src="{{ Storage::url($trainee->photo_path) }}"
                             class="w-6 h-6 rounded-full object-cover border border-slate-200 flex-shrink-0">
                    @else
                        <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                    <span class="text-sm font-semibold text-slate-700 leading-tight">{{ $trainee->name }}</span>
                    @if($isCustom)
                        <span class="text-[10px] font-medium text-sky-500 bg-sky-50 border border-sky-200 rounded-full px-2 py-0.5">personnalisé</span>
                    @else
                        <span class="text-[10px] text-slate-400">par défaut</span>
                    @endif
                </div>

                {{-- Thresholds + save on one centered line --}}
                <div class="flex items-center justify-center gap-5 flex-wrap">
                    @foreach($transitions as $t)
                    @php
                        $current     = $profile?->{$t['trainee_key']};
                        $placeholder = (string)($settings->{$t['global_key']} ?? 2);
                        $isSet       = $current !== null;
                    @endphp
                    <div class="flex items-center gap-2">
                        {{-- Transition badges --}}
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md border text-[10px] font-semibold {{ $t['from_bg'] }} {{ $t['from_text'] }} {{ $t['from_border'] }}">
                            {{ $t['from'] }}
                        </span>
                        <svg class="w-3 h-3 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md border text-[10px] font-semibold {{ $t['to_bg'] }} {{ $t['to_text'] }} {{ $t['to_border'] }}">
                            {{ $t['to'] }}
                        </span>
                        {{-- Input + clear --}}
                        <div class="flex flex-col items-center gap-0.5">
                            <input type="number"
                                   name="{{ $t['trainee_key'] }}"
                                   value="{{ $current }}"
                                   min="1" max="10"
                                   placeholder="{{ $placeholder }}"
                                   class="w-12 text-center text-sm font-bold rounded-lg border-2 py-1 tabular-nums focus:outline-none transition-colors
                                          {{ $isSet
                                              ? 'border-sky-300 text-sky-700 bg-sky-50 focus:border-sky-400'
                                              : 'border-slate-200 text-slate-400 bg-white focus:border-sky-300' }}">
                            @if($isSet)
                            <button type="button" onclick="clearTraineeInput(this)"
                                    class="text-[9px] text-slate-400 hover:text-red-500 transition-colors leading-none">effacer</button>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                    <div class="w-px h-5 bg-slate-200"></div>
                    @endif
                    @endforeach

                    <button type="submit"
                            class="px-3 py-1.5 text-xs font-semibold text-slate-500 bg-white border border-slate-200 rounded-lg
                                   hover:border-sky-300 hover:text-sky-600 hover:bg-sky-50 transition-colors
                                   opacity-0 group-hover:opacity-100 ml-2">
                        Sauver
                    </button>
                </div>
            </form>
            @empty
            <div class="px-6 py-10 text-center text-sm text-slate-400">
                Aucun stagiaire ayant complété l'onboarding.
            </div>
            @endforelse
        </div>
    </div>

    <p class="text-xs text-slate-400 mt-4 text-center">
        Les changements prennent effet immédiatement lors du prochain calcul de progression.
    </p>

</div>

<script>
function stepValue(id, delta) {
    var el = document.getElementById(id);
    if (!el) return;
    var v = parseInt(el.value) || parseInt(el.min) || 1;
    v = Math.min(Math.max(v + delta, parseInt(el.min) || 1), parseInt(el.max) || 10);
    el.value = v;
}

function clearTraineeInput(btn) {
    var cell  = btn.closest('div');
    var input = cell.querySelector('input[type="number"]');
    if (!input) return;
    input.value = '';
    input.placeholder = input.placeholder; // keep placeholder
    input.className = input.className
        .replace('border-sky-300 text-sky-700 bg-sky-50 focus:border-sky-400',
                 'border-slate-200 text-slate-400 bg-white focus:border-sky-300');
    btn.remove();
}
</script>

@endsection
