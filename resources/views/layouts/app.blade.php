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
            <nav class="flex gap-4 text-sm font-medium">
                <a href="{{ route('eateries.index') }}" class="hover:underline">Eateries</a>
                <a href="{{ route('districts.index') }}" class="hover:underline">Districts</a>
                <a href="{{ route('eateries.create') }}" class="rounded bg-amber-400 px-3 py-1 text-stone-900 hover:bg-amber-300">Add eatery</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if (session('status'))
            <x-alert type="success">{{ session('status') }}</x-alert>
        @endif

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
