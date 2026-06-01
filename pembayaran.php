<?php require_once __DIR__ . '/protect.php'; ?>
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
                    <div class="row g-3 mb-5" id="paymentMethodsContainer">
                        <!-- Methods will be loaded dynamically -->
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
        let checkoutProgramId = null;

        document.addEventListener("DOMContentLoaded", function() {
            // Cek apakah ada data tagihan. Jika tidak ada (user iseng buka link langsung), tendang balik.
            let program = sessionStorage.getItem("checkout_program");
            let nominal = sessionStorage.getItem("checkout_nominal");
            checkoutProgramId = Number(sessionStorage.getItem("checkout_program_id") || 0);
            
            if (!program || !nominal || !checkoutProgramId) {
                window.location.href = "donasi.php";
                return;
            }

            // Format angka menjadi Rupiah (Contoh: 50000 -> 50.000)
            formatNominal = new Intl.NumberFormat('id-ID').format(nominal);

            // Tampilkan Data ke Layar
            document.getElementById("detail-program").innerText = program;
            document.getElementById("detail-nominal").innerText = "Rp " + formatNominal;
            document.getElementById("detail-nama").innerText = localStorage.getItem("userName") || "Hamba Allah";
            loadPaymentMethods();
        });

        // Fungsi efek klik pilihan pembayaran (dinamis)
        let paymentMethodsList = [];
        let selectedMethod = null;

        function pilihMetode(element, methodId) {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            selectedMethod = paymentMethodsList.find(m => m.id === methodId) || null;
        }

        async function loadPaymentMethods() {
            try {
                const res = await fetch('./api/payment-methods.php');
                const json = await res.json();
                if (!res.ok || !json.success) throw new Error(json.message || 'Gagal memuat metode pembayaran');
                paymentMethodsList = json.data || [];
                renderPaymentMethods();
            } catch (err) {
                console.error('Load payment methods error:', err);
            }
        }

        function renderPaymentMethods() {
            const container = document.getElementById('paymentMethodsContainer');
            container.innerHTML = '';
            if (!Array.isArray(paymentMethodsList) || paymentMethodsList.length === 0) {
                container.innerHTML = '<div class="col-12 text-muted">Belum ada metode pembayaran.</div>';
                return;
            }

            paymentMethodsList.forEach(function(m, idx) {
                if (!m.active) return;
                const icon = m.type === 'qris' ? 'bi-qr-code-scan text-success' : 'bi-bank2 text-primary';
                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `<div class="payment-method ${idx===0? 'selected':''}" onclick="pilihMetode(this, '${m.id}')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="fw-bold">${m.name}</span><div class="small text-muted">${m.account}</div></div>
                        <div><i class="bi ${icon} fs-4"></i></div>
                    </div>
                </div>`;
                container.appendChild(col);
                if (idx===0 && !selectedMethod) {
                    // auto-select first
                    const el = col.querySelector('.payment-method');
                    if (el) pilihMetode(el, m.id);
                }
            });
        }

        // Fungsi pembayaran menggunakan API backend
        async function prosesBayar() {
            if (!checkoutProgramId) {
                Swal.fire({ icon: 'error', title: 'Data Program Hilang', text: 'Silakan kembali ke halaman donasi dan pilih program lagi.', confirmButtonColor: '#059669' });
                return;
            }

            const nominal = Number(sessionStorage.getItem("checkout_nominal") || 0);
            const doa = sessionStorage.getItem("checkout_doa") || '';

            if (!nominal || nominal <= 0) {
                Swal.fire({ icon: 'warning', title: 'Nominal Tidak Valid', text: 'Masukkan nominal donasi yang benar.', confirmButtonColor: '#059669' });
                return;
            }

            const method = selectedMethod;
            if (!method) {
                Swal.fire({ icon: 'warning', title: 'Pilih Metode', text: 'Silakan pilih metode pembayaran terlebih dahulu.', confirmButtonColor: '#059669' });
                return;
            }

            const qrisImageHtml = method.image ? `<div class="text-center mb-3"><img src="${method.image}" alt="QR" class="img-fluid rounded-3" style="max-height:220px; object-fit:contain;"></div>` : '';

            const detailHtml = `
                <div class="text-start">
                    <p><strong>Metode:</strong> ${method.name}</p>
                    <p><strong>No. Rekening / Instruksi:</strong><br><span class="fw-bold">${method.account}</span></p>
                    <p><strong>Atas Nama:</strong> ${method.owner || '-'} </p>
                    ${qrisImageHtml}
                    <p>Silakan lakukan transfer sesuai total tagihan, lalu tekan tombol <strong>Sudah bayar</strong>.</p>
                    <div class="border rounded-3 p-3 bg-light mt-3">
                        <div class="fw-semibold">Total yang harus ditransfer</div>
                        <div class="fs-5 text-emerald fw-bold">Rp ${formatNominal}</div>
                    </div>
                </div>
            `;

            const result = await Swal.fire({
                title: 'Instruksi Pembayaran',
                html: detailHtml,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sudah bayar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#059669',
                width: 600,
                allowOutsideClick: false
            });

            if (!result.isConfirmed) {
                return;
            }

            try {
                Swal.fire({ title: 'Mencatat Pembayaran...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const response = await fetch('api/create-donasi.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        program_id: checkoutProgramId,
                        amount: nominal,
                        payment_method: method.name,
                        payment_method_id: method.id,
                        message: doa
                    })
                });

                const createResult = await response.json();

                if (!response.ok || !createResult.success) {
                    throw new Error(createResult.message || 'Gagal membuat donasi');
                }

                sessionStorage.removeItem("checkout_program");
                sessionStorage.removeItem("checkout_program_id");
                sessionStorage.removeItem("checkout_nominal");
                sessionStorage.removeItem("checkout_doa");

                Swal.fire({
                    icon: 'success',
                    title: 'Terima Kasih atas Donasinya',
                    text: 'Harap menunggu validasi pembayaran. Donasi Anda akan segera diverifikasi oleh tim kami.',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location.href = 'user-dashboard.php';
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses Donasi',
                    text: error.message || 'Terjadi kesalahan saat membuat donasi.',
                    confirmButtonColor: '#d33'
                });
            }
        }
    </script>
</body>
</html>