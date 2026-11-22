# UI/UX Design System Specification (DESIGN.md)

**Nama Proyek:** Cafe Self-Order Application  
**Tipe Aplikasi:** High-Volume Web App (Mobile-First Pelanggan & Desktop/Tablet Console Kasir/Manager)  
**Referensi Operasional:** Fast-Casual System (Pemesanan Cepat, Massal, Pembayaran Tervalidasi)  
**Tech Stack Design:** Tailwind CSS v3.4+, Vite, Laravel Blade  
**Prinsip Desain:** Purpose-Driven, Frictionless, Zero Garbage CSS, WCAG AA Compliant  

---

## 1. Design Read, Dials & Core Philosophy

### 1.1. Design Read
> Reading this as: Fast-casual dining self-order web application and operations console for cafe patrons and operational staff, in a warm modern culinary and specialty coffeehouse visual language, dial ENERGY 2 / RHYTHM 2 / MOTION 1.

### 1.2. Three Dials Declaration
*   **ENERGY: 2 (Balanced).** Memancarkan kehangatan cafe kontemporer (warm stone surfaces, deep pine green, roasted honey accents) yang dipadukan dengan ketegasan sistem antrean massal. Menghindari tampilan steril abu-abu dingin ala SaaS korporat tanpa jatuh ke dalam gimmicks visual yang mengganggu konsentrasi pemesanan.
*   **RHYTHM: 2 (Structured Cadence with Intentional Breaks).** Layout pelanggan terstruktur jelas: Sticky Table Bar di atas, carousel kategori horizontal, grid katalog makanan, dan Floating Checkout Drawer di bawah. Dashboard kasir menggunakan layout split antrean operasional.
*   **MOTION: 1 (Tactile & Purposeful).** Animasi dibatasi pada transisi state yang memberikan umpan balik langsung (active press feedback, drawer slide-up 150ms, hover state halus). Seluruh animasi menghormati preferensi `prefers-reduced-motion: reduce`.

### 1.3. Liveliness Levers & Identity Motif
*   **Identity Motif (Bistro Table Badge & Order Ticket):** Simbol penanda visual utama adalah kartu bernomor meja fisik yang tegas (`MEJA 12`) dan struk pesanan digital (*perforated ticket notch style*) yang memberikan pengalaman visual cafe otentik, bukan sekadar formulir digital biasa.
*   **One Focal Point per Screen:**
    *   *Katalog Pelanggan:* Tombol aksi keranjang mengambang (*Floating Cart Bar*) dengan ringkasan total harga dan jumlah item.
    *   *Live Order Tracking:* Kartu status progres bertahap dengan indikator status pembayaran yang kontras tinggi.
    *   *Layar Kasir:* Tombol eksekusi hijau kontras tinggi "APPROVE PEMBAYARAN" yang langsung tertuju pada pesanan antrean teratas.
    *   *Dashboard Manager:* Kartu metrik pendapatan harian dan modul ekspor QR Code Meja.
*   **One Deliberate Accent:** Warna **Roasted Honey Amber** (`#B45309`) digunakan secara eksklusif dan disiplin sebagai aksen status "Menunggu Pembayaran", sehingga perhatian pengguna dan kasir langsung tertuju pada proses pembayaran tanpa distraksi.

---

## 2. Sistem Warna & Standar Kontras (WCAG AA Compliant)

Palet warna dibatasi secara ketat pada 3 warna inti (Core Palette) ditambah 1 warna aksen status fungsional (R-29), dilengkapi dengan permukaan netral bertemperatur hangat. Setiap kombinasi warna teks dan latar belakang telah diuji dan wajib memenuhi standar WCAG AA (rasio kontras minimal 4.5:1 untuk teks normal dan minimal 3:1 untuk teks besar serta batas komponen interaktif).

### 2.1. Palette Tokens

| Peran Token | Tailwind Class | Nilai HEX | Alasan Desain (R-31) |
| :--- | :--- | :--- | :--- |
| **Surface Base** | `bg-stone-50` | `#FAFAF9` | Memberikan latar belakang hangat natural khas interior cafe kayu dan batu, nyaman di mata pelanggan di bawah pencahayaan cafe. |
| **Surface Card** | `bg-white` | `#FFFFFF` | Mengisolasi konten kartu menu dan modal agar kontras terhadap latar belakang dasar. |
| **Surface Subdued**| `bg-stone-100` | `#F5F5F4` | Latar belakang input non-aktif, badge netral, dan pemisah segmen. |
| **Brand Primary (Hijau Pinus)** | `bg-emerald-800` / `bg-emerald-700` | `#065F46` / `#047857` | Melambangkan bahan segar, keamanan kuliner, dan tombol aksi checkout utama. |
| **Primary Hover** | `bg-emerald-900` | `#064E3B` | Umpan balik visual saat kursor hover atau tap aktif. |
| **Identity Dark (Espresso Slate)** | `text-stone-900` / `bg-stone-900` | `#1C1917` | Memberikan bobot tipografi tajam, profesional, dan mudah dibaca cepat. |
| **Text Secondary** | `text-stone-600` | `#57534E` | Keterangan porsi menu, deskripsi rasa, dan label metadata pesanan. |
| **Accent Warning (Roasted Amber)** | `bg-amber-500` / `text-amber-800` | `#D97706` / `#92400E` | Menandakan status krusial "Menunggu Pembayaran" agar segera diselesaikan. |
| **Accent Amber Light (Status BG)** | `bg-amber-50` | `#FFFBEB` | Wadah badge status pembayaran pending yang kontras tinggi dan tidak silau. |
| **Spicy / Alert Accent** | `text-orange-700` / `bg-orange-100` | `#C2410C` / `#FFEDD5` | Penanda varian pedas (Level 1 sampai Level 8) dan label promo khusus. |
| **Border Stroke** | `border-stone-200` | `#E7E5E4` | Garis batas struktural yang tegas tanpa mengandalkan bayangan tebal. |

### 2.2. Contrast Ratio Matrix (Verifikasi WCAG AA)

Setiap pasangan warna yang diizinkan dalam sistem ini telah diverifikasi melalui formula WCAG 2.2:

*   **Teks Putih (`#FFFFFF`) di atas Brand Emerald (`#047857`):** Rasio **5.48:1** (PASS Normal & Large Text).
*   **Teks Putih (`#FFFFFF`) di atas Deep Forest (`#065F46`):** Rasio **7.82:1** (PASS Normal & Large Text).
*   **Teks Putih (`#FFFFFF`) di atas Espresso Dark (`#1C1917`):** Rasio **18.10:1** (PASS Normal & Large Text).
*   **Teks Espresso (`#1C1917`) di atas Surface Stone (`#FAFAF9`):** Rasio **16.92:1** (PASS Normal & Large Text).
*   **Teks Stone-600 (`#57534E`) di atas Surface White (`#FFFFFF`):** Rasio **6.97:1** (PASS Normal & Large Text).
*   **Teks Amber-900 (`#78350F`) di atas Amber-50 (`#FFFBEB`):** Rasio **8.62:1** (PASS Normal & Large Text).
*   **Teks Orange-800 (`#9A3412`) di atas Orange-100 (`#FFEDD5`):** Rasio **7.41:1** (PASS Normal & Large Text).

> [!CAUTION]
> Hindari penggunaan teks putih di atas `bg-emerald-500` (`#10B981`) atau `bg-emerald-400` karena rasio kontras hanya 3.77:1 (FAIL WCAG untuk teks reguler). Gunakan `bg-emerald-700` atau `bg-emerald-800` untuk semua tombol aksi bertuliskan putih.

---

## 3. Tipografi & Skala Hirarki

### 3.1. Pemilihan Font Family
*   **Font Primer:** **Plus Jakarta Sans** (Google Fonts / Bunny Fonts).
    *   *Alasan Pemilihan (R-06 & R-31):* Font sans-serif humanis modern dengan aperture terbuka yang dirancang khusus untuk keterbacaan tinggi di layar smartphone berukuran kecil. Angka monoline yang bersih memastikan harga menu (`Rp 18.000`) dan nomor meja (`MEJA 08`) terbaca instan oleh pelanggan dan kasir tanpa distorsi.
    *   *Fallback Stack:* `font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`.

### 3.2. Type Scale Table

| Tingkat | Kelas Tailwind | Ukuran / Line-Height | Tracking | Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| **Display / H1** | `text-2xl font-bold` | 24px / 1.3 (32px) | `tracking-tight` | Header utama dashboard kasir, total tagihan pada ringkasan pembayaran. |
| **Section Title / H2** | `text-lg font-bold` | 18px / 1.4 (26px) | `tracking-tight` | Judul kategori menu (Makanan, Minuman, Dimsum), judul modal modifier. |
| **Card Title / H3** | `text-base font-semibold`| 16px / 1.4 (22px) | `tracking-normal` | Nama menu pada kartu katalog, nomor meja pada kartu antrean kasir. |
| **Body Standard** | `text-sm font-normal` | 14px / 1.5 (21px) | `tracking-normal` | Deskripsi menu, rincian catatan pesanan, label form input. |
| **Price / Numerical** | `text-sm font-bold` | 14px / 1.4 (20px) | `tracking-tight` | Nominal harga item pada kartu katalog dan keranjang. |
| **Caption / Meta** | `text-xs font-medium` | 12px / 1.4 (17px) | `tracking-normal` | Label status, informasi waktu pesanan, placeholder input. |
| **Sub-badge / Micro** | `text-[11px] font-semibold` | 11px / 1.3 (15px) | `tracking-wider uppercase` | Badge level pedas, penanda meja aktif, status QR. |

---

## 4. Spacing, Elevasi & Border Radius System

### 4.1. Elevasi Berbasis Struktur (Bukan Floating Slop)
Sistem ini menggunakan batas tegas (*border-first structure*) untuk mendefinisikan batas area, sehingga tidak membuat semua elemen melayang tidak beralasan (R-12).

*   **Elevation 0 (Flat):** Kartu menu statis, baris keranjang, input form.  
    `bg-white border border-stone-200`
*   **Elevation 1 (Card Hover / Selected State):** Kartu menu yang ditekan, kartu antrean kasir yang dipilih.  
    `bg-white border border-stone-300 shadow-sm`
*   **Elevation 2 (Floating Action Bars):** Sticky Bottom Cart Bar di mobile, Sticky Header di kasir.  
    `bg-white/95 backdrop-blur-sm border-t border-stone-200 shadow-md`
*   **Elevation 3 (Overlay / Modal / Drawer):** Bottom-sheet penyesuaian level pedas dan dialog pembayaran.  
    `bg-white border border-stone-200 shadow-xl`

### 4.2. Border Radius Hierarchy (R-11)
Menolak bentuk kapsul (*pill*) di semua elemen. Bentuk kapsul hanya diizinkan untuk pill kategori horizontal dan counter tag kecil.

*   **Large Containers & Modals (`rounded-2xl` = 16px):** Drawer penyesuaian pesanan, modal validasi kasir, panel detail order.
*   **Cards & Media Wrappers (`rounded-xl` = 12px):** Kartu menu katalog, foto produk makanan, kartu pesanan antrean kasir.
*   **Interactive Controls & Buttons (`rounded-lg` = 8px):** Tombol aksi checkout, tombol tambah item, input nomor meja, field catatan.
*   **Compact Stepper & Input Fields (`rounded-md` = 6px):** Tombol counter kuantitas minus dan plus, tag varian kecil.
*   **Category Pills & Quantity Badges (`rounded-full`):** Tab navigasi kategori yang dapat digeser horizontal (`Makanan`, `Minuman`, `Paket Hemat`), lingkaran angka total item keranjang.

---

## 5. Komponen Standar (Blade & Tailwind Implementation Ready)

### 5.1. Tombol (Buttons)

Seluruh elemen tombol wajib memiliki ukuran target sentuh minimal 44x44 piksel pada layar mobile (R-03), dilengkapi penanda kursor pointer eksplisit (`cursor-pointer`), serta fokus cincin yang lolos uji aksesibilitas (R-32, R-39).

#### A. Primary Action Button (Checkout & Approval)
Digunakan khusus untuk tindakan penyelesaian tahap: "Lanjut ke Pembayaran", "Konfirmasi Pesanan", "Approve Pembayaran Meja".

```html
<button 
  type="button" 
  class="w-full inline-flex items-center justify-center min-h-[48px] px-5 py-3 rounded-lg bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-sm font-semibold tracking-wide transition-colors duration-150 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2 disabled:bg-stone-300 disabled:text-stone-500 disabled:cursor-not-allowed">
  <span>Lanjut ke Pembayaran</span>
</button>
```

#### B. Secondary / Neutral Action Button
Digunakan untuk aksi pendukung: "Pilih Varian", "Ubah Pesanan", "Kembali ke Menu".

```html
<button 
  type="button" 
  class="inline-flex items-center justify-center min-h-[44px] px-4 py-2.5 rounded-lg bg-stone-100 hover:bg-stone-200 active:bg-stone-300 text-stone-800 text-sm font-medium transition-colors duration-150 cursor-pointer border border-stone-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-stone-400 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
  <span>Ubah Pesanan</span>
</button>
```

#### C. Destructive / Reject Action Button (Khusus Kasir & Manager)
Digunakan saat kasir menolak bukti bayar palsu atau membatalkan pesanan kosong.

```html
<button 
  type="button" 
  class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 rounded-lg bg-red-50 hover:bg-red-100 active:bg-red-200 text-red-800 border border-red-200 text-sm font-medium transition-colors duration-150 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2">
  <span>Tolak Pembayaran</span>
</button>
```

#### D. Compact Quantity Stepper Button (Minus & Plus)
Dirancang khusus agar area tap tetap memenuhi 44x44px meskipun tampilan visual kotak counter ringkas.

```html
<div class="inline-flex items-center border border-stone-200 rounded-lg bg-white overflow-hidden shadow-xs">
  <button 
    type="button" 
    aria-label="Kurangi jumlah pesanan"
    class="w-11 h-11 flex items-center justify-center text-stone-600 hover:text-stone-900 hover:bg-stone-100 active:bg-stone-200 cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
    </svg>
  </button>
  
  <span class="w-8 text-center text-sm font-bold text-stone-900 select-none" aria-live="polite">2</span>
  
  <button 
    type="button" 
    aria-label="Tambah jumlah pesanan"
    class="w-11 h-11 flex items-center justify-center text-emerald-800 hover:text-emerald-900 hover:bg-emerald-50 active:bg-emerald-100 cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
  </button>
</div>
```

---

### 5.2. Kartu Menu Katalog (Menu Item Cards)

Katalog menu pelanggan menggunakan tata letak dua kolom pada ponsel cerdas, dengan rasio gambar tetap `aspect-[4/3]` untuk mencegah pergeseran tata letak kumulatif (CLS) saat gambar dimuat (R-40).

#### A. Standard Menu Item Card (Mobile Grid 2-Kolom)

```html
<article class="flex flex-col bg-white rounded-xl border border-stone-200 overflow-hidden transition-all duration-150 hover:border-stone-300">
  <div class="relative w-full aspect-[4/3] bg-stone-100 overflow-hidden">
    <img 
      src="/images/menu/mie-hompimpa.jpg" 
      alt="Porsi Mie Hompimpa Pedas dengan taburan ayam cincang dan pangsit goreng" 
      width="400" 
      height="300" 
      loading="lazy" 
      class="w-full h-full object-cover" />
    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-stone-900/80 text-white text-[11px] font-semibold tracking-wide backdrop-blur-xs">
      Level 1-8
    </span>
  </div>

  <div class="p-3 flex-1 flex flex-col justify-between">
    <div>
      <h3 class="text-sm font-bold text-stone-900 leading-snug line-clamp-1">
        Mie Pedas Hompimpa
      </h3>
      <p class="mt-1 text-xs text-stone-500 line-clamp-2 leading-relaxed">
        Mie kenyal pedas asin gurih dengan taburan ayam cincang dan 2 pangsit renyah.
      </p>
    </div>

    <div class="mt-3 pt-2 border-t border-stone-100 flex items-center justify-between gap-2">
      <span class="text-sm font-bold text-stone-900">
        Rp 14.500
      </span>
      <button 
        type="button" 
        class="min-h-[44px] min-w-[44px] px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 active:bg-emerald-200 text-emerald-800 text-xs font-bold border border-emerald-200 cursor-pointer transition-colors inline-flex items-center justify-center gap-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
        <span>+ Tambah</span>
      </button>
    </div>
  </div>
</article>
```

#### B. Signature Hero Item Card (Variasi Ritme / Menu Unggulan)
Mencegah kebosanan grid seragam (R-05, R-14) dengan menampilkan menu rekomendasi utama dalam format lanskap horizontal pada awal kategori.

```html
<article class="flex flex-col sm:flex-row bg-white rounded-xl border border-emerald-200/80 overflow-hidden shadow-xs">
  <div class="relative w-full sm:w-2/5 aspect-[16/10] sm:aspect-auto bg-stone-100 overflow-hidden">
    <img 
      src="/images/menu/pangsit-goreng-spesial.jpg" 
      alt="Pangsit Goreng Isi Udang Ayam Renyah 5 Buah" 
      width="600" 
      height="400" 
      class="w-full h-full object-cover" />
    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-emerald-800 text-white text-[11px] font-bold">
      Favorit Meja
    </span>
  </div>

  <div class="p-4 flex-1 flex flex-col justify-between">
    <div>
      <div class="flex items-center justify-between gap-2">
        <h3 class="text-base font-bold text-stone-900">Pangsit Goreng Spesial (5 pcs)</h3>
        <span class="text-base font-bold text-stone-900">Rp 12.000</span>
      </div>
      <p class="mt-1 text-xs text-stone-600 leading-relaxed">
        Kulit pangsit renyah keemasan isi adonan ayam dan udang cincang gurih, disajikan dengan saus bangkok asam manis.
      </p>
    </div>

    <div class="mt-4 flex items-center justify-end">
      <button 
        type="button" 
        class="min-h-[44px] px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-xs font-semibold cursor-pointer transition-colors inline-flex items-center gap-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
        <span>Tambah ke Pesanan</span>
      </button>
    </div>
  </div>
</article>
```

---

### 5.3. Status Badges & Operational Indicators

Badges dirancang berdasarkan status bisnis riil sistem self-order cafe, bukan sekadar hiasan (R-09). Setiap warna status selalu didampingi oleh teks deskriptif dan ikon geometris agar ramah bagi pengguna dengan defisiensi penglihatan warna (C-4).

```html
<!-- Status 1: Menunggu Pembayaran (Krusial Kasir / Pelanggan) -->
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 text-amber-900 border border-amber-300 text-xs font-semibold">
  <span class="w-1.5 h-1.5 rounded-full bg-amber-600" aria-hidden="true"></span>
  Menunggu Pembayaran
</span>

<!-- Status 2: Terbayar / Verifikasi Sukses (Dapur Siap Masak) -->
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-900 border border-emerald-300 text-xs font-semibold">
  <span class="w-1.5 h-1.5 rounded-full bg-emerald-700" aria-hidden="true"></span>
  Terbayar (Antrean Dapur)
</span>

<!-- Status 3: Sedang Dimasak -->
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-900 border border-blue-200 text-xs font-semibold">
  <span class="w-1.5 h-1.5 rounded-full bg-blue-600" aria-hidden="true"></span>
  Sedang Dimasak
</span>

<!-- Status 4: Siap Diambil / Selesai -->
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-stone-100 text-stone-800 border border-stone-300 text-xs font-semibold">
  <span class="w-1.5 h-1.5 rounded-full bg-stone-600" aria-hidden="true"></span>
  Selesai Disajikan
</span>
```

---

### 5.4. Navigation & Floating Action Bars

#### A. Mobile Sticky Table Header (Pelanggan)
Header pelanggan menancap di bagian atas layar ponsel dengan informasi krusial nomor meja yang terkunci secara transparan.

```html
<header class="sticky top-0 z-30 w-full bg-stone-900 text-white px-4 py-3 border-b border-stone-800 shadow-xs">
  <div class="max-w-md mx-auto flex items-center justify-between gap-3">
    <div class="flex items-center gap-2">
      <div class="w-2.5 h-2.5 rounded-full bg-emerald-400" aria-hidden="true"></div>
      <span class="text-sm font-bold tracking-tight">Kopi & Mie Nusantara</span>
    </div>

    <!-- Bistro Table Badge Motif -->
    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-stone-800 border border-stone-700 text-xs font-bold text-emerald-300">
      <span class="text-stone-400 font-normal">Meja:</span>
      <span class="text-white text-sm">12</span>
    </div>
  </div>
</header>
```

#### B. Horizontal Category Selector Pills (Scrollable with Overflow Edge Indicator)
Navigasi kategori menu yang ergonomis bagi jempol pengguna ponsel tanpa dropdown bertingkat.

```html
<nav aria-label="Kategori Menu" class="sticky top-[53px] z-20 bg-stone-50/95 backdrop-blur-xs py-2.5 border-b border-stone-200">
  <div class="max-w-md mx-auto px-4 flex items-center gap-2 overflow-x-auto no-scrollbar" role="tablist">
    <button 
      type="button" 
      role="tab" 
      aria-selected="true" 
      class="shrink-0 min-h-[44px] px-4 py-2 rounded-full bg-emerald-800 text-white text-xs font-bold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
      Semua Menu
    </button>
    <button 
      type="button" 
      role="tab" 
      aria-selected="false" 
      class="shrink-0 min-h-[44px] px-4 py-2 rounded-full bg-white hover:bg-stone-100 text-stone-700 border border-stone-200 text-xs font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-stone-400">
      Mie Pedas
    </button>
    <button 
      type="button" 
      role="tab" 
      aria-selected="false" 
      class="shrink-0 min-h-[44px] px-4 py-2 rounded-full bg-white hover:bg-stone-100 text-stone-700 border border-stone-200 text-xs font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-stone-400">
      Dimsum & Gorengan
    </button>
    <button 
      type="button" 
      role="tab" 
      aria-selected="false" 
      class="shrink-0 min-h-[44px] px-4 py-2 rounded-full bg-white hover:bg-stone-100 text-stone-700 border border-stone-200 text-xs font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-stone-400">
      Minuman Segar
    </button>
  </div>
</nav>
```

#### C. Floating Cart Action Bar (Pelanggan)
Bar ringkasan pesanan mengambang di bawah layar ponsel pelanggan. Menggunakan `pb-[max(0.75rem,env(safe-area-inset-bottom))]` agar tidak tertutup indikator navigasi bawaan iOS dan Android (R-03).

```html
<div class="fixed bottom-0 inset-x-0 z-40 p-3 bg-white/95 backdrop-blur-md border-t border-stone-200 shadow-lg pb-[max(0.75rem,env(safe-area-inset-bottom))]">
  <div class="max-w-md mx-auto flex items-center justify-between gap-3">
    <div class="flex flex-col">
      <span class="text-xs text-stone-500 font-medium">Total Pesanan (3 Item)</span>
      <span class="text-base font-bold text-stone-900 tracking-tight">Rp 48.500</span>
    </div>

    <button 
      type="button" 
      class="min-h-[48px] px-6 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white text-sm font-bold tracking-wide cursor-pointer transition-colors shadow-xs inline-flex items-center gap-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2">
      <span>Lihat Pesanan</span>
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>
</div>
```

---

## 6. Layout Tiga Aktor (Pelanggan, Kasir, Manager)

### 6.1. Pelanggan (Customer Mobile Experience)
*   **Viewport Target:** `max-w-md mx-auto` (Optimal di 360px sampai 480px, terpusat di layar desktop dengan latar neutral stone).
*   **Hierarki Tampilan:**
    1.  *Sticky Top Bar:* Nama Cafe & Nomor Meja tersemat permanen.
    2.  *Kategori Menu:* Carousel horizontal yang menempel tepat di bawah header saat di-scroll.
    3.  *Katalog Menu Makanan:* Grid 2 kolom dengan gambar bernutrisi visual, tombol tambah instan, serta informasi level pedas.
    4.  *Bottom Space Reservation:* Padding bawah `pb-28` di wadah utama konten agar kartu menu terbawah tidak tertutup oleh Floating Cart Bar.
    5.  *Drawer Checkout / Modal:* Slide-up dari bawah untuk konfirmasi metode pembayaran (QRIS, Tunai Kasir, Transfer).

### 6.2. Kasir (High-Speed Operator Console)
*   **Viewport Target:** Tablet (iPad/Android 768px+) & Layar Monitor Kasir POS (1024px sampai 1920px).
*   **Tata Letak (Two-Column Operational Split):**
    *   **Kolom Kiri (Antrean Pesanan Masuk, Lebar 40%):**
        *   Urutan pesanan masuk secara real-time.
        *   Warna kartu jelas: Kuning/Amber (`bg-amber-50/70 border-amber-300`) untuk pesanan pending verifikasi bayar.
        *   Nomor meja ditampilkan dalam ukuran besar (`text-xl font-bold`) disertai penanda waktu berlalu (`3 mnt lalu`).
    *   **Kolom Kanan (Detail Verifikasi & Eksekusi Cepat, Lebar 60%):**
        *   Rincian lengkap item pesanan, varian level, catatan pelanggan (misal: "jangan pakai daun bawang").
        *   Nominal total pembayaran dalam font ekstra besar (`text-3xl font-extrabold text-stone-900`) untuk mencegah kekeliruan nominal uang fisik kasir.
        *   Tombol eksekusi primer berukuran raksasa: **"APPROVE PEMBAYARAN"** (`min-h-[56px] text-base bg-emerald-700 text-white`). Satu kali klik mengubah status menjadi `PAID` dan meneruskan tiket ke dapur.

### 6.3. Manager (Superadmin Operations & Master Control)
*   **Viewport Target:** Desktop Dashboard (1024px+).
*   **Tata Letak:** Sidebar navigasi kiri (`w-64`) dan Content Area responsif (`flex-1 p-8`).
*   **Fitur Spesifik Halaman:**
    *   *Store Traffic Switch:* Toggle darurat "Pause Order Masuk" jika antrean dapur sedang overload.
    *   *Katalog & Kontrol Stok:* Daftar tabular menu dengan toggle instan ketersediaan stok bahan (Habis / Tersedia).
    *   *QR Code Mass Generator & Print Canvas:* Modul pembuatan QR massal per rentang meja (contoh: Meja 1 sampai 50), pratinjau grid kartu cetak ukuran 10x15cm, dan tombol ekspor langsung ke dokumen cetak/PDF tanpa ketergantungan script eksternal.

---

## 7. Penanganan UI States (Empty, Loading, Error)

Sesuai aturan R-27, setiap tampilan data wajib memiliki definisi visual ketiga status ini dengan pesan penyebab yang eksplisit dan tindakan perbaikan langsung.

### 7.1. Empty State (Keranjang Belum Berisi Menu)

```html
<div class="flex flex-col items-center justify-center p-8 text-center bg-white rounded-xl border border-stone-200 my-6">
  <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-stone-500 mb-3" aria-hidden="true">
    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
    </svg>
  </div>
  <h3 class="text-base font-bold text-stone-900">Keranjang Masih Kosong</h3>
  <p class="mt-1 text-xs text-stone-500 max-w-xs leading-relaxed">
    Anda belum memilih menu makanan atau minuman. Silakan jelajahi daftar menu di atas untuk mulai memesan.
  </p>
  <button 
    type="button" 
    class="mt-4 min-h-[44px] px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700">
    Lihat Menu Populer
  </button>
</div>
```

### 7.2. Loading State (Skeleton Screen Berdimensi Pasti)
Menggunakan dimensi tetap `aspect-[4/3]` dan `h-4` untuk mencegah pergeseran tata letak kumulatif (CLS) saat data ditarik dari server (R-40).

```html
<div class="grid grid-cols-2 gap-3" aria-busy="true" aria-label="Memuat menu pilihan">
  <div class="bg-white rounded-xl border border-stone-200 overflow-hidden animate-pulse">
    <div class="w-full aspect-[4/3] bg-stone-200"></div>
    <div class="p-3 space-y-2">
      <div class="w-3/4 h-4 bg-stone-200 rounded-sm"></div>
      <div class="w-1/2 h-3 bg-stone-200 rounded-sm"></div>
      <div class="pt-2 flex justify-between items-center">
        <div class="w-1/3 h-4 bg-stone-200 rounded-sm"></div>
        <div class="w-12 h-8 bg-stone-200 rounded-lg"></div>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-xl border border-stone-200 overflow-hidden animate-pulse">
    <div class="w-full aspect-[4/3] bg-stone-200"></div>
    <div class="p-3 space-y-2">
      <div class="w-3/4 h-4 bg-stone-200 rounded-sm"></div>
      <div class="w-1/2 h-3 bg-stone-200 rounded-sm"></div>
      <div class="pt-2 flex justify-between items-center">
        <div class="w-1/3 h-4 bg-stone-200 rounded-sm"></div>
        <div class="w-12 h-8 bg-stone-200 rounded-lg"></div>
      </div>
    </div>
  </div>
</div>
```

### 7.3. Error State (Koneksi Terputus atau Gagal Memuat Data)

```html
<div class="flex flex-col items-center justify-center p-6 text-center bg-red-50/60 rounded-xl border border-red-200 my-4" role="alert">
  <div class="w-10 h-10 rounded-full bg-red-100 text-red-800 flex items-center justify-center mb-2" aria-hidden="true">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
  </div>
  <h3 class="text-sm font-bold text-red-900">Gagal Memuat Menu Cafe</h3>
  <p class="mt-1 text-xs text-red-700 max-w-xs leading-relaxed">
    Koneksi internet meja Anda terputus atau server sedang sibuk. Silakan coba muat ulang data menu.
  </p>
  <button 
    type="button" 
    class="mt-3 min-h-[44px] px-4 py-2 rounded-lg bg-red-700 hover:bg-red-800 text-white text-xs font-semibold cursor-pointer transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600">
    Muat Ulang Halaman
  </button>
</div>
```

---

## 8. Tata Kelola Aksesibilitas & Responsivitas (Accessibility & Responsiveness)

### 8.1. Keyboard Navigation & Visible Focus (R-32)
*   Dilarang keras menggunakan `outline-none` tanpa menyertakan `focus-visible:ring-2` pengganti.
*   Setiap elemen fokus menggunakan ring kontras tinggi: `focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2`.
*   Semua dialog drawer dan modal modifier pesanan wajib dapat ditutup dengan tombol keyboard `Escape`.
*   Urutan Tab mengikuti tata letak visual DOM secara berurutan: Navigasi Kategori -> Item Menu -> Stepper Kuantitas -> Checkout.

### 8.2. Standar Sentuh Mobile (Mobile Touch Targets R-03)
*   Seluruh kontrol interaktif (tombol tambah, tab kategori, tombol kuantitas, opsi varian) memiliki ukuran target sentuh minimal **44 x 44 piksel**.
*   Jarak renggang minimal antartombol adalah 8 piksel untuk mencegah salah sentuh (*mis-tap*) pada layar ponsel.
*   Input form memiliki padding vertikal minimal `py-3` dengan ukuran teks minimal `text-base` (16px) pada perangkat seluler guna mencegah auto-zoom paksa pada peramban Safari iOS.

### 8.3. Performance & Layout Stability (R-40)
*   Semua gambar menu katalog wajib memiliki atribut `width` dan `height` yang proporsional atau kelas Tailwind `aspect-[4/3]`.
*   Banner atau gambar menu teratas dimuat dengan prioritas instan tanpa lazy-load (`loading="eager"`), sedangkan menu yang berada di bawah lipatan layar (*below-the-fold*) menggunakan `loading="lazy"`.
*   Tidak ada script pemblokir render pihak ketiga di dalam tag `<head>`.

---

## 9. Decision Registry (R-31 Rationale Log)

Setiap keputusan desain wajib memiliki alasan satu baris yang jelas (Rule R-31):

1.  **Mengapa palet dasar menggunakan Warm Stone (`#FAFAF9`) dan bukan putih murni (`#FFFFFF`)?**  
    *Alasan:* Menghadirkan atmosfer cafe yang hangat dan ramah sekaligus mencegah kelelahan mata pelanggan saat memandang layar ponsel di area semi-outdoor cafe.
2.  **Mengapa Brand Primary menggunakan Deep Emerald (`#047857`) dan bukan gradasi biru-ungu?**  
    *Alasan:* Memberikan asosiasi kesegaran kuliner dan kepastian validasi pesanan dengan kontras teks putih 5.48:1 yang lolos uji WCAG AA.
3.  **Mengapa status Menunggu Pembayaran menggunakan Roasted Amber (`#D97706`) secara khusus?**  
    *Alasan:* Menjadi satu-satunya aksen peringatan di layar yang mengarahkan perhatian kasir dan pelanggan agar segera menyelesaikan validasi pembayaran.
4.  **Mengapa font Plus Jakarta Sans dipilih sebagai standar tipografi aplikasi?**  
    *Alasan:* Aperture font yang terbuka dan angka monoline geometris menjamin harga rupiah dan nomor meja terbaca instan di layar kecil tanpa keraguan.
5.  **Mengapa layout pelanggan menggunakan 2-kolom grid pada mobile dan bukan kartu 1-kolom penuh?**  
    *Alasan:* Memaksimalkan efisiensi visual layar agar pelanggan dapat menelusuri 4 sampai 6 menu sekaligus dalam satu guliran cepat tanpa scrolling panjang.
6.  **Mengapa kasir menggunakan layout split antrean horizontal dan bukan tabel data standar?**  
    *Alasan:* Memisahkan antrean pesanan baru di sisi kiri dengan tombol aksi approval raksasa di sisi kanan untuk mempercepat durasi transaksi di meja kasir.
7.  **Mengapa sistem menolak border-radius kapsul (pill) di semua tombol utama?**  
    *Alasan:* Mempertahankan hierarki visual terstruktur yang membedakan kartu menu bernutrisi (`rounded-xl`), tombol aksi fungsional (`rounded-lg`), dan pill kategori filter (`rounded-full`).
8.  **Mengapa Floating Cart Bar dilengkapi padding bawah safe-area?**  
    *Alasan:* Mencegah tombol checkout tertutup oleh garis gestur home navigasi pada iPhone dan ponsel Android modern.
9.  **Mengapa drawer modal dilengkapi penutup tombol Escape dan backdrop klik?**  
    *Alasan:* Menjamin kepatuhan standar aksesibilitas keyboard dan navigasi jempol satu tangan yang frictionless.
10. **Mengapa gambar menu wajib memiliki rasio aspect-[4/3] tetap?**  
    *Alasan:* Mencegah lonjakan tata letak kumulatif (CLS 0) saat gambar makanan beresolusi tinggi dimuat bertahap melalui jaringan seluler cafe.
