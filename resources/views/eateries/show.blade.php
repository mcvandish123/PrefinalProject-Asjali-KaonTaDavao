@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold">
                {{ $eatery->name }}
                @unless ($eatery->is_open)
                    <span class="ml-1 rounded bg-stone-200 px-1.5 py-0.5 text-xs font-normal text-stone-600">Closed</span>
                @endunless
            </h1>
            <p class="text-stone-500">
                <a href="{{ route('districts.show', $eatery->district) }}" class="font-medium hover:underline">{{ $eatery->district->name }}</a>
                · {{ $eatery->address }} · {{ str_repeat('₱', ['budget' => 1, 'mid' => 2, 'splurge' => 3][$eatery->price_range] ?? 1) }}
                @if ($eatery->specialty)
                    · Specialty: {{ $eatery->specialty }}
                @endif
            </p>
            <p class="mt-1 text-sm text-stone-500">
                @if ($averageRating)
                    <x-star-rating :rating="$averageRating" /> {{ number_format($averageRating, 1) }} ·
                @endif
                {{ $eatery->reviews->count() }} {{ Str::plural('review', $eatery->reviews->count()) }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('eateries.edit', $eatery) }}" class="rounded border border-stone-300 px-3 py-1 text-sm hover:bg-white">Edit</a>
            <form method="POST" action="{{ route('eateries.destroy', $eatery) }}" onsubmit="return confirm('Delete this eatery?')">
                @csrf
                @method('DELETE')
                <button class="rounded border border-red-300 px-3 py-1 text-sm text-red-700 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="mb-4 flex flex-wrap gap-1">
        @foreach ($eatery->features as $feature)
            <x-feature-badge :feature="$feature" />
        @endforeach
    </div>

    @if ($eatery->permit)
        <section class="mb-6 rounded border border-amber-200 bg-white p-4 text-sm">
            <h2 class="mb-1 font-semibold">Business permit</h2>
            <p>
                {{ $eatery->permit->permit_no }} · expires {{ $eatery->permit->expires_on->format('F j, Y') }}
                @if ($eatery->permit->isExpired())
                    <span class="font-semibold text-red-700">Permit expired</span>
                @endif
            </p>
        </section>
    @endif

    <section class="mb-8">
        <h2 class="mb-2 text-lg font-semibold">Dishes</h2>
        <ul class="divide-y rounded border border-amber-200 bg-white">
            @forelse ($eatery->dishes as $dish)
                <li class="flex justify-between px-4 py-2">
                    <span>
                        {{ $dish->name }}
                        @if ($dish->is_bestseller)
                            <span class="ml-1 rounded bg-red-100 px-1.5 py-0.5 text-xs text-red-800">Bestseller</span>
                        @endif
                    </span>
                    <span>₱{{ number_format($dish->price, 2) }}</span>
                </li>
            @empty
                <li class="px-4 py-2 text-stone-500">No dishes listed.</li>
            @endforelse
        </ul>
    </section>

    <section>
        <h2 class="mb-2 text-lg font-semibold">Reviews</h2>
        @forelse ($eatery->reviews as $review)
            @include('reviews.partials.review', ['context' => 'eatery'])
        @empty
            <p class="mb-4 text-stone-500">No reviews yet.</p>
        @endforelse

        @unless ($eatery->is_open)
            <p class="mt-6 rounded border border-stone-300 bg-stone-100 p-4 text-stone-600">This eatery is closed. Reviews are paused.</p>
        @else
        <form method="POST" action="{{ route('eateries.reviews.store', $eatery) }}" class="mt-6 max-w-xl rounded border border-amber-200 bg-white p-4">
            @csrf
            <h3 class="mb-3 font-semibold">Write a review</h3>

            <x-form-field name="user_id" label="Reviewer">
                <select id="user_id" name="user_id" class="w-full rounded border border-stone-300 px-3 py-2">
                    <option value="">Choose a reviewer</option>
                    @foreach ($reviewers as $reviewer)
                        <option value="{{ $reviewer->id }}" @selected(old('user_id') == $reviewer->id)>{{ $reviewer->name }}</option>
                    @endforeach
                </select>
            </x-form-field>

            <x-form-field name="rating" label="Rating (1–5)">
                <input id="rating" name="rating" type="number" min="1" max="5" value="{{ old('rating') }}" class="w-24 rounded border border-stone-300 px-3 py-2">
            </x-form-field>

            <x-form-field name="body" label="Your review">
                <textarea id="body" name="body" rows="3" class="w-full rounded border border-stone-300 px-3 py-2">{{ old('body') }}</textarea>
            </x-form-field>

            <button type="submit" class="rounded bg-red-800 px-4 py-2 font-semibold text-white hover:bg-red-700">Submit review</button>
        </form>
        @endunless
    </section>
@endsection
