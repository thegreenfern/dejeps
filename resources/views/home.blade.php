@extends('layouts.app')

@section('title', 'Suivi DEJEPS · Accueil')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center">

    <h1 class="text-3xl font-semibold text-slate-800 mb-2 tracking-tight">
        Suivi DEJEPS
    </h1>
    <p class="text-slate-400 text-sm mb-12">Plongée subaquatique · CREPS Provence-Alpes Côte d'Azur</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 w-full max-w-lg">

        <form method="POST" action="{{ route('role.select') }}">
            @csrf
            <input type="hidden" name="role" value="instructor">
            <button type="submit"
                class="w-full group flex flex-col items-center gap-3 rounded-2xl border border-slate-200
                       bg-white px-8 py-10 shadow-sm hover:border-blue-400 hover:shadow-md
                       transition-all duration-200 cursor-pointer">
                <span class="text-3xl">🎓</span>
                <span class="text-base font-medium text-slate-700 group-hover:text-blue-600 transition-colors">
                    Je suis formateur
                </span>
            </button>
        </form>

        <form method="POST" action="{{ route('role.select') }}">
            @csrf
            <input type="hidden" name="role" value="trainee">
            <button type="submit"
                class="w-full group flex flex-col items-center gap-3 rounded-2xl border border-slate-200
                       bg-white px-8 py-10 shadow-sm hover:border-emerald-400 hover:shadow-md
                       transition-all duration-200 cursor-pointer">
                <span class="text-3xl">🤿</span>
                <span class="text-base font-medium text-slate-700 group-hover:text-emerald-600 transition-colors">
                    Je suis stagiaire
                </span>
            </button>
        </form>

    </div>

</div>
@endsection
