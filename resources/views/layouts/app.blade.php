<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kampusku - Beasiswa</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Layout for the application --}}
<body class="min-h-screen bg-white text-zinc-900 flex flex-col">
    <header class="border-b border-zinc-200">
        <div class="mx-auto w-full max-w-5xl px-4 py-4">
            <div class="flex items-center justify-between gap-4">
                <div class="font-semibold tracking-tight">
                    Kampusku
                </div>

                <nav class="flex items-center gap-2" aria-label="Menu Beasiswa">
                    @php
                        $tabs = [
                            ['label' => 'Pilihan Beasiswa', 'route' => 'beasiswa.pilihan'],
                            ['label' => 'Daftar', 'route' => 'beasiswa.daftar'],
                            ['label' => 'Hasil', 'route' => 'beasiswa.hasil'],
                        ];
                    @endphp

                    @foreach ($tabs as $tab)
                        @php
                            $active = request()->routeIs($tab['route']);
                            $classes = $active
                                ? 'bg-zinc-900 text-white'
                                : 'bg-white text-zinc-700 hover:bg-zinc-100';
                        @endphp

                        <a
                            href="{{ route($tab['route']) }}"
                            class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium border border-zinc-200 {{ $classes }}"
                        >
                            {{ $tab['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="mx-auto w-full max-w-5xl px-4 py-8 flex-1">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                <div class="font-medium">Ada error pada input:</div>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 mt-auto">
        <div class="mx-auto w-full max-w-5xl px-4 py-6 text-center text-xs text-zinc-500">
            Copyright © {{ date('Y') }} kampuskuaja.ac.id
        </div>
    </footer>
</body>
</html>
