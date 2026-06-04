@extends('layouts.app')
@section('title', 'Direction de plongée · ' . $trainee->name)

@section('content')
<div class="max-w-2xl mx-auto">

    @include('trainee._nav')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800 leading-tight">Direction de plongée</h1>
        <p class="text-sm text-slate-400 mt-1">Évaluations pratiques en milieu naturel</p>
    </div>

    @if($evals->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 px-6 py-12 text-center text-sm text-slate-400">
            Aucune évaluation pour l'instant.
        </div>
    @else

    {{-- Summary chips --}}
    @php
        $total  = $evals->count();
        $valide = $evals->where('status', 'valide')->count();
        $echec  = $evals->where('status', 'echec')->count();
    @endphp
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <span class="text-xs font-medium text-slate-500 bg-slate-100 border border-slate-200 rounded-full px-3 py-1">
            {{ $total }} évaluation{{ $total > 1 ? 's' : '' }}
        </span>
        @if($valide > 0)
            <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full px-3 py-1">
                {{ $valide }} validée{{ $valide > 1 ? 's' : '' }}
            </span>
        @endif
        @if($echec > 0)
            <span class="text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-full px-3 py-1">
                {{ $echec }} échec{{ $echec > 1 ? 's' : '' }}
            </span>
        @endif
    </div>

    {{-- Evaluation cards --}}
    <div class="space-y-4">
        @foreach($evals as $dp)
        @php $comps = \App\Models\DirectionPlongeeEvaluation::competencies(); @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-5">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-4">
                <span class="text-sm font-semibold text-slate-700">
                    {{ $dp->evaluated_at->locale('fr')->isoFormat('D MMMM YYYY') }}
                </span>
                <span class="inline-flex items-center text-xs font-semibold border rounded-full px-2.5 py-0.5 {{ \App\Models\DirectionPlongeeEvaluation::statusColor($dp->status) }}">
                    {{ \App\Models\DirectionPlongeeEvaluation::statusLabel($dp->status) }}
                </span>
                @if($dp->note_globale !== null)
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 border border-slate-200 rounded-full px-2.5 py-0.5">
                        Note : {{ number_format($dp->note_globale, 2) }} / 3
                    </span>
                @endif
            </div>

            {{-- Competencies --}}
            <div class="space-y-2">
                @foreach($comps as $key => $comp)
                @php $val = $dp->$key; @endphp
                <div class="flex items-center gap-3">
                    <div class="flex items-start gap-1 flex-1 min-w-0">
                        <span class="text-amber-500 text-xs font-bold mt-0.5 flex-shrink-0">★</span>
                        <span class="text-xs text-slate-600 leading-snug">{{ $comp['label'] }}</span>
                    </div>
                    <div class="flex-shrink-0">
                        @if($val === null)
                            <span class="inline-flex items-center justify-center w-8 h-6 rounded border-2 border-slate-100 text-xs text-slate-300">—</span>
                        @else
                            @php
                                $valColor = match($val) {
                                    1 => 'border-red-400 bg-red-50 text-red-600',
                                    2 => 'border-amber-400 bg-amber-50 text-amber-600',
                                    3 => 'border-emerald-400 bg-emerald-50 text-emerald-600',
                                    default => 'border-slate-200 bg-white text-slate-400',
                                };
                            @endphp
                            <span class="inline-flex items-center justify-center w-8 h-6 rounded border-2 text-xs font-bold {{ $valColor }}">{{ $val }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($dp->instructor_notes)
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <p class="text-xs text-slate-400 uppercase tracking-wide font-semibold mb-1">Notes</p>
                    <p class="text-xs text-slate-500 italic">{{ $dp->instructor_notes }}</p>
                </div>
            @endif

        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
