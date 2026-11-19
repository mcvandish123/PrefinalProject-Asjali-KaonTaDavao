@props(['eatery'])

<article class="rounded-lg border border-amber-200 bg-white p-4 shadow-sm">
    <h2 class="text-lg font-semibold text-red-800">
        <a href="{{ route('eateries.show', $eatery) }}" class="hover:underline">{{ $eatery->name }}</a>
        @unless ($eatery->is_open)
            <span class="ml-1 rounded bg-stone-200 px-1.5 py-0.5 text-xs font-normal text-stone-600">Closed</span>
        @endunless
    </h2>
    <p class="text-sm text-stone-500">
        <a href="{{ route('districts.show', $eatery->district) }}" class="font-medium hover:underline">{{ $eatery->district->name }}</a>
        · {{ ucfirst($eatery->price_range) }}
        @if ($eatery->specialty)
            · {{ $eatery->specialty }}
        @endif
    </p>
    @if ($eatery->permit)
        <p class="text-xs text-stone-400">Permit {{ $eatery->permit->permit_no }}</p>
    @endif
    <div class="my-2 flex flex-wrap gap-1">
        @foreach ($eatery->features as $feature)
            <x-feature-badge :feature="$feature" />
        @endforeach
    </div>
    <p class="text-sm text-stone-500">
        @if ($eatery->reviews_avg_rating)
            <x-star-rating :rating="$eatery->reviews_avg_rating" />
            {{ number_format($eatery->reviews_avg_rating, 1) }} ·
        @endif
        {{ $eatery->reviews_count }} {{ Str::plural('review', $eatery->reviews_count) }}
    </p>
</article>
