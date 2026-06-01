<?php
require_once 'koneksi.php';

$urgentPrograms = [];
$query = "SELECT * FROM programs
          WHERE status='active'
          AND end_date IS NOT NULL
          AND end_date >= CURDATE()
          ORDER BY end_date ASC
          LIMIT 3";

$result = $koneksi->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $urgentPrograms[] = $row;
    }
}

include 'component/header.php';
?>

    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="https://www.megasyariah.co.id/bms-new/edukasi-tips/hukum_sedekah.jpg" class="d-block w-100" alt="Masjid">
                <div class="carousel-caption d-none d-md-block" data-aos="fade-up" data-aos-duration="1500">
                    <h1 class="fw-bold display-4">Satu Sedekah, Sejuta Harapan</h1>
                    <p class="fs-5">Salurkan donasi Anda dengan mudah, transparan, dan amanah.</p>
                    <button onclick="requireLogin(event)" class="btn btn-emerald btn-lg mt-3 px-5 rounded-pill">Tunaikan Sedekah</button>
                </div>
            </div>
            <div class="carousel-item" data-bs-interval="5000">
                <img src="https://ichef.bbci.co.uk/ace/ws/640/cpsprodpb/60ca/live/b64852f0-d25d-11f0-b6dc-c3fa29c21ab2.jpg.webp" class="d-block w-100" alt="Berbagi">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="fw-bold display-4">Bantu Sesama yang Membutuhkan</h1>
                    <p class="fs-5">Sedekah Anda adalah harapan baru bagi mereka yang menanti uluran tangan.</p>
                    <button onclick="requireLogin(event)" class="btn btn-emerald btn-lg mt-3 px-5 rounded-pill">Mulai Berbagi</button>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>

    <div id="layanan" class="container py-5 mt-4">
        <div class="text-center mb-5" data-aos="fade-down">
            <h2 class="fw-bold text-emerald">Program Kebaikan Kami</h2>
            <p class="text-muted">Pilih kategori program donasi dan sedekah yang ingin Anda salurkan</p>
        </div>
        
        <div class="row g-4 mb-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card card-donasi h-100 p-4 text-center">
                    <div class="mb-3"><h1 class="display-3">🕌</h1></div>
                    <h4 class="fw-bold">Sedekah Jariyah</h4>
                    <p class="text-muted">Pahala tak terputus dengan berpartisipasi dalam pembangunan fasilitas umat dan rumah ibadah.</p>
                    <button onclick="requireLogin(event)" class="btn btn-outline-success mt-auto rounded-pill fw-semibold w-100">Lihat Program</button>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card card-donasi h-100 p-4 text-center">
                    <div class="mb-3"><h1 class="display-3">🤝</h1></div>
                    <h4 class="fw-bold">Donasi Kemanusiaan</h4>
                    <p class="text-muted">Bantu ringankan beban saudara kita yang sedang terdampak krisis, darurat pangan, dan musibah.</p>
                    <button onclick="requireLogin(event)" class="btn btn-outline-success mt-auto rounded-pill fw-semibold w-100">Lihat Program</button>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card card-donasi h-100 p-4 text-center">
                    <div class="mb-3"><h1 class="display-3">👦</h1></div>
                    <h4 class="fw-bold">Santunan Anak Yatim</h4>
                    <p class="text-muted">Ukir senyum di wajah mereka dengan memberikan dukungan pendidikan dan pemenuhan kebutuhan hidup.</p>
                    <button onclick="requireLogin(event)" class="btn btn-outline-success mt-auto rounded-pill fw-semibold w-100">Lihat Program</button>
                </div>
            </div>
        </div>
    </div>

    <div id="program" class="container py-5 mb-5 bg-white rounded-4 shadow-sm px-4 px-md-5">
        <div class="text-center mb-5" data-aos="fade-down">
            <span class="badge bg-danger mb-2 px-3 py-2 rounded-pill fs-6"><i class="bi bi-clock-history me-1"></i> Segera Berakhir</span>
            <h2 class="fw-bold text-emerald">Bantuan Segera Disalurkan</h2>
            <p class="text-muted">Salurkan kepedulian Anda sebelum batas waktu program di bawah ini berakhir.</p>
        </div>

        
<div class="row g-4">

<?php foreach($urgentPrograms as $index => $program): ?>
<?php
$persen = ($program['target_amount'] > 0)
    ? min(100, round(($program['collected_amount'] / $program['target_amount']) * 100))
    : 0;

$sisaHari = max(0, ceil((strtotime($program['end_date']) - time()) / 86400));
$gambar = !empty($program['image_url']) ? $program['image_url'] : 'https://via.placeholder.com/600x400';
?>
<div class="col-md-4 col-sm-6" data-aos="fade-up">
    <div class="adara-card">
        <div class="adara-img-wrapper">
            <img src="<?= htmlspecialchars($gambar) ?>" class="adara-img" alt="<?= htmlspecialchars($program['title']) ?>">
            <span class="adara-category-badge"><?= htmlspecialchars($program['category']) ?></span>
        </div>

        <div class="adara-body">
            <h3 class="adara-title"><?= htmlspecialchars($program['title']) ?></h3>

            <div class="adara-creator">
                <i class="bi bi-patch-check-fill"></i> DonasiKu
            </div>

            <div class="mt-auto">
                <div class="progress adara-progress">
                    <div class="progress-bar" style="width: <?= $persen ?>%"></div>
                </div>

                <div class="adara-footer">
                    <div>
                        <span class="adara-amount-label">Terkumpul</span>
                        <span class="adara-amount">
                            Rp <?= number_format($program['collected_amount'],0,',','.') ?>
                        </span>
                    </div>

                    <div class="adara-days">
                        <?= $sisaHari ?> Hari Lagi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

</div>

<div class="text-center mt-5"
 data-aos="zoom-in">
            <button onclick="requireLogin(event)" class="btn btn-orange fw-bold rounded-pill px-5 py-3 shadow-sm">
                Lihat Semua Program
            </button>
        </div>
    </div>

<?php include 'component/footer.php'; ?>