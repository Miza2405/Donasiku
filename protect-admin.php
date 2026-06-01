<?php
/**
 * File Proteksi Admin
 * Include file ini di halaman admin yang memerlukan akses admin
 */

require_once __DIR__ . '/auth.php';

// Cek apakah user sudah login
if (!isLoggedIn()) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Cek apakah user adalah admin
if (!isAdmin()) {
    // User bukan admin, redirect ke dashboard user
    header('Location: user-dashboard.php');
    exit;
}
?>
