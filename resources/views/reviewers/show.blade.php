@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold">{{ $reviewer->name }}</h1>
    <p class="mb-6 text-stone-500">{{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }} written</p>

    @forelse ($reviews as $review)
        @include('reviews.partials.review', ['context' => 'reviewer'])
    @empty
        <p class="text-stone-500">No reviews written yet.</p>
    @endforelse
@endsection
