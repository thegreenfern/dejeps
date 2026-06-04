{{-- Trainee section navigation — used on sub-pages (all items are links) --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        @if($trainee->photo_path)
            <img src="{{ Storage::url($trainee->photo_path) }}"
                 class="w-12 h-12 rounded-full object-cover border border-slate-200 flex-shrink-0">
        @else
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        @endif
        <div>
            <h1 class="text-xl font-bold text-slate-800">Bonjour, {{ explode(' ', $trainee->name)[0] }}</h1>
            <p class="text-sm text-slate-400">Formation DEJEPS Plongée</p>
        </div>
    </div>
    <a href="{{ route('trainee.profile.edit') }}"
       class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg border border-slate-200 text-slate-500 hover:border-slate-300 hover:text-slate-700 transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
        </svg>
        Mon profil
    </a>
</div>

<div class="flex gap-1 bg-slate-100 rounded-xl p-1 mb-6">
    @php
        $navItems = [
            'trainee.dashboard'      => ['label' => 'UC1 / UC2',    'url' => route('trainee.dashboard') . '#uc12'],
            'trainee.dashboard_epmsp'=> ['label' => 'EPMSP',        'url' => route('trainee.dashboard') . '#epmsp'],
            'trainee.dp'             => ['label' => 'Dir. plongée', 'url' => route('trainee.dp')],
            'trainee.comp-annexes'   => ['label' => 'Annexes',      'url' => route('trainee.comp-annexes')],
            'trainee.peda'           => ['label' => 'Pédagogie',    'url' => route('trainee.peda')],
            'trainee.parcours'       => ['label' => 'Parcours',     'url' => route('trainee.parcours')],
            'trainee.ressources'     => ['label' => 'Ressources',   'url' => route('trainee.ressources')],
        ];
        $current = request()->routeIs('trainee.dp') ? 'trainee.dp'
                 : (request()->routeIs('trainee.comp-annexes') ? 'trainee.comp-annexes'
                 : (request()->routeIs('trainee.peda') ? 'trainee.peda'
                 : (request()->routeIs('trainee.parcours') ? 'trainee.parcours'
                 : (request()->routeIs('trainee.ressources') ? 'trainee.ressources'
                 : null))));
    @endphp
    @foreach($navItems as $key => $item)
        @php $isActive = $key === $current; @endphp
        <a href="{{ $item['url'] }}"
           class="flex-1 py-2 px-3 rounded-lg text-sm font-medium text-center transition-colors
                  {{ $isActive ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-white/60' }}">
            {{ $item['label'] }}
        </a>
    @endforeach
</div>
