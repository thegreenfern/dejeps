@extends('layouts.app')
@section('title', 'Onboarding — Test de personnalité')

@section('content')
<x-wizard-progress :current="2" />

<div class="max-w-2xl mx-auto">
    <div class="flex items-start justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Test de personnalité</h1>
        <button type="button" id="randomize-btn"
                class="text-xs text-slate-400 hover:text-slate-600 border border-slate-200 hover:border-slate-300 rounded-lg px-3 py-1.5 transition-colors flex-shrink-0 mt-1">
            Réponses aléatoires
        </button>
    </div>
    <p class="text-slate-500 mb-2">
        Ce questionnaire mesure cinq grandes dimensions de la personnalité (modèle OCEAN).
        Il n'y a pas de bonnes ou mauvaises réponses — répondez spontanément.
    </p>
    <p class="text-xs text-slate-400 mb-8">{{ count($questions) }} questions · environ 10 minutes</p>

    <form method="POST" action="{{ route('onboarding.step2.save') }}" id="big5-form">
        @csrf

        {{-- Likert scale legend --}}
        <div class="hidden sm:grid grid-cols-5 gap-1 text-center text-xs text-slate-400 mb-4 px-1">
            <span>Pas du tout<br>d'accord</span>
            <span>Plutôt pas<br>d'accord</span>
            <span>Neutre</span>
            <span>Plutôt<br>d'accord</span>
            <span>Tout à fait<br>d'accord</span>
        </div>

        <div class="space-y-2" id="questions-list">
            @foreach($questions as $i => $q)
                <div class="bg-white rounded-lg border border-slate-200 px-4 py-3
                            @error("responses.{$i}") border-red-400 @enderror"
                     data-index="{{ $i }}">
                    <p class="text-sm text-slate-700 mb-3">
                        <span class="text-slate-400 text-xs mr-1">{{ $i + 1 }}.</span>
                        {{ $q['text'] }}
                    </p>
                    <div class="grid grid-cols-5 gap-1">
                        @for($v = 1; $v <= 5; $v++)
                            <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                <input type="radio"
                                       name="responses[{{ $i }}]"
                                       value="{{ $v }}"
                                       class="sr-only peer"
                                       {{ old("responses.{$i}") == $v ? 'checked' : '' }}
                                       required>
                                <span class="w-8 h-8 rounded-full border-2 border-slate-200 flex items-center justify-center
                                             text-xs font-medium text-slate-400
                                             peer-checked:border-sky-500 peer-checked:bg-sky-500 peer-checked:text-white
                                             group-hover:border-sky-300 transition-colors">
                                    {{ $v }}
                                </span>
                                <span class="sm:hidden text-xs text-slate-400 leading-tight text-center">
                                    @switch($v)
                                        @case(1) Pas du tout @break
                                        @case(2) Plutôt non @break
                                        @case(3) Neutre @break
                                        @case(4) Plutôt oui @break
                                        @case(5) Tout à fait @break
                                    @endswitch
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

        @if($errors->has('responses') || $errors->hasAny(array_map(fn($i) => "responses.{$i}", range(0, 119))))
            <p class="mt-4 text-sm text-red-600">Veuillez répondre à toutes les questions avant de continuer.</p>
        @endif

        <div class="flex justify-between mt-8">
            <a href="{{ route('onboarding.step1') }}"
               class="px-5 py-2.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
                ← Retour
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Continuer →
            </button>
        </div>
    </form>
</div>

<script>
const total = {{ count($questions) }};

// Randomize all answers
document.getElementById('randomize-btn').addEventListener('click', function() {
    for (let i = 0; i < total; i++) {
        const val = Math.floor(Math.random() * 5) + 1;
        const input = document.querySelector(`input[name="responses[${i}]"][value="${val}"]`);
        if (input) input.checked = true;
    }
});

// Scroll to first unanswered question on submit attempt
document.getElementById('big5-form').addEventListener('submit', function(e) {
    for (let i = 0; i < total; i++) {
        const answered = document.querySelector(`input[name="responses[${i}]"]:checked`);
        if (!answered) {
            e.preventDefault();
            const el = document.querySelector(`[data-index="${i}"]`);
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            el.classList.add('ring-2', 'ring-red-400');
            setTimeout(() => el.classList.remove('ring-2', 'ring-red-400'), 2000);
            break;
        }
    }
});
</script>
@endsection
