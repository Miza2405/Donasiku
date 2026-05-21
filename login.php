<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Daftar - DonasiKu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #059669 0%, #34d399 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .text-emerald { color: #059669; }
        .auth-card { background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); overflow: hidden; }
        .illustration-bg { background: url('https://images.unsplash.com/photo-1600096194534-95cf5ece04cf?auto=format&fit=crop&w=800&q=80') center/cover; position: relative; }
        .illustration-bg::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(5, 150, 105, 0.3); }
        .btn-emerald { background-color: #059669; color: white; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn-emerald:hover { background-color: #047857; color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(5, 150, 105, 0.4); }
        .nav-pills .nav-link.active { background-color: #059669; border-radius: 8px; }
        .nav-pills .nav-link { color: #6c757d; font-weight: 600; transition: 0.3s; }
        .nav-pills .nav-link:hover:not(.active) { color: #059669; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 15px; }
        .form-control:focus, .form-select:focus { border-color: #059669; box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.25); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12" data-aos="zoom-in" data-aos-duration="800">
            <div class="row auth-card">
                <div class="col-md-5 d-none d-md-block illustration-bg"></div>
                <div class="col-md-7 p-sm-5 p-4">
                    <div class="text-center mb-4">
                        <a href="index.php" class="text-decoration-none"><h2 class="fw-bold text-emerald">💚 DonasiKu</h2></a>
                        <p class="text-muted">Selamat datang kembali, pahlawan kebaikan!</p>
                    </div>
                    
                    <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" role="tab" aria-selected="true" data-bs-toggle="pill" data-bs-target="#pills-login" type="button">Login</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" role="tab" aria-selected="false" data-bs-toggle="pill" data-bs-target="#pills-register" type="button">Daftar</button></li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        
                        <div class="tab-pane fade show active" id="pills-login">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="roleSelect">Masuk Sebagai</label>
                                    <select class="form-select mb-3" id="roleSelect">
                                        <option value="user">Donatur (User)</option>
                                        <option value="admin">Relawan / Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" id="emailLogin" class="form-control" placeholder="Contoh: user@email.com" required>
                                </div>
                                <div class="input-group mb-3">
                                <input type="password" class="form-control" id="passwordLogin" placeholder="Password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check"><input type="checkbox" class="form-check-input" id="rememberMe"><label class="form-check-label text-muted small" for="rememberMe">Ingat Saya</label></div>
                                    <a href="resetpw.php" class="text-emerald text-decoration-none small fw-semibold">Lupa Password?</a>
                                </div>
                                <button type="submit" class="btn btn-emerald w-100 py-2">Masuk Sekarang</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-register">
                            <form id="registerForm">
                                <div class="mb-3"><label class="form-label fw-semibold">Nama Lengkap</label><input type="text" class="form-control" placeholder="Masukkan nama lengkap" required></div>
                                <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" class="form-control" placeholder="nama@email.com" required></div>
                                <div class="mb-4"><label class="form-label fw-semibold">Password</label><input type="password" class="form-control" placeholder="Buat password yang kuat" required></div>
                                <button type="submit" class="btn btn-emerald w-100 py-2">Buat Akun Baru</button>
                            </form>
                        </div>

                    </div>
                    <div class="text-center mt-4"><a href="index.php" class="text-muted text-decoration-none small">← Kembali ke Beranda</a></div>
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

    // FUNGSI CEK SESI: Jika sudah login, jangan boleh buka halaman login lagi
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem("isLoggedIn") === "true") {
            let role = localStorage.getItem("userRole");
            // Langsung lempar ke dashboard masing-masing
            window.location.href = (role === "admin") ? "admin-dashboard.php" : "user-dashboard.php";
        }
    });

    // FUNGSI VALIDASI LOGIN
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault(); 
        
        // Ambil data yang diketik pengguna
        let role = document.getElementById("roleSelect").value;
        let email = document.getElementById("emailLogin").value;
        let password = document.getElementById("passwordLogin").value;
        
        // simulasi login
        // --- SKENARIO 1: Login Sebagai USER (Donatur) ---
        if (role === "user" && email === "Kyosei@email.com" && password === "12345") {
            prosesLogin(role, "Kyosei", "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=100&q=80");
        }
        // --- SKENARIO 2: Login Sebagai ADMIN ---
        else if (role === "admin" && email === "Kevin@email.com" && password === "12345") {
            prosesLogin(role, "Admin DonasiKu", "https://ui-avatars.com/api/?name=Admin&background=059669&color=fff");
        }
        // --- SKENARIO 3: Email atau Password Salah ---
        else {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: 'Email atau Password yang Anda masukkan salah.',
                confirmButtonColor: '#d33'
            });
        }
    });

    // FUNGSI MENYIMPAN DATA & PINDAH HALAMAN
    function prosesLogin(role, nama, avatar) {
        // Simpan semua data profil ke memori browser
        localStorage.setItem("isLoggedIn", "true");
        localStorage.setItem("userRole", role);
        localStorage.setItem("userName", nama);
        localStorage.setItem("avatarUrl", avatar);

        // Munculkan notifikasi sukses lalu pindah halaman
        Swal.fire({ 
            icon: 'success', 
            title: 'Alhamdulillah, Berhasil!', 
            text: 'Mengarahkan ke Dashboard...',
            showConfirmButton: false, 
            timer: 1500 
        }).then(() => {
            if (role === "admin") { 
                window.location.href = "admin-dashboard.php"; 
            } else { 
                window.location.href = "user-dashboard.php"; 
            }
        });
    }

    // FUNGSI SIMULASI DAFTAR (Register)
    document.getElementById("registerForm").addEventListener("submit", function(event) {
        event.preventDefault();
        Swal.fire({ 
            icon: 'success', 
            title: 'Pendaftaran Berhasil!', 
            showConfirmButton: false, 
            timer: 1500 
        }).then(() => {
            window.location.href = "verify-otp.php";
        });
    });
</script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordLogin'); 
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function (e) {
        // Cek tipe input, jika password maka ubah jadi text, sebaliknya
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Ganti ikon mata terbuka atau tertutup
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>