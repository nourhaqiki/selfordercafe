# Audit 001 Follow-up

## Temuan #1: Selesai

Nomor meja pada halaman pelanggan sekarang hanya ditampilkan, tidak lagi memiliki kontrol untuk diubah. QR/link meja dibuat sebagai tautan bertanda tangan; perubahan nomor meja pada URL ditolak. Checkout menggunakan nomor meja yang tersimpan dari tautan tersebut dan mengabaikan nilai `table_number` kiriman pelanggan.

## Verifikasi

- **PASS:** 4 feature test terkait pelanggan dan QR lulus, 35 assertion.
- **PASS:** Nomor meja pada tautan bertanda tangan tampil sebagai `Meja 05`; mengubah nomor pada tautan menghasilkan HTTP 403.
- **PASS:** Mengirim `table_number` berbeda saat checkout tidak mengubah meja pada pesanan.
- **PASS:** Filter kategori tetap menampilkan meja `05`.
- **PASS:** Tautan QR pada halaman cetak memuat signature; halaman menu merespons HTTP 200.
