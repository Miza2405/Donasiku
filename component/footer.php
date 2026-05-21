<footer class="py-4 text-center mt-auto" style="background-color: #111827; color: #d1d5db;">
        <div class="container">
            <h4 class="text-emerald fw-bold mb-3" style="color: #059669;">🎁 DonasiKu</h4>
            <p class="mb-0">© 2026 DonasiKu. Dibuat dengan niat baik untuk sesama.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. INISIALISASI ANIMASI
        AOS.init({ duration: 800, once: true, offset: 50 });

        document.addEventListener("DOMContentLoaded", function() {
            // ==========================================
            // 2. FUNGSI CEK LOGIN & DROPDOWN NAVBAR
            // ==========================================
            let statusLogin = localStorage.getItem("isLoggedIn");
            if (statusLogin === "true") {
                let userRole = localStorage.getItem("userRole");
                let storedName = localStorage.getItem("userName") || "Pengguna"; 
                let storedAvatar = localStorage.getItem("avatarUrl") || `https://ui-avatars.com/api/?name=${storedName}&background=059669&color=fff`;
                let dashboardLink = (userRole === "admin") ? "admin-dashboard.php" : "user-dashboard.php";
                
                let authContainer = document.querySelector('.ms-lg-3');
                if (authContainer) {
                    authContainer.innerHTML = `
                        <div class="dropdown">
                            <a class="text-decoration-none d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="${storedAvatar}" style="object-fit: cover;" alt="Profile" class="rounded-circle border border-2 border-success shadow-sm" width="40" height="40">
                                <span class="fw-bold text-dark ms-2 d-none d-lg-block">Halo, ${storedName.split(' ')[0]}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3" style="border-radius: 12px; min-width: 200px;">
                                <li><a class="dropdown-item py-2 fw-semibold" href="${dashboardLink}"><i class="bi bi-grid-1x2-fill me-2 text-emerald"></i> Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 fw-bold text-danger" href="#" onclick="logoutDariNavbar(event)"><i class="bi bi-box-arrow-right me-2"></i> Keluar</a></li>
                            </ul>
                        </div>
                    `;
                }
            }

            // ==========================================
            // 3. FUNGSI FILTER KATEGORI (Hanya jalan di donasi.php)
            // ==========================================
            const filterButtons = document.querySelectorAll('.filter-btn');
            const programItems = document.querySelectorAll('.program-item');

            if (filterButtons.length > 0) {
                filterButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Hapus class active dari semua tombol
                        filterButtons.forEach(btn => btn.classList.remove('active'));
                        // Tambahkan ke tombol yang diklik
                        this.classList.add('active');

                        const filterValue = this.getAttribute('data-filter');

                        // Loop semua kartu donasi
                        programItems.forEach(item => {
                            if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                                item.style.display = 'block';
                                item.style.animation = 'none';
                                item.offsetHeight; /* Trigger reflow agar animasi bisa diulang mulus */
                                item.style.animation = 'fadeInScale 0.4s ease-in-out';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });
            }
        });

        // ==========================================
        // 4. FUNGSI PERINGATAN WAJIB LOGIN
        // ==========================================
        function requireLogin(event) {
            event.preventDefault(); 
            let statusLogin = localStorage.getItem("isLoggedIn");

            if (statusLogin === "true") {
                window.location.href = 'donasi.php';
            } else {
                Swal.fire({
                    icon: 'warning', title: 'Harap Login Dahulu', 
                    text: 'Untuk mengakses layanan donasi dan melihat detail program, Anda wajib masuk menggunakan akun yang terdaftar.',
                    showCancelButton: true, confirmButtonColor: '#059669', cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Login / Daftar Sekarang', cancelButtonText: 'Batal', reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) { window.location.href = 'login.php'; } // Pastikan ini mengarah ke file loginmu
                });
            }
        }

        // ==========================================
        // 5. FUNGSI BUKA DETAIL DONASI
        // ==========================================
        function bukaDetail(event, judul, gambar, terkumpul, target, kategori) {
            event.preventDefault();
            let statusLogin = localStorage.getItem("isLoggedIn");

            if (statusLogin === "true") {
                sessionStorage.setItem("detail_judul", judul);
                sessionStorage.setItem("detail_gambar", gambar);
                sessionStorage.setItem("detail_terkumpul", terkumpul);
                sessionStorage.setItem("detail_target", target);
                sessionStorage.setItem("detail_kategori", kategori);
                window.location.href = "detail-donasi.php"; // Ubah ke .php jika file detail-donasi juga diubah
            } else {
                requireLogin(event);
            }
        }
        
        // Membuat alias agar fungsi klik di index.php tetap aman dan tidak error
        const bukaDetailIndex = bukaDetail;

        // ==========================================
        // 6. FUNGSI LOGOUT
        // ==========================================
        function logoutDariNavbar(event) {
            event.preventDefault();
            Swal.fire({
                icon: 'question', title: 'Keluar Akun?', text: 'Anda yakin ingin keluar dari sesi ini?',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem("isLoggedIn");
                    localStorage.removeItem("userRole");
                    localStorage.removeItem("userName");
                    localStorage.removeItem("avatarUrl");
                    window.location.reload(); 
                }
            });
        }
    </script>
</body>
</html>