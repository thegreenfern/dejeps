@extends('layouts.app')
@section('title', ($dp ? 'Modifier' : 'Ajouter') . ' une évaluation – Direction de plongée · ' . $trainee->name)

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('instructor.trainee.show', $trainee) }}#dp"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        {{ $trainee->name }}
    </a>

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800 leading-tight">
            {{ $dp ? 'Modifier l\'évaluation' : 'Nouvelle évaluation' }}
        </h1>
        <p class="text-sm text-slate-400 mt-1">Direction de plongée · {{ $trainee->name }}</p>
    </div>

    <form method="POST"
          action="{{ $dp ? route('instructor.dp.update', [$trainee, $dp]) : route('instructor.dp.store', $trainee) }}"
          class="space-y-6">
        @csrf
        @if($dp) @method('PUT') @endif

        {{-- Date + Status --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Date d'évaluation <span class="text-red-500">*</span></label>
                    <input type="date" name="evaluated_at"
                           value="{{ $dp ? $dp->evaluated_at->format('Y-m-d') : date('Y-m-d') }}"
                           required
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Statut</label>
                    <select name="status"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @foreach(['en_cours' => 'En cours', 'valide' => 'Validé', 'echec' => 'Échec'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($dp ? $dp->status : 'en_cours') === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Competency ratings --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-700 mb-1">Compétences</h2>
            <p class="text-xs text-slate-400 mb-4">
                <span class="text-amber-500 font-bold">★</span> requis · 1 Insuffisant · 2 Satisfaisant · 3 Maîtrisé · — Non évalué
            </p>

            <div class="space-y-3">
                @foreach(\App\Models\DirectionPlongeeEvaluation::competencies() as $key => $comp)
                @php $cur = $dp ? (string)($dp->$key ?? '') : ''; @endphp
                <div class="flex items-center gap-3">
                    <div class="flex items-start gap-1 flex-1 min-w-0">
                        <span class="text-amber-500 text-xs font-bold mt-0.5 flex-shrink-0">★</span>
                        <span class="text-xs text-slate-700 leading-snug">{{ $comp['label'] }}</span>
                    </div>
                    <div class="flex gap-1 flex-shrink-0">
                        <label class="cursor-pointer">
                            <input type="radio" name="{{ $key }}" value="1" class="sr-only peer" {{ $cur === '1' ? 'checked' : '' }}>
                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-red-400 peer-checked:bg-red-50 hover:border-slate-300 transition-colors cursor-pointer">
                                <span class="text-xs font-bold text-slate-400 peer-checked:text-red-600">1</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="{{ $key }}" value="2" class="sr-only peer" {{ $cur === '2' ? 'checked' : '' }}>
                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-amber-400 peer-checked:bg-amber-50 hover:border-slate-300 transition-colors cursor-pointer">
                                <span class="text-xs font-bold text-slate-400 peer-checked:text-amber-600">2</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="{{ $key }}" value="3" class="sr-only peer" {{ $cur === '3' ? 'checked' : '' }}>
                            <div class="w-8 rounded border-2 border-slate-200 py-1 text-center peer-checked:border-emerald-400 peer-checked:bg-emerald-50 hover:border-slate-300 transition-colors cursor-pointer">
                                <span class="text-xs font-bold text-slate-400 peer-checked:text-emerald-600">3</span>
                            </div>
                        </label>
                        <label class="cursor-pointer" title="Non évalué">
                            <input type="radio" name="{{ $key }}" value="" class="sr-only peer" {{ $cur === '' ? 'checked' : '' }}>
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
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <label class="block text-xs font-medium text-slate-600 mb-1">Notes formateur</label>
            <textarea name="instructor_notes" rows="3"
                      placeholder="Observations sur l'évaluation…"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ $dp?->instructor_notes }}</textarea>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('instructor.trainee.show', $trainee) }}#dp"
               class="text-sm text-slate-500 hover:text-slate-700 transition-colors">Annuler</a>
            <button type="submit"
                    class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                {{ $dp ? 'Enregistrer les modifications' : 'Ajouter l\'évaluation' }}
            </button>
        </div>
    </form>

</div>
@endsection
