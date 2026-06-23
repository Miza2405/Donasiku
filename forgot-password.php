<?php
// Memanggil kelas PHPMailer dari folder yang sudah Anda siapkan
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Generate Token
    $token = bin2hex(random_bytes(32));
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Update ke database
    $stmt = $koneksi->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $hashedToken, $expiry, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yohamixide@gmail.com'; 
            $mail->Password   = 'rofd ubgh baln bycx'; // App Password Google
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('donasiku@mail.com', 'Sistem Donasiku');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Anda';
            $mail->Body    = "Klik tautan ini untuk reset: <a href='http://localhost/download/reset-password.php?token=$token&email=$email'>Reset Password</a>";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'Email reset password telah dikirim!']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Gagal kirim: " . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak ditemukan.']);
    }
}
?>