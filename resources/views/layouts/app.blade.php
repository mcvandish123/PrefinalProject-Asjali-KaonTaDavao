<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaon Ta! Davao – Asjali's Food Guide</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-amber-50 text-stone-800">
    <header class="bg-red-800 text-white shadow">
        <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-4">
            <a href="{{ route('home') }}" class="text-xl font-bold">Kaon Ta! Davao – Asjali's Food Guide</a>
            @include('partials.nav')
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @include('partials.flash')

        @if ($errors->any())
            <x-alert type="error">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @yield('content')
    </main>

    <footer class="py-6 text-center text-sm text-stone-500">
        Kaon Ta! Davao – City Tourism Office eatery guide
    </footer>
</body>
</html>
