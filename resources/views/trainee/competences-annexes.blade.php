@extends('layouts.app')
@section('title', 'Compétences annexes · ' . $trainee->name)

@section('content')
<div class="max-w-2xl mx-auto">

    @include('trainee._nav')

    @php
        $comps    = \App\Models\CompetencesAnnexes::competencies();
        $status   = $rec->globalStatus();
        $acquired = $rec->acquiredCount();
        $total    = count($comps);
        $pct      = $total > 0 ? round($acquired / $total * 100) : 0;
    @endphp

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800 leading-tight">Compétences annexes</h1>
        <p class="text-sm text-slate-400 mt-1">Compétences opérationnelles de la structure d'accueil</p>
    </div>

    {{-- Summary --}}
    <div class="flex items-center gap-4 mb-6">
        <span class="inline-flex items-center text-xs font-semibold border rounded-full px-3 py-1 {{ \App\Models\CompetencesAnnexes::statusColor($status) }}">
            {{ \App\Models\CompetencesAnnexes::statusLabel($status) }}
        </span>
        <div class="flex-1 flex items-center gap-3">
            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all
                            {{ $acquired === $total ? 'bg-emerald-400' : 'bg-sky-400' }}"
                     style="width: {{ $pct }}%"></div>
            </div>
            <span class="text-xs font-semibold text-slate-500 flex-shrink-0">{{ $acquired }}/{{ $total }} acquises</span>
        </div>
    </div>

    {{-- Competency grid --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <p class="text-xs text-slate-400">1 Non acquis · 2 En cours d'acquisition · 3 Acquis</p>

        @foreach($comps as $key => $comp)
        @php
            $val      = $rec->$key;
            $valColor = match($val) {
                1       => 'border-red-400 bg-red-50 text-red-600',
                2       => 'border-amber-400 bg-amber-50 text-amber-600',
                3       => 'border-emerald-400 bg-emerald-50 text-emerald-600',
                default => 'border-slate-100 bg-slate-50 text-slate-300',
            };
            $valLabel = match($val) {
                1 => '1',
                2 => '2',
                3 => '3',
                default => '—',
            };
        @endphp
        <div class="flex items-center gap-3">
            <div class="flex-1 min-w-0">
                <span class="text-sm font-medium text-slate-700">{{ $comp['label'] }}</span>
                <span class="text-xs text-slate-400 ml-2">{{ $comp['description'] }}</span>
            </div>
            <span class="inline-flex items-center justify-center w-8 h-7 rounded border-2 text-xs font-bold flex-shrink-0 {{ $valColor }}">{{ $valLabel }}</span>
        </div>
        @endforeach
    </div>

    @if($rec->notes_formateur)
    <div class="mt-4 bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Notes</p>
        <p class="text-sm text-slate-600">{{ $rec->notes_formateur }}</p>
    </div>
    @endif

</div>
@endsection
