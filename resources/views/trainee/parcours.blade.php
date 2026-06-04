@extends('layouts.app')
@section('title', 'Mon parcours · ' . $trainee->name)

@section('content')
<div class="max-w-3xl mx-auto">

    @include('trainee._nav')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-800">Mon parcours</h1>
        <p class="text-sm text-slate-400">{{ $trainee->name }} · Formation DEJEPS Plongée</p>
    </div>

    @php
        $today        = \Carbon\Carbon::today();
        $tlEvents     = $timeline['events'];
        $tlOngoing    = $timeline['ongoing'];
        $sessionCount = collect($tlEvents)->where('type', 'session')->count();
        $grouped = [];
        foreach ($tlEvents as $event) {
            $monthKey = \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('MMMM YYYY');
            $grouped[$monthKey][] = $event;
        }
        $ratingLabel = ['A' => 'Acquis', 'ECA' => 'En cours', 'NT' => 'Non traité'];
        $ratingColor = [
            'A'   => ['bg' => '#f0fdf4', 'border' => '#86efac', 'text' => '#15803d', 'dot' => '#22c55e'],
            'ECA' => ['bg' => '#fffbeb', 'border' => '#fcd34d', 'text' => '#92400e', 'dot' => '#f59e0b'],
            'NT'  => ['bg' => '#f8fafc', 'border' => '#cbd5e1', 'text' => '#64748b', 'dot' => '#94a3b8'],
        ];
        $levelColor = ['N1' => '#0ea5e9', 'N2' => '#8b5cf6', 'N3' => '#f97316', 'N4' => '#ec4899'];
    @endphp

    {{-- Summary bar --}}
    <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:10px 16px; flex-shrink:0;">
            <svg style="width:16px;height:16px;color:#0ea5e9;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ $sessionCount }} séance{{ $sessionCount > 1 ? 's' : '' }} réalisée{{ $sessionCount > 1 ? 's' : '' }}</span>
        </div>
        @if(!empty($tlOngoing))
            <div style="flex:1; min-width:0; background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:10px 14px;">
                <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#0ea5e9;flex-shrink:0;animation:pulse 2s infinite;"></span>
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#0284c7;">En cours</span>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:6px;">
                    @foreach($tlOngoing as $task)
                        @php $urgent = $task['days_left'] <= 7; @endphp
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:500;color:#1e293b;background:#fff;border:1px solid {{ $urgent ? '#fed7aa' : '#e2e8f0' }};border-radius:8px;padding:3px 10px;">
                            {{ $task['name'] }}
                            <span style="font-size:10px;font-weight:700;color:{{ $urgent ? '#ea580c' : '#64748b' }};">J-{{ $task['days_left'] }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>

    @if(empty($tlEvents))
        <div style="text-align:center; padding:48px 24px; color:#94a3b8;">
            <svg style="width:40px;height:40px;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/>
            </svg>
            <p style="font-size:14px;">Aucun événement à afficher</p>
        </div>
    @else
    <div style="position:relative; padding-left:88px;">
        <div style="position:absolute; left:43px; top:0; bottom:0; width:2px; background:#e2e8f0; border-radius:1px;"></div>

        @foreach($grouped as $monthKey => $events)
            <div style="position:relative; margin-bottom:6px; margin-top:20px;">
                <div style="position:absolute; left:-52px; width:18px; height:18px; border-radius:50%; background:#e2e8f0; border:2px solid #fff; top:1px;"></div>
                <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8;">{{ $monthKey }}</span>
            </div>

            @foreach($events as $event)
                @if($event['type'] === 'deadline')
                    @php
                        $isPast   = $event['past'];
                        $isToday  = $event['days'] === 0;
                        $isUrgent = !$isPast && $event['days'] <= 14;
                    @endphp
                    <div style="position:relative; margin-bottom:10px;">
                        <div style="position:absolute; left:-50px; top:14px; width:14px; height:14px; border-radius:50%; background:{{ $isPast ? '#cbd5e1' : ($isUrgent ? '#ea580c' : '#0284c7') }}; border:2px solid #fff; box-shadow:0 0 0 3px {{ $isPast ? '#f1f5f9' : ($isUrgent ? '#fff7ed' : '#f0f9ff') }};"></div>
                        <div style="position:absolute; left:-86px; top:13px; width:28px; text-align:right; font-size:10px; font-weight:600; color:#94a3b8;">
                            {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                        </div>
                        <div style="background:{{ $isPast ? '#f8fafc' : ($isUrgent ? '#fff7ed' : '#f0f9ff') }}; border:1px solid {{ $isPast ? '#e2e8f0' : ($isUrgent ? '#fed7aa' : '#bae6fd') }}; border-radius:12px; padding:12px 16px; {{ $isPast ? 'opacity:0.75;' : '' }} display:flex; align-items:center; gap:12px;">
                            <div style="flex-shrink:0; width:32px; height:32px; border-radius:8px; background:{{ $isPast ? '#e2e8f0' : ($isUrgent ? '#ea580c' : '#0284c7') }}; display:flex; align-items:center; justify-content:center;">
                                @if($event['icon'] === 'document')
                                    <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @elseif($event['icon'] === 'star')
                                    <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @else
                                    <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21l1.653-3.306A9.974 9.974 0 013 12C3 6.477 7.477 2 13 2s10 4.477 10 10-4.477 10-10 10a9.96 9.96 0 01-5.824-1.881L3 21z" />
                                    </svg>
                                @endif
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:13px; font-weight:600; color:{{ $isPast ? '#64748b' : '#1e293b' }};">{{ $event['label'] }}</div>
                                <div style="font-size:11px; color:#94a3b8; margin-top:1px;">{{ \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
                            </div>
                            @if(!$isPast)
                                <span style="flex-shrink:0; font-size:11px; font-weight:700; padding:3px 9px; border-radius:999px; background:{{ $isUrgent ? '#ea580c' : '#0284c7' }}; color:#fff;">
                                    {{ $event['days'] === 0 ? "Aujourd'hui" : 'J-' . $event['days'] }}
                                </span>
                            @else
                                <span style="flex-shrink:0; font-size:11px; font-weight:600; padding:3px 9px; border-radius:999px; background:#e2e8f0; color:#64748b;">Passé</span>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Session event --}}
                    @php
                        $r  = $event['rating'];
                        $rc = $ratingColor[$r] ?? $ratingColor['NT'];
                        $lc = $levelColor[$event['level']] ?? '#64748b';
                    @endphp
                    <div style="position:relative; margin-bottom:10px;">
                        <div style="position:absolute; left:-50px; top:14px; width:10px; height:10px; border-radius:50%; background:{{ $rc['dot'] }}; border:2px solid #fff; box-shadow:0 0 0 2px {{ $rc['border'] }};"></div>
                        <div style="position:absolute; left:-86px; top:13px; width:28px; text-align:right; font-size:10px; font-weight:600; color:#94a3b8;">
                            {{ \Carbon\Carbon::parse($event['date'])->format('d') }}
                        </div>
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:12px 16px; display:flex; align-items:flex-start; gap:10px;">
                            <div style="flex-shrink:0; width:3px; align-self:stretch; border-radius:2px; background:{{ $lc }}; min-height:32px;"></div>
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                    <span style="font-size:13px; font-weight:600; color:#1e293b;">{{ $event['label'] }}</span>
                                    @if($event['level'])
                                        <span style="font-size:10px; font-weight:700; padding:1px 7px; border-radius:999px; background:{{ $lc }}1a; color:{{ $lc }}; border:1px solid {{ $lc }}40;">{{ $event['level'] }}</span>
                                    @endif
                                </div>
                                @if($event['situation'])
                                    <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $event['situation'] }}</div>
                                @endif
                                @if($event['global_comment'] ?? null)
                                    <div style="font-size:11px; color:#475569; margin-top:4px; font-style:italic; line-height:1.4;">{{ $event['global_comment'] }}</div>
                                @endif
                                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">{{ \Carbon\Carbon::parse($event['date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</div>
                            </div>
                            @if($r)
                                <span style="flex-shrink:0; align-self:flex-start; font-size:11px; font-weight:600; padding:3px 9px; border-radius:999px; background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}; border:1px solid {{ $rc['border'] }};">{{ $r }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
    @endif

    <div class="pb-10"></div>
</div>
@endsection
