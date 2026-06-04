{{-- Leaflet map for dive sites. Expects $sites (Collection of SitePlongee). --}}
@php
$sitesMapData = $sites->map(function ($s) {
    return ['nom' => $s->nom, 'lat' => $s->latitude, 'lng' => $s->longitude, 'profondeur' => $s->profondeur_max, 'niveau' => $s->niveau_requis, 'description' => $s->description];
})->values();
@endphp
<div id="sites-map"
     style="height:288px;"
     class="w-full rounded-xl border border-slate-200"
     data-sites='@json($sitesMapData)'></div>
