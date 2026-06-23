<?php
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 1. Cek apakah token masih valid
    $stmt = $koneksi->prepare("SELECT reset_token, token_expiry FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // 2. Verifikasi token
    if ($user && password_verify($token, $user['reset_token']) && strtotime($user['token_expiry']) > time()) {
        
        // 3. Update password dan hapus token agar tidak bisa dipakai lagi
        $update = $koneksi->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?");
        $update->bind_param("ss", $newPassword, $email);
        
        if ($update->execute()) {
            echo "Password berhasil diubah! Silakan <a href='../login.php'>Login</a>";
        } else {
            echo "Gagal memperbarui password.";
        }
    } else {
        echo "Tautan tidak valid atau sudah kedaluwarsa.";
    }
}
?>