@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Pilihan Beasiswa</h1>
        </div>

        {{-- Pilihan Beasiswa --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($pilihan as $item)
                <div class="rounded-lg border border-zinc-200 p-5">
                    <div class="text-lg font-semibold">{{ $item['nama'] ?? '-' }}</div>
                    <div class="mt-3 text-sm text-zinc-700">
                        <div class="font-medium text-zinc-900">Syarat / Ketentuan</div>
                        <ul class="mt-2 list-disc pl-5 space-y-1">
                            @foreach (($item['syarat'] ?? []) as $syarat)
                                <li>{{ $syarat }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <a
                href="{{ route('beasiswa.daftar') }}"
                class="inline-flex items-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white"
            >
                Lanjut ke Pendaftaran
            </a>
        </div>
    </div>
@endsection
