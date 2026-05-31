@extends('layouts.app')
@section('title', 'Contre-évaluation · ' . $trainee->name)

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('instructor.dashboard') }}"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Tableau de bord
    </a>

    {{-- Trainee profile card --}}
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
            <div class="flex items-center gap-4 mt-1">
                @if($trainee->email)
                    <span class="text-xs text-slate-400">{{ $trainee->email }}</span>
                @endif
                @if($trainee->profile?->completed_at)
                    <span class="text-xs text-slate-400">Onboarding complété le {{ $trainee->profile->completed_at->format('d/m/Y') }}</span>
                @endif
            </div>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Contre-évaluation</p>
            <p class="text-xs text-slate-400 mt-0.5">Formateur</p>
        </div>
    </div>

    {{-- Column header --}}
    <div class="grid grid-cols-[1fr_100px_160px_36px] gap-3 px-4 mb-2">
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Compétence</span>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide text-center">Stagiaire</span>
        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide text-center">Formateur</span>
        <span></span>
    </div>

    <form method="POST" action="{{ route('instructor.positioning.save', $trainee) }}" class="space-y-8" id="eval-form">
        @csrf

        @foreach($competencies as $category => $items)
            <section>
                <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3 pb-2 border-b border-slate-100">
                    {{ $category }}
                </h2>

                <div class="space-y-2">
                    @foreach($items as $competency)
                        @php
                            $assessment   = $assessments->get($competency->id);
                            $traineeScore = $assessment?->trainee_score;
                            $tutorScore   = $assessment?->tutor_score;
                            $tutorNotes   = $assessment?->tutor_notes ?? '';
                            $hasError     = $errors->has("scores.{$competency->id}");
                        @endphp

                        <div class="bg-white rounded-xl border {{ $hasError ? 'border-red-300' : 'border-slate-200' }} p-3">
                            <div class="grid grid-cols-[1fr_100px_160px_36px] gap-3 items-center">

                                {{-- Competency label --}}
                                <p class="text-sm text-slate-700 leading-snug">{{ $competency->label }}</p>

                                {{-- Trainee self-score (read-only badge) --}}
                                <div class="flex justify-center">
                                    @if($traineeScore === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 border-2 border-red-300 text-red-600 font-bold text-sm">A</span>
                                    @elseif($traineeScore === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 border-2 border-amber-300 text-amber-600 font-bold text-sm">B</span>
                                    @elseif($traineeScore === 3)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 border-2 border-emerald-300 text-emerald-600 font-bold text-sm">C</span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-400 text-xs">—</span>
                                    @endif
                                </div>

                                {{-- Tutor rating (A/B/C radio buttons) --}}
                                <div class="flex gap-1.5">
                                    {{-- A --}}
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="1"
                                               class="sr-only peer"
                                               {{ (old("scores.{$competency->id}", $tutorScore) == 1) ? 'checked' : '' }} required>
                                        <div class="rounded-lg border-2 border-slate-200 py-1.5 text-center
                                                    peer-checked:border-red-400 peer-checked:bg-red-50
                                                    hover:border-slate-300 transition-colors cursor-pointer">
                                            <span class="block text-sm font-bold text-slate-400
                                                         peer-checked:text-red-600">A</span>
                                        </div>
                                    </label>
                                    {{-- B --}}
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="2"
                                               class="sr-only peer"
                                               {{ (old("scores.{$competency->id}", $tutorScore) == 2) ? 'checked' : '' }} required>
                                        <div class="rounded-lg border-2 border-slate-200 py-1.5 text-center
                                                    peer-checked:border-amber-400 peer-checked:bg-amber-50
                                                    hover:border-slate-300 transition-colors cursor-pointer">
                                            <span class="block text-sm font-bold text-slate-400
                                                         peer-checked:text-amber-600">B</span>
                                        </div>
                                    </label>
                                    {{-- C --}}
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="scores[{{ $competency->id }}]" value="3"
                                               class="sr-only peer"
                                               {{ (old("scores.{$competency->id}", $tutorScore) == 3) ? 'checked' : '' }} required>
                                        <div class="rounded-lg border-2 border-slate-200 py-1.5 text-center
                                                    peer-checked:border-emerald-400 peer-checked:bg-emerald-50
                                                    hover:border-slate-300 transition-colors cursor-pointer">
                                            <span class="block text-sm font-bold text-slate-400
                                                         peer-checked:text-emerald-600">C</span>
                                        </div>
                                    </label>
                                </div>

                                {{-- Notes toggle --}}
                                <button type="button"
                                        onclick="toggleNotes(this)"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 hover:border-slate-300 text-slate-400 hover:text-slate-600 transition-colors {{ $tutorNotes ? 'border-violet-300 text-violet-500 bg-violet-50' : '' }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Notes (initially hidden unless already has content) --}}
                            <div class="notes-panel mt-2 {{ $tutorNotes ? '' : 'hidden' }}">
                                @if($assessment?->trainee_evidence)
                                    <p class="text-xs text-slate-400 italic mb-1.5">
                                        Stagiaire : « {{ $assessment->trainee_evidence }} »
                                    </p>
                                @endif
                                <textarea name="notes[{{ $competency->id }}]"
                                          rows="2"
                                          placeholder="Notes du formateur (facultatif)…"
                                          class="w-full text-xs rounded-lg border border-slate-200 px-3 py-2 text-slate-700 resize-none focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent">{{ old("notes.{$competency->id}", $tutorNotes) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="flex items-center justify-between pt-2 pb-10">
            <a href="{{ route('instructor.dashboard') }}"
               class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 border border-slate-300 rounded-lg hover:border-slate-400 transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Enregistrer la contre-évaluation →
            </button>
        </div>
    </form>

</div>

<script>
function toggleNotes(btn) {
    const row   = btn.closest('.bg-white');
    const panel = row.querySelector('.notes-panel');
    panel.classList.toggle('hidden');
    if (!panel.classList.contains('hidden')) {
        panel.querySelector('textarea').focus();
    }
}

// Scroll to first unanswered group on submit
document.getElementById('eval-form').addEventListener('submit', function (e) {
    const seen   = new Set();
    const radios = this.querySelectorAll('input[type="radio"][required]');
    for (const radio of radios) {
        if (seen.has(radio.name)) continue;
        seen.add(radio.name);
        const group      = this.querySelectorAll(`input[name="${CSS.escape(radio.name)}"]`);
        const anyChecked = [...group].some(r => r.checked);
        if (!anyChecked) {
            e.preventDefault();
            radio.closest('.bg-white').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
    }
});
</script>
@endsection
