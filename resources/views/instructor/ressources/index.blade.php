@extends('layouts.app')
@section('title', 'Intranet')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-2xl font-bold text-slate-800 mb-1">Intranet</h1>
    <p class="text-sm text-slate-400 mb-8">UC1 / UC2 · Ressources de formation</p>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- UC1 / UC2 settings                                                --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <form method="POST" action="{{ route('instructor.uc12.settings.save') }}" id="settings-form">
    @csrf

    {{-- Dates officielles --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Dates officielles</h2>
        <p class="text-xs text-slate-400 mb-5">Ces dates sont affichées dans le calendrier visible par le formateur sur chaque fiche stagiaire.</p>
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
        <input type="hidden" name="uc2_submission_deadline"
               value="{{ $settings->uc2_submission_deadline?->format('Y-m-d') ?? '2026-06-12' }}">
    </div>

    {{-- Centre de plongée --}}
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

    {{-- Documents --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-10">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-1">Documents UC1 / UC2</h2>
        <p class="text-xs text-slate-400 mb-5">Les fichiers déposés ici sont téléchargeables par les stagiaires.</p>

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
                    @csrf @method('DELETE')
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

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- Ressources                                                         --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Ressources pédagogiques</h2>
            <p class="text-sm text-slate-400 mt-0.5">Documents et liens pour la formation</p>
        </div>
        <button onclick="openModal('modal-add')"
                class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter une ressource
        </button>
    </div>

    @foreach($sections as $key => $label)
        @php $items = $ressources->get($key, collect()); @endphp
        <div class="mb-8" id="section-{{ $key }}">
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">{{ $label }}</h3>
                <div class="flex-1 h-px bg-slate-200"></div>
                <button onclick="openModalForSection('modal-add', '{{ $key }}')"
                        class="text-xs text-violet-600 hover:text-violet-700 font-medium transition-colors">
                    + Ajouter
                </button>
            </div>

            @if($items->isEmpty())
                <div class="bg-white border border-dashed border-slate-200 rounded-xl px-5 py-4 text-center">
                    <p class="text-xs text-slate-400">Aucune ressource dans cette section.</p>
                </div>
            @else
                <div class="space-y-2" data-section="{{ $key }}">
                    @foreach($items as $item)
                        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-4 py-3 group hover:border-slate-300 transition-all"
                             data-id="{{ $item->id }}">

                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                @if($item->icon() === 'pdf') bg-red-50
                                @elseif($item->icon() === 'word') bg-blue-50
                                @elseif($item->icon() === 'excel') bg-emerald-50
                                @elseif($item->icon() === 'image') bg-amber-50
                                @elseif($item->icon() === 'link') bg-violet-50
                                @else bg-slate-100 @endif">
                                @if($item->icon() === 'pdf')
                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                @elseif($item->icon() === 'link')
                                    <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                @if($item->isFile())
                                    <a href="{{ route('instructor.ressources.download', $item) }}"
                                       class="text-sm font-medium text-slate-700 hover:text-violet-600 transition-colors truncate block">
                                        {{ $item->titre }}
                                    </a>
                                    <p class="text-xs text-slate-400 truncate">{{ $item->fichier_nom_original }}</p>
                                @else
                                    <a href="{{ $item->lien_externe }}" target="_blank" rel="noopener noreferrer"
                                       class="text-sm font-medium text-slate-700 hover:text-violet-600 transition-colors truncate block">
                                        {{ $item->titre }}
                                    </a>
                                    <p class="text-xs text-slate-400 truncate">{{ $item->lien_externe }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <button onclick="moveItem(this, -1)" title="Monter"
                                        class="p-1 rounded text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button onclick="moveItem(this, 1)" title="Descendre"
                                        class="p-1 rounded text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('instructor.ressources.destroy', $item) }}"
                                      onsubmit="return confirm('Supprimer cette ressource ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="p-1 rounded text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    {{-- Sites de plongée --}}
    <div class="mt-4 mb-8">
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Sites de plongée</h3>
            <div class="flex-1 h-px bg-slate-200"></div>
            <button onclick="openModal('modal-add-site')"
                    class="text-xs text-violet-600 hover:text-violet-700 font-medium transition-colors">
                + Ajouter un site
            </button>
        </div>

        <div class="mb-4">
            @include('partials.sites-map')
        </div>

        @if($sites->isEmpty())
            <div class="bg-white border border-dashed border-slate-200 rounded-xl px-5 py-6 text-center">
                <p class="text-xs text-slate-400">Aucun site enregistré.</p>
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left text-xs font-semibold text-slate-500 px-4 py-3">Nom</th>
                            <th class="text-left text-xs font-semibold text-slate-500 px-4 py-3">Coordonnées</th>
                            <th class="text-left text-xs font-semibold text-slate-500 px-4 py-3">Prof. max</th>
                            <th class="text-left text-xs font-semibold text-slate-500 px-4 py-3">Niveau</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sites as $site)
                            <tr class="group hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $site->nom }}</p>
                                    @if($site->description)
                                        <p class="text-xs text-slate-400 mt-0.5">{{ mb_substr($site->description, 0, 60) }}{{ mb_strlen($site->description) > 60 ? '…' : '' }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-500 font-mono">
                                    {{ number_format($site->latitude, 4) }}, {{ number_format($site->longitude, 4) }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $site->profondeur_max ? $site->profondeur_max . ' m' : '–' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($site->niveau_requis)
                                        <span class="inline-flex items-center text-xs bg-slate-100 text-slate-600 rounded-full px-2 py-0.5">
                                            {{ $site->niveau_requis }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">–</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity justify-end">
                                        <button onclick="openEditSite({{ $site->id }}, {{ json_encode($site->toArray()) }})"
                                                class="p-1 rounded text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('instructor.ressources.sites.destroy', $site) }}"
                                              onsubmit="return confirm('Supprimer ce site ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="p-1 rounded text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Modal: Add Ressource --}}
<div id="modal-add" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modal-add')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Ajouter une ressource</h3>
                <button onclick="closeModal('modal-add')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('instructor.ressources.store') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Section</label>
                    <select name="section" id="modal-section-select" required
                            class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                        @foreach($sections as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Titre</label>
                    <input type="text" name="titre" required maxlength="255"
                           placeholder="Titre de la ressource"
                           class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-2">Type</label>
                    <div class="flex gap-2 mb-3">
                        <button type="button" onclick="switchType('file')"
                                id="tab-file"
                                class="flex-1 text-xs font-medium py-1.5 rounded-lg border border-violet-600 bg-violet-600 text-white transition-colors">
                            Fichier
                        </button>
                        <button type="button" onclick="switchType('link')"
                                id="tab-link"
                                class="flex-1 text-xs font-medium py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-slate-300 transition-colors">
                            Lien externe
                        </button>
                    </div>
                    <div id="type-file">
                        <input type="file" name="fichier"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp"
                               class="w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 transition-colors">
                        <p class="text-xs text-slate-400 mt-1">PDF, Word, Excel, images — max 20 Mo</p>
                    </div>
                    <div id="type-link" class="hidden">
                        <input type="url" name="lien_externe" maxlength="500"
                               placeholder="https://..."
                               class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('modal-add')"
                            class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2 rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Add/Edit Site --}}
<div id="modal-add-site" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modal-add-site')"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 id="modal-site-title" class="text-base font-semibold text-slate-800">Ajouter un site</h3>
                <button onclick="closeModal('modal-add-site')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="form-site" method="POST" action="{{ route('instructor.ressources.sites.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <span id="method-site"></span>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Nom du site</label>
                        <input type="text" name="nom" id="site-nom" required maxlength="255"
                               class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Latitude</label>
                        <input type="number" name="latitude" id="site-lat" step="0.0000001" required
                               class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Longitude</label>
                        <input type="number" name="longitude" id="site-lng" step="0.0000001" required
                               class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Profondeur max (m)</label>
                        <input type="number" name="profondeur_max" id="site-profondeur" min="1" max="300"
                               class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Niveau requis</label>
                        <select name="niveau_requis" id="site-niveau"
                                class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent">
                            <option value="">–</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau }}">{{ $niveau }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Description</label>
                        <textarea name="description" id="site-description" rows="2" maxlength="1000"
                                  class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent resize-none"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('modal-add-site')"
                            class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2 rounded-lg transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function openModalForSection(modalId, section) {
    document.getElementById('modal-section-select').value = section;
    openModal(modalId);
}
function switchType(type) {
    var fileDiv = document.getElementById('type-file');
    var linkDiv = document.getElementById('type-link');
    var tabFile = document.getElementById('tab-file');
    var tabLink = document.getElementById('tab-link');
    if (type === 'file') {
        fileDiv.classList.remove('hidden');
        linkDiv.classList.add('hidden');
        tabFile.classList.add('bg-violet-600', 'text-white', 'border-violet-600');
        tabFile.classList.remove('text-slate-500', 'border-slate-200');
        tabLink.classList.remove('bg-violet-600', 'text-white', 'border-violet-600');
        tabLink.classList.add('text-slate-500', 'border-slate-200');
    } else {
        linkDiv.classList.remove('hidden');
        fileDiv.classList.add('hidden');
        tabLink.classList.add('bg-violet-600', 'text-white', 'border-violet-600');
        tabLink.classList.remove('text-slate-500', 'border-slate-200');
        tabFile.classList.remove('bg-violet-600', 'text-white', 'border-violet-600');
        tabFile.classList.add('text-slate-500', 'border-slate-200');
    }
}

function openEditSite(id, data) {
    document.getElementById('modal-site-title').textContent = 'Modifier le site';
    var form = document.getElementById('form-site');
    form.action = '/formateur/ressources/sites/' + id;
    document.getElementById('method-site').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('site-nom').value         = data.nom || '';
    document.getElementById('site-lat').value         = data.latitude || '';
    document.getElementById('site-lng').value         = data.longitude || '';
    document.getElementById('site-profondeur').value  = data.profondeur_max || '';
    document.getElementById('site-niveau').value      = data.niveau_requis || '';
    document.getElementById('site-description').value = data.description || '';
    openModal('modal-add-site');
}

(function () {
    var closeBtn = document.getElementById('modal-add-site').querySelector('button[onclick*="closeModal"]');
    var overlay  = document.getElementById('modal-add-site').querySelector('.absolute.inset-0');
    function resetSiteModal() {
        document.getElementById('modal-site-title').textContent = 'Ajouter un site';
        var form = document.getElementById('form-site');
        form.action = '{{ route('instructor.ressources.sites.store') }}';
        document.getElementById('method-site').innerHTML = '';
        form.reset();
    }
    closeBtn.addEventListener('click', resetSiteModal);
    overlay.addEventListener('click', resetSiteModal);
})();

function moveItem(btn, direction) {
    var row  = btn.closest('[data-id]');
    var list = row.parentElement;
    if (direction === -1 && row.previousElementSibling) {
        list.insertBefore(row, row.previousElementSibling);
    } else if (direction === 1 && row.nextElementSibling) {
        list.insertBefore(row.nextElementSibling, row);
    }
    var ids = Array.from(list.querySelectorAll('[data-id]')).map(function(el) { return parseInt(el.dataset.id); });
    fetch('{{ route('instructor.ressources.reorder') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify({ ids: ids }),
    });
}

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
@endsection
