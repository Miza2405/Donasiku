<?php include 'component/header.php'; ?>

    <style>
        /* Latar Belakang Hero About */
        .hero-about { 
            background: linear-gradient(rgba(5, 150, 105, 0.85), rgba(5, 150, 105, 0.95)), url('https://images.unsplash.com/photo-1593113565694-c6c703b44b82?auto=format&fit=crop&w=1200&q=80') center/cover fixed; 
            padding: 120px 0; 
            color: white; 
            text-align: center; 
        }
        
        /* Kartu Statistik */
        .stat-card { 
            background: white; border-radius: 15px; padding: 40px 20px; 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: center; 
            border-bottom: 5px solid #059669; transition: all 0.3s ease; 
        }
        .stat-card:hover { 
            transform: translateY(-10px); box-shadow: 0 15px 30px rgba(5, 150, 105, 0.15); 
        }
        
        /* Kartu Kontak */
        .contact-card { 
            border-left: 5px solid #059669; border-radius: 15px; 
        }
    </style>

    <header class="hero-about">
        <div class="container" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="display-4 fw-bold mb-3">Mengenal DonasiKu</h1>
            <p class="lead w-75 mx-auto">Platform digital terpercaya untuk menyalurkan Donasi, Sedekah, dan Bantuan Kemanusiaan secara transparan, mudah, dan tepat sasaran kepada para penerima manfaat.</p>
        </div>
    </header>

    <section class="container py-5 mt-5">
        <div class="row align-items-center g-5">
            <div class="col-md-6" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4 shadow-lg" alt="Anak-anak tersenyum">
            </div>
            <div class="col-md-6 px-lg-4" data-aos="fade-left">
                <h2 class="fw-bold text-emerald mb-4">Visi & Misi Kami</h2>
                <h5 class="fw-bold"><span class="text-emerald">🎯</span> Visi</h5>
                <p class="text-muted mb-4">Menjadi pengelola dana kemanusiaan berbasis teknologi terdepan yang mengangkat derajat sesama dan menebar rahmat bagi semesta alam.</p>
                <h5 class="fw-bold"><span class="text-emerald">🚀</span> Misi</h5>
                <ul class="text-muted mb-0" style="line-height: 2;">
                    <li>Memfasilitasi kemudahan berdonasi melalui inovasi aplikasi digital.</li>
                    <li>Menyalurkan dana bantuan secara transparan, akuntabel, dan amanah.</li>
                    <li>Mengadakan program pemberdayaan dan bantuan sosial secara berkelanjutan.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="bg-light py-5 mt-4">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-down">
                <h2 class="fw-bold text-emerald">Jejak Kebaikan Kita</h2>
                <p class="text-muted">Alhamdulillah, bersama kita telah menebar banyak manfaat.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-card">
                        <h2 class="fw-bold text-emerald display-5">15.000+</h2>
                        <p class="text-muted fw-semibold mb-0">Donatur Bergabung</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-card">
                        <h2 class="fw-bold text-emerald display-5">Rp 12 M</h2>
                        <p class="text-muted fw-semibold mb-0">Dana Tersalurkan</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-card">
                        <h2 class="fw-bold text-emerald display-5">50.000+</h2>
                        <p class="text-muted fw-semibold mb-0">Penerima Manfaat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-down">
                <h2 class="fw-bold text-emerald">Hubungi Layanan Kami</h2>
                <p class="text-muted">Punya pertanyaan seputar program penyaluran atau donasi? Kami siap membantu.</p>
            </div>
            <div class="row g-5 align-items-center">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="card border-0 shadow-sm p-4 h-100 contact-card">
                        <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                        <div class="d-flex mb-3 align-items-center">
                            <div class="bg-light p-3 rounded-circle text-emerald me-3"><i class="bi bi-geo-alt-fill fs-5"></i></div>
                            <div><h6 class="fw-bold mb-0">Kantor Pusat DonasiKu</h6><p class="text-muted small mb-0">Jl. Kebaikan No. 99, Jakarta</p></div>
                        </div>
                        <div class="d-flex mb-3 align-items-center">
                            <div class="bg-light p-3 rounded-circle text-emerald me-3"><i class="bi bi-envelope-fill fs-5"></i></div>
                            <div><h6 class="fw-bold mb-0">Email Layanan</h6><p class="text-muted small mb-0">layanan@donasiku.org</p></div>
                        </div>
                        <div class="d-flex mb-4 align-items-center">
                            <div class="bg-light p-3 rounded-circle text-emerald me-3"><i class="bi bi-telephone-fill fs-5"></i></div>
                            <div><h6 class="fw-bold mb-0">Call Center</h6><p class="text-muted small mb-0">021-888-9999 (Senin - Jumat)</p></div>
                        </div>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i> Chat Admin via WhatsApp
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 15px;">
                        <h4 class="fw-bold mb-4">Kirim Pesan Langsung</h4>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" placeholder="Nama Anda" required>
                                        <label>Nama Lengkap</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" placeholder="name@example.com" required>
                                        <label>Alamat Email</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select id="kategoriPesan" class="form-select">
                                            <option selected disabled>Pilih Kategori...</option>
                                            <option value="1">Konsultasi Program Donasi</option>
                                            <option value="2">Konfirmasi Pembayaran</option>
                                            <option value="3">Kendala Teknis Aplikasi</option>
                                        </select>
                                        <label for="kategoriPesan">Kategori Pesan</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Tulis pesan" style="height: 150px" required></textarea>
                                        <label>Tuliskan pesan Anda...</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-emerald w-100 py-3 rounded-pill fw-bold">
                                        <i class="bi bi-send-fill me-2"></i> Kirim Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'component/footer.php'; ?>