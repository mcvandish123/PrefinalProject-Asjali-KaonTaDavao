@props(['rating', 'compact' => false])

@php
    $rounded = (int) round($rating);
@endphp

@if ($compact)
    <span class="text-amber-600" title="{{ number_format($rating, 1) }} out of 5">{{ number_format($rating, 1) }} ★</span>
@else
    <span class="text-amber-500" title="{{ $rating }} out of 5" aria-label="{{ $rating }} out of 5 stars">{{ str_repeat('★', $rounded) }}<span class="text-stone-300">{{ str_repeat('★', 5 - $rounded) }}</span></span>
@endif
