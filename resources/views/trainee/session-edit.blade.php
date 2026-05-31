@extends('layouts.app')
@section('title', 'Modifier l\'auto-évaluation')

@section('content')
@php
    $compPoints  = \App\Models\TraineeUc3::competencyPoints();
    $levelColors = [
        'N1' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
        'N2' => 'bg-sky-50 text-sky-600 border-sky-200',
        'N3' => 'bg-violet-50 text-violet-600 border-violet-200',
        'N4' => 'bg-amber-50 text-amber-600 border-amber-200',
    ];
@endphp

<div class="max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('trainee.dashboard') }}#seances"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour au tableau de bord
    </a>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-slate-800">Auto-évaluation</p>
            <p class="text-xs text-slate-400 truncate">{{ $sessionLabel }}</p>
        </div>
        @if($sessionLevel && isset($levelColors[$sessionLevel]))
            <span class="text-[10px] font-semibold border rounded px-1.5 py-0.5 flex-shrink-0 {{ $levelColors[$sessionLevel] }}">{{ $sessionLevel }}</span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('trainee.seances.save') }}">
        @csrf
        <input type="hidden" name="slug"          value="{{ $slug }}">
        <input type="hidden" name="session_label" value="{{ $session['session_label'] ?? $sessionLabel }}">
        <input type="hidden" name="session_level" value="{{ $session['session_level'] ?? $sessionLevel }}">

        {{-- Date --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4 mb-4">
            <label for="session-date" class="block text-xs font-medium text-slate-600 mb-1.5">Date de la séance</label>
            <input type="date" id="session-date" name="session_date"
                   value="{{ $session['session_date'] ?? date('Y-m-d') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 bg-white">
        </div>

        {{-- Note globale --}}
        @php $globalRating = $session['global_rating'] ?? '1'; @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-slate-700 mb-4">Note globale de la séance</h2>
            <div class="flex gap-3 mb-4">
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="global_rating" value="1" {{ (!$globalRating || $globalRating === '1') ? 'checked' : '' }} class="sr-only">
                    <span class="flex items-center justify-center py-3 text-sm font-bold rounded-lg border-2 border-slate-200 text-slate-500 bg-slate-50 transition-colors">1</span>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="global_rating" value="2" {{ $globalRating === '2' ? 'checked' : '' }} class="sr-only">
                    <span class="flex items-center justify-center py-3 text-sm font-bold rounded-lg border-2 border-amber-200 text-amber-500 bg-amber-50 transition-colors">2</span>
                </label>
                <label class="cursor-pointer flex-1">
                    <input type="radio" name="global_rating" value="3" {{ $globalRating === '3' ? 'checked' : '' }} class="sr-only">
                    <span class="flex items-center justify-center py-3 text-sm font-bold rounded-lg border-2 border-emerald-200 text-emerald-500 bg-emerald-50 transition-colors">3</span>
                </label>
            </div>
            <input type="text" name="global_comment"
                   value="{{ $session['global_comment'] ?? '' }}"
                   placeholder="Commentaire global sur la séance…"
                   class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-400">
        </div>

        {{-- Compétences détaillées (same for both types) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-4 space-y-6">
            <h2 class="text-base font-semibold text-slate-700">Détail par compétence</h2>
            @foreach($compPoints as $groupKey => $group)
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">{{ $group['label'] }}</p>
                <div class="space-y-4">
                    @foreach($group['items'] as $key => $point)
                    @continue(($point['pratique_only'] ?? false) && !$isPratique)
                    @php $notation = in_array($session[$key] ?? null, ['3', '2']) ? $session[$key] : '1'; @endphp
                    <div>
                        <p class="text-sm text-slate-600 leading-snug mb-2">{{ $point['label'] }}</p>
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1.5 flex-shrink-0">
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="1" {{ $notation === '1' ? 'checked' : '' }} class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-slate-200 text-slate-500 bg-slate-50 transition-colors">1</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="2" {{ $notation === '2' ? 'checked' : '' }} class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-amber-200 text-amber-500 bg-amber-50 transition-colors">2</span>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="notations[{{ $key }}]" value="3" {{ $notation === '3' ? 'checked' : '' }} class="sr-only">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded border-2 border-emerald-200 text-emerald-500 bg-emerald-50 transition-colors">3</span>
                                </label>
                            </div>
                            <input type="text" name="notes[{{ $key }}]"
                                   value="{{ $session[$key . '_note'] ?? '' }}"
                                   placeholder="Commentaire…"
                                   class="flex-1 min-w-0 text-sm rounded border border-slate-200 px-3 py-1.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-1 focus:ring-violet-400">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Exercices pratiques (pratique sessions only) --}}
        @if($isPratique && $pratiqueComp)
        @php $exercisesDone = $session['exercises_done'] ?? []; @endphp
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-slate-700 mb-4">Exercices pratiques abordés</h2>
            <div class="space-y-2.5">
                @foreach($pratiqueComp['keys'] as $exKey => $exLabel)
                <label class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" name="exercises[]" value="{{ $exKey }}"
                           {{ in_array($exKey, $exercisesDone) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 accent-sky-600 cursor-pointer">
                    <span class="text-sm text-slate-600 group-hover:text-slate-800 leading-snug">{{ $exLabel }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notes de séance --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-6">
            <h2 class="text-base font-semibold text-slate-700 mb-3">Notes personnelles</h2>
            <textarea name="session_note" rows="3" placeholder="Vos observations sur cette séance…"
                class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2.5 text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-violet-400 resize-none">{{ $session['session_note'] ?? '' }}</textarea>
        </div>

        <button type="submit"
            class="w-full py-3 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            Enregistrer les modifications
        </button>
    </form>

    <form method="POST" action="{{ route('trainee.seances.delete', $slug) }}"
          class="mt-3"
          onsubmit="return confirm('Supprimer cette séance ? Cette action est irréversible.')">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="w-full py-3 text-sm font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl border border-red-200 transition-colors">
            Supprimer l'auto-évaluation
        </button>
    </form>

</div>

<script>
(function () {
    var RATING_ON  = { '1': ['#475569','#f1f5f9'], '2': ['#d97706','#fef3c7'], '3': ['#059669','#d1fae5'] };
    var RATING_OFF = { '1': ['#e2e8f0','#f8fafc'], '2': ['#fde68a','#fffbeb'], '3': ['#a7f3d0','#ecfdf5'] };

    function refreshRatingGroup(name) {
        document.querySelectorAll('input[type="radio"][name="' + name + '"]').forEach(function(radio) {
            var span = radio.nextElementSibling;
            var s = radio.checked ? RATING_ON[radio.value] : RATING_OFF[radio.value];
            if (s && span) { span.style.borderColor = s[0]; span.style.backgroundColor = s[1]; }
        });
    }

    function refreshAllRatings() {
        var seen = {};
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            if (!seen[radio.name]) { seen[radio.name] = true; refreshRatingGroup(radio.name); }
        });
    }

    document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() { refreshRatingGroup(this.name); });
    });

    refreshAllRatings();
})();
</script>
@endsection
