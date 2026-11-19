@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold">Eateries in {{ $district->name }}</h1>
    <p class="mb-6 text-stone-500">{{ $district->reviews_count }} {{ Str::plural('review', $district->reviews_count) }} in {{ $district->name }}</p>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($eateries as $eatery)
            <x-eatery-card :eatery="$eatery" />
        @empty
            <p>No eateries in this district yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $eateries->links() }}</div>
@endsection
