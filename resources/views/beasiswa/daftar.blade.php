@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-center">Daftar Beasiswa</h1>
        </div>

        <div class="mx-auto w-full max-w-xl rounded-lg border border-zinc-200 p-6">
            <div class="mb-4 text-sm font-medium text-zinc-900">Registrasi Beasiswa</div>

            <form
                method="POST"
                action="{{ route('beasiswa.store') }}"
                enctype="multipart/form-data"
                data-beasiswa-form
            >
                @csrf

                {{-- Nama --}}
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="nama" class="text-sm text-zinc-700">Nama</label>
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            value="{{ old('nama') }}"
                            required
                            autocomplete="name"
                            placeholder="Nama lengkap"
                        />
                    </div>

                    {{-- Email --}}
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="email" class="text-sm text-zinc-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="e.g. john.doe@example.com"
                        />
                    </div>

                    {{-- Nomor HP --}}
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="nomor_hp" class="text-sm text-zinc-700">Nomor HP</label>
                        <input
                            id="nomor_hp"
                            name="nomor_hp"
                            type="text"
                            inputmode="numeric"
                            pattern="\d+"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            value="{{ old('nomor_hp') }}"
                            required
                            aria-describedby="nomor_hp_help"
                            placeholder="e.g. 081234567890"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="semester" class="text-sm text-zinc-700">Semester saat ini</label>
                        <select
                            id="semester"
                            name="semester"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            required
                        >
                            <option value="">Pilih</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" @selected(old('semester') == (string) $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- IPK Terakhir --}}
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="ipk" class="text-sm text-zinc-700">IPK Terakhir</label>
                        <input
                            id="ipk"
                            name="ipk_display"
                            type="text"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm bg-zinc-100"
                            value="{{ number_format($ipk, 1, ',', '.') }}"
                            readonly
                            data-ipk
                        />
                    </div>

                    {{-- Pilihan Beasiswa --}}
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="pilihan_beasiswa" class="text-sm text-zinc-700">Pilihan Beasiswa</label>
                        <select
                            id="pilihan_beasiswa"
                            name="pilihan_beasiswa"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            data-pilihan
                            @disabled(! $eligible)
                            required
                        >
                            <option value="">Pilih</option>
                            @foreach ($pilihan as $item)
                                <option value="{{ $item['kode'] }}" @selected(old('pilihan_beasiswa') === ($item['kode'] ?? null))>
                                    {{ $item['nama'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Upload Berkas Syarat --}}
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-2 md:items-center">
                        <label for="berkas" class="text-sm text-zinc-700">Upload Berkas Syarat</label>
                        <input
                            id="berkas"
                            name="berkas"
                            type="file"
                            class="w-full rounded-md border border-zinc-300 px-3 py-2 text-sm"
                            data-berkas
                            @disabled(! $eligible)
                            required
                        />
                    </div>

                    {{-- Not Eligible Message --}}
                    @if (! $eligible)
                        <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" data-not-eligible>
                            IPK di bawah 3.0, pilihan beasiswa, upload berkas, dan tombol daftar dinonaktifkan.
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium bg-white hover:bg-zinc-100"
                            data-submit
                            @disabled(! $eligible)
                        >
                            Daftar
                        </button>
                        {{-- Reset Button --}}
                        <button
                            type="reset"
                            class="rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium bg-white hover:bg-zinc-100"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
