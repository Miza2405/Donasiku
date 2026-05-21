<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email (OTP) - ZakatKu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #059669 0%, #34d399 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
        }
        
        .text-emerald { color: #059669; }
        
        .otp-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); 
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
        }

        .icon-mail {
            width: 80px;
            height: 80px;
            background-color: #ecfdf5;
            color: #059669;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px auto;
        }

        /* Styling khusus untuk kotak OTP */
        .otp-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border-radius: 10px;
            border: 2px solid #dee2e6;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }
        
        .otp-input:focus {
            border-color: #059669;
            box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.25);
            outline: none;
            background-color: #ffffff;
        }
        
        /* Menghilangkan panah atas/bawah pada input number (Chrome, Safari, Edge, Opera) */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Menghilangkan panah atas/bawah pada input number (Firefox) */
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .btn-emerald { 
            background-color: #059669; color: white; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;
        }
        .btn-emerald:hover { 
            background-color: #047857; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(5, 150, 105, 0.4);
        }
        
        .resend-link { color: #059669; text-decoration: none; font-weight: 600; cursor: pointer; }
        .resend-link.disabled { color: #adb5bd; cursor: not-allowed; pointer-events: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center" data-aos="zoom-in" data-aos-duration="800">
            <div class="otp-card text-center">
                
                <div class="icon-mail">
                    <i class="bi bi-envelope-paper-fill"></i>
                </div>
                
                <h3 class="fw-bold text-dark mb-2">Verifikasi Email Anda</h3>
                <p class="text-muted mb-4 small px-3">
                    Kami telah mengirimkan 6 digit kode OTP ke email <span class="fw-bold text-dark">f***n@email.com</span>. Masukkan kode tersebut di bawah ini untuk mengaktifkan akun Anda.
                </p>

                <form id="otpForm">
                    <div class="otp-container">
                        <input type="number" class="otp-input" maxlength="1" required autofocus aria-label="OTP digit 1">
                        <input type="number" class="otp-input" maxlength="1" required aria-label="OTP digit 2">
                        <input type="number" class="otp-input" maxlength="1" required aria-label="OTP digit 3">
                        <input type="number" class="otp-input" maxlength="1" required aria-label="OTP digit 4">
                        <input type="number" class="otp-input" maxlength="1" required aria-label="OTP digit 5">
                        <input type="number" class="otp-input" maxlength="1" required aria-label="OTP digit 6">
                    </div>
                    
                    <button type="submit" class="btn btn-emerald w-100 py-3 mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> Verifikasi Akun
                    </button>
                </form>

                <div class="mt-2 text-muted small">
                    Belum menerima email? <br>
                    <a class="resend-link disabled" id="resendBtn">Kirim ulang kode dalam <span id="timer">01:00</span></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    AOS.init({ once: true });

    // 1. Logika untuk otomatis pindah kotak input OTP
    const inputs = document.querySelectorAll('.otp-input');
    
    inputs.forEach((input, index) => {
        // Mencegah input lebih dari 1 angka
        input.addEventListener('input', function() {
            if (this.value.length > 1) {
                this.value = this.value.slice(0, 1);
            }
            // Jika ada isinya, pindah ke kotak berikutnya
            if (this.value !== '' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        // Menangkap tombol Backspace untuk kembali ke kotak sebelumnya
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // 2. Simulasi Countdown Timer untuk "Kirim Ulang OTP"
    let timeLeft = 60; // 60 detik
    const timerElement = document.getElementById('timer');
    const resendBtn = document.getElementById('resendBtn');

    const countdown = setInterval(() => {
        timeLeft--;
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        
        // Format agar selalu 2 digit (contoh: 00:09)
        timerElement.innerText = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        if (timeLeft <= 0) {
            clearInterval(countdown);
            resendBtn.classList.remove('disabled');
            resendBtn.innerHTML = "Kirim Ulang OTP Sekarang";
            
            // Aksi saat tombol diklik lagi
            resendBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Terkirim!',
                    text: 'Kode OTP baru telah dikirim ke email Anda.',
                    confirmButtonColor: '#059669',
                    timer: 2000
                });
            });
        }
    }, 1000);

    // 3. Simulasi Submit Form Verifikasi
    document.getElementById("otpForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        
        // Ambil semua value dari input OTP
        let otpCode = "";
        inputs.forEach(input => {
            otpCode += input.value;
        });

        // Simulasi Loading & Validasi (Misal OTP yang benar adalah angka apapun selama 6 digit terisi)
        if(otpCode.length === 6) {
            Swal.fire({
                title: 'Memverifikasi...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Verifikasi Berhasil!',
                    text: 'Akun Anda sudah aktif. Silakan masuk.',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Menuju Halaman Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login.php"; // Arahkan kembali ke halaman login
                    }
                });
            }, 1500);
        }
    });
</script>
</body>
</html>