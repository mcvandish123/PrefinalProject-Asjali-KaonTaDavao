@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Districts</h1>
    <ul class="divide-y rounded border border-amber-200 bg-white">
        @foreach ($districts as $district)
            <li class="flex justify-between px-4 py-3">
                <a href="{{ route('districts.show', $district) }}" class="font-medium text-red-800 hover:underline">{{ $district->name }}</a>
                <span class="text-sm text-stone-500">{{ $district->eateries_count }} {{ Str::plural('eatery', $district->eateries_count) }}</span>
            </li>
        @endforeach
    </ul>
@endsection
