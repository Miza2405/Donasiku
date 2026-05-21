<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - ZakatKu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            /* Background konsisten dengan halaman Auth */
            background: linear-gradient(135deg, #059669 0%, #34d399 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
        }
        
        .text-emerald { color: #059669; }
        
        .reset-card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.2); 
            padding: 40px 30px;
            max-width: 500px;
            width: 100%;
        }

        .icon-lock {
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

        .btn-emerald { 
            background-color: #059669; color: white; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;
        }
        .btn-emerald:hover { 
            background-color: #047857; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(5, 150, 105, 0.4);
        }
        
        .form-control { border-radius: 8px; padding: 12px 15px; }
        .form-control:focus { border-color: #059669; box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.25); }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-center" data-aos="zoom-in" data-aos-duration="800">
            <div class="reset-card text-center">
                
                <div class="icon-lock">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h3 class="fw-bold text-dark mb-2">Lupa Password?</h3>
                <p class="text-muted mb-4 small px-3">
                    Jangan khawatir! Masukkan email yang terdaftar pada akun Anda, dan kami akan mengirimkan tautan untuk mengatur ulang password.
                </p>

                <form id="resetForm">
                    <div class="mb-4 text-start">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" class="form-control border-start-0 ps-0 bg-light" placeholder="nama@email.com" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-emerald w-100 py-3 mb-4">
                        <i class="bi bi-send-fill me-2"></i> Kirim Tautan Reset
                    </button>
                </form>

                <div class="mt-2">
                    <a href="login.php" class="text-emerald text-decoration-none fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
                    </a>
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

    // Simulasi pengiriman email reset password
    document.getElementById("resetForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        
        // Memunculkan loading buatan
        Swal.fire({
            title: 'Memproses...',
            text: 'Mencari email Anda di sistem kami',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulasi delay server 1.5 detik, lalu munculkan pesan sukses
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Email Terkirim!',
                text: 'Silakan cek kotak masuk atau folder spam email Anda untuk tautan reset password.',
                confirmButtonColor: '#059669',
                confirmButtonText: 'Kembali ke Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                }
            });
        }, 1500);
    });
</script>
</body>
</html>