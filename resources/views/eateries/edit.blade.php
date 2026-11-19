@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Edit {{ $eatery->name }}</h1>
    <form method="POST" action="{{ route('eateries.update', $eatery) }}" class="max-w-xl">
        @method('PUT')
        @include('eateries.partials.form')
    </form>
@endsection
