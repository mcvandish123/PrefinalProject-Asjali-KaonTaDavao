@props(['feature'])

<a href="{{ route('eateries.index', ['feature' => $feature->id]) }}" class="inline-block rounded-full bg-amber-200 px-2.5 py-0.5 text-xs font-medium text-amber-900 hover:bg-amber-300">{{ $feature->name }}</a>
