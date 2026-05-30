<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Controller untuk alur pendaftaran beasiswa.
 *
 * Initial state:
 * - User mengakses menu Pilihan/Daftar/Hasil.
 * Final state:
 * - Data pendaftaran tersimpan sementara di session, dan berkas tersimpan di storage publik.
 * 
 * @author sep sarip hidayattuloh 
 * 
 * @since 30 Mei 2026
 
 */
class BeasiswaController extends Controller
{
    /**
     * Menampilkan halaman Pilihan Beasiswa beserta syaratnya.
     *
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    public function pilihan(): View
    {
        $ipk = $this->currentIpkForDisplay();

        return view('beasiswa.pilihan', [
            'pilihan' => config('beasiswa.pilihan', []),
            'ipk' => $ipk,
            'eligible' => $this->isEligible($ipk),
        ]);
    }

    /**
     * Menampilkan form pendaftaran beasiswa.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    public function create(): View
    {
        $ipk = $this->resolveIpkForForm();

        return view('beasiswa.daftar', [
            'pilihan' => config('beasiswa.pilihan', []),
            'ipk' => $ipk,
            'eligible' => $this->isEligible($ipk),
        ]);
    }

    /**
     * Menyimpan data pendaftaran ke session dan upload berkas ke storage.
     * Catatan:
     * - Jika IPK < 3.0 maka proses ditolak.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    public function store(Request $request): RedirectResponse
    {
        
        $ipk = (float) session()->get('beasiswa.ipk_current', $this->ipkValues()[0] ?? 3.4);

        if (! $this->isEligible($ipk)) {
            return back()
                ->withInput()
                ->withErrors([
                    'ipk' => 'IPK di bawah 3.0, tidak dapat melanjutkan pendaftaran beasiswa.',
                ]);
        }

        $pilihan = config('beasiswa.pilihan', []);
        $allowedCodes = array_values(array_filter(array_map(fn ($p) => Arr::get($p, 'kode'), $pilihan)));

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'nomor_hp' => ['required', 'regex:/^\d+$/', 'max:20'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'pilihan_beasiswa' => ['required', Rule::in($allowedCodes)],
            'berkas' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,zip', 'max:10240'],
        ], [
            'nomor_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'semester.min' => 'Semester minimal 1.',
            'semester.max' => 'Semester maksimal 8.',
            'berkas.mimes' => 'Berkas harus bertipe pdf/jpg/png/zip.',
        ]);

        $uploadedFile = $request->file('berkas');
        $storedPath = Storage::disk('public')->putFile('beasiswa', $uploadedFile);

        $record = [
            'id' => (string) Str::uuid(),
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'nomor_hp' => $validated['nomor_hp'],
            'semester' => (int) $validated['semester'],
            'ipk' => $ipk,
            'pilihan_beasiswa' => $validated['pilihan_beasiswa'],
            'berkas_path' => $storedPath,
            'berkas_nama' => $uploadedFile->getClientOriginalName(),
            'status_ajuan' => 'belum di verifikasi',
        ];

        $applications = session()->get('beasiswa.applications', []);
        array_unshift($applications, $record);
        session(['beasiswa.applications' => $applications]);

        return redirect()
            ->route('beasiswa.hasil')
            ->with('success', 'Pendaftaran berhasil disimpan (status: belum di verifikasi).');
    }

    /**
     * Menampilkan list hasil pendaftaran (data dari session).
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    public function hasil(): View
    {
        $applications = session()->get('beasiswa.applications', []);

        return view('beasiswa.hasil', [
            'applications' => $applications,
            'pilihanMap' => $this->pilihanMap(),
            'ipk' => $this->currentIpkForDisplay(),
        ]);
    }

    /**
     * Download berkas syarat berdasarkan ID pendaftaran (data dari session).
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    public function downloadBerkas(string $id)
    {
        $applications = session()->get('beasiswa.applications', []);

        $match = null;
        foreach ($applications as $row) {
            if (($row['id'] ?? null) === $id) {
                $match = $row;
                break;
            }
        }

        if (! $match || empty($match['berkas_path'])) {
            abort(404);
        }

        $path = (string) $match['berkas_path'];
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $downloadName = (string) ($match['berkas_nama'] ?? basename($path));

        $absolutePath = Storage::disk('public')->path($path);

        return response()->download($absolutePath, $downloadName);
    }

    /**
     * Mengambil IPK demo dari konfigurasi.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    private function demoIpk(): float
    {
        return (float) config('beasiswa.demo_ipk', 3.4);
    }

    /**
     * Mengambil daftar nilai IPK.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     *
     */
    private function ipkValues(): array
    {
        $values = config('beasiswa.ipk_values', [3.4, 2.9]);
        if (! is_array($values) || count($values) === 0) {
            return [3.4, 2.9];
        }

        $floats = [];
        foreach ($values as $value) {
            $floats[] = (float) $value;
        }

        return $floats;
    }

    /**
     * Menentukan IPK untuk ditampilkan di form daftar.
     *
     * catatan:
     * - Setiap kali halaman Daftar dibuka, IPK berganti urut: 3.4, lalu 2.9, lalu 3.4, dst.
     * - Nilai yang ditampilkan disimpan di session agar konsisten saat submit.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    private function resolveIpkForForm(): float
    {
        $values = $this->ipkValues();
        $count = max(1, count($values));
        $index = (int) session()->get('beasiswa.ipk_index', 0);

        $ipk = (float) $values[$index % $count];

        // Simpan IPK yang sedang aktif untuk POST berikutnya.
        session(['beasiswa.ipk_current' => $ipk]);

        // Siapkan index untuk kunjungan berikutnya.
        session(['beasiswa.ipk_index' => ($index + 1) % $count]);

        return $ipk;
    }

    /**
     * IPK untuk display umum (pilihan/hasil). Jika belum pernah buka form, gunakan nilai pertama.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    private function currentIpkForDisplay(): float
    {
        $current = session()->get('beasiswa.ipk_current');
        if ($current !== null) {
            return (float) $current;
        }

        return (float) ($this->ipkValues()[0] ?? $this->demoIpk());
    }

    /**
     * Mengecek apakah IPK memenuhi syarat.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    private function isEligible(float $ipk): bool
    {
        return $ipk >= 3.0;
    }

    /**
     * Membuat mapping kode->nama beasiswa.
     * 
     * @author sep sarip hidayattuloh
     * 
     * @since 30 Mei 2026
     */
    private function pilihanMap(): array
    {
        $map = [];
        foreach (config('beasiswa.pilihan', []) as $p) {
            $kode = Arr::get($p, 'kode');
            $nama = Arr::get($p, 'nama');
            if (is_string($kode) && is_string($nama)) {
                $map[$kode] = $nama;
            }
        }

        return $map;
    }
}
