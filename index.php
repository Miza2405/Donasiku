<?php include 'component/header.php'; ?>

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
            
            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                <div class="adara-card" onclick="bukaDetailIndex(event, 'Indonesia Darurat Bencana', 'https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200', '35076524', '100000000', 'Bencana Alam')">
                    <div class="adara-img-wrapper">
                        <img src="https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200" class="adara-img" alt="Bencana">
                        <span class="adara-category-badge"><i class="bi bi-tsunami"></i> Bencana Alam</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Indonesia Darurat Bencana: Longsor & Banjir</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress">
                                <div class="progress-bar" style="width: 78%"></div>
                            </div>
                            <div class="adara-footer">
                                <div>
                                    <span class="adara-amount-label">Terkumpul</span>
                                    <span class="adara-amount">Rp 35.076.524</span>
                                </div>
                                <div class="adara-days">7 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                <div class="adara-card" onclick="bukaDetailIndex(event, 'Bantu Pangan dan Air Indonesia Palestina', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80', '27066258', '200000000', 'Pangan')">
                    <div class="adara-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80" class="adara-img" alt="Pangan">
                        <span class="adara-category-badge"><i class="bi bi-basket-fill"></i> Krisis Pangan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Bantu Pangan dan Air Bersih Untuk Palestina</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Lazis DonasiKu</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress">
                                <div class="progress-bar" style="width: 45%"></div>
                            </div>
                            <div class="adara-footer">
                                <div>
                                    <span class="adara-amount-label">Terkumpul</span>
                                    <span class="adara-amount">Rp 27.066.258</span>
                                </div>
                                <div class="adara-days">Tanpa Batas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                <div class="adara-card" onclick="bukaDetailIndex(event, 'Bantuan Medis & Kemanusiaan Darurat', 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=600&q=80', '54560750', '100000000', 'Kesehatan')">
                    <div class="adara-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=600&q=80" class="adara-img" alt="Medis">
                        <span class="adara-category-badge"><i class="bi bi-heart-pulse-fill"></i> Medis</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Bantuan Medis & Kemanusiaan Darurat</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Sahabat Relawan</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress">
                                <div class="progress-bar" style="width: 60%"></div>
                            </div>
                            <div class="adara-footer">
                                <div>
                                    <span class="adara-amount-label">Terkumpul</span>
                                    <span class="adara-amount">Rp 54.560.000</span>
                                </div>
                                <div class="adara-days">12 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-5" data-aos="zoom-in">
            <button onclick="requireLogin(event)" class="btn btn-orange fw-bold rounded-pill px-5 py-3 shadow-sm">
                Lihat Semua Program
            </button>
        </div>
    </div>

<?php include 'component/footer.php'; ?>