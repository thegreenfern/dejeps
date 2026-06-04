<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Suivi DEJEPS')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @if(session('role'))
    @php
        $notifCount = \App\Models\AppNotification::unreadCountFor(session('role'), session('trainee_id'));
    @endphp
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-700 tracking-wide">
                    Suivi DEJEPS
                </a>
                <div class="flex items-center gap-5">
                    {{-- Intranet & Aide — both roles --}}
                    @if(session('role') === 'instructor')
                        @php $intranetUrl = route('instructor.ressources.index'); $intranetActive = request()->routeIs('instructor.ressources.*'); @endphp
                    @else
                        @php $intranetUrl = route('trainee.ressources'); $intranetActive = request()->routeIs('trainee.ressources'); @endphp
                    @endif
                    <a href="{{ $intranetUrl }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors
                              {{ $intranetActive ? 'text-violet-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253M3 12c0 .778.099 1.533.284 2.253"/>
                        </svg>
                        Intranet
                    </a>
                    {{-- Instructor-only links --}}
                    @if(session('role') === 'instructor')
                    <a href="{{ route('instructor.progression-config') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors
                              {{ request()->routeIs('instructor.progression-config') ? 'text-violet-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                        </svg>
                        Progression
                    </a>
                    @endif
                    <a href="{{ route('instructor.aide') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors
                              {{ request()->routeIs('instructor.aide') ? 'text-violet-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                        Aide
                    </a>
                    {{-- Calendar link --}}
                    <a href="{{ route('calendar.index') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors
                              {{ request()->routeIs('calendar.*') ? 'text-violet-600' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                        </svg>
                        Calendrier
                    </a>
                    {{-- Notification bell --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative inline-flex items-center transition-colors
                              {{ request()->routeIs('notifications.*') ? 'text-violet-600' : 'text-slate-400 hover:text-slate-600' }}"
                       title="Notifications">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                        @if($notifCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 min-w-[16px] h-4 px-1 rounded-full bg-violet-600 text-white text-[10px] font-bold flex items-center justify-center leading-none">
                                {{ $notifCount > 99 ? '99+' : $notifCount }}
                            </span>
                        @endif
                    </a>
                    {{-- Role switcher --}}
                    <a href="{{ route('home') }}"
                       class="text-xs text-slate-400 hover:text-slate-600 transition-colors">
                        Changer de rôle
                    </a>
                </div>
            </div>
        </header>
    @endif

    <main class="max-w-5xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
