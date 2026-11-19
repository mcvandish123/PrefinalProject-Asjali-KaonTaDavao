<div class="mb-3 rounded border border-amber-200 bg-white p-4">
    <div class="flex items-center justify-between text-sm">
        <a href="{{ route('reviewers.show', $review->user) }}" class="font-semibold text-red-800 hover:underline">{{ $review->user->name }}</a>
        <x-star-rating :rating="$review->rating" />
    </div>
    <p class="mt-1">{{ $review->body }}</p>
    @isset($showEatery)
        <p class="mt-1 text-xs text-stone-500">
            on <a href="{{ route('eateries.show', $review->eatery) }}" class="hover:underline">{{ $review->eatery->name }}</a>
        </p>
    @endisset
</div>
