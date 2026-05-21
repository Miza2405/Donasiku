<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Program - DonasiKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; padding-top: 80px; }
        .text-emerald { color: #059669; } .bg-emerald { background-color: #059669; }
        .navbar { background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        .img-detail { width: 100%; height: 400px; object-fit: cover; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        
        .progress-detail { height: 12px; border-radius: 10px; background-color: #e2e8f0; }
        .progress-detail .progress-bar { background-color: #059669; transition: width 1s ease-in-out; }
        
        .card-donasi-sticky { position: sticky; top: 100px; border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        
        .btn-nominal { border: 1px solid #dee2e6; color: #495057; border-radius: 10px; transition: 0.2s; padding: 12px; background: white;}
        .btn-nominal:hover, .btn-nominal.active { background-color: #059669; color: white; border-color: #059669; }
        .btn-emerald { background-color: #059669; color: white; border-radius: 10px; font-weight: 600; transition: 0.3s; }
        .btn-emerald:hover { background-color: #047857; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-emerald" href="index.php">💚 DonasiKu</a>
            <a href="donasi.php" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        </div>
    </nav>

    <div class="container py-4 mb-5">
        <div class="row g-5">
            <!-- KOLOM KIRI: Foto & Cerita -->
            <div class="col-lg-7">
                <img id="viewGambar" src="" class="img-detail mb-4" alt="Gambar Program">
                
                <span class="badge bg-success mb-2 px-3 py-2 rounded-pill shadow-sm" id="viewKategori">Kategori</span>
                <h2 class="fw-bold mb-4" id="viewJudul">Memuat Judul Program...</h2>
                
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-shield-check text-emerald fs-3 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Donasi Terverifikasi</h6>
                            <small class="text-muted">Digalang oleh Yayasan DonasiKu Nasional</small>
                        </div>
                    </div>
                    <hr>
                    <h5 class="fw-bold mb-3">Kisah Program</h5>
                    <!-- Di sinilah cerita akan berubah secara otomatis -->
                    <p class="text-muted" style="line-height: 1.8;" id="viewKisah">
                        Memuat cerita kampanye...
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: Form Donasi Melayang (Sticky) -->
            <div class="col-lg-5">
                <div class="card card-donasi-sticky p-4">
                    <h5 class="fw-bold mb-3">Informasi Donasi</h5>
                    
                    <div class="progress progress-detail mb-3">
                        <div class="progress-bar" id="viewProgress" style="width: 0%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <small class="text-muted d-block">Terkumpul</small>
                            <h4 class="fw-bold text-emerald mb-0" id="viewTerkumpul">Rp 0</h4>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Target</small>
                            <span class="fw-semibold text-dark" id="viewTarget">Rp 0</span>
                        </div>
                    </div>

                    <form id="formCheckout">
                        <h6 class="fw-bold mb-3">Pilih Nominal Donasi</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6"><button type="button" class="btn btn-nominal w-100 fw-bold" onclick="setNominal(50000)">Rp 50.000</button></div>
                            <div class="col-6"><button type="button" class="btn btn-nominal w-100 fw-bold" onclick="setNominal(100000)">Rp 100.000</button></div>
                            <div class="col-6"><button type="button" class="btn btn-nominal w-100 fw-bold" onclick="setNominal(250000)">Rp 250.000</button></div>
                            <div class="col-6"><button type="button" class="btn btn-nominal w-100 fw-bold" onclick="setNominal(500000)">Rp 500.000</button></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Nominal Lainnya</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 fw-bold">Rp</span>
                                <input type="number" class="form-control bg-light border-0" id="inputNominal" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Sertakan Doa (Opsional)</label>
                            <textarea class="form-control bg-light border-0" id="inputDoa" rows="2" placeholder="Semoga berkah..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-emerald w-100 py-3 fs-5 shadow-sm">
                            <i class="bi bi-heart-fill me-2"></i> Lanjutkan Donasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
        const kisahDefault = "Assalamu'alaikum #OrangBaik. Bantuan Anda sangat berarti bagi mereka. Donasi yang terkumpul akan langsung disalurkan kepada para penerima manfaat sesuai dengan kategori program yang Anda pilih. Mari bersama-sama kita ringankan beban saudara kita.";

        document.addEventListener("DOMContentLoaded", function() {
            let judul = sessionStorage.getItem("detail_judul");
            let gambar = sessionStorage.getItem("detail_gambar");
            let terkumpul = parseInt(sessionStorage.getItem("detail_terkumpul"));
            let target = parseInt(sessionStorage.getItem("detail_target"));
            let kategori = sessionStorage.getItem("detail_kategori");

            if(!judul) { window.location.href = "donasi.php"; return; }

            let persentase = (terkumpul / target) * 100;
            if(persentase > 100) persentase = 100;

            let badgeClass = "bg-success";
            let progColor = "#059669";
            
            if(kategori.includes("Bencana") || kategori.includes("Kesehatan") || kategori.includes("Pangan")) {
                badgeClass = "bg-danger"; progColor = "#ef4444";
            } else if (kategori.includes("Pendidikan")) {
                badgeClass = "bg-info text-dark"; progColor = "#0ea5e9";
            } else if (kategori.includes("Sembako")) {
                badgeClass = "bg-warning text-dark"; progColor = "#f59e0b";
            } else if (kategori.includes("Pemberdayaan")) {
                badgeClass = "bg-secondary"; progColor = "#8b5cf6";
            }

            // KISAH PROGRAM PINTAR (Mendeteksi Kata Kunci)
            let teksCerita = kisahDefault;
            let judulLower = judul.toLowerCase();

            if (judulLower.includes("bencana") || judulLower.includes("longsor")) {
                teksCerita = "Bencana alam telah meluluhlantakkan rumah dan harapan saudara-saudara kita. Ribuan warga kini terpaksa mengungsi dengan fasilitas seadanya. Mari ulurkan tangan kita untuk menyediakan tenda darurat, selimut, obat-obatan, dan makanan siap saji bagi mereka yang terdampak.";
            } else if (judulLower.includes("sembako")) {
                teksCerita = "Masih banyak keluarga dhuafa di sekitar kita yang kesulitan sekadar untuk makan esok hari. Kenaikan harga bahan pokok semakin mencekik mereka. Melalui program ini, kita akan mendistribusikan paket sembako bergizi (beras, minyak, lauk pauk) agar dapur mereka tetap mengepul.";
            } else if (judulLower.includes("medis") || judulLower.includes("kesehatan")) {
                teksCerita = "Banyak pasien dari kalangan tidak mampu yang terpaksa menghentikan pengobatan karena terbentur biaya rumah sakit dan tebusan obat yang mahal. Donasi Anda akan disalurkan untuk melunasi tunggakan pengobatan, membelikan alat bantu medis, dan biaya pendampingan pasien kritis.";
            } else if (judulLower.includes("palestina")) {
                teksCerita = "Saudara kita di Palestina terus menghadapi krisis kemanusiaan parah. Akses air bersih dan bahan pangan sangat terbatas. Bantuan dari Anda akan disalurkan langsung melalui mitra terpercaya untuk mendirikan dapur umum darurat dan mendistribusikan tangki air bersih di camp pengungsian.";
            } else if (judulLower.includes("yatim")) {
                teksCerita = "Anak-anak yatim di pelosok negeri seringkali harus putus sekolah dan bekerja di usia dini demi membantu ibu mereka bertahan hidup. Program ini bertujuan untuk memberikan santunan bulanan, perlengkapan sekolah, serta biaya pendidikan agar mereka bisa meraih cita-citanya layaknya anak-anak lain.";
            } else if (judulLower.includes("modal") || judulLower.includes("ibu tangguh")) {
                teksCerita = "Banyak ibu tunggal (janda) yang harus menjadi tulang punggung keluarga namun tidak memiliki modal untuk memulai usaha mikro. Program pemberdayaan ini akan memberikan bantuan modal berupa alat kerja, gerobak, atau uang tunai, disertai pendampingan agar mereka bisa mandiri secara finansial.";
            } else if (judulLower.includes("masjid")) {
                teksCerita = "Di sebuah desa terpelosok, warga harus berjalan berkilo-kilometer untuk melaksanakan shalat berjamaah karena tidak ada masjid terdekat. Pembangunan rumah Allah ini terhenti di tengah jalan karena kurangnya dana patungan warga. Mari berinvestasi pahala jariyah dengan membelikan material bangunan untuk masjid ini.";
            } else if (judulLower.includes("beasiswa") || judulLower.includes("santri")) {
                teksCerita = "Generasi penghafal Al-Quran adalah aset bangsa dan agama. Namun, banyak dari para santri berprestasi ini berasal dari keluarga pra-sejahtera. Beasiswa ini akan menjamin kebutuhan hidup, kitab, dan biaya asrama mereka, sehingga mereka bisa fokus menuntaskan hafalan 30 Juz.";
            } else if (judulLower.includes("makanan") || judulLower.includes("pekerja")) {
                teksCerita = "Pekerja jalanan seperti pemulung, tukang becak, dan penyapu jalan bekerja keras dari pagi buta hingga malam hari, seringkali tanpa sempat makan yang layak. Program Sedekah ini akan rutin membagikan ratusan porsi makanan hangat bergizi sebagai sumber tenaga mereka mengais rezeki yang halal.";
            }

            document.getElementById("viewJudul").innerText = judul;
            document.getElementById("viewGambar").src = gambar;
            
            let badgeKategori = document.getElementById("viewKategori");
            badgeKategori.innerText = kategori;
            badgeKategori.className = `badge ${badgeClass} mb-2 px-3 py-2 rounded-pill shadow-sm`;
            
            let progressBar = document.getElementById("viewProgress");
            progressBar.style.width = persentase + "%";
            progressBar.style.backgroundColor = progColor;

            document.getElementById("viewKisah").innerText = teksCerita;
            document.getElementById("viewTerkumpul").innerText = "Rp " + new Intl.NumberFormat('id-ID').format(terkumpul);
            document.getElementById("viewTarget").innerText = "Rp " + new Intl.NumberFormat('id-ID').format(target);
        });

        function setNominal(val) {
            document.getElementById('inputNominal').value = val;
            document.querySelectorAll('.btn-nominal').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        document.getElementById('formCheckout').addEventListener('submit', function(event) {
            event.preventDefault();
            
            if (localStorage.getItem("isLoggedIn") !== "true") {
                Swal.fire({
                    icon: 'warning', title: 'Harap Login Dahulu', text: 'Untuk melanjutkan transaksi, Anda harus masuk ke akun.',
                    showCancelButton: true, confirmButtonText: 'Login / Daftar', cancelButtonText: 'Batal', confirmButtonColor: '#059669'
                }).then((result) => {
                    if (result.isConfirmed) { window.location.href = 'login.php'; }
                });
                return;
            }

            let judulProgram = sessionStorage.getItem("detail_judul"); 
            let nominal = document.getElementById('inputNominal').value;
            let doa = document.getElementById('inputDoa').value;

            sessionStorage.setItem("checkout_program", judulProgram);
            sessionStorage.setItem("checkout_nominal", nominal);
            sessionStorage.setItem("checkout_doa", doa);

            window.location.href = "pembayaran.php";
        });
    </script>
</body>
</html>