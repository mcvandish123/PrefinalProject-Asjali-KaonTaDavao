@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold">{{ $district->name }}</h1>
    <p class="mb-6 text-stone-500">{{ $district->reviews_count }} {{ Str::plural('review', $district->reviews_count) }} in {{ $district->name }}</p>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($eateries as $eatery)
            <x-eatery-card :eatery="$eatery" />
        @empty
            <p class="col-span-full">No eateries in this district yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $eateries->links() }}</div>
@endsection
