<?php

/**
 * Konfigurasi Beasiswa.
 *
 * Catatan:
 * - Data IPK diasumsikan berasal dari sistem (bukan input user).
 * 
 * @author sep sarip hidayattuloh
 *
 * 30 Mei 2026
 */

return [
    // IPK Lolos jika IPK >= 3.0.
    // Untuk kebutuhan tugas: tampilkan IPK bergantian (contoh: 3.4 lalu 2.9).
    'ipk_values' => [
        (float) env('BEASISWA_DEMO_IPK_1', 3.4),
        (float) env('BEASISWA_DEMO_IPK_2', 2.9),
    ],

    // Minimal 2 pilihan beasiswa.
    'pilihan' => [
        [
            'kode' => 'akademik',
            'nama' => 'Beasiswa Akademik',
            'syarat' => [
                'IPK minimal 3.0',
                'Semester aktif (1–8)',
                'Upload berkas persyaratan',
            ],
        ],
        [
            'kode' => 'non_akademik',
            'nama' => 'Beasiswa Non-Akademik',
            'syarat' => [
                'Prestasi non-akademik (organisasi/olahraga/seni)',
                'Semester aktif (1–8)',
                'Upload berkas persyaratan',
            ],
        ],
    ],
];
