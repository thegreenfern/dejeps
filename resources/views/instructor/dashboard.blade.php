@extends('layouts.app')
@section('title', 'Formateur · Tableau de bord')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-start justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tableau de bord formateur</h1>
            <p class="text-sm text-slate-400 mt-1">Suivi des stagiaires DEJEPS Plongée</p>
        </div>
        <a href="{{ route('instructor.uc12') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:border-violet-300 hover:text-violet-700 hover:bg-violet-50 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            UC1 / UC2
        </a>
    </div>

    @if($trainees->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-slate-400 text-sm">Aucun stagiaire enregistré pour l'instant.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($trainees as $trainee)
                <a href="{{ route('instructor.trainee.show', $trainee) }}"
                   class="flex items-center gap-4 bg-white rounded-xl border border-slate-200 px-5 py-4 hover:border-slate-300 hover:shadow-sm transition-all group">

                    {{-- Photo --}}
                    @if($trainee->photo_path)
                        <img src="{{ Storage::url($trainee->photo_path) }}"
                             class="w-11 h-11 rounded-full object-cover border border-slate-200 flex-shrink-0">
                    @else
                        <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 group-hover:text-slate-900">{{ $trainee->name }}</p>
                        <div class="flex items-center gap-3 mt-0.5">
                            @if($trainee->email)
                                <span class="text-xs text-slate-400">{{ $trainee->email }}</span>
                            @endif
                            @if($trainee->date_of_birth)
                                <span class="text-xs text-slate-300">·</span>
                                <span class="text-xs text-slate-400">{{ $trainee->date_of_birth->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex-shrink-0 text-right">
                        @if($trainee->hasCompletedOnboarding())
                            <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2.5 py-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Dossier complet
                            </span>
                            @if($trainee->profile?->completed_at)
                                <p class="text-xs text-slate-400 mt-1">{{ $trainee->profile->completed_at->format('d/m/Y') }}</p>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2.5 py-1">
                                Onboarding en cours
                            </span>
                        @endif
                    </div>

                    {{-- Arrow --}}
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-400 flex-shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection
