@extends('layouts.app')
@section('title', 'Espace stagiaire')

@section('content')
<div class="max-w-md mx-auto">

    <h1 class="text-2xl font-bold text-slate-800 mb-1">Espace stagiaire</h1>
    <p class="text-slate-500 mb-8 text-sm">Sélectionnez votre profil pour accéder à votre suivi.</p>

    <div class="space-y-3">

        {{-- Existing trainee cards --}}
        @foreach($trainees as $trainee)
            <form method="POST" action="{{ route('trainee.identify') }}">
                @csrf
                <input type="hidden" name="action" value="existing">
                <input type="hidden" name="trainee_id" value="{{ $trainee->id }}">
                <button type="submit"
                        class="w-full flex items-center gap-4 bg-white rounded-xl border border-slate-200 px-5 py-4
                               hover:border-sky-300 hover:shadow-sm transition-all group text-left">

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

                    {{-- Name + status --}}
                    <div class="flex-1 min-w-0 text-left">
                        <p class="font-semibold text-slate-800 group-hover:text-sky-700 transition-colors">
                            {{ $trainee->name }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            @if($trainee->hasCompletedOnboarding())
                                Dossier complet
                            @else
                                Onboarding en cours
                            @endif
                        </p>
                    </div>

                    <svg class="w-4 h-4 text-slate-300 group-hover:text-sky-400 flex-shrink-0 transition-colors"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>
        @endforeach

        {{-- New profile --}}
        <form method="POST" action="{{ route('trainee.identify') }}">
            @csrf
            <input type="hidden" name="action" value="new">
            <button type="submit"
                    class="w-full flex items-center gap-4 bg-white rounded-xl border-2 border-dashed border-slate-200 px-5 py-4
                           hover:border-sky-300 hover:bg-sky-50 transition-all group text-left">
                <div class="w-11 h-11 rounded-full bg-slate-100 group-hover:bg-sky-100 flex items-center justify-center flex-shrink-0 transition-colors">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div class="flex-1 text-left">
                    <p class="font-semibold text-slate-600 group-hover:text-sky-700 transition-colors">
                        Créer un nouveau profil
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">Démarrer le questionnaire d'entrée en formation</p>
                </div>
            </button>
        </form>

    </div>

</div>
@endsection
