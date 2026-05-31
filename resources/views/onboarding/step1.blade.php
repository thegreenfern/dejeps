@extends('layouts.app')
@section('title', 'Onboarding — Étape 1')

@section('content')
<x-wizard-progress :current="1" />

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-slate-800 mb-1">Bienvenue dans votre formation DEJEPS</h1>
    <p class="text-slate-500 mb-8">Commençons par faire connaissance. Ces informations aideront votre formateur à personnaliser votre accompagnement.</p>

    <div class="flex justify-end mb-4">
        <button type="button" id="btn-autofill"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-dashed border-slate-300
                       text-xs font-medium text-slate-400 hover:border-violet-400 hover:text-violet-600
                       hover:bg-violet-50 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.155-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            Remplir pour test
        </button>
    </div>

    <form method="POST" action="{{ route('onboarding.step1.save') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Identité --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Informations personnelles</h2>

            {{-- Photo + name row --}}
            <div class="flex gap-4 items-start">
                <div class="flex-shrink-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Photo</label>
                    <label for="photo" class="cursor-pointer block">
                        <div id="photo-preview"
                             class="w-20 h-20 rounded-full border-2 border-dashed border-slate-300 hover:border-sky-400
                                    flex items-center justify-center bg-slate-50 overflow-hidden transition-colors">
                            @if($trainee?->photo_path)
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
                           value="{{ old('name', $trainee?->name) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('name') border-red-400 @enderror"
                           placeholder="Ex : Marie Dupont" required>
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror

                    <label class="block text-sm font-medium text-slate-700 mb-1 mt-3" for="date_of_birth">
                        Date de naissance <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="{{ old('date_of_birth', $trainee?->date_of_birth?->format('Y-m-d')) }}"
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
                           value="{{ old('email', $trainee?->email) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('email') border-red-400 @enderror"
                           placeholder="marie@exemple.fr" required>
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="phone">
                        Téléphone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone"
                           value="{{ old('phone', $trainee?->phone) }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('phone') border-red-400 @enderror"
                           placeholder="06 12 34 56 78" required>
                    @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- CV upload --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    CV <span class="text-slate-400 font-normal text-xs">(PDF, Word — max 10 Mo)</span>
                </label>
                @if($trainee?->cv_path)
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
                        {{ $trainee?->cv_path ? 'Remplacer le CV…' : 'Déposer votre CV…' }}
                    </span>
                </label>
                <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" class="sr-only">
                @error('cv')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Expériences antérieures --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Expériences antérieures</h2>

            {{-- Yes/No questions --}}
            @php
                $priorExp = $trainee?->profile?->prior_experiences ?? [];
                $yesNoQuestions = [
                    ['field' => 'prior_has_other_jobs',  'label' => "Avez-vous déjà eu d'autres expériences professionnelles ?"],
                    ['field' => 'prior_has_diving_work', 'label' => "Avez-vous déjà travaillé (bénévolement ou non) dans un centre ou une association de plongée ?"],
                    ['field' => 'prior_has_guided',      'label' => "Avez-vous déjà guidé une palanquée en plongée ?"],
                    ['field' => 'prior_has_taught',      'label' => "Avez-vous déjà réalisé une formation de plongée (en tant que moniteur) ?"],
                ];
            @endphp
            <div class="space-y-3 pb-2 border-b border-slate-100">
                @foreach($yesNoQuestions as $q)
                    @php
                        $storedKey = str_replace('prior_', '', $q['field']);
                        $stored    = array_key_exists($storedKey, $priorExp) ? ($priorExp[$storedKey] ? '1' : '0') : null;
                        $val       = old($q['field'], $stored);
                    @endphp
                    <div class="flex items-center justify-between gap-6">
                        <span class="text-sm text-slate-700 leading-snug flex-1">{{ $q['label'] }}</span>
                        <div class="yn-group inline-flex rounded-lg border border-slate-200 overflow-hidden flex-shrink-0 text-xs font-semibold">
                            <label class="cursor-pointer">
                                <input type="radio" name="{{ $q['field'] }}" value="1" class="sr-only"
                                       {{ $val === '1' ? 'checked' : '' }}>
                                <span class="block px-4 py-1.5 transition-colors text-slate-500">Oui</span>
                            </label>
                            <label class="cursor-pointer border-l border-slate-200">
                                <input type="radio" name="{{ $q['field'] }}" value="0" class="sr-only"
                                       {{ $val === '0' ? 'checked' : '' }}>
                                <span class="block px-4 py-1.5 transition-colors text-slate-500">Non</span>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_diving_level">
                    Votre niveau de plongée actuel <span class="text-red-500">*</span>
                </label>
                <select id="prior_diving_level" name="prior_diving_level" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent @error('prior_diving_level') border-red-400 @enderror">
                    <option value="">— Choisir —</option>
                    @foreach(['PA40', 'N3', 'N4GP', 'Instructeur RSTC', 'Instructeur-trainer RSTC'] as $level)
                        <option value="{{ $level }}"
                            {{ old('prior_diving_level', $trainee?->profile?->prior_experiences['diving_level'] ?? '') === $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
                @error('prior_diving_level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_teaching">
                    Expériences d'enseignement ou d'encadrement <span class="text-red-500">*</span>
                </label>
                <textarea id="prior_teaching" name="prior_teaching" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('prior_teaching') border-red-400 @enderror"
                          placeholder="Cours donnés, niveaux encadrés, contextes (club, école, stage…)"
                          required>{{ old('prior_teaching', $trainee?->profile?->prior_experiences['teaching'] ?? '') }}</textarea>
                @error('prior_teaching')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="prior_other">
                    Autres expériences pertinentes (sport, éducation, management…)
                </label>
                <textarea id="prior_other" name="prior_other" rows="2"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none">{{ old('prior_other', $trainee?->profile?->prior_experiences['other'] ?? '') }}</textarea>
            </div>
        </section>

        {{-- Ice-breaking --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Pour mieux vous connaître</h2>
            <p class="text-xs text-slate-400">Prenez le temps de répondre avec sincérité — il n'y a pas de bonne ou mauvaise réponse.</p>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_motivation">
                    Qu'est-ce qui vous a amené(e) à vous engager dans ce DEJEPS ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_motivation" name="ice_motivation" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_motivation') border-red-400 @enderror"
                          required>{{ old('ice_motivation', $trainee?->profile?->ice_breaking['motivation'] ?? '') }}</textarea>
                @error('ice_motivation')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_strengths">
                    Quels sont vos points forts en tant qu'enseignant(e) ou futur(e) enseignant(e) ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_strengths" name="ice_strengths" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_strengths') border-red-400 @enderror"
                          required>{{ old('ice_strengths', $trainee?->profile?->ice_breaking['strengths'] ?? '') }}</textarea>
                @error('ice_strengths')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="ice_challenges">
                    Quels aspects de cette formation vous semblent les plus difficiles ou les plus incertains ? <span class="text-red-500">*</span>
                </label>
                <textarea id="ice_challenges" name="ice_challenges" rows="3"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none @error('ice_challenges') border-red-400 @enderror"
                          required>{{ old('ice_challenges', $trainee?->profile?->ice_breaking['challenges'] ?? '') }}</textarea>
                @error('ice_challenges')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </section>

        {{-- Comments --}}
        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-3">Commentaires libres</h2>
            <textarea id="trainee_comments" name="trainee_comments" rows="4"
                      class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none"
                      placeholder="Informations complémentaires, contexte particulier, questions pour votre formateur…">{{ old('trainee_comments', $trainee?->profile?->trainee_comments ?? '') }}</textarea>
        </section>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Continuer →
            </button>
        </div>
    </form>
</div>

<script>
// Photo preview
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

// CV filename
document.getElementById('cv').addEventListener('change', function () {
    const label = document.getElementById('cv-label');
    label.textContent = this.files[0] ? this.files[0].name : 'Déposer votre CV…';
});

// ── Oui/Non toggles ─────────────────────────────────────────────────────
(function () {
    function syncGroup(group) {
        group.querySelectorAll('input[type="radio"]').forEach(radio => {
            const span = radio.nextElementSibling;
            const isOui = radio.value === '1';
            span.classList.toggle('bg-emerald-400', isOui && radio.checked);
            span.classList.toggle('bg-slate-200',   !isOui && radio.checked);
            span.classList.toggle('text-white',      isOui && radio.checked);
            span.classList.toggle('text-slate-700',  !isOui && radio.checked);
            span.classList.toggle('text-slate-500',  !radio.checked);
        });
    }
    document.querySelectorAll('.yn-group').forEach(group => {
        syncGroup(group);
        group.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => syncGroup(group));
        });
    });
})();

// ── Autofill for testing ─────────────────────────────────────────────────
document.getElementById('btn-autofill').addEventListener('click', function () {
    const profiles = [
        {
            name: 'Lucas Bernard',
            email: 'lucas.bernard@gmail.com',
            phone: '06 12 34 56 78',
            dob: '1990-07-15',
            has_other_jobs: '1', has_diving_work: '1', has_guided: '1', has_taught: '0',
            level: 'N4GP',
            teaching: 'Encadrement de niveaux 1 à 3 depuis 5 ans au sein du club ASPTT Marseille. Animation de stages découverte pour enfants et adultes.',
            other: 'Moniteur de natation BEESAN depuis 2018. Responsable pédagogique adjoint du club.',
            motivation: 'Passionné de plongée depuis l\'adolescence, je souhaite professionnaliser mon activité d\'encadrement pour pouvoir enseigner dans un cadre rémunéré et transmettre ma passion à un plus grand nombre.',
            strengths: 'Bonne aisance en milieu subaquatique, sens de la pédagogie développé avec des publics variés, capacité à adapter mon discours selon le niveau des apprenants.',
            challenges: 'La partie théorique sur la physique et la physiologie me semble dense. Je redoute également la gestion administrative liée au futur diplôme.',
            comments: 'Je suis disponible tous les week-ends et deux soirs par semaine pour les sessions de formation.',
        },
        {
            name: 'Camille Moreau',
            email: 'camille.moreau@outlook.fr',
            phone: '07 89 01 23 45',
            dob: '1994-03-22',
            has_other_jobs: '0', has_diving_work: '1', has_guided: '1', has_taught: '0',
            level: 'PA40',
            teaching: '3 ans d\'encadrement bénévole en club, niveaux 1 et 2 principalement. Participation à des expéditions de plongée technique.',
            other: 'Ancienne gymnaste de compétition. Diplôme BAFA — animation de colonies de vacances sportives.',
            motivation: 'Après plusieurs années comme bénévole, je veux franchir le cap du professsionnalisme. Le DEJEPS représente la voie naturelle pour valider mes compétences et élargir mes débouchés.',
            strengths: 'Très à l\'aise dans l\'eau, patiente avec les débutants, organisée dans la préparation de séances.',
            challenges: 'Je manque de confiance dans la prise de parole face à un groupe adulte, surtout quand le public est expérimenté.',
            comments: '',
        },
        {
            name: 'Antoine Roux',
            email: 'antoine.roux@laposte.net',
            phone: '06 55 44 33 22',
            dob: '1987-11-08',
            has_other_jobs: '1', has_diving_work: '1', has_guided: '1', has_taught: '1',
            level: 'Instructeur RSTC',
            teaching: 'Initiateur club depuis 4 ans. Formation de plongeurs niveau 1 et initiation enfants. Quelques stages mer en tant que responsable pédagogique.',
            other: 'Expérience en management d\'équipe (chef de projet en entreprise). Secouriste PSE1.',
            motivation: 'Je cherche une reconversion professionnelle dans le milieu sportif. La plongée est ma passion depuis 15 ans et le DEJEPS me permettra de l\'exercer comme métier principal.',
            strengths: 'Leadership naturel, rigueur dans l\'organisation, bonne connaissance du milieu associatif et de la gestion de groupe.',
            challenges: 'La pédagogie formelle et la rédaction de documents professionnels sont des points sur lesquels je dois progresser.',
            comments: 'Je bénéficie d\'un CPF et d\'un soutien de mon employeur actuel pour la formation.',
        },
    ];

    const p = profiles[Math.floor(Math.random() * profiles.length)];

    document.getElementById('name').value              = p.name;
    document.getElementById('date_of_birth').value     = p.dob;
    document.getElementById('email').value             = p.email;
    document.getElementById('phone').value             = p.phone;
    ['prior_has_other_jobs', 'prior_has_diving_work', 'prior_has_guided', 'prior_has_taught'].forEach(field => {
        const key = field.replace('prior_', '');
        const radio = document.querySelector(`input[name="${field}"][value="${p[key]}"]`);
        if (radio) radio.checked = true;
    });
    document.getElementById('prior_diving_level').value = p.level;
    document.getElementById('prior_teaching').value    = p.teaching;
    document.getElementById('prior_other').value       = p.other;
    document.getElementById('ice_motivation').value    = p.motivation;
    document.getElementById('ice_strengths').value     = p.strengths;
    document.getElementById('ice_challenges').value    = p.challenges;
    document.getElementById('trainee_comments').value  = p.comments;
});
</script>
@endsection
