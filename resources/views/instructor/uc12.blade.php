@extends('layouts.app')
@section('title', 'UC1 / UC2 · Dates & Centre')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('instructor.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Tableau de bord
        </a>
    </div>
    <h1 class="text-2xl font-bold text-slate-800 mb-1">UC1 / UC2</h1>
    <p class="text-sm text-slate-400 mb-8">Projet de développement et gestion d'une structure sportive</p>

    <form method="POST" action="{{ route('instructor.uc12.settings.save') }}" id="settings-form">
    @csrf

    {{-- ── Dates officielles ───────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Dates officielles</h2>
        <p class="text-xs text-slate-400 mb-5">Ces dates sont affichées dans le calendrier visible par le formateur sur chaque fiche stagiaire.</p>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Modifiables</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Dépôt du dossier</label>
                    <input type="date" name="uc1_submission_deadline"
                           value="{{ $settings->uc1_submission_deadline?->format('Y-m-d') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Oral final (soutenance)</label>
                    <input type="date" name="uc1_jury_date"
                           value="{{ $settings->uc1_jury_date?->format('Y-m-d') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Rattrapage</label>
                    <input type="date" name="uc2_jury_date"
                           value="{{ $settings->uc2_jury_date?->format('Y-m-d') ?? '2026-10-23' }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Évaluations EPMSP</label>
                    <input type="date" name="epmsp_date"
                           value="{{ $settings->epmsp_date?->format('Y-m-d') ?? '2026-05-12' }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>
            {{-- hide uc2_submission_deadline but keep it in sync --}}
            <input type="hidden" name="uc2_submission_deadline"
                   value="{{ $settings->uc2_submission_deadline?->format('Y-m-d') ?? '2026-06-12' }}">
        </div>
    </div>

    {{-- ── Centre de plongée ────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <details {{ $settings->dc_name ? '' : 'open' }}>
            <summary class="flex items-center justify-between cursor-pointer list-none group">
                <div>
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Centre de plongée</h2>
                    @if($settings->dc_name)
                        <p class="text-sm text-slate-700 mt-0.5">
                            {{ $settings->dc_name }}
                            @if($settings->dc_address)
                                <span class="text-slate-400">· {{ $settings->dc_address }}</span>
                            @endif
                        </p>
                    @else
                        <p class="text-xs text-slate-400 mt-0.5">Aucune information renseignée</p>
                    @endif
                </div>
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="mt-5 pt-4 border-t border-slate-100 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nom du centre</label>
                        <input type="text" name="dc_name" value="{{ old('dc_name', $settings->dc_name) }}"
                               placeholder="Ex : Club Subaquatique de Nice"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Type de structure</label>
                        <select name="dc_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <option value="">— Choisir —</option>
                            @foreach(['Club associatif', 'Structure commerciale', 'CREPS / établissement public', 'Autre'] as $type)
                                <option value="{{ $type }}" {{ old('dc_type', $settings->dc_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Adresse / Zone géographique</label>
                        <input type="text" name="dc_address" value="{{ old('dc_address', $settings->dc_address) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Directeur technique</label>
                        <input type="text" name="dc_director" value="{{ old('dc_director', $settings->dc_director) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">E-mail</label>
                        <input type="email" name="dc_email" value="{{ old('dc_email', $settings->dc_email) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                        <input type="tel" name="dc_phone" value="{{ old('dc_phone', $settings->dc_phone) }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Description générale</label>
                    <textarea name="dc_description" rows="3"
                              placeholder="Contexte, équipement, zone de plongée, particularités…"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('dc_description', $settings->dc_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Notes pour la formation</label>
                    <textarea name="dc_notes" rows="3"
                              placeholder="Informations spécifiques utiles pour le suivi des stagiaires…"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('dc_notes', $settings->dc_notes) }}</textarea>
                </div>
            </div>
        </details>
    </div>

    <div class="flex justify-end pb-6">
        <button type="submit"
                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg transition-colors">
            Enregistrer
        </button>
    </div>

    </form>

    {{-- ── Documents ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-10">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-1">Documents</h2>
        <p class="text-xs text-slate-400 mb-5">Les fichiers déposés ici sont téléchargeables par les stagiaires.</p>

        {{-- Upload form --}}
        <form method="POST" action="{{ route('instructor.uc12.document.upload') }}"
              enctype="multipart/form-data" id="doc-upload-form">
            @csrf
            <label id="drop-zone"
                   class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-slate-300 rounded-xl py-8 px-4 cursor-pointer hover:border-sky-400 hover:bg-sky-50/40 transition-colors">
                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                <span class="text-sm font-medium text-slate-500">Glisser un fichier ici ou <span class="text-sky-600">parcourir</span></span>
                <span class="text-xs text-slate-400">Tous types · max 20 Mo</span>
                <input type="file" name="file" id="doc-file-input" class="sr-only" required>
            </label>
            <div id="doc-selected" class="hidden mt-3 flex items-center gap-2 text-sm text-slate-600">
                <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <span id="doc-selected-name" class="truncate"></span>
                <button type="submit"
                        class="ml-auto flex-shrink-0 px-4 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-semibold rounded-lg transition-colors">
                    Envoyer
                </button>
            </div>
            @error('file')
                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </form>

        {{-- File list --}}
        @if($documents->isNotEmpty())
        <ul class="mt-6 space-y-2">
            @foreach($documents as $doc)
            <li class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-slate-200 group">
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span class="flex-1 text-sm font-medium text-slate-700 truncate">{{ $doc->original_name }}</span>
                <span class="text-xs text-slate-400 flex-shrink-0">{{ $doc->formattedSize() }}</span>
                <a href="{{ route('uc12.document.download', $doc) }}"
                   class="flex-shrink-0 p-1.5 rounded-md text-slate-400 hover:text-sky-600 hover:bg-sky-50 transition-colors"
                   title="Télécharger">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('instructor.uc12.document.delete', $doc) }}" onsubmit="return confirm('Supprimer ce document ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex-shrink-0 p-1.5 rounded-md text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors"
                            title="Supprimer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </li>
            @endforeach
        </ul>
        @else
        <p class="mt-6 text-xs text-slate-400 text-center py-4">Aucun document déposé.</p>
        @endif
    </div>

    <script>
    (function () {
        var input    = document.getElementById('doc-file-input');
        var selected = document.getElementById('doc-selected');
        var nameEl   = document.getElementById('doc-selected-name');
        var dropZone = document.getElementById('drop-zone');

        input.addEventListener('change', function () {
            if (this.files.length) {
                nameEl.textContent = this.files[0].name;
                selected.classList.remove('hidden');
            }
        });

        ['dragover', 'dragenter'].forEach(function (evt) {
            dropZone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropZone.classList.add('border-sky-400', 'bg-sky-50');
            });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            dropZone.addEventListener(evt, function (e) {
                e.preventDefault();
                dropZone.classList.remove('border-sky-400', 'bg-sky-50');
            });
        });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            var files = e.dataTransfer.files;
            if (files.length) {
                input.files = files;
                nameEl.textContent = files[0].name;
                selected.classList.remove('hidden');
            }
        });
    })();
    </script>

</div>
@endsection
