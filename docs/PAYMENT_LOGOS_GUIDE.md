# Panduan Logo Metode Pembayaran

## 🎯 Cara Kerja Logo

Logo metode pembayaran dapat ditambahkan dengan 2 cara:

### 1. **Menggunakan URL CDN (Rekomendasi untuk memulai)**
```json
{
    "id": "m6a1d81c3ae491",
    "type": "bank",
    "name": "BNI",
    "account": "12345678",
    "owner": "Donasiku",
    "image": "https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg",
    "active": true
}
```

### 2. **Menggunakan File Lokal (Lebih stabil & cepat)**
```json
{
    "id": "m6a1d81c3ae491",
    "type": "bank",
    "name": "BNI",
    "account": "12345678",
    "owner": "Donasiku",
    "image": "payments/bni-logo.png",
    "active": true
}
```

---

## 📱 Logo Bank & E-Wallet Indonesia

### Bank Logo CDN URLs

| Bank | CDN URL |
|------|---------|
| **BNI** | `https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg` |
| **BCA** | `https://upload.wikimedia.org/wikipedia/id/2/2e/BCA_logo.svg` |
| **Mandiri** | `https://upload.wikimedia.org/wikipedia/id/3/3a/Bank_Mandiri_logo.svg` |
| **BRI** | `https://upload.wikimedia.org/wikipedia/id/7/73/BRI_logo.svg` |

### E-Wallet Logo CDN URLs

| E-Wallet | CDN URL |
|----------|---------|
| **Gopay** | `https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Gopay_logo.svg/320px-Gopay_logo.svg.png` |
| **OVO** | `https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/OVO_Logo.svg/250px-OVO_Logo.svg.png` |
| **Dana** | `https://upload.wikimedia.org/wikipedia/commons/e/e7/Dana_ID_logo.png` |

---

## 🔧 Cara Menambah Metode Pembayaran Baru

1. Buka file `data/payment-methods.json`
2. Tambahkan entry baru:

```json
{
    "id": "m6a1d81d800157",
    "type": "ewallet",
    "name": "OVO",
    "account": "0812345678",
    "owner": "Yayasan Donasiku",
    "image": "https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/OVO_Logo.svg/250px-OVO_Logo.svg.png",
    "active": true
}
```

3. Simpan file dan refresh halaman pembayaran.

---

## 📥 Menggunakan Logo Lokal (Untuk Performa Lebih Baik)

Jika ingin menggunakan file lokal daripada CDN:

1. **Download logo** dari sumber resmi atau Wikimedia Commons
2. **Simpan ke folder** `payments/` dengan nama yang deskriptif
   - Contoh: `bni-logo.png`, `gopay-logo.png`, dll
3. **Update `payment-methods.json`**:
   ```json
   "image": "payments/bni-logo.png"
   ```

### Upload Melalui Admin Dashboard

Admin juga bisa upload logo langsung melalui halaman manajemen metode pembayaran dengan fitur upload gambar di form.

---

## 🎨 Rekomendasi Logo

- **Format**: PNG, SVG, atau JPG
- **Ukuran Rekomendasi**: 200x100px atau 300x150px
- **Ukuran File**: Maksimal 5MB (sudah dikonfigurasi di API)
- **Transparency**: Direkomendasikan untuk logo agar terlihat bagus di berbagai background

---

## ✅ Status Saat Ini

Metode pembayaran yang sudah dikonfigurasi:

| Metode | Type | Logo | Status |
|--------|------|------|--------|
| QRIS SEMUA PEMBAYARAN | QRIS | `qris/QRIS.jpg` | ✅ Aktif |
| BNI | Bank | CDN (Wikimedia) | ✅ Aktif |
| Gopay | E-Wallet | CDN (Wikimedia) | ✅ Aktif |

---

## 🆘 Troubleshooting

### Logo tidak muncul?
- Periksa URL CDN apakah masih valid
- Pastikan path file lokal benar relative dari root folder
- Cek di browser DevTools → Network tab untuk melihat error loading image

### Logo terlihat buram/kecil?
- Increase `height` atau `width` di CSS
- Gunakan SVG untuk hasil yang lebih sharp
- Edit di file `pembayaran.php` bagian `style="height: 45px;"`

### Ingin mengubah logo lebih besar?
Edit di `pembayaran.php`:
```javascript
logoHtml = `<img src="${m.image}" alt="${m.name}" style="height: 60px; object-fit: contain; border-radius: 6px;">`;
```

