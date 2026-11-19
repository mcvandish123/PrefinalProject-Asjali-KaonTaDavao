@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">All eateries</h1>

    <div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
        <span class="text-stone-500">Filter by feature:</span>
        @foreach ($features as $feature)
            <a href="{{ route('eateries.index', ['feature' => $feature->id]) }}"
               class="rounded-full border px-2.5 py-0.5 {{ $activeFeature?->id === $feature->id ? 'border-red-800 bg-red-800 text-white' : 'border-amber-300 bg-white hover:bg-amber-100' }}">{{ $feature->name }}</a>
        @endforeach
        @if ($activeFeature)
            <a href="{{ route('eateries.index') }}" class="text-red-700 underline">Clear</a>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($eateries as $eatery)
            <x-eatery-card :eatery="$eatery" />
        @empty
            <p>No eateries found.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $eateries->links() }}</div>
@endsection
