# Project Requirement Manual (PRM)

**Nama Proyek:** Cafe Self-Order Application  
**Tipe Aplikasi:** Web App (Mobile-First untuk Pelanggan) & Dashboard (Web untuk Kasir/Manager)  
**Referensi Utama:** Alur pemesanan Mie Gacoan (Cepat, Massal, Sistematis)  
**Dokumen Versi:** 1.3 (Penambahan Aturan Zero Garbage Code)  

## 1. Ringkasan Eksekutif

Aplikasi ini adalah sistem *Self-Ordering* berbasis QR Code untuk memfasilitasi pemesanan dan pembayaran secara mandiri oleh pelanggan dari meja. Sistem ini dirancang untuk volume transaksi tinggi dengan 3 aktor utama: Pelanggan, Kasir, dan Manager (Superadmin). Aturan bisnis utamanya adalah **Validasi Pembayaran Ketat**: Dapur/Sistem tidak akan memproses pesanan apapun sampai status pembayaran dikonfirmasi.

## 2. Filosofi Pengembangan (Core Development Philosophy)

Untuk menjaga performa aplikasi dan kecepatan pengembangan, proyek ini menganut prinsip **ZERO GARBAGE CODE & ANTISLOP**:
*   **Tanpa Kode Sampah:** Dilarang keras memasukkan *boilerplate* yang berlebihan, kode logika yang tidak terpakai (dead code), atau *styling* CSS yang tumpang tindih.
*   **To The Point:** Tulis kode hanya untuk fungsi yang diminta. Tidak perlu komentar penjelasan yang bertele-tele di dalam kode dasar jika fungsionalitasnya sudah jelas.
*   **Efisiensi File:** Hindari pembuatan banyak file terpisah jika fungsionalitas bisa disatukan dalam komponen yang bersih dan terstruktur rapi.

## 3. Aktor & Alur Pengguna (User Flow)

### 3.1. User 1: Pelanggan (Customer)

*   **Fokus UI/UX:** Sangat responsif di *smartphone*, *frictionless* (tanpa hambatan), visual menu terstruktur rapi. Tanpa perlu *login/register* (Guest Checkout).
*   **Alur Tugas:**
    1.  **Scan Barcode:** Memindai QR Code di meja (Sistem membaca nomor meja otomatis).
    2.  **Masuk ke Menu:** Melihat katalog menu.
    3.  **Memesan Makanan:** Memilih item, varian, dan melakukan *Checkout*.
    4.  **Bayar:** Memilih metode pembayaran (Cash/QRIS/TF/Debit/Credit).
    5.  **Menunggu Notifikasi:** Masuk ke *Live Order Tracking*. Status: **"Menunggu Pembayaran"**.
    6.  **Menerima Makanan:** Status berubah menjadi **"Sedang Dimasak"**, lalu **"Selesai"**.

### 3.2. User 2: Kasir (Cashier / Operator)

*   **Fokus UI/UX:** Efisiensi operasional, warna status yang jelas, rapi.
*   **Alur Tugas:**
    1.  **Mendapat Notifikasi:** Indikator visual saat pesanan baru masuk.
    2.  **Mengecek Pembayaran:** Melihat daftar pesanan *pending* berserta nomor mejanya.
    3.  **Approve Pembayaran:** Memvalidasi pembayaran fisik/digital -> Klik **"Approve"**.
    4.  **Mengecek Uang Masuk:** Memantau ringkasan pendapatan shift.
    5.  **Mengelola Pemesanan:** Memantau status dapur.
    6.  **Memberikan Makanan:** Menyelesaikan orderan di sistem saat makanan diserahkan.

### 3.3. User 3: Manager (Superadmin)

*   **Fokus UI/UX:** Analitik, kontrol penuh, manajemen master data.
*   **Alur Tugas:**
    1.  **Mengelola Toko:** Manajemen menu, harga, stok.
    2.  **Mendapat Notifikasi:** Memantau operasional *real-time*.
    3.  **Mengelola Traffic:** Mengatur status toko/meja (misal: *Pause order* jika dapur penuh).
    4.  **Mengelola Pendapatan:** Laporan keuangan.
    5.  **Manajemen QR Code Meja:**
        *   **Generate QR Code:** Membuat QR massal atau satuan yang sudah mengikat (*binding*) pada nomor meja.
        *   **Cetak/Export:** Menyimpan QR Code dalam bentuk PDF siap cetak.

## 4. Aturan Bisnis & Logika Sistem

### 4.1. Golden Rule: Payment First Validation

*   `DRAFT` -> `PENDING_PAYMENT` (Menunggu bayar) -> `PAID` (Validasi berhasil, dapur mulai bekerja) -> `PREPARING` -> `COMPLETED`.
*   Pesanan tidak akan pernah diteruskan ke dapur/display dapur jika status belum `PAID`.

### 4.2. Logika QR Code

*   Payload QR: tautan bertanda tangan yang mengikat nomor meja; perubahan parameter meja ditolak.
*   *State* nomor meja dikunci dalam sesi agar pelanggan tidak salah kirim pesanan.

## 5. Panduan UI/UX (UI/UX Guidelines)

*   **Pendekatan Desain:** Sederhana, Rapi, Terstruktur, dan *Hyper-Responsive* (Mobile-First Optimization).
*   **Palet Warna:**
    *   **Warna Dasar:** Putih atau *Off-white* untuk layout yang bersih.
    *   **Hijau (Primary/Success):** Tombol aksi utama (Checkout, Approve). Kesan segar dan valid.
    *   **Biru (Secondary/Informative):** Aksen, border, header (Info Meja, Dashboard). Kesan profesional.
    *   **Kuning (Accent/Warning):** Status "Menunggu Pembayaran" atau notifikasi waspada.
*   **Pewarnaan Gradasi:**
    *   Gunakan *subtle gradients* menggunakan Tailwind. Contoh: `bg-gradient-to-r from-green-400 to-green-600` untuk tombol aksi, atau perpaduan Biru ke Hijau untuk *Header* pelanggan.
*   **Tipografi:** Font *sans-serif* (Inter/Poppins), manfaatkan jarak (padding/margin) yang luas agar tidak sesak di layar HP.

## 6. Tech Stack & Environment (Development)

*   **Framework:** Laravel (PHP) untuk backend MVC yang tangguh.
*   **Local Server:** Laragon (Cepat, terstruktur, auto-virtual host).
*   **Frontend & Bundling:** Vite + Tailwind CSS (Untuk *styling* gradasi, responsivitas, dan kompilasi super cepat).
*   **Database:** MySQL/MariaDB.
*   **AI Integrations:** Penggunaan sistem **.agents** dengan protokol **antislop** yang ketat. 

## 7. Template Prompt (Anti-Slop Terintegrasi)

Gunakan format prompt berikut saat memerintahkan AI (.agents) untuk membuat kode berdasarkan PRM ini:

*   **Prompt Komponen UI (Tailwind):**
    *"Buatkan komponen Blade Laravel untuk kartu menu. Gunakan Tailwind. Desain rapi, mobile-first, palet putih dengan tombol gradasi (from-green-400 to-green-500). Patuhi aturan Antislop: Dilarang memasukkan kode boilerplate, inline CSS, atau komentar tidak penting. Berikan kode HTML murni langsung."*

*   **Prompt Backend (Laravel Controller):**
    *"Buatkan method store di OrderController. Tangkap ID meja, set status PENDING_PAYMENT, simpan ke database. Patuhi aturan Antislop: Jangan sertakan komentar penjelasan panjang, use statements yang tidak perlu, atau kode error handling dasar jika tidak diminta. Kode murni PHP."*