@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('trainee.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Tableau de bord
        </a>
    </div>

    <h1 class="text-2xl font-bold text-slate-800 mb-1">Mon profil</h1>
    <p class="text-slate-500 text-sm mb-8">Mettez à jour vos informations personnelles et vos réponses d'entrée en formation.</p>

    <form method="POST" action="{{ route('trainee.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Identité --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Informations personnelles</h2>

            {{-- Photo + name --}}
            <div class="flex gap-4 items-start">
                <div class="flex-shrink-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Photo</label>
                    <label for="photo" class="cursor-pointer block">
                        <div id="photo-preview"
                             class="w-20 h-20 rounded-full border-2 border-dashed border-slate-300 hover:border-sky-400
                                    flex items-center justify-center bg-slate-50 overflow-hidden transition-colors">
                            @if($trainee->photo_path)
                                <img src="{{ Storage::url($trainee->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="block text-xs text-slate-400 text-center mt-1">Changer</span>
                    </label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="sr-only">
                    @error('photo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="name">
                        Prénom et nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $trainee->name) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('name') border-red-400 @enderror"
                           required>
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                    <label class="block text-sm font-medium text-slate-700 mb-1 mt-3" for="date_of_birth">
                        Date de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="{{ old('date_of_birth', $trainee->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('date_of_birth') border-red-400 @enderror"
                           required>
                    @error('date_of_birth')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email + Phone --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="email">
                        Adresse e-mail <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $trainee->email) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('email') border-red-400 @enderror"
                           required>
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="phone">
                        Téléphone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone', $trainee->phone) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('phone') border-red-400 @enderror"
                           required>
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- CV --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    CV <span class="text-slate-400 font-normal text-xs">(PDF, Word — max 10 Mo)</span>
                </label>
                @if($trainee->cv_path)
                    <p class="text-xs text-emerald-600 mb-1">
                        ✓ CV déjà déposé —
                        <a href="{{ Storage::url($trainee->cv_path) }}" target="_blank" class="underline">voir</a>
                    </p>
                @endif
                <label for="cv"
                       class="flex items-center gap-3 border border-dashed border-slate-300 hover:border-sky-400
                              rounded-lg px-4 py-3 cursor-pointer transition-colors group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span id="cv-label" class="text-sm text-slate-400 group-hover:text-slate-600">
                        {{ $trainee->cv_path ? 'Remplacer le CV…' : 'Déposer votre CV…' }}
                    </span>
                </label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" class="sr-only">
                @error('cv')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Expériences antérieures --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Expériences antérieures</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_diving_level">
                    Votre niveau de plongée actuel <span class="text-red-500">*</span>
                </label>
                <input type="text" id="prior_diving_level" name="prior_diving_level"
                       value="{{ old('prior_diving_level', $trainee->profile?->prior_experiences['diving_level'] ?? '') }}"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('prior_diving_level') border-red-400 @enderror"
                       required>
                @error('prior_diving_level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_teaching">
                    Expériences d'enseignement ou d'encadrement <span class="text-red-500">*</span>
                </label>
                <textarea id="prior_teaching" name="prior_teaching" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('prior_teaching') border-red-400 @enderror"
                          required>{{ old('prior_teaching', $trainee->profile?->prior_experiences['teaching'] ?? '') }}</textarea>
                @error('prior_teaching')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_other">
                    Autres expériences pertinentes
                </label>
                <textarea id="prior_other" name="prior_other" rows="2"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none">{{ old('prior_other', $trainee->profile?->prior_experiences['other'] ?? '') }}</textarea>
            </div>
        </section>

        {{-- Pour mieux vous connaître --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Pour mieux vous connaître</h2>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_motivation">
                    Qu'est-ce qui vous a amené(e) à vous engager dans ce DEJEPS ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_motivation" name="ice_motivation" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_motivation') border-red-400 @enderror"
                          required>{{ old('ice_motivation', $trainee->profile?->ice_breaking['motivation'] ?? '') }}</textarea>
                @error('ice_motivation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_strengths">
                    Quels sont vos points forts en tant qu'enseignant(e) ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_strengths" name="ice_strengths" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_strengths') border-red-400 @enderror"
                          required>{{ old('ice_strengths', $trainee->profile?->ice_breaking['strengths'] ?? '') }}</textarea>
                @error('ice_strengths')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_challenges">
                    Quels aspects vous semblent les plus difficiles ou incertains ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_challenges" name="ice_challenges" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_challenges') border-red-400 @enderror"
                          required>{{ old('ice_challenges', $trainee->profile?->ice_breaking['challenges'] ?? '') }}</textarea>
                @error('ice_challenges')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Commentaires --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Commentaires libres</h2>
            <textarea id="trainee_comments" name="trainee_comments" rows="4"
                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none"
                      placeholder="Informations complémentaires, contexte particulier, questions pour votre formateur…">{{ old('trainee_comments', $trainee->profile?->trainee_comments ?? '') }}</textarea>
        </section>

        <div class="flex justify-end pb-10">
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photo-preview').innerHTML =
            `<img src="${e.target.result}" class="w-full h-full object-cover">`;
    };
    reader.readAsDataURL(file);
});

document.getElementById('cv').addEventListener('change', function () {
    const label = document.getElementById('cv-label');
    label.textContent = this.files[0] ? this.files[0].name : 'Déposer votre CV…';
});
</script>
@endsection
