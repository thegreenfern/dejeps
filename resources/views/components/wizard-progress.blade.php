@props(['current' => 1])

@php
$steps = [
    1 => 'Profil',
    2 => 'Personnalité',
    3 => 'Auto-évaluation',
    4 => 'Confirmation',
];
@endphp

<div class="mb-10">
    <div class="flex items-center justify-between">
        @foreach($steps as $num => $label)
            @php
                $done    = $num < $current;
                $active  = $num === $current;
                $pending = $num > $current;
            @endphp

            <div class="flex flex-col items-center flex-1 relative">
                {{-- Connector line (before) --}}
                @if($num > 1)
                    <div class="absolute left-0 top-4 w-1/2 h-px
                        {{ $done || $active ? 'bg-sky-500' : 'bg-slate-200' }}">
                    </div>
                @endif

                {{-- Connector line (after) --}}
                @if($num < count($steps))
                    <div class="absolute right-0 top-4 w-1/2 h-px
                        {{ $done ? 'bg-sky-500' : 'bg-slate-200' }}">
                    </div>
                @endif

                {{-- Circle --}}
                <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold
                    {{ $done   ? 'bg-sky-500 text-white' : '' }}
                    {{ $active ? 'bg-sky-600 text-white ring-4 ring-sky-100' : '' }}
                    {{ $pending ? 'bg-white text-slate-400 border-2 border-slate-200' : '' }}">
                    @if($done)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $num }}
                    @endif
                </div>

                {{-- Label --}}
                <span class="mt-2 text-xs
                    {{ $active  ? 'text-sky-700 font-semibold' : '' }}
                    {{ $done    ? 'text-sky-500' : '' }}
                    {{ $pending ? 'text-slate-400' : '' }}">
                    {{ $label }}
                </span>
            </div>
        @endforeach
    </div>
</div>
