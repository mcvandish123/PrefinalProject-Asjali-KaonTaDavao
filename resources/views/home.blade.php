@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Welcome to the Davao eatery guide</h1>

    <div class="mb-10 grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-amber-200 bg-white p-4 text-center">
            <p class="text-3xl font-bold text-red-800">{{ $eateryCount }}</p>
            <p class="text-sm text-stone-500">{{ $eateryCount === 1 ? 'Eatery' : 'Eateries' }}</p>
        </div>
        <div class="rounded-lg border border-amber-200 bg-white p-4 text-center">
            <p class="text-3xl font-bold text-red-800">{{ $districtCount }}</p>
            <p class="text-sm text-stone-500">{{ Str::plural('District', $districtCount) }}</p>
        </div>
        <div class="rounded-lg border border-amber-200 bg-white p-4 text-center">
            <p class="text-3xl font-bold text-red-800">{{ $reviewCount }}</p>
            <p class="text-sm text-stone-500">{{ Str::plural('Review', $reviewCount) }}</p>
        </div>
    </div>

    <h2 class="mb-4 text-xl font-semibold">Top Rated</h2>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($topRated as $eatery)
            <x-eatery-card :eatery="$eatery" />
        @empty
            <p class="text-stone-500">No rated eateries yet.</p>
        @endforelse
    </div>
@endsection
