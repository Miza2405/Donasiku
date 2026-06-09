<?php require_once __DIR__ . '/protect-admin.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DonasiKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Library Chart.js untuk Grafik Statistik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; overflow-x: hidden; }
        .text-emerald { color: #059669; } .bg-emerald { background-color: #059669; }
        .wrapper { display: flex; width: 100%; align-items: stretch; min-height: 100vh; }
        
        #sidebar { min-width: 250px; max-width: 250px; background: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); z-index: 100; }
        #sidebar .sidebar-header { padding: 20px; background: #059669; color: white; text-align: center; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1em; display: block; color: #555; text-decoration: none; font-weight: 600; cursor: pointer; transition: 0.2s;}
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #059669; background: #eef2f0; border-right: 4px solid #059669; }
        
        #content { width: 100%; padding: 20px; }
        .top-navbar { background: white; border-radius: 10px; padding: 10px 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .dash-card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: 0.3s; }
        .dash-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        
        .table-custom { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .table-custom th, .table-custom td { padding: 15px; vertical-align: middle; }
        
        .content-section { display: none; animation: fadeIn 0.4s ease-in-out; }
        .content-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-content-custom { border-radius: 15px; }

        .form-control:focus, .form-select:focus { border-color: #059669; box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.25); }
        /* Style Khusus Laporan ala Referensi */
        .report-card { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; align-items: center; }
        .report-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-right: 15px; }
        .report-icon-in { background-color: #e6f4ea; color: #059669; }
        .report-icon-out { background-color: #fce8e6; color: #d93025; }
        .report-icon-balance { background-color: #e8f0fe; color: #1a73e8; }
        .report-label { font-size: 0.85rem; color: #5f6368; font-weight: 600; margin-bottom: 2px; }
        .report-value { font-size: 1.4rem; font-weight: 700; color: #202124; margin-bottom: 5px; }
        .report-trend { font-size: 0.75rem; font-weight: 600; }
        .trend-up { color: #059669; } .trend-down { color: #d93025; }
        
        .filter-bar { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 15px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 24px; }
        .chart-box { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); height: 100%; }
        /* ========================================================
   CSS UNTUK SLIDER REKENING & E-WALLET (AKTIF / NONAKTIF)
   ======================================================== */

/* 1. Jika Slider menggunakan bawaan Bootstrap (.form-switch) */
.form-switch .form-check-input {
    cursor: pointer;
    transition: all 0.3s ease;
}
/* Warna saat OFF (Nonaktif) -> Abu-abu */
.form-switch .form-check-input:not(:checked) {
    background-color: #cbd5e1 !important; 
    border-color: #94a3b8 !important;
}
/* Warna saat ON (Aktif) -> Hijau Emerald */
.form-switch .form-check-input:checked {
    background-color: #059669 !important; 
    border-color: #059669 !important;
}

/* 2. Jika Slider menggunakan CSS Kustom (.switch / .slider) */
.switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
}
.switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}
/* Warna saat OFF (Nonaktif) -> Abu-abu */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1; /* <-- Ini yang membuatnya abu-abu */
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
/* Warna saat ON (Aktif) -> Hijau Emerald */
input:checked + .slider {
    background-color: #059669;
}
input:checked + .slider:before {
    transform: translateX(22px);
}
    </style>
</head>
<body>
<script>
    window.APP_ADMIN_ROLE = '<?php echo isset($_SESSION['admin_role']) ? addslashes($_SESSION['admin_role']) : 'super_admin'; ?>';
    window.APP_USER_ID = '<?php echo isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; ?>';
</script>
<div class="wrapper">
    <!-- SIDEBAR NAVIGASI -->
    <nav id="sidebar" class="d-none d-md-block">
        <div class="sidebar-header"><h3 class="fw-bold mb-0">🎁 DonasiKu</h3><small>Panel Admin</small></div>
        <ul class="list-unstyled components" id="menu-list">
            <li class="active" id="menu-dashboard"><a onclick="switchMenu('dashboard', 'Dashboard Utama')"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a></li>
            <li id="menu-program"><a onclick="switchMenu('program', 'Manajemen Program')"><i class="bi bi-box2-heart-fill me-2"></i> Program Donasi</a></li>
            <li id="menu-donatur"><a onclick="switchMenu('donatur', 'Data Donatur')"><i class="bi bi-people-fill me-2"></i> Data Donatur</a></li>
            <li id="menu-transaksi"><a onclick="switchMenu('transaksi', 'Data Transaksi')"><i class="bi bi-wallet-fill me-2"></i> Data Transaksi</a></li>
            <li id="menu-laporan"><a onclick="switchMenu('laporan', 'Laporan Keuangan')"><i class="bi bi-bar-chart-line-fill me-2"></i> Laporan</a></li>
            <li id="menu-pengaturan"><a onclick="switchMenu('pengaturan', 'Pengaturan Sistem')"><i class="bi bi-gear-fill me-2"></i> Pengaturan</a></li>
        </ul>
        <div class="p-3 mt-4"><button onclick="logout()" class="btn btn-danger w-100 rounded-pill"><i class="bi bi-box-arrow-left me-2"></i> Keluar</button></div>
    </nav>

    <!-- KONTEN UTAMA -->
    <div id="content">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div><h5 class="fw-bold mb-0 text-emerald" id="top-title">Dashboard Utama</h5></div>
            <div class="d-flex align-items-center"><span class="me-3 fw-semibold text-muted">Halo, Admin</span><img src="https://ui-avatars.com/api/?name=Admin&background=059669&color=fff" alt="Avatar Admin" class="rounded-circle" width="40"></div>
        </div>

        <!-- 1. DASHBOARD -->
        <div id="sec-dashboard" class="content-section active-section">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Total Dana Donasi</p><h3 class="fw-bold text-emerald mb-0" id="stat-total-dana">Rp 0</h3></div><div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i></div></div></div></div>
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Menunggu Verifikasi</p><h3 class="fw-bold text-warning mb-0" id="stat-pending-trx">0 Trx</h3></div><div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div></div></div></div>
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Total Donatur</p><h3 class="fw-bold text-primary mb-0" id="stat-total-donatur">0 Orang</h3></div><div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div></div></div></div>
            </div>
            
            <h5 class="fw-bold mb-3 mt-4">Transaksi Perlu Verifikasi</h5>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID TRX</th><th>Nama Donatur</th><th>Program Donasi</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody id="tbody-pending-transaksi">
                        <tr>
                            <td class="fw-bold">#TRX-001</td>
                            <td><a href="#" class="text-emerald text-decoration-none fw-bold" onclick="bukaInvoice('TRX-001', 'Suud nofa', 'Sedekah Jariyah', 'Rp 2.500.000', '20 Mar 2026', 'Bank BCA')">Suud nofa</a></td>
                            <td>Sedekah Jariyah</td>
                            <td class="fw-semibold text-emerald">Rp 2.500.000</td>
                            <td><span id="badge-TRX-001" class="badge bg-warning text-dark">Pending</span></td>
                            <td id="aksi-TRX-001"><button class="btn btn-sm btn-success rounded-pill" onclick="bukaInvoice('TRX-001', 'Suud nofa', 'Sedekah Jariyah', 'Rp 2.500.000', '20 Mar 2026', 'Bank BCA')"><i class="bi bi-check-circle"></i> Verifikasi</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. MANAJEMEN PROGRAM (Gambar disinkronkan dengan halaman Donasi) -->
        <div id="sec-program" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Daftar Program Kampanye</h5>
                <button class="btn btn-emerald rounded-pill" data-bs-toggle="modal" data-bs-target="#tambahProgramModal">
                    <i class="bi bi-plus-circle me-1"></i> Buat Program Baru
                </button>
            </div>
            
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Judul Program</th>
                            <th>Kategori</th>
                            <th>Target Dana</th>
                            <th>Terkumpul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-program">
                        <!-- 1. Bencana -->
                        <tr>
                            <td><img src="https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Indonesia Darurat Bencana: Longsor & Banjir</td>
                            <td><span class="badge bg-danger">Bencana Alam</span></td>
                            <td>Rp 100.000.000</td>
                            <td class="text-emerald fw-bold">Rp 35.076.524</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Edit Program"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Tutup Program"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 2. Sembako -->
                        <tr>
                            <td><img src="https://www.lead.co.id/wp-content/uploads/2020/04/IMG-20200404-WA0187.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Berbagi Paket Sembako Untuk Keluarga Dhuafa</td>
                            <td><span class="badge bg-warning text-dark">Pangan & Sembako</span></td>
                            <td>Rp 30.000.000</td>
                            <td class="text-emerald fw-bold">Rp 18.500.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 3. Medis -->
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantuan Medis Darurat & Kemanusiaan</td>
                            <td><span class="badge bg-danger">Kesehatan</span></td>
                            <td>Rp 100.000.000</td>
                            <td class="text-emerald fw-bold">Rp 54.560.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 4. Palestina -->
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantu Pangan dan Air Bersih Untuk Palestina</td>
                            <td><span class="badge bg-danger">Krisis Pangan</span></td>
                            <td>Rp 200.000.000</td>
                            <td class="text-emerald fw-bold">Rp 27.066.258</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 5. Yatim -->
                        <tr>
                            <td><img src="https://pantiyatim.or.id/wp-content/uploads/2020/11/anak-yatim.jpeg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Santunan Anak Yatim Pelosok Negeri</td>
                            <td><span class="badge bg-info text-dark">Pendidikan</span></td>
                            <td>Rp 50.000.000</td>
                            <td class="text-emerald fw-bold">Rp 12.400.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 6. Modal Usaha -->
                        <tr>
                            <td><img src="https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantuan Modal Usaha Untuk Ibu Tangguh</td>
                            <td><span class="badge bg-secondary">Pemberdayaan</span></td>
                            <td>Rp 20.000.000</td>
                            <td class="text-emerald fw-bold">Rp 8.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 7. Masjid -->
                        <tr>
                            <td><img src="https://pro.kutaitimurkab.go.id/wp-content/uploads/2025/06/a59864bd-0917-49ca-a55d-3549fffe2210-1024x684.jpeg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Pembangunan Masjid Pelosok Desa</td>
                            <td><span class="badge bg-success">Pembangunan</span></td>
                            <td>Rp 500.000.000</td>
                            <td class="text-emerald fw-bold">Rp 250.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 8. Beasiswa -->
                        <tr>
                            <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s=10" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Beasiswa Pendidikan Santri Penghafal Quran</td>
                            <td><span class="badge bg-info text-dark">Pendidikan</span></td>
                            <td>Rp 150.000.000</td>
                            <td class="text-emerald fw-bold">Rp 45.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 9. Sedekah Makanan -->
                        <tr>
                            <td><img src="https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Sedekah Makanan Hangat Untuk Pekerja Jalanan</td>
                            <td><span class="badge bg-warning text-dark">Pangan & Sembako</span></td>
                            <td>Rp 10.000.000</td>
                            <td class="text-emerald fw-bold">Rp 5.200.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. DATA DONATUR -->
        <div id="sec-donatur" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4"><h5 class="fw-bold mb-0">Daftar Pengguna (Donatur)</h5></div>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>No</th><th>Nama Lengkap</th><th>Email</th><th>No. HP</th><th>Tgl Terdaftar</th><th>Aksi</th></tr></thead>
                    <tbody id="tbody-donatur"><tr><td colspan="6" class="text-center text-muted py-4">Memuat data donatur...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- 4. DATA TRANSAKSI -->
        <div id="sec-transaksi" class="content-section">
            <h5 class="fw-bold mb-4">Semua Riwayat Transaksi</h5>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Tanggal</th><th>ID TRX</th><th>Donatur</th><th>Program</th><th>Nominal</th><th>Status</th></tr></thead>
                    <tbody id="tbody-semua-transaksi"><tr><td colspan="6" class="text-center text-muted py-4">Memuat data transaksi...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- 5. LAPORAN & STATISTIK (Desain Baru) -->
        <div id="sec-laporan" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Laporan Keuangan & Penyaluran</h5>
                    <button class="btn btn-outline-success rounded-pill btn-sm fw-semibold"
                         onclick="cetakLaporanPDF()">
                        <i class="bi bi-printer me-1"></i> Cetak PDF
                    </button>
            </div>
            
            <!-- Kartu Statistik Atas -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-in"><i class="bi bi-arrow-up-right"></i></div>
                        <div>
                            <div class="report-label">Total Pemasukan</div>
                            <div id="reportTotalIncome" class="report-value">Rp 120.500.000</div>
                            <div class="report-trend trend-up"><i class="bi bi-arrow-up-short"></i> 18% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-out"><i class="bi bi-arrow-down-left"></i></div>
                        <div>
                            <div class="report-label">Total Pengeluaran</div>
                            <div id="reportTotalExpense" class="report-value">Rp 0</div>
                            <div class="report-trend trend-down"><i class="bi bi-arrow-down-short"></i> 8% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-balance"><i class="bi bi-wallet2"></i></div>
                        <div>
                            <div class="report-label">Saldo Bersih</div>
                            <div id="reportNetBalance" class="report-value text-emerald">Rp 0</div>
                            <div class="report-trend trend-up"><i class="bi bi-arrow-up-short"></i> 25% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar d-flex flex-wrap align-items-end gap-3">
                <div style="flex: 2; min-width: 200px;">
                    <label class="form-label small fw-bold text-muted mb-1">Periode</label>
                    <select id="reportPeriod" class="form-select bg-light border-0" onchange="handleReportPeriodChange(true)">
                        <option value="monthly">Bulanan</option>
                        <option value="yearly" selected>Tahunan</option>
                    </select>
                </div>
                <div style="flex: 2; min-width: 200px;">
                    <label id="reportMonthLabel" class="form-label small fw-bold text-muted mb-1">Pilih Bulan</label>
                    <input type="number" id="reportMonth" class="form-control bg-light border-0" min="2000" max="2100" step="1" onchange="updateChartData()">
                </div>
                <div>
                    <button class="btn btn-emerald px-4 fw-semibold" onclick="updateChartData()">Terapkan</button>
                </div>
            </div>

            <!-- Area Grafik -->
            <div class="row g-4 mb-4">
                <!-- Bar Chart (Kiri) -->
                <div class="col-lg-8">
                    <div class="chart-box">
                        <h6 class="fw-bold mb-4 text-muted">Pemasukan vs Pengeluaran</h6>
                        <div style="height: 300px;"><canvas id="barChart"></canvas></div>
                    </div>
                </div>
                <!-- Donut Chart (Kanan) -->
                <div class="col-lg-4">
                    <div class="chart-box">
                        <h6 class="fw-bold mb-4 text-muted">Distribusi Kategori</h6>
                        <div style="height: 250px; display: flex; justify-content: center;"><canvas id="donutChart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="chart-box mt-2">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                    <h6 class="fw-bold mb-0 text-muted">Riwayat Penyaluran Dana Terakhir</h6>
                    <button class="btn btn-emerald btn-sm rounded-pill px-3" onclick="openPenyaluranModal()">
                        <i class="bi bi-plus-lg me-1"></i> Program Penyaluran
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Tgl Penyaluran</th><th>Program Tujuan</th><th>Penerima Manfaat</th><th>Nominal</th><th>Bukti</th></tr></thead>
                        <tbody id="tbody-penyaluran">
                            <tr><td colspan="5" class="text-center text-muted py-4">Memuat data penyaluran...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<!-- ================= MODALS ================= -->

<!-- Modal Tambah Rekening -->
<div class="modal fade" id="modalTambahRekening" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-emerald"><i class="bi bi-credit-card-2-front me-2"></i> Tambah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formRekening" enctype="multipart/form-data">
                    <input type="hidden" id="methodId" value="">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis Pembayaran</label>
                        <select id="methodType" class="form-select">
                            <option value="bank">Transfer Bank</option>
                            <option value="ewallet">E-Wallet (Gopay/OVO/Dana)</option>
                            <option value="qris">QRIS (Barcode)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Bank / E-Wallet</label>
                        <input type="text" id="methodName" class="form-control" placeholder="Contoh: Bank BNI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Rekening / Instruksi</label>
                        <input type="text" id="methodAccount" class="form-control" placeholder="Contoh: 0987654321 atau instruksi QRIS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Atas Nama (Pemilik Rekening)</label>
                        <input type="text" id="methodOwner" class="form-control" placeholder="Yayasan DonasiKu">
                    </div>
                    <div class="mb-3" id="methodImageContainer">
                        <label class="form-label small fw-semibold">Gambar / QR (opsional)</label>
                        <input type="file" id="methodImage" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="methodActive" checked>
                        <label class="form-check-label small" for="methodActive">Aktifkan metode ini</label>
                    </div>
                    <button type="button" class="btn btn-emerald w-100 rounded-pill fw-bold" onclick="savePaymentMethod()">Simpan Metode</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-emerald"><i class="bi bi-person-plus me-2"></i> Tambah Pengelola Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formAdmin">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" id="adminName" class="form-control" placeholder="Masukkan nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Utama</label>
                        <input type="email" id="adminEmail" class="form-control" placeholder="admin@donasiku.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Pilih Hak Akses (Role)</label>
                        <select id="adminRole" class="form-select" required>
                            <option value="super_admin">Super Admin (Bisa akses semua)</option>
                            <option value="staff_keuangan">Staf Keuangan (Hanya akses transaksi & laporan)</option>
                            <option value="staff_program">Staf Program (Hanya akses program & laporan)</option>
                        </select>
                    </div>
                    <div class="alert alert-warning small py-2 border-0 bg-warning bg-opacity-10 text-dark">
                        <i class="bi bi-info-circle-fill me-1"></i> Password default <strong>12345</strong> akan disimpan ke akun baru.
                    </div>
                    <button type="button" class="btn btn-emerald w-100 rounded-pill fw-bold" onclick="daftarkanAdmin()">Daftarkan Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Invoice -->
<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header bg-emerald text-white border-0"><h5 class="modal-title fw-bold" id="invoiceModalLabel"><i class="bi bi-receipt me-2"></i> Detail Transaksi <span id="inv-id">#TRX-000</span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm border mb-3">
                    <div class="row mb-2"><div class="col-5 text-muted small">Nama Donatur</div><div class="col-7 fw-bold text-dark text-end" id="inv-name">Nama</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Tgl Transaksi</div><div class="col-7 fw-semibold text-end" id="inv-date">Tgl</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Metode Bayar</div><div class="col-7 fw-semibold text-end" id="inv-method">Metode</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Program Donasi</div><div class="col-7 fw-semibold text-end" id="inv-type">Program</div></div>
                </div>
                <div class="bg-emerald bg-opacity-10 p-3 rounded-3 border border-success border-opacity-25 d-flex justify-content-between align-items-center"><span class="fw-bold text-emerald">Total Nominal</span><h4 class="fw-bold text-emerald mb-0" id="inv-nominal">Rp 0</h4></div>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-between"><button type="button" class="btn btn-outline-danger px-4 rounded-pill" onclick="aksiTolak()"><i class="bi bi-x-circle me-1"></i> Tolak</button><button type="button" class="btn btn-success px-4 rounded-pill" onclick="aksiVerifikasi()"><i class="bi bi-check-circle me-1"></i> Verifikasi Sah</button></div>
        </div>
    </div>
</div>

<!-- Modal Tambah Program Baru -->
<div class="modal fade" id="tambahProgramModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold text-emerald"><i class="bi bi-plus-square-fill me-2"></i> Buat Program Kampanye Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <form id="formProgramBaru">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Judul Program</label>
                            <input type="text" id="progTitle" class="form-control" placeholder="Contoh: Bantuan Air Bersih..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select id="progCategory" class="form-select" required>
                                <option value="jariyah">Sedekah Jariyah</option>
                                <option value="darurat">Darurat</option>
                                <option value="yatim">Pendidikan & Yatim</option>
                                <option value="pangan">Pangan & Sembako</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Target Pengumpulan Dana (Rp)</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" id="progTarget" class="form-control" placeholder="100000000" required></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Batas Waktu</label>
                            <input type="date" id="progEndDate" class="form-control">
                            <small class="text-muted" style="font-size: 0.7rem;">Kosongkan jika program berjalan selamanya (Tanpa Batas)</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Upload Gambar/Banner Promosi</label>
                            <input type="file" id="progImage" class="form-control" accept="image/jpeg,image/png,image/gif,image/jpg,image/webp" required>
                            <small class="text-muted">Format: JPG, PNG, GIF | Max: 5MB</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Kisah & Latar Belakang Program</label>
                            <textarea id="progDescription" class="form-control" rows="4" placeholder="Ceritakan mengapa program ini dibuat..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light"><button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-emerald rounded-pill px-4" onclick="simpanProgram()"><i class="bi bi-save me-1"></i> Terbitkan Program</button></div>
        </div>
    </div>
</div>

<!-- Modal Tambah Program Penyaluran -->
<div class="modal fade" id="penyaluranModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-emerald"><i class="bi bi-box-arrow-up-right me-2"></i> Tambah Program Penyaluran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form id="formPenyaluran">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small">Program Tujuan</label>
                            <select id="penyaluranProgram" class="form-select" required>
                                <option value="">Memuat program...</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Tanggal Penyaluran</label>
                            <input type="date" id="penyaluranTanggal" class="form-control" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small">Penerima Manfaat</label>
                            <input type="text" id="penyaluranPenerima" class="form-control" placeholder="Contoh: Panti Asuhan Kasih Ibu" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Nominal Penyaluran (Rp)</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" id="penyaluranNominal" class="form-control" min="1" placeholder="15000000" required></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Upload Bukti Penyaluran</label>
                            <input type="file" id="penyaluranBukti" class="form-control" accept="image/jpeg,image/png,image/gif,image/jpg,image/webp">
                            <small class="text-muted">Format: JPG, PNG, GIF, WEBP | Max: 5MB</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-emerald rounded-pill px-4" onclick="simpanPenyaluran()"><i class="bi bi-save me-1"></i> Simpan Penyaluran</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Program -->
<div class="modal fade" id="editProgramModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold text-emerald"><i class="bi bi-pencil-square me-2"></i> Edit Program Kampanye</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <form id="formEditProgram">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Judul Program</label>
                            <input type="text" id="editTitle" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select id="editCategory" class="form-select" required>
                                <option value="jariyah">Sedekah Jariyah</option>
                                <option value="darurat">Darurat</option>
                                <option value="yatim">Pendidikan & Yatim</option>
                                <option value="pangan">Pangan & Sembako</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Target Pengumpulan Dana (Rp)</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" id="editTargetAmount" class="form-control" required></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Status Program</label>
                            <select id="editStatus" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dana Terkumpul</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" id="editCollectedAmount" class="form-control" disabled></div>
                        </div>
                        <input type="hidden" id="editImageUrl">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Ganti Gambar Program (Opsional)</label>
                            <input type="file" id="editImage" class="form-control" accept="image/jpeg,image/png,image/gif,image/jpg,image/webp">
                            <div class="form-text">Kosongkan jika ingin mempertahankan gambar lama.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Kisah & Latar Belakang Program</label>
                            <textarea id="editDescription" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light"><button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-emerald rounded-pill px-4" onclick="simpanEditProgram()"><i class="bi bi-save me-1"></i> Simpan Perubahan</button></div>
        </div>
    </div>
</div>

<!-- 6. PENGATURAN SISTEM (Pembayaran & Admin) -->
        <div id="sec-pengaturan" class="content-section">
            <h5 class="fw-bold mb-4">Pengaturan Sistem</h5>
            
            <!-- Navigasi Tabs -->
            <ul class="nav nav-pills mb-4 gap-2" id="settingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" data-bs-toggle="pill" data-bs-target="#tab-pembayaran" type="button" style="border-radius: 10px;">
                        <i class="bi bi-credit-card-2-front-fill me-1"></i> Metode Pembayaran
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" data-bs-toggle="pill" data-bs-target="#tab-admin" type="button" style="border-radius: 10px;">
                        <i class="bi bi-person-badge-fill me-1"></i> Manajemen Admin
                    </button>
                </li>
            </ul>

            <!-- Isi Tabs -->
            <div class="tab-content bg-white p-4 rounded-4 shadow-sm border border-light">
                
                <!-- TAB 1: REKENING & PEMBAYARAN -->
                <div class="tab-pane fade show active" id="tab-pembayaran" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Daftar Rekening & E-Wallet</h6>
                            <small class="text-muted">Metode pembayaran yang akan tampil di halaman donatur.</small>
                        </div>
                        <button class="btn btn-emerald btn-sm rounded-pill px-3" onclick="openAddMethodModal()">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Metode
                        </button>
                    </div>

                    <div class="row g-3">
                        <!-- Kartu Bank BCA -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-bank2 text-primary fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Bank BCA</h6>
                                            <small class="text-muted d-block">1234-5678-90 a.n Yayasan DonasiKu</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked title="Nonaktifkan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kartu Bank Mandiri -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-bank2 text-warning fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Bank Mandiri</h6>
                                            <small class="text-muted d-block">0987-6543-21 a.n Yayasan DonasiKu</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kartu QRIS -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-qr-code-scan text-danger fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">QRIS Nasional</h6>
                                            <small class="text-muted d-block">Gopay, OVO, Dana, LinkAja</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: MANAJEMEN ADMIN -->
                <div class="tab-pane fade" id="tab-admin" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Akses Pengelola Sistem</h6>
                            <small class="text-muted">Atur siapa saja yang memiliki akses ke dashboard ini.</small>
                        </div>
                        <button class="btn btn-emerald btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Profil</th>
                                    <th>Nama Lengkap</th>
                                    <th>Hak Akses (Role)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin">
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Kevini&background=059669&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Kevin <br><small class="text-muted fw-normal">Kevin@email.com</small></td>
                                    <td><span class="badge bg-primary">Super Admin</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary disabled" title="Tidak bisa hapus diri sendiri"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Suud+Nofa&background=0ea5e9&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Suud Nofa <br><small class="text-muted fw-normal">suud@donasiku.com</small></td>
                                    <td><span class="badge bg-info text-dark">Staf Program</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="hapusAdmin('Suud Nofa')"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Dean&background=f59e0b&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Miza <br><small class="text-muted fw-normal">Miza@email.com</small></td>
                                    <td><span class="badge bg-warning text-dark">Staf Keuangan</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="hapusAdmin('Miza')"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let semuaTransaksiAdmin = [];
    let currentTransactionId = null;

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
    }

    function formatTanggal(value) {
        return new Date(value).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function getStatusBadge(status) {
        if (status === 'success') {
            return '<span class="badge bg-success">Berhasil</span>';
        }

        if (status === 'failed') {
            return '<span class="badge bg-danger">Ditolak</span>';
        }

        return '<span class="badge bg-warning text-dark">Pending</span>';
    }

    function getCategoryLabel(category) {
        const labels = {
            jariyah: 'Sedekah Jariyah',
            yatim: 'Pendidikan & Yatim',
            pangan: 'Pangan & Sembako',
            darurat: 'Darurat'
        };

        return labels[category] || category;
    }

    function getProgramImage(program) {
        if (program.image_url) {
            return program.image_url;
        }

        const fallbackImages = {
            jariyah: 'https://images.unsplash.com/photo-1564769662533-4f00a87b4056?auto=format&fit=crop&w=800&q=80',
            yatim: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
            pangan: 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80',
            darurat: 'https://images.unsplash.com/photo-1584515933487-779824d29309?auto=format&fit=crop&w=800&q=80'
        };

        return fallbackImages[program.category] || fallbackImages.pangan;
    }

    async function fetchAdmin(action) {
        const response = await fetch('./api/admin.php?action=' + action);
        const responseText = await response.text();
        let result;

        try {
            result = JSON.parse(responseText);
        } catch (error) {
            throw new Error(responseText || 'Response server tidak valid');
        }

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal memuat data admin');
        }

        return result.data;
    }

    async function fetchAdminUrl(action, params = {}) {
        const searchParams = new URLSearchParams({ action: action });
        Object.keys(params).forEach(function (key) {
            if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
                searchParams.set(key, params[key]);
            }
        });

        const response = await fetch('./api/admin.php?' + searchParams.toString());
        const responseText = await response.text();
        let result;

        try {
            result = JSON.parse(responseText);
        } catch (error) {
            throw new Error(responseText || 'Response server tidak valid');
        }

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal memuat data admin');
        }

        return result.data;
    }

    function setInnerText(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.innerText = value;
        }
    }

    function renderStats(data) {
        // Use total collected from programs to reflect total donations collected
        setInnerText('stat-total-dana', formatRupiah(data.programs?.total_collected || 0));
        setInnerText('stat-pending-trx', Number(data.transactions?.pending_count || 0) + ' Trx');
        setInnerText('stat-total-donatur', Number(data.donors?.total_donors || 0) + ' Orang');
    }

    function renderPendingTransactions(transactions) {
        const tbody = document.getElementById('tbody-pending-transaksi');
        const pending = transactions.filter(function (trx) {
            return trx.status === 'pending';
        });

        if (pending.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi yang perlu diverifikasi.</td></tr>';
            return;
        }

        tbody.innerHTML = pending.map(function (trx) {
            return (
                '<tr>' +
                    '<td class="fw-bold">#' + escapeHtml(trx.trx_code) + '</td>' +
                    '<td><a href="#" class="text-emerald text-decoration-none fw-bold" onclick="bukaInvoiceById(' + trx.id + ')">' + escapeHtml(trx.donor_name) + '</a></td>' +
                    '<td>' + escapeHtml(trx.program_title) + '</td>' +
                    '<td class="fw-semibold text-emerald">' + formatRupiah(trx.amount) + '</td>' +
                    '<td>' + getStatusBadge(trx.status) + '</td>' +
                    '<td><button class="btn btn-sm btn-success rounded-pill" onclick="bukaInvoiceById(' + trx.id + ')"><i class="bi bi-check-circle"></i> Verifikasi</button></td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderPrograms(programs) {
        const tbody = document.getElementById('tbody-program');

        if (programs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada program donasi.</td></tr>';
            return;
        }

        tbody.innerHTML = programs.map(function (program) {
            const statusClass = program.status === 'active' ? 'bg-success' : 'bg-secondary';

            return (
                '<tr>' +
                    '<td><img src="' + getProgramImage(program) + '" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>' +
                    '<td class="fw-bold">' + escapeHtml(program.title) + '</td>' +
                    '<td><span class="badge bg-info text-dark">' + escapeHtml(getCategoryLabel(program.category)) + '</span></td>' +
                    '<td>' + formatRupiah(program.target_amount) + '</td>' +
                    '<td class="text-emerald fw-bold">' + formatRupiah(program.collected_amount) + '</td>' +
                    '<td><span class="badge ' + statusClass + '">' + escapeHtml(program.status) + '</span></td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-2" onclick="editProgram(' + program.id + ')" title="Edit Program"><i class="bi bi-pencil-square"></i></button>' +
                        '<button class="btn btn-sm btn-outline-danger" onclick="hapusProgram(' + program.id + ', \'' + escapeHtml(program.title) + '\')" title="Hapus Program"><i class="bi bi-trash"></i></button>' +
                    '</td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderDonatur(users) {
        const tbody = document.getElementById('tbody-donatur');

        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada donatur.</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(function (user, index) {
            return (
                '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td class="fw-bold">' + escapeHtml(user.name) + '</td>' +
                    '<td>' + escapeHtml(user.email) + '</td>' +
                    '<td>' + escapeHtml(user.phone || '-') + '</td>' +
                    '<td>' + formatTanggal(user.created_at) + '</td>' +
                    '<td><span class="text-muted small">Role user</span></td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderAllTransactions(transactions) {
        const tbody = document.getElementById('tbody-semua-transaksi');

        if (transactions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>';
            return;
        }

        tbody.innerHTML = transactions.map(function (trx) {
            return (
                '<tr>' +
                    '<td>' + formatTanggal(trx.created_at) + '</td>' +
                    '<td class="fw-bold">#' + escapeHtml(trx.trx_code) + '</td>' +
                    '<td>' + escapeHtml(trx.donor_name) + '</td>' +
                    '<td>' + escapeHtml(trx.program_title) + '</td>' +
                    '<td class="fw-semibold text-emerald">' + formatRupiah(trx.amount) + '</td>' +
                    '<td>' + getStatusBadge(trx.status) + '</td>' +
                '</tr>'
            );
        }).join('');
    }

    function applyAdminRoleVisibility() {
        const adminRole = localStorage.getItem('adminRole') || window.APP_ADMIN_ROLE || 'super_admin';
        const allowedSections = {
            super_admin: ['dashboard', 'program', 'donatur', 'transaksi', 'laporan', 'pengaturan'],
            staff_keuangan: ['dashboard', 'transaksi', 'laporan'],
            staff_program: ['program', 'laporan']
        };

        const visible = allowedSections[adminRole] || allowedSections.super_admin;

        document.querySelectorAll('#menu-list li').forEach(item => {
            const sectionId = item.id.replace('menu-', '');
            item.style.display = visible.includes(sectionId) ? '' : 'none';
        });

        document.querySelectorAll('.content-section').forEach(section => {
            const sectionKey = section.id.replace('sec-', '');
            if (!visible.includes(sectionKey)) {
                section.classList.remove('active-section');
                section.style.display = 'none';
            } else {
                section.style.display = '';
            }
        });

        const activeMenu = document.querySelector('#menu-list li.active');
        if (!activeMenu || activeMenu.style.display === 'none') {
            const firstSection = visible[0];
            if (firstSection) {
                switchMenu(firstSection, document.getElementById('menu-' + firstSection)?.innerText || 'Dashboard');
            }
        }

        const userLabel = document.querySelector('.top-navbar .d-flex span');
        if (userLabel) {
            const roleLabel = adminRole === 'super_admin' ? 'Super Admin' : adminRole === 'staff_keuangan' ? 'Staf Keuangan' : 'Staf Program';
            userLabel.innerText = 'Halo, ' + roleLabel;
        }
    }

    async function daftarkanAdmin() {
        const name = document.getElementById('adminName').value.trim();
        const email = document.getElementById('adminEmail').value.trim();
        const adminRole = document.getElementById('adminRole').value;

        if (!name || !email || !adminRole) {
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', text: 'Lengkapi nama, email, dan hak akses admin.', confirmButtonColor: '#d33' });
            return;
        }

        try {
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            const response = await fetch('./api/admin.php?action=create_admin', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: name, email: email, admin_role: adminRole })
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal membuat admin');
            }

            bootstrap.Modal.getInstance(document.getElementById('modalTambahAdmin')).hide();
            document.getElementById('formAdmin').reset();
            await loadAdminDashboardData();
            Swal.fire({ icon: 'success', title: 'Berhasil', text: result.message, confirmButtonColor: '#059669' });
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function renderAdmins(admins) {
        const tbody = document.getElementById('tbody-admin');
        const currentAdminId = Number(localStorage.getItem('userId') || window.APP_USER_ID || 0);

        if (admins.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada admin.</td></tr>';
            return;
        }

        tbody.innerHTML = admins.map(function (admin) {
            const avatar = admin.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(admin.name) + '&background=059669&color=fff';
            let badgeClass = 'bg-primary';
            let roleLabel = 'Super Admin';

            if (admin.admin_role === 'staff_keuangan') {
                badgeClass = 'bg-warning text-dark';
                roleLabel = 'Staf Keuangan';
            } else if (admin.admin_role === 'staff_program') {
                badgeClass = 'bg-info text-dark';
                roleLabel = 'Staf Program';
            }

            const canDelete = currentAdminId && Number(admin.id) !== currentAdminId;
            const deleteButton = canDelete
                ? '<button class="btn btn-sm btn-outline-danger" onclick="hapusAdmin(' + admin.id + ', \'' + escapeHtml(admin.name) + '\')"><i class="bi bi-trash"></i></button>'
                : '<button class="btn btn-sm btn-outline-secondary disabled" title="Tidak bisa hapus diri sendiri"><i class="bi bi-trash"></i></button>';

            return (
                '<tr>' +
                    '<td><img src="' + avatar + '" class="rounded-circle" width="40" height="40" style="object-fit: cover;"></td>' +
                    '<td class="fw-bold">' + escapeHtml(admin.name) + '<br><small class="text-muted fw-normal">' + escapeHtml(admin.email) + '</small></td>' +
                    '<td><span class="badge ' + badgeClass + '">' + escapeHtml(roleLabel) + '</span></td>' +
                    '<td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>' +
                    '<td>' + deleteButton + '</td>' +
                '</tr>'
            );
        }).join('');
    }

    async function loadAdminDashboardData() {
        try {
            const stats = await fetchAdmin('stats');
            const transactions = await fetchAdmin('transactions');
            const programs = await fetchAdmin('programs');
            const users = await fetchAdmin('users');
            const admins = await fetchAdmin('admins');
            const distributions = await fetchAdminUrl('distributions', {
                period: getReportPeriod(),
                month: getCurrentReportMonth()
            });

            semuaTransaksiAdmin = transactions;
            cachedPrograms = programs;
            reportData = stats;
            renderStats(stats);
            renderReportCards(stats);
            renderPenyaluranTable(distributions);
            renderPendingTransactions(transactions);
            renderAllTransactions(transactions);
            renderPrograms(programs);
            renderDonatur(users);
            renderAdmins(admins);

            if (document.getElementById('sec-laporan')?.classList.contains('active-section')) {
                initChart();
            }
        } catch (error) {
            console.error('Admin dashboard error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Dashboard',
                text: error.message,
                confirmButtonColor: '#d33'
            });
        }
    }

    // FUNGSI NAVIGASI MENU
    function switchMenu(targetId, title) {
        document.querySelectorAll('#menu-list li').forEach(item => item.classList.remove('active'));
        document.getElementById('menu-' + targetId).classList.add('active');
        document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active-section'));
        document.getElementById('sec-' + targetId).classList.add('active-section');
        document.getElementById('top-title').innerText = title;

        if (targetId === 'laporan') {
            setTimeout(initChart, 0);
        }
    }

    function logout() {
        localStorage.removeItem("isLoggedIn");
        localStorage.removeItem("userRole");
        window.location.href = 'index.php'; 
    }

    // FUNGSI INVOICE
    let currentTrxId = ""; 
    function bukaInvoiceById(transactionId) {
        const trx = semuaTransaksiAdmin.find(function (item) {
            return Number(item.id) === Number(transactionId);
        });

        if (!trx) {
            Swal.fire({ icon: 'error', title: 'Transaksi Tidak Ditemukan', confirmButtonColor: '#d33' });
            return;
        }

        currentTransactionId = trx.id;
        currentTrxId = trx.trx_code;
        document.getElementById('inv-id').innerText = '#' + trx.trx_code;
        document.getElementById('inv-name').innerText = trx.donor_name;
        document.getElementById('inv-type').innerText = trx.program_title;
        document.getElementById('inv-nominal').innerText = formatRupiah(trx.amount);
        document.getElementById('inv-date').innerText = formatTanggal(trx.created_at);
        document.getElementById('inv-method').innerText = trx.payment_method;
        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    }

    function bukaInvoice(id, name, type, nominal, date, method) {
        currentTransactionId = null;
        currentTrxId = id; 
        document.getElementById('inv-id').innerText = '#' + id; 
        document.getElementById('inv-name').innerText = name; 
        document.getElementById('inv-type').innerText = type; 
        document.getElementById('inv-nominal').innerText = nominal; 
        document.getElementById('inv-date').innerText = date; 
        document.getElementById('inv-method').innerText = method;
        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    }

    async function updateTransactionStatus(newStatus) {
        if (!currentTransactionId) {
            Swal.fire({ icon: 'error', title: 'Transaksi database tidak ditemukan', confirmButtonColor: '#d33' });
            return;
        }

        try {
            const response = await fetch('./api/admin.php?action=verify', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: currentTransactionId,
                    new_status: newStatus
                })
            });
            const responseText = await response.text();
            const result = JSON.parse(responseText);

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal update transaksi');
            }

            bootstrap.Modal.getInstance(document.getElementById('invoiceModal')).hide();
            await loadAdminDashboardData();

            Swal.fire({
                icon: newStatus === 'success' ? 'success' : 'error',
                title: newStatus === 'success' ? 'Transaksi Diverifikasi!' : 'Transaksi Ditolak',
                text: result.message,
                confirmButtonColor: newStatus === 'success' ? '#059669' : '#d33'
            });
        } catch (error) {
            console.error('Verify error:', error);
            Swal.fire({ icon: 'error', title: 'Gagal Update', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    function aksiVerifikasi() { 
        updateTransactionStatus('success');
    }

    function aksiTolak() { 
        updateTransactionStatus('failed');
    }

    function renderProgramOptions(programs) {
        const select = document.getElementById('penyaluranProgram');
        if (!select) return;

        if (!programs || programs.length === 0) {
            select.innerHTML = '<option value="">Belum ada program</option>';
            return;
        }

        select.innerHTML = '<option value="">Pilih program tujuan</option>' + programs.map(function (program) {
            return '<option value="' + program.id + '">' + escapeHtml(program.title) + '</option>';
        }).join('');
    }

    async function openPenyaluranModal() {
        const form = document.getElementById('formPenyaluran');
        if (form) {
            form.reset();
        }

        const now = new Date();
        const today = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0');
        const dateInput = document.getElementById('penyaluranTanggal');
        if (dateInput) {
            dateInput.value = today;
        }

        if (!cachedPrograms || cachedPrograms.length === 0) {
            cachedPrograms = await fetchAdmin('programs');
        }

        renderProgramOptions(cachedPrograms);
        new bootstrap.Modal(document.getElementById('penyaluranModal')).show();
    }

    async function simpanPenyaluran() {
        const programId = document.getElementById('penyaluranProgram').value;
        const beneficiary = document.getElementById('penyaluranPenerima').value.trim();
        const amount = Number(document.getElementById('penyaluranNominal').value || 0);
        const distributedAt = document.getElementById('penyaluranTanggal').value;
        const proofInput = document.getElementById('penyaluranBukti');

        if (!programId || !beneficiary || amount <= 0 || !distributedAt) {
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', text: 'Lengkapi program, tanggal, penerima manfaat, dan nominal penyaluran.', confirmButtonColor: '#d33' });
            return;
        }

        if (proofInput.files && proofInput.files.length > 0) {
            const file = proofInput.files[0];
            const maxSize = 5 * 1024 * 1024;
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];

            if (file.size > maxSize) {
                Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Ukuran bukti maksimal 5MB.', confirmButtonColor: '#d33' });
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                Swal.fire({ icon: 'error', title: 'Tipe File Tidak Diizinkan', text: 'Hanya JPG, PNG, GIF, dan WEBP yang diterima.', confirmButtonColor: '#d33' });
                return;
            }
        }

        const formData = new FormData();
        formData.append('program_id', programId);
        formData.append('beneficiary', beneficiary);
        formData.append('amount', amount);
        formData.append('distributed_at', distributedAt);

        if (proofInput.files && proofInput.files.length > 0) {
            formData.append('proof_image', proofInput.files[0]);
        }

        try {
            Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const response = await fetch('./api/create-distribution.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal menyimpan penyaluran');
            }

            bootstrap.Modal.getInstance(document.getElementById('penyaluranModal')).hide();
            await loadAdminDashboardData();
            Swal.fire({ icon: 'success', title: 'Penyaluran Disimpan!', text: result.message, confirmButtonColor: '#059669' });
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    // FUNGSI SIMPAN PROGRAM BARU

    let currentEditProgramId = null;

    async function simpanProgram() {
        const form = document.getElementById('formProgramBaru');
        const formData = new FormData();
        
        const title = document.getElementById('progTitle').value;
        const category = document.getElementById('progCategory').value;
        const target_amount = document.getElementById('progTarget').value;
        const description = document.getElementById('progDescription').value;
        const imageInput = document.getElementById('progImage');
        const endDate = document.getElementById('progEndDate').value;

        // Validasi input
        if (!title || !category || !target_amount || !description) {
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', text: 'Harap isi semua field yang diperlukan.', confirmButtonColor: '#d33' });
            return;
        }

        // Validasi file
        if (!imageInput.files || imageInput.files.length === 0) {
            Swal.fire({ icon: 'warning', title: 'File Tidak Dipilih', text: 'Silakan pilih gambar terlebih dahulu.', confirmButtonColor: '#d33' });
            return;
        }

        const file = imageInput.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];

        // Validasi ukuran
        if (file.size > maxSize) {
            Swal.fire({ icon: 'error', title: 'File Terlalu Besar', text: 'Ukuran file maksimal 5MB.', confirmButtonColor: '#d33' });
            return;
        }

        // Validasi tipe
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({ icon: 'error', title: 'Tipe File Tidak Diizinkan', text: 'Hanya JPG, PNG, dan GIF yang diterima.', confirmButtonColor: '#d33' });
            return;
        }

        formData.append('title', title);
        formData.append('category', category);
        formData.append('target_amount', target_amount);
        formData.append('description', description);
        formData.append('image', file); // Kirim file langsung
        if (endDate) {
            formData.append('end_date', endDate);
        }

        try {
            Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            const response = await fetch('./api/create-program.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal membuat program');
            }

            bootstrap.Modal.getInstance(document.getElementById('tambahProgramModal')).hide();
            Swal.fire({ icon: 'success', title: 'Program Diterbitkan!', text: result.message, confirmButtonColor: '#059669' });
            form.reset();
            // Reload data program
            const programs = await fetchAdmin('programs');
            cachedPrograms = programs;
            renderPrograms(programs);
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function editProgram(programId) {
        try {
            const programs = await fetchAdmin('programs');
            const program = programs.find(p => p.id == programId);

            if (!program) throw new Error('Program tidak ditemukan');

            currentEditProgramId = programId;
            // Isi form edit dengan data program
            document.getElementById('editTitle').value = program.title;
            document.getElementById('editCategory').value = program.category;
            document.getElementById('editTargetAmount').value = program.target_amount;
            document.getElementById('editCollectedAmount').value = program.collected_amount;
            document.getElementById('editDescription').value = program.description;
            document.getElementById('editStatus').value = program.status;
            document.getElementById('editImageUrl').value = program.image_url || '';
            document.getElementById('editImage').value = '';
            
            new bootstrap.Modal(document.getElementById('editProgramModal')).show();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function simpanEditProgram() {
        const title = document.getElementById('editTitle').value;
        const category = document.getElementById('editCategory').value;
        const target_amount = parseFloat(document.getElementById('editTargetAmount').value);
        const description = document.getElementById('editDescription').value;
        const status = document.getElementById('editStatus').value;
        const imageUrl = document.getElementById('editImageUrl').value;
        const imageInput = document.getElementById('editImage');

        if (!title || !category || target_amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', confirmButtonColor: '#d33' });
            return;
        }

        try {
            const formData = new FormData();
            formData.append('program_id', currentEditProgramId);
            formData.append('title', title);
            formData.append('category', category);
            formData.append('target_amount', target_amount);
            formData.append('description', description);
            formData.append('status', status);
            formData.append('image_url', imageUrl);

            if (imageInput.files && imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            const response = await fetch('./api/edit-program.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (!result.success) throw new Error(result.message);

            bootstrap.Modal.getInstance(document.getElementById('editProgramModal')).hide();
            Swal.fire({ icon: 'success', title: 'Program Diperbarui!', text: result.message, confirmButtonColor: '#059669' });
            
            // Reload data program
            const programs = await fetchAdmin('programs');
            cachedPrograms = programs;
            renderPrograms(programs);
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function hapusProgram(programId, programTitle) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Program?',
            text: 'Apakah Anda yakin ingin menghapus program "' + programTitle + '"?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('./api/delete-program.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ program_id: programId })
                    });
                    const data = await response.json();

                    if (!data.success) throw new Error(data.message);

                    Swal.fire({ icon: 'success', title: 'Program Dihapus!', text: data.message, confirmButtonColor: '#059669' });
                    
                    // Reload data program
                    const programs = await fetchAdmin('programs');
                    cachedPrograms = programs;
                    renderPrograms(programs);
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
                }
            }
        });
    }

    // FUNGSI INISIALISASI GRAFIK CHART.JS (Pemasukan vs Pengeluaran)
    // FUNGSI INISIALISASI GRAFIK CHART.JS (Versi Baru)
    let myBarChart = null;
    let myDonutChart = null;
    let reportData = null;
    let cachedPrograms = [];

    function renderReportCards(data) {
        const currentTotal = Number(data.programs?.total_collected || 0);
        const totalExpense = Number(data.distributions?.total_distributed || 0);
        const totalIncome = currentTotal + totalExpense;

        setInnerText('reportTotalIncome', formatRupiah(totalIncome));
        setInnerText('reportTotalExpense', formatRupiah(totalExpense));
        setInnerText('reportNetBalance', formatRupiah(currentTotal));
        setInnerText('reportTotalPrograms', (data.programs?.total_programs || 0) + ' Program');
        setInnerText('reportTotalDonors', (data.donors?.total_donors || 0) + ' Donatur');
    }

    function renderPenyaluranTable(distributions) {
        const tbody = document.getElementById('tbody-penyaluran');
        if (!tbody) return;

        if (!distributions || distributions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data penyaluran.</td></tr>';
            return;
        }

        tbody.innerHTML = distributions.map(function (item) {
            const proof = item.proof_image
                ? '<a class="btn btn-sm btn-outline-secondary" href="' + item.proof_image + '" target="_blank" rel="noopener"><i class="bi bi-file-image"></i></a>'
                : '<span class="text-muted">-</span>';

            return (
                '<tr>' +
                    '<td>' + formatTanggal(item.distributed_at) + '</td>' +
                    '<td class="fw-bold">' + escapeHtml(item.program_title) + '</td>' +
                    '<td>' + escapeHtml(item.beneficiary) + '</td>' +
                    '<td class="text-danger fw-bold">- ' + formatRupiah(item.amount) + '</td>' +
                    '<td>' + proof + '</td>' +
                '</tr>'
            );
        }).join('');
    }

    async function loadFilteredDistributions() {
        const distributions = await fetchAdminUrl('distributions', {
            period: getReportPeriod(),
            month: getCurrentReportMonth()
        });
        renderPenyaluranTable(distributions);
    }

    function getCurrentReportMonth() {
        const monthInput = document.getElementById('reportMonth');

        if (getReportPeriod() === 'yearly') {
            const selectedYear = monthInput && monthInput.value ? monthInput.value : String(new Date().getFullYear());
            return selectedYear + '-01';
        }

        if (monthInput && monthInput.value) {
            return monthInput.value;
        }

        const now = new Date();
        return now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
    }

    function getReportPeriod() {
        return document.getElementById('reportPeriod')?.value || 'monthly';
    }

    function getDaysInSelectedMonth() {
        const selectedMonth = getCurrentReportMonth();
        const parts = selectedMonth.split('-');
        const year = Number(parts[0]);
        const month = Number(parts[1]);
        const totalDays = new Date(year, month, 0).getDate();
        const days = [];

        for (let day = 1; day <= totalDays; day++) {
            const date = year + '-' + String(month).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            days.push({ value: date, label: String(day) });
        }

        return days;
    }

    function getMonthsInSelectedYear() {
        const formatter = new Intl.DateTimeFormat('id-ID', { month: 'short' });
        const year = Number(getCurrentReportMonth().split('-')[0]);
        const months = [];

        for (let index = 0; index < 12; index++) {
            const date = new Date(year, index, 1);
            const value = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            const label = formatter.format(date);
            months.push({ value: value, label: label });
        }

        return months;
    }

    function getReportChartData() {
        const isYearly = getReportPeriod() === 'yearly';
        const labels = isYearly ? getMonthsInSelectedYear() : getDaysInSelectedMonth();
        const incomeSource = isYearly ? (reportData?.monthly_incomes || []) : (reportData?.daily_incomes || []);
        const expenseSource = isYearly ? (reportData?.monthly_distributions || []) : (reportData?.daily_distributions || []);
        const incomeKey = isYearly ? 'month' : 'date';
        const expenseKey = isYearly ? 'month' : 'date';

        return {
            labels: labels.map(item => item.label),
            incomeData: labels.map(item => {
                const income = incomeSource.find(row => row[incomeKey] === item.value);
                return Number(income?.total_success || 0);
            }),
            expenseData: labels.map(item => {
                const expense = expenseSource.find(row => row[expenseKey] === item.value);
                return Number(expense?.total_distributed || 0);
            })
        };
    }

    function getCategoryChartData() {
        const isYearly = getReportPeriod() === 'yearly';
        const selectedMonth = getCurrentReportMonth();
        const selectedYear = selectedMonth.split('-')[0];
        const rows = reportData?.monthly_category_distribution || [];
        const totals = {};

        rows.forEach(row => {
            const matchesFilter = isYearly
                ? String(row.month || '').startsWith(selectedYear + '-')
                : row.month === selectedMonth;

            if (matchesFilter) {
                totals[row.category] = (totals[row.category] || 0) + Number(row.total_amount || 0);
            }
        });

        const entries = Object.entries(totals);
        if (entries.length === 0 && rows.length === 0 && reportData && Array.isArray(reportData.category_distribution)) {
            return reportData.category_distribution.map(item => ({
                label: getCategoryLabel(item.category),
                value: Number(item.total_amount || 0)
            }));
        }

        return entries.map(([category, value]) => ({
            label: getCategoryLabel(category),
            value: value
        }));
    }

    function handleReportPeriodChange(shouldApply = false) {
        const label = document.getElementById('reportMonthLabel');
        const input = document.getElementById('reportMonth');
        const now = new Date();

        if (label) {
            label.innerText = getReportPeriod() === 'yearly' ? 'Pilih Tahun' : 'Pilih Bulan';
        }

        if (input) {
            if (getReportPeriod() === 'yearly') {
                const selectedYear = input.value ? input.value.substring(0, 4) : String(now.getFullYear());
                input.type = 'number';
                input.min = '2000';
                input.max = '2100';
                input.step = '1';
                input.value = selectedYear;
                input.onchange = updateChartData;
            } else {
                const selectedYear = input.value ? input.value.substring(0, 4) : String(now.getFullYear());
                input.type = 'month';
                input.removeAttribute('min');
                input.removeAttribute('max');
                input.removeAttribute('step');
                input.value = selectedYear + '-' + String(now.getMonth() + 1).padStart(2, '0');
                input.onchange = updateChartData;
            }
        }

        initChart();
        if (shouldApply) {
            updateChartData();
        }
    }

    function initChart() {
        const barCanvas = document.getElementById('barChart');
        const donutCanvas = document.getElementById('donutChart');
        if (!barCanvas || !donutCanvas) return;

        const chartData = getReportChartData();
        const incomeData = chartData.incomeData;
        const expenseData = chartData.expenseData;
        const categoryItems = getCategoryChartData();
        const categoryLabels = categoryItems.map(item => item.label);
        const categoryData = categoryItems.map(item => item.value);

        if (categoryLabels.length === 0) {
            categoryLabels.push('Sedekah Jariyah', 'Pendidikan & Yatim', 'Pangan & Sembako', 'Darurat');
            categoryData.push(0, 0, 0, 0);
        }

        const chartLabels = chartData.labels;

        if (myBarChart) {
            myBarChart.data.labels = chartLabels;
            myBarChart.data.datasets[0].data = incomeData;
            myBarChart.data.datasets[1].data = expenseData;
            myBarChart.update();
            myBarChart.resize();
        } else {
            const ctxBar = barCanvas.getContext('2d');
            myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: incomeData,
                            backgroundColor: '#059669',
                            borderRadius: 4,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Pengeluaran',
                            data: expenseData,
                            backgroundColor: '#ef4444',
                            borderRadius: 4,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom' },
                        tooltip: { callbacks: { label: function(context) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw); } } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { callback: function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); } } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        if (myDonutChart) {
            myDonutChart.data.labels = categoryLabels;
            myDonutChart.data.datasets[0].data = categoryData;
            myDonutChart.update();
            myDonutChart.resize();
        } else {
            const ctxDonut = donutCanvas.getContext('2d');
            myDonutChart = new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryData,
                        backgroundColor: ['#059669', '#0ea5e9', '#f59e0b', '#ef4444', '#64748b'],
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 11 } } }
                    }
                }
            });
        }
    }

    async function updateChartData() {
        initChart();
        try {
            await loadFilteredDistributions();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal Memuat Penyaluran', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    // FUNGSI SIMPAN PENGATURAN (Untuk Rekening & Admin)
    function simpanPengaturan(pesan) {
        // Tutup semua modal yang terbuka
        let modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            let instance = bootstrap.Modal.getInstance(modal);
            if (instance) { instance.hide(); }
        });

        // Munculkan notifikasi sukses
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: pesan,
            confirmButtonColor: '#059669'
        });
    }

    // Payment methods management (Admin)
    async function loadPaymentMethods() {
        try {
            const res = await fetch('./api/payment-methods.php');
            const json = await res.json();
            if (!res.ok || !json.success) throw new Error(json.message || 'Gagal memuat metode');
            renderPaymentMethods(json.data || []);
        } catch (err) {
            console.error('Load payment methods error:', err);
        }
    }

    function renderPaymentMethods(list) {
        const container = document.querySelector('#tab-pembayaran .row.g-3');
        if (!container) return;
        container.innerHTML = '';
        if (!Array.isArray(list) || list.length === 0) {
            container.innerHTML = '<div class="col-12 text-muted">Belum ada metode pembayaran.</div>';
            return;
        }

        list.forEach(function(m) {
            const activeClass = m.active ? 'border-success' : 'border-secondary';
            const icon = m.type === 'qris' ? 'bi-qr-code-scan text-danger' : 'bi-bank2 text-primary';
            const imgHtml = m.image ? '<img src="' + m.image + '" style="height:48px;object-fit:contain;" class="me-3 rounded">' : '';

            const col = document.createElement('div');
            col.className = 'col-md-6';
            col.innerHTML = `<div class="card border ${activeClass} border-opacity-25 rounded-3 h-100 bg-light">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi ${icon} fs-4"></i></div>
                        <div>
                            <h6 class="fw-bold mb-0">${escapeHtml(m.name)}</h6>
                            <small class="text-muted d-block">${escapeHtml(m.account)} ${m.owner ? ('a.n ' + escapeHtml(m.owner)) : ''}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="openEditMethodModal('${m.id}')"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deletePaymentMethod('${m.id}')"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>`;

            container.appendChild(col);
        });
    }

    function openEditMethodModal(id) {
        fetch('./api/payment-methods.php')
            .then(r => r.json())
            .then(json => {
                const m = (json.data || []).find(x => x.id === id);
                if (!m) return Swal.fire({icon:'error', title:'Metode tidak ditemukan'});
                document.getElementById('methodId').value = m.id;
                document.getElementById('methodType').value = m.type || 'bank';
                document.getElementById('methodName').value = m.name || '';
                document.getElementById('methodAccount').value = m.account || '';
                document.getElementById('methodOwner').value = m.owner || '';
                document.getElementById('methodActive').checked = !!m.active;
                document.getElementById('methodImage').value = '';
                // toggle image input/select based on type
                toggleMethodImageInput();
                // if qris and image exists, set select value after fetch
                if (m.type === 'qris' && m.image) {
                    // delay to allow select population
                    setTimeout(() => {
                        const sel = document.getElementById('methodImageSelect');
                        if (sel) sel.value = m.image;
                    }, 300);
                }
                new bootstrap.Modal(document.getElementById('modalTambahRekening')).show();
            }).catch(err => { console.error(err); Swal.fire({icon:'error', title:'Gagal muat metode'}); });
    }

    function openAddMethodModal() {
        document.getElementById('methodId').value = '';
        document.getElementById('methodType').value = 'bank';
        document.getElementById('methodName').value = '';
        document.getElementById('methodAccount').value = '';
        document.getElementById('methodOwner').value = '';
        document.getElementById('methodImage').value = '';
        document.getElementById('methodActive').checked = true;
        toggleMethodImageInput();
        new bootstrap.Modal(document.getElementById('modalTambahRekening')).show();
    }

    async function savePaymentMethod() {
        try {
            const id = document.getElementById('methodId').value || '';
            const fd = new FormData();
            fd.append('action', id ? 'update' : 'create');
            if (id) fd.append('id', id);
            fd.append('type', document.getElementById('methodType').value);
            fd.append('name', document.getElementById('methodName').value);
            fd.append('account', document.getElementById('methodAccount').value);
            fd.append('owner', document.getElementById('methodOwner').value);
            fd.append('active', document.getElementById('methodActive').checked ? '1' : '0');
            const fileEl = document.getElementById('methodImage');
            const type = document.getElementById('methodType').value;
            if (type === 'qris') {
                const sel = document.getElementById('methodImageSelect');
                if (sel && sel.value) {
                    fd.append('image_url', sel.value);
                } else if (fileEl && fileEl.files && fileEl.files[0]) {
                    fd.append('image', fileEl.files[0]);
                }
            } else {
                if (fileEl && fileEl.files && fileEl.files[0]) fd.append('image', fileEl.files[0]);
            }

            const res = await fetch('./api/payment-methods.php', { method: 'POST', body: fd });
            const json = await res.json();
            if (!res.ok || !json.success) throw new Error(json.message || 'Gagal menyimpan');
            bootstrap.Modal.getInstance(document.getElementById('modalTambahRekening')).hide();
            await loadPaymentMethods();
            Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Metode tersimpan', confirmButtonColor: '#059669' });
        } catch (err) {
            console.error('Save method error:', err);
            Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Gagal menyimpan metode' });
        }
    }

    async function deletePaymentMethod(id) {
        const c = await Swal.fire({ icon: 'warning', title: 'Hapus metode?', showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33' });
        if (!c.isConfirmed) return;
        const fd = new FormData(); fd.append('action','delete'); fd.append('id', id);
        const res = await fetch('./api/payment-methods.php', { method: 'POST', body: fd });
        const json = await res.json();
        if (!res.ok || !json.success) return Swal.fire({icon:'error', title:'Gagal', text: json.message});
        await loadPaymentMethods();
        Swal.fire({icon:'success', title:'Dihapus'});
    }

    async function fetchQrisList() {
        try {
            const res = await fetch('./api/qris-list.php');
            const json = await res.json();
            if (!res.ok || !json.success) return [];
            return json.data || [];
        } catch (err) {
            console.error('fetchQrisList error', err);
            return [];
        }
    }

    async function toggleMethodImageInput() {
        const container = document.getElementById('methodImageContainer');
        if (!container) return;
        const type = document.getElementById('methodType').value;
        if (type === 'qris') {
            // replace with select populated from server
            const images = await fetchQrisList();
            let html = '<label class="form-label small fw-semibold">Pilih Gambar QRIS dari folder</label>';
            html += '<select id="methodImageSelect" class="form-select mb-2">';
            html += '<option value="">-- Pilih QRIS --</option>';
            images.forEach(function(p) { html += '<option value="'+p+'">'+p.split('/').pop()+'</option>'; });
            html += '</select>';
            html += '<div class="small text-muted">Atau unggah gambar baru jika ingin menambahkan gambar lainnya.</div>';
            html += '<input type="file" id="methodImage" class="form-control mt-2" accept="image/*">';
            container.innerHTML = html;
        } else {
            container.innerHTML = '<label class="form-label small fw-semibold">Gambar / QR (opsional)</label>\n                        <input type="file" id="methodImage" class="form-control" accept="image/*">';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const typeEl = document.getElementById('methodType');
        if (typeEl) typeEl.addEventListener('change', toggleMethodImageInput);
    });

    // FUNGSI HAPUS ADMIN DENGAN KONFIRMASI
    async function hapusAdmin(adminId, nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Akses?',
            text: `Anda yakin ingin mencabut hak akses untuk admin ${nama}?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Cabut Akses',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('./api/admin.php?action=delete_admin', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ admin_id: adminId })
                    });
                    const resultData = await response.json();

                    if (!response.ok || !resultData.success) {
                        throw new Error(resultData.message || 'Gagal menghapus admin');
                    }

                    await loadAdminDashboardData();
                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: resultData.message, confirmButtonColor: '#059669' });
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const monthInput = document.getElementById('reportMonth');
        if (monthInput && !monthInput.value) {
            const now = new Date();
            monthInput.value = String(now.getFullYear());
        }
        handleReportPeriodChange();
        applyAdminRoleVisibility();
        loadAdminDashboardData();
        loadPaymentMethods();
    });


 async function cetakLaporanPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const totalPemasukan =
        document.getElementById('reportTotalIncome')?.innerText || 'Rp 0';

    const totalPengeluaran =
        document.getElementById('reportTotalExpense')?.innerText || 'Rp 0';

    const pemasukan = parseInt(totalPemasukan.replace(/[^\d]/g, '')) || 0;
    const pengeluaran = parseInt(totalPengeluaran.replace(/[^\d]/g, '')) || 0;
    const totalBersih = pemasukan - pengeluaran;

    let y = 20;

    doc.setFontSize(18);
    doc.text('LAPORAN KEUANGAN DONASIKU', 20, y);

    y += 10;

    doc.setFontSize(10);
    doc.text(
        'Tanggal Cetak : ' +
        new Date().toLocaleString('id-ID'),
        20,
        y
    );

    y += 10;

    doc.line(20, y, 190, y);

    y += 15;

    doc.setFontSize(14);
    doc.text('Ringkasan Keuangan', 20, y);

    y += 10;

    doc.setFontSize(11);
    doc.text('Total Pemasukan : ' + totalPemasukan, 25, y);

    y += 8;
    doc.text('Total Pengeluaran : ' + totalPengeluaran, 25, y);

    y += 8;
    doc.text(
        'Total Bersih : Rp ' +
        totalBersih.toLocaleString('id-ID'),
        25,
        y
    );

    y += 20;

    doc.setFontSize(14);
    doc.text('Riwayat Penyaluran Dana Terakhir', 20, y);

    y += 10;

    doc.setFontSize(10);

    doc.text('Tanggal', 20, y);
    doc.text('Program', 55, y);
    doc.text('Penerima', 115, y);
    doc.text('Nominal', 190, y, { align: 'right' });

    y += 5;

    doc.line(20, y, 190, y);

    y += 8;

    const rows = document.querySelectorAll(
        '#tbody-penyaluran tr'
    );

    rows.forEach(row => {

        const cols = row.querySelectorAll('td');

        if (cols.length < 4) return;

        if (y > 270) {
            doc.addPage();
            y = 20;
        }

        const tanggal = cols[0].innerText.trim();
        const program = cols[1].innerText.trim();
        const penerima = cols[2].innerText.trim();
        const nominal = cols[3].innerText.trim();

        doc.text(tanggal.substring(0, 20), 20, y);
        doc.text(program.substring(0, 30), 55, y);
        doc.text(penerima.substring(0, 25), 115, y);
        doc.text(nominal, 190, y, { align: 'right' });

        y += 8;
    });

    doc.save('laporan-keuangan-donasiku.pdf');
}
</script>
</body>
</html>
