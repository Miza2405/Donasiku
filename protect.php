<?php
/**
 * File Proteksi Halaman
 * Include file ini di halaman yang memerlukan user login
 */

require_once __DIR__ . '/auth.php';

// Cek apakah user sudah login
if (!isLoggedIn()) {
    // Redirect ke login page
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
?>
