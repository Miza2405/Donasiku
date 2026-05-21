<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pembayaran - DonasiKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding-top: 80px; }
        .text-emerald { color: #059669; } .bg-emerald { background-color: #059669; }
        .navbar { background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .checkout-card { background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
        .payment-method { border: 2px solid #eee; border-radius: 12px; padding: 15px; cursor: pointer; transition: 0.3s; }
        .payment-method:hover { border-color: #059669; background-color: #f0fdf4; }
        .payment-method.selected { border-color: #059669; background-color: #e6fced; }
        .btn-emerald { background-color: #059669; color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; }
        .btn-emerald:hover { background-color: #047857; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand fw-bold text-emerald fs-4" href="index.php">💚 DonasiKu <span class="text-muted fs-6 fw-normal">| Checkout Aman</span></a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-card p-4 p-md-5">
                    
                    <a href="donasi.php" class="text-decoration-none text-muted mb-4 d-block"><i class="bi bi-arrow-left me-2"></i>Kembali ke Program</a>
                    
                    <h4 class="fw-bold mb-4 border-bottom pb-3">Rincian Donasi Anda</h4>
                    
                    <div class="bg-light p-4 rounded-3 mb-4">
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Program Pilihan</div>
                            <div class="col-6 text-end fw-bold" id="detail-program">Memuat...</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Nama Donatur</div>
                            <div class="col-6 text-end fw-semibold" id="detail-nama">Memuat...</div>
                        </div>
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-6"><h5 class="fw-bold text-emerald mb-0">Total Tagihan</h5></div>
                            <div class="col-6 text-end"><h3 class="fw-bold text-emerald mb-0" id="detail-nominal">Rp 0</h3></div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 mt-5">Pilih Metode Pembayaran</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <div class="payment-method selected" onclick="pilihMetode(this, 'BCA Virtual Account')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">BCA Virtual Account</span>
                                    <i class="bi bi-bank text-primary fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-method" onclick="pilihMetode(this, 'QRIS (Gopay/OVO/Dana)')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">QRIS Semua E-Wallet</span>
                                    <i class="bi bi-qr-code-scan text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="payment-method" onclick="pilihMetode(this, 'Mandiri Virtual Account')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Mandiri Virtual Account</span>
                                    <i class="bi bi-bank2 text-warning fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-emerald w-100 py-3 fs-5 shadow" onclick="prosesBayar()">
                        <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang
                    </button>
                    <p class="text-center text-muted small mt-3"><i class="bi bi-lock-fill"></i> Transaksi Anda dienkripsi dan aman.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let metodePilihan = "BCA Virtual Account"; // Default
        let formatNominal = "";

        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah ada data tagihan. Jika tidak ada (user iseng buka link langsung), tendang balik.
            let program = sessionStorage.getItem("checkout_program");
            let nominal = sessionStorage.getItem("checkout_nominal");
            
            if (!program || !nominal) {
                window.location.href = "donasi.php";
                return;
            }

            // Format angka menjadi Rupiah (Contoh: 50000 -> 50.000)
            formatNominal = new Intl.NumberFormat('id-ID').format(nominal);

            // Tampilkan Data ke Layar
            document.getElementById("detail-program").innerText = program;
            document.getElementById("detail-nominal").innerText = "Rp " + formatNominal;
            document.getElementById("detail-nama").innerText = localStorage.getItem("userName") || "Hamba Allah";
        });

        // Fungsi efek klik pilihan pembayaran
        function pilihMetode(element, namaMetode) {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            metodePilihan = namaMetode;
        }

        // Fungsi Simulasikan Pembayaran & Rekam Transaksi
        function prosesBayar() {
            // Membuat ID Transaksi unik acak (Contoh: TRX-7492)
            let idTrx = "TRX-" + Math.floor(Math.random() * 9000 + 1000);
            
            // Menyusun objek data transaksi baru
            let transaksiBaru = {
                id: idTrx,
                tanggal: new Date().toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }),
                program: sessionStorage.getItem("checkout_program"),
                nominal: "Rp " + formatNominal,
                metode: metodePilihan,
                status: "Pending" // Otomatis pending nunggu admin
            };

            // Ambil riwayat transaksi lama dari localStorage (jika ada)
            let riwayat = JSON.parse(localStorage.getItem("riwayatTransaksi")) || [];
            
            // Masukkan transaksi baru ke baris paling atas
            riwayat.unshift(transaksiBaru);
            
            // Simpan kembali ke localStorage
            localStorage.setItem("riwayatTransaksi", JSON.stringify(riwayat));

            // Bersihkan keranjang sementara
            sessionStorage.removeItem("checkout_program");
            sessionStorage.removeItem("checkout_nominal");

            // Tampilkan sukses
            Swal.fire({
                icon: 'success',
                title: 'Alhamdulillah!',
                text: 'Instruksi pembayaran telah dikirim. Mengarahkan ke Dashboard Anda...',
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                window.location.href = "user-dashboard.php";
            });
        }
    </script>
</body>
</html>