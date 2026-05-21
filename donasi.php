<?php include 'component/header.php'; ?>

    <style>
        .hero-catalog {
            background: linear-gradient(
                rgba(5, 150, 105, 0.7), 
                rgba(5, 150, 105, 0.85)
            ), 
            url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=1200&q=80'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding: 80px 0;
            color: white;
            text-align: center;
        }
        
        /* Filter Tabs */
        .nav-pills .nav-link { 
            color: #64748b; font-weight: 600; border-radius: 50px; padding: 8px 20px; margin: 5px; 
            transition: all 0.3s ease; border: 1px solid transparent; cursor: pointer; 
            background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .nav-pills .nav-link:hover { color: #059669; border-color: #059669; }
        .nav-pills .nav-link.active { background-color: #059669; color: white; border-color: #059669; box-shadow: 0 4px 10px rgba(5,150,105,0.2); }

        /* Efek Animasi Saat Filter Ditekan */
        .program-item { animation: fadeInScale 0.4s ease-in-out; }
        @keyframes fadeInScale { 0% { opacity: 0; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
    </style>

    <header class="hero-catalog mb-4">
        <div class="container" data-aos="fade-down">
            <h2 class="fw-bold mb-2">Pilih Program Kebaikan</h2>
            <p class="lead mb-0">Temukan kampanye donasi yang ingin Anda bantu hari ini.</p>
        </div>
    </header>

    <div class="container mb-5">
        
        <div class="d-flex justify-content-center flex-wrap mb-5" data-aos="fade-up" id="filter-container">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item"><button class="nav-link active filter-btn" data-filter="all">Semua Program</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="bencana">Bencana Alam</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="pangan">Pangan & Sembako</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="kesehatan">Kesehatan</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="pendidikan">Pendidikan</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="pembangunan">Pembangunan</button></li>
                <li class="nav-item"><button class="nav-link filter-btn" data-filter="pemberdayaan">Pemberdayaan</button></li>
            </ul>
        </div>

        <div class="row g-4" id="program-list">
            
            <div class="col-md-4 col-sm-6 program-item" data-category="bencana">
                <div class="adara-card" onclick="bukaDetail(event, 'Indonesia Darurat Bencana: Longsor & Banjir', 'https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200', '35076524', '100000000', 'Bencana Alam')">
                    <div class="adara-img-wrapper">
                        <img src="https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200" class="adara-img" alt="Bencana">
                        <span class="adara-category-badge"><i class="bi bi-tsunami"></i> Bencana Alam</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Indonesia Darurat Bencana: Longsor & Banjir</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 78%"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 35.076.524</span></div>
                                <div class="adara-days">7 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pangan">
                <div class="adara-card" onclick="bukaDetail(event, 'Berbagi Paket Sembako Untuk Keluarga Dhuafa', 'https://www.lead.co.id/wp-content/uploads/2020/04/IMG-20200404-WA0187.jpg', '18500000', '30000000', 'Pangan & Sembako')">
                    <div class="adara-img-wrapper">
                        <img src="https://www.lead.co.id/wp-content/uploads/2020/04/IMG-20200404-WA0187.jpg" class="adara-img" alt="Sembako">
                        <span class="adara-category-badge" style="color: #f59e0b;"><i class="bi bi-bag-heart-fill"></i> Pangan & Sembako</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Berbagi Paket Sembako Untuk Keluarga Dhuafa</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 61%; background-color: #f59e0b;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 18.500.000</span></div>
                                <div class="adara-days" style="color: #d97706; background: #fffbeb; border-color: #fde68a;">20 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="kesehatan">
                <div class="adara-card" onclick="bukaDetail(event, 'Bantuan Medis Darurat & Kemanusiaan', 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80', '54560000', '100000000', 'Kesehatan')">
                    <div class="adara-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80" class="adara-img" alt="Bantuan Medis">
                        <span class="adara-category-badge"><i class="bi bi-heart-pulse-fill"></i> Kesehatan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Bantuan Medis Darurat & Kemanusiaan</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 54%"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 54.560.000</span></div>
                                <div class="adara-days">12 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pangan">
                <div class="adara-card" onclick="bukaDetail(event, 'Bantu Pangan dan Air Bersih Untuk Palestina', 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80', '27066258', '200000000', 'Krisis Pangan')">
                    <div class="adara-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80" class="adara-img" alt="Pangan Palestina">
                        <span class="adara-category-badge"><i class="bi bi-basket-fill"></i> Krisis Pangan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Bantu Pangan dan Air Bersih Untuk Palestina</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 45%"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 27.066.258</span></div>
                                <div class="adara-days">Tanpa Batas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pendidikan">
                <div class="adara-card" onclick="bukaDetail(event, 'Santunan Anak Yatim Pelosok Negeri', 'https://pantiyatim.or.id/wp-content/uploads/2020/11/anak-yatim.jpeg', '12400000', '50000000', 'Pendidikan')">
                    <div class="adara-img-wrapper">
                        <img src="https://pantiyatim.or.id/wp-content/uploads/2020/11/anak-yatim.jpeg" class="adara-img" alt="Santunan Yatim">
                        <span class="adara-category-badge" style="color: #0ea5e9;"><i class="bi bi-book-fill"></i> Pendidikan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Santunan Anak Yatim Pelosok Negeri</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 25%; background-color: #0ea5e9;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 12.400.000</span></div>
                                <div class="adara-days" style="color: #0ea5e9; background: #f0f9ff; border-color: #bae6fd;">Tanpa Batas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pemberdayaan">
                <div class="adara-card" onclick="bukaDetail(event, 'Bantuan Modal Usaha Untuk Ibu Tangguh', 'https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg', '8000000', '20000000', 'Pemberdayaan')">
                    <div class="adara-img-wrapper">
                        <img src="https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg" class="adara-img" alt="Modal Usaha">
                        <span class="adara-category-badge" style="color: #8b5cf6;"><i class="bi bi-shop"></i> Pemberdayaan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Bantuan Modal Usaha Untuk Ibu Tangguh</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 40%; background-color: #8b5cf6;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 8.000.000</span></div>
                                <div class="adara-days" style="color: #8b5cf6; background: #f5f3ff; border-color: #ddd6fe;">15 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pembangunan">
                <div class="adara-card" onclick="bukaDetail(event, 'Pembangunan Masjid Pelosok Desa', 'https://pro.kutaitimurkab.go.id/wp-content/uploads/2025/06/a59864bd-0917-49ca-a55d-3549fffe2210-1024x684.jpeg', '250000000', '500000000', 'Pembangunan')">
                    <div class="adara-img-wrapper">
                        <img src="https://pro.kutaitimurkab.go.id/wp-content/uploads/2025/06/a59864bd-0917-49ca-a55d-3549fffe2210-1024x684.jpeg" class="adara-img" alt="Pembangunan Masjid">
                        <span class="adara-category-badge" style="color: #059669;"><i class="bi bi-bricks"></i> Pembangunan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Pembangunan Masjid Pelosok Desa</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 50%; background-color: #059669;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 250.000.000</span></div>
                                <div class="adara-days" style="color: #059669; background: #f0fdf4; border-color: #a7f3d0;">45 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pendidikan">
                <div class="adara-card" onclick="bukaDetail(event, 'Beasiswa Santri Penghafal Quran', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s', '45000000', '150000000', 'Pendidikan')">
                    <div class="adara-img-wrapper">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s=10" class="adara-img" alt="Beasiswa Santri">
                        <span class="adara-category-badge" style="color: #0ea5e9;"><i class="bi bi-mortarboard-fill"></i> Pendidikan</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Beasiswa Pendidikan Santri Penghafal Quran</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 30%; background-color: #0ea5e9;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 45.000.000</span></div>
                                <div class="adara-days" style="color: #0ea5e9; background: #f0f9ff; border-color: #bae6fd;">30 Hari Lagi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 program-item" data-category="pangan">
                <div class="adara-card" onclick="bukaDetail(event, 'Sedekah Makanan Untuk Pekerja Jalanan', 'https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg', '5200000', '10000000', 'Pangan & Sembako')">
                    <div class="adara-img-wrapper">
                        <img src="https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg" class="adara-img" alt="Makanan">
                        <span class="adara-category-badge" style="color: #f59e0b;"><i class="bi bi-cup-hot-fill"></i> Pangan & Sembako</span>
                    </div>
                    <div class="adara-body">
                        <h3 class="adara-title">Sedekah Makanan Hangat Untuk Pekerja Jalanan</h3>
                        <div class="adara-creator"><i class="bi bi-patch-check-fill"></i> Yayasan DonasiKu Nasional</div>
                        <div class="mt-auto">
                            <div class="progress adara-progress"><div class="progress-bar" style="width: 52%; background-color: #f59e0b;"></div></div>
                            <div class="adara-footer">
                                <div><span class="adara-amount-label">Terkumpul</span><span class="adara-amount">Rp 5.200.000</span></div>
                                <div class="adara-days" style="color: #d97706; background: #fffbeb; border-color: #fde68a;">Setiap Jumat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php include 'component/footer.php'; ?>