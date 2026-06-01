<?php
return [
    // Isi dengan Gmail pengirim. Untuk Gmail wajib pakai App Password, bukan password login biasa.
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_username' => 'gmail-pengirim@gmail.com',
    'smtp_password' => 'isi-app-password-gmail',
    'from_name' => 'DonasiKu',

    // Email tujuan pesan kontak. Boleh lebih dari satu.
    'recipients' => [
        'kdsaputro555@gmail.com',
        'kevindwisaputro555@gmail.com',
    ],
];
