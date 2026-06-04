@extends('layouts.app')
@section('title', 'Ressources · ' . $trainee->name)

@section('content')
<div class="max-w-3xl mx-auto">

    @include('trainee._nav')

    <h2 class="text-xl font-bold text-slate-800 mb-1">Ressources</h2>
    <p class="text-sm text-slate-400 mb-6">Documents et liens pour votre formation DEJEPS Plongée</p>

    {{-- Sections --}}
    @foreach($sections as $key => $label)
        @php $items = $ressources->get($key, collect()); @endphp
        @if($items->isNotEmpty())
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">{{ $label }}</h3>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>
                <div class="space-y-2">
                    @foreach($items as $item)
                        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-4 py-3 hover:border-slate-300 transition-all">

                            {{-- Icon --}}
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

                            {{-- Title --}}
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

                            {{-- External link indicator --}}
                            @if($item->isLink())
                                <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            @else
                                <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    @if($ressources->isEmpty())
        <div class="bg-white border border-dashed border-slate-200 rounded-xl px-5 py-12 text-center">
            <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <p class="text-sm text-slate-400">Aucune ressource disponible pour l'instant.</p>
        </div>
    @endif

    {{-- Sites de plongée --}}
    @if($sites->isNotEmpty())
        <div class="mt-6 mb-8">
            <div class="flex items-center gap-3 mb-4">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Sites de plongée</h3>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            {{-- Map --}}
            <div class="mb-4">
                @include('partials.sites-map')
            </div>

            {{-- Sites list --}}
            <div class="space-y-2">
                @foreach($sites as $site)
                    <div class="bg-white border border-slate-200 rounded-xl px-4 py-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-800 text-sm">{{ $site->nom }}</p>
                                @if($site->description)
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $site->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 mt-0.5">
                                @if($site->profondeur_max)
                                    <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 rounded-full px-2 py-0.5">
                                        {{ $site->profondeur_max }} m
                                    </span>
                                @endif
                                @if($site->niveau_requis)
                                    <span class="text-xs bg-slate-100 text-slate-600 rounded-full px-2 py-0.5">
                                        {{ $site->niveau_requis }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
