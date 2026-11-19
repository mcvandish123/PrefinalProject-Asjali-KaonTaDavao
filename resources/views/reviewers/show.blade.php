@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold">{{ $reviewer->name }}</h1>
    <p class="mb-6 text-stone-500">
        {{ $reviewer->reviews->count() }} {{ Str::plural('review', $reviewer->reviews->count()) }}
        @if ($reviewer->reviews->isNotEmpty())
            · average {{ number_format($reviewer->reviews->avg('rating'), 1) }} / 5
        @endif
    </p>

    @forelse ($reviewer->reviews as $review)
        @include('reviews.partials.review', ['showEatery' => true])
    @empty
        <p class="text-stone-500">This reviewer has not written any reviews yet.</p>
    @endforelse
@endsection
