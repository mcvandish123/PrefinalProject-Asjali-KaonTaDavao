@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-2xl font-bold">All eateries</h1>

    <form method="GET" action="{{ route('eateries.index') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded border border-amber-200 bg-white p-4 text-sm">
        <div>
            <label for="q" class="mb-1 block font-medium">Search</label>
            <input id="q" name="q" type="text" value="{{ $filters['q'] }}" placeholder="Name or specialty" class="rounded border border-stone-300 px-3 py-2">
        </div>
        <div>
            <label for="district" class="mb-1 block font-medium">District</label>
            <select id="district" name="district" class="rounded border border-stone-300 px-3 py-2">
                <option value="">All districts</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected((string) $filters['district'] === (string) $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="feature" class="mb-1 block font-medium">Feature</label>
            <select id="feature" name="feature" class="rounded border border-stone-300 px-3 py-2">
                <option value="">All features</option>
                @foreach ($features as $feature)
                    <option value="{{ $feature->name }}" @selected($filters['feature'] === $feature->name)>{{ $feature->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded bg-red-800 px-4 py-2 font-semibold text-white hover:bg-red-700">Filter</button>
        <a href="{{ route('eateries.index') }}" class="py-2 text-red-700 underline">Reset</a>
    </form>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($eateries as $eatery)
            <x-eatery-card :eatery="$eatery" />
        @empty
            <p class="col-span-full">No eateries match your filters.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $eateries->links() }}</div>
@endsection
