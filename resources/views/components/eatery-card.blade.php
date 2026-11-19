@props(['eatery'])

@php
    $priceSymbols = str_repeat('₱', ['budget' => 1, 'mid' => 2, 'splurge' => 3][$eatery->price_range] ?? 1);
@endphp

<article class="rounded-lg border border-amber-200 bg-white p-4 shadow-sm">
    <h2 class="text-lg font-semibold text-red-800">
        <a href="{{ route('eateries.show', $eatery) }}" class="hover:underline">{{ $eatery->name }}</a>
        @unless ($eatery->is_open)
            <span class="ml-1 rounded bg-stone-200 px-1.5 py-0.5 text-xs font-normal text-stone-600">Closed</span>
        @endunless
    </h2>
    <p class="text-sm text-stone-500">
        <a href="{{ route('districts.show', $eatery->district) }}" class="font-medium hover:underline">{{ $eatery->district->name }}</a>
        · {{ $priceSymbols }}
        @if ($eatery->specialty)
            · {{ $eatery->specialty }}
        @endif
    </p>
    <div class="my-2 flex flex-wrap gap-1">
        @foreach ($eatery->features as $feature)
            <x-feature-badge :feature="$feature" />
        @endforeach
    </div>
    <p class="text-sm text-stone-600">
        @if ($eatery->reviews_count > 0)
            <x-rating :rating="$eatery->reviews_avg_rating" compact /> ({{ $eatery->reviews_count }} {{ Str::plural('review', $eatery->reviews_count) }})
        @else
            No reviews yet
        @endif
    </p>
</article>
