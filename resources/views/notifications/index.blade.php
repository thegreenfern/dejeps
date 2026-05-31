@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Back --}}
    <a href="{{ $role === 'instructor' ? route('instructor.dashboard') : route('trainee.dashboard') }}"
       class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition-colors mb-6">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour
    </a>

    <h1 class="text-lg font-bold text-slate-800 mb-6">Notifications</h1>

    @if($notifications->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                </svg>
            </div>
            <p class="text-sm text-slate-500">Aucune notification pour le moment.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notif)
            @php
                $data        = $notif->data ?? [];
                $sessionLabel = $data['session_label'] ?? $notif->slug;
                $sessionLevel = $data['session_level'] ?? null;
                $traineeName  = $data['trainee_name'] ?? $notif->trainee?->name ?? '—';

                if ($notif->type === 'seance_added') {
                    $icon      = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2';
                    $iconColor = 'text-sky-500';
                    $iconBg    = 'bg-sky-50';
                    $title     = $traineeName . ' a ajouté une auto-évaluation';
                    $subtitle  = $sessionLabel . ($sessionLevel ? ' · ' . $sessionLevel : '');
                    $link      = route('notifications.read', $notif);
                    $linkLabel = 'Voir la séance';
                } elseif ($notif->type === 'review_request') {
                    $icon      = 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
                    $iconColor = 'text-amber-500';
                    $iconBg    = 'bg-amber-50';
                    $title     = $traineeName . ' demande un retour sur son avancement';
                    $subtitle  = 'Calendrier du projet · UC1 / UC2';
                    $link      = route('notifications.read', $notif);
                    $linkLabel = 'Donner un retour';
                } elseif ($notif->type === 'project_feedback') {
                    $icon      = 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z';
                    $iconColor = 'text-emerald-500';
                    $iconBg    = 'bg-emerald-50';
                    $title     = 'Votre formateur a laissé un retour sur votre projet';
                    $subtitle  = $data['feedback_text'] ?? '';
                    $link      = route('notifications.read', $notif);
                    $linkLabel = 'Voir le retour';
                } else {
                    $icon      = 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z';
                    $iconColor = 'text-violet-500';
                    $iconBg    = 'bg-violet-50';
                    $title     = 'Votre formateur a évalué une séance';
                    $subtitle  = $sessionLabel . ($sessionLevel ? ' · ' . $sessionLevel : '');
                    $link      = route('notifications.read', $notif);
                    $linkLabel = 'Voir l\'évaluation';
                }
            @endphp
            <div class="bg-white rounded-xl border {{ $notif->read_at ? 'border-slate-200' : 'border-violet-200' }} p-4 flex items-start gap-4">
                <div class="w-9 h-9 rounded-full {{ $iconBg }} flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4.5 h-4.5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800">{{ $title }}</p>
                    @if($subtitle)
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $subtitle }}</p>
                    @endif
                    <p class="text-[11px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if(!$notif->read_at)
                        <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    @endif
                    <a href="{{ $link }}"
                       class="text-xs font-medium text-violet-600 hover:text-violet-800 whitespace-nowrap">
                        {{ $linkLabel }} →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
