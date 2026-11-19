<div class="mb-3 rounded border border-amber-200 bg-white p-4">
    <div class="flex flex-wrap items-center justify-between gap-2 text-sm">
        @if ($context === 'reviewer')
            <a href="{{ route('eateries.show', $review->eatery) }}" class="font-semibold text-red-800 hover:underline">{{ $review->eatery->name }}</a>
        @else
            <a href="{{ route('reviewers.show', $review->user) }}" class="font-semibold text-red-800 hover:underline">{{ $review->user->name }}</a>
        @endif
        <span class="flex items-center gap-2">
            <x-star-rating :rating="$review->rating" />
            <span class="text-stone-500">{{ $review->rating }}/5</span>
            @if ($context === 'eatery')
                <span class="text-stone-400">{{ $review->created_at->format('M j, Y') }}</span>
            @endif
        </span>
    </div>
    <p class="mt-1">{{ $review->body }}</p>
    @if ($context === 'eatery')
        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-2" onsubmit="return confirm('Delete this review?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs text-red-700 hover:underline">Delete</button>
        </form>
    @endif
</div>
