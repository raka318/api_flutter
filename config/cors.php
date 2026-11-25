<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | Mengatur izin akses API dan file storage dari aplikasi frontend
    | (seperti Flutter Web) yang berjalan di port atau domain berbeda.
    |
    */

    // Diubah: Tambahkan tanda '*' agar semua sub-rute di dalam api/ dan storage/ diizinkan
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    // Mengizinkan semua metode HTTP (GET, POST, PUT, PATCH, DELETE, OPTIONS)
    'allowed_methods' => ['*'],

    // Mengizinkan request dari semua alamat origin selama tahap pengembangan
    'allowed_origins' => ['*'],

    // Pola origin tambahan jika menggunakan regex
    'allowed_origins_patterns' => [],

    // Mengizinkan semua header termasuk Accept dan Authorization (Bearer token)
    'allowed_headers' => ['*'],

    // Header tambahan yang boleh dibaca oleh client
    'exposed_headers' => [],

    // Durasi cache untuk preflight request (dalam detik)
    'max_age' => 0,

    // False karena autentikasi menggunakan Bearer Token Sanctum, bukan cookie sesi
    'supports_credentials' => false,

];