@extends('layouts.app')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Add an eatery</h1>
    <form method="POST" action="{{ route('eateries.store') }}" class="max-w-xl">
        @include('eateries._form')
    </form>
@endsection
