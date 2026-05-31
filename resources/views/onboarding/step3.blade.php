@extends('layouts.app')
@section('title', 'Onboarding — Auto-évaluation')

@section('content')
<x-wizard-progress :current="3" />

<div class="max-w-2xl mx-auto">
    <div class="flex items-start justify-between mb-2">
        <h1 class="text-2xl font-bold text-slate-800">Auto-évaluation des compétences</h1>
        <button type="button" id="randomize-btn"
                class="text-xs text-slate-400 hover:text-slate-600 border border-slate-200 hover:border-slate-300 rounded-lg px-3 py-1.5 transition-colors flex-shrink-0 mt-1">
            Réponses aléatoires
        </button>
    </div>
    <p class="text-slate-500 mb-8">
        Pour chaque compétence, évaluez honnêtement votre niveau actuel en vous basant sur vos expériences passées.
        Vous pouvez ajouter un exemple concret pour appuyer votre réponse.
    </p>

    {{-- Scale legend --}}
    <div class="grid grid-cols-3 gap-3 mb-8">
        <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
            <span class="block text-lg font-bold text-red-600 mb-0.5">1</span>
            <span class="text-xs text-slate-500">Je n'ai aucune notion</span>
        </div>
        <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
            <span class="block text-lg font-bold text-amber-600 mb-0.5">2</span>
            <span class="text-xs text-slate-500">Je sais faire avec aide</span>
        </div>
        <div class="p-3 bg-white border border-slate-200 rounded-lg text-center">
            <span class="block text-lg font-bold text-emerald-600 mb-0.5">3</span>
            <span class="text-xs text-slate-500">Je maitrise de manière autonome</span>
        </div>
    </div>

    <form method="POST" action="{{ route('onboarding.step3.save') }}" class="space-y-10">
        @csrf

        @foreach($competencies as $category => $items)
            @php $isHidden = in_array($category, $hiddenCategories); @endphp
            <section @if($isHidden) class="hidden" @endif>
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100">
                    {{ $category }}
                </h2>

                @if($isHidden)
                    {{-- Hidden categories are auto-scored 1 (Aucune notion) --}}
                    @foreach($items as $competency)
                        <input type="hidden" name="scores[{{ $competency->id }}]" value="1">
                    @endforeach
                @else
                    <div class="space-y-3">
                        @foreach($items as $competency)
                            <div class="bg-white rounded-xl border border-slate-200 p-4
                                        @error("scores.{$competency->id}") border-red-400 @enderror">

                                <p class="text-sm text-slate-700 mb-3">{{ $competency->label }}</p>

                                {{-- A / B / C buttons --}}
                                <div class="flex gap-2 mb-3">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="1"
                                               class="sr-only peer" {{ old("scores.{$competency->id}") == 1 ? 'checked' : '' }} required>
                                        <div class="rounded-lg border-2 border-slate-200 py-2 text-center
                                                    peer-checked:border-red-400 peer-checked:bg-red-50
                                                    hover:border-slate-300 transition-colors">
                                            <span class="block text-base font-bold text-slate-400 peer-checked:text-red-600">1</span>
                                            <span class="text-xs text-slate-400 peer-checked:text-red-500">Aucune notion</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="2"
                                               class="sr-only peer" {{ old("scores.{$competency->id}") == 2 ? 'checked' : '' }}>
                                        <div class="rounded-lg border-2 border-slate-200 py-2 text-center
                                                    peer-checked:border-amber-400 peer-checked:bg-amber-50
                                                    hover:border-slate-300 transition-colors">
                                            <span class="block text-base font-bold text-slate-400 peer-checked:text-amber-600">2</span>
                                            <span class="text-xs text-slate-400 peer-checked:text-amber-500">Avec aide</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="3"
                                               class="sr-only peer" {{ old("scores.{$competency->id}") == 3 ? 'checked' : '' }}>
                                        <div class="rounded-lg border-2 border-slate-200 py-2 text-center
                                                    peer-checked:border-emerald-400 peer-checked:bg-emerald-50
                                                    hover:border-slate-300 transition-colors">
                                            <span class="block text-base font-bold text-slate-400 peer-checked:text-emerald-600">3</span>
                                            <span class="text-xs text-slate-400 peer-checked:text-emerald-500">Autonome</span>
                                        </div>
                                    </label>
                                </div>

                                {{-- Evidence (optional) --}}
                                <input type="text"
                                       name="evidence[{{ $competency->id }}]"
                                       value="{{ old("evidence.{$competency->id}") }}"
                                       class="w-full rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-xs text-slate-600 placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent"
                                       placeholder="Exemple ou expérience liée (optionnel)">
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach

        @if($errors->count())
            <p class="text-sm text-red-600">Veuillez évaluer toutes les compétences avant de continuer.</p>
        @endif

        <div class="flex justify-between pt-4">
            <a href="{{ route('onboarding.step2') }}"
               class="px-5 py-2.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
                ← Retour
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Terminer →
            </button>
        </div>
    </form>
</div>
<script>
document.getElementById('randomize-btn').addEventListener('click', function () {
    @foreach($competencies as $category => $items)
    @if(!in_array($category, $hiddenCategories))
    @foreach($items as $competency)
    (function () {
        var radios = document.querySelectorAll('input[name="scores[{{ $competency->id }}]"][type="radio"]');
        radios.forEach(function (r) { r.checked = false; });
        var pick = radios[Math.floor(Math.random() * radios.length)];
        if (pick) pick.checked = true;
    })();
    @endforeach
    @endif
    @endforeach
});
</script>
@endsection
