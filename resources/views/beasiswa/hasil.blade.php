@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold">Hasil Pendaftaran</h1>
        </div>
        {{-- Message saat data tidak ada --}}
        @if (empty($applications))
            <div class="rounded-lg border border-zinc-200 p-6 text-sm text-zinc-700">
                Belum ada data pendaftaran. Silakan daftar di menu <a class="underline" href="{{ route('beasiswa.daftar') }}">Daftar</a>.
            </div>
        @else
        {{-- Tabel Hasil Pendaftaran --}}
            <div class="overflow-x-auto rounded-lg border border-zinc-200">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-zinc-50 text-zinc-700">
                        <tr>
                            <th class="px-4 py-3 font-medium">No</th>
                            <th class="px-4 py-3 font-medium">Nama</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium">Nomor HP</th>
                            <th class="px-4 py-3 font-medium">Semester</th>
                            <th class="px-4 py-3 font-medium">IPK</th>
                            <th class="px-4 py-3 font-medium">Pilihan Beasiswa</th>
                            <th class="px-4 py-3 font-medium">Berkas</th>
                            <th class="px-4 py-3 font-medium">Status Ajuan</th>
                        </tr>
                    </thead>

                    {{-- data pendaftaran beasiswa --}}
                    <tbody class="divide-y divide-zinc-200">
                        @foreach ($applications as $row)
                            <tr class="bg-white">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $row['nama'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $row['email'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $row['nomor_hp'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $row['semester'] ?? '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ isset($row['ipk']) ? number_format((float) $row['ipk'], 1, ',', '.') : '-' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $kode = $row['pilihan_beasiswa'] ?? null;
                                        $nama = is_string($kode) ? ($pilihanMap[$kode] ?? $kode) : '-';
                                    @endphp
                                    {{ $nama }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if (!empty($row['berkas_path']))
                                        <a
                                            class="underline"
                                            href="{{ route('beasiswa.berkas', ['id' => $row['id'] ?? '']) }}"
                                            target="_blank"
                                            rel="noreferrer"
                                        >
                                            {{ $row['berkas_nama'] ?? 'download' }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $row['status_ajuan'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
