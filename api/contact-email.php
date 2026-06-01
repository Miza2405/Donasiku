<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$configPath = __DIR__ . '/../config/contact-mail.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Konfigurasi email belum tersedia']);
    exit;
}

$config = require $configPath;

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$category = trim($_POST['category'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$category || !$message) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nama, email, kategori, dan pesan wajib diisi']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format email pengirim tidak valid']);
    exit;
}

$smtpUsername = trim($config['smtp_username'] ?? '');
$smtpPassword = trim($config['smtp_password'] ?? '');
$recipients = array_values(array_filter($config['recipients'] ?? [], function ($recipient) {
    return filter_var($recipient, FILTER_VALIDATE_EMAIL);
}));

if (!$smtpUsername || !$smtpPassword || $smtpUsername === 'gmail-pengirim@gmail.com' || $smtpPassword === 'isi-app-password-gmail' || count($recipients) === 0) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Konfigurasi Gmail belum diisi dengan benar']);
    exit;
}

function smtpRead($socket) {
    $response = '';

    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;

        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }

    return $response;
}

function smtpCommand($socket, $command, $expectedCodes) {
    if ($command !== null) {
        fwrite($socket, $command . "\r\n");
    }

    $response = smtpRead($socket);
    $code = (int)substr($response, 0, 3);

    if (!in_array($code, (array)$expectedCodes, true)) {
        throw new Exception('SMTP error: ' . trim($response));
    }

    return $response;
}

function encodeHeader($value) {
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

function dotStuff($message) {
    return preg_replace('/^\./m', '..', $message);
}

try {
    $host = $config['smtp_host'] ?? 'smtp.gmail.com';
    $port = (int)($config['smtp_port'] ?? 587);
    $fromName = trim($config['from_name'] ?? 'DonasiKu');
    $timeout = 20;

    $socket = stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, $timeout);
    if (!$socket) {
        throw new Exception("Tidak bisa terhubung ke SMTP: {$errstr}");
    }

    stream_set_timeout($socket, $timeout);
    smtpCommand($socket, null, 220);
    smtpCommand($socket, 'EHLO localhost', 250);
    smtpCommand($socket, 'STARTTLS', 220);

    if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        throw new Exception('Gagal mengaktifkan TLS SMTP');
    }

    smtpCommand($socket, 'EHLO localhost', 250);
    smtpCommand($socket, 'AUTH LOGIN', 334);
    smtpCommand($socket, base64_encode($smtpUsername), 334);
    smtpCommand($socket, base64_encode($smtpPassword), 235);

    $safeName = str_replace(["\r", "\n"], '', $name);
    $safeEmail = str_replace(["\r", "\n"], '', $email);
    $safeCategory = str_replace(["\r", "\n"], '', $category);
    $subject = 'Pesan Layanan DonasiKu - ' . $safeCategory;
    $body = "Pesan baru dari halaman Hubungi Layanan Kami\n\n";
    $body .= "Nama: {$safeName}\n";
    $body .= "Email: {$safeEmail}\n";
    $body .= "Kategori: {$safeCategory}\n\n";
    $body .= "Isi Pesan:\n{$message}\n";

    $headers = [];
    $headers[] = 'From: ' . encodeHeader($fromName) . " <{$smtpUsername}>";
    $headers[] = 'Reply-To: ' . encodeHeader($safeName) . " <{$safeEmail}>";
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'Content-Transfer-Encoding: 8bit';
    $headers[] = 'X-Mailer: DonasiKu Contact Form';

    $emailData = 'Subject: ' . encodeHeader($subject) . "\r\n";
    $emailData .= 'To: ' . implode(', ', $recipients) . "\r\n";
    $emailData .= implode("\r\n", $headers) . "\r\n\r\n";
    $emailData .= dotStuff($body) . "\r\n.";

    smtpCommand($socket, 'MAIL FROM:<' . $smtpUsername . '>', 250);
    foreach ($recipients as $recipient) {
        smtpCommand($socket, 'RCPT TO:<' . $recipient . '>', [250, 251]);
    }
    smtpCommand($socket, 'DATA', 354);
    smtpCommand($socket, $emailData, 250);
    smtpCommand($socket, 'QUIT', 221);
    fclose($socket);

    echo json_encode(['success' => true, 'message' => 'Pesan berhasil dikirim. Tim kami akan segera menghubungi Anda.']);
} catch (Exception $e) {
    if (isset($socket) && is_resource($socket)) {
        fclose($socket);
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal mengirim email: ' . $e->getMessage()]);
}
?>
