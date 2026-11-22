<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak QR Code Meja - Kopi & Mie Nusantara</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .page-break {
                page-break-after: always;
            }
            .qr-card {
                break-inside: avoid;
                page-break-inside: avoid;
                border: 2px solid #1C1917 !important;
            }
        }
    </style>
</head>
<body class="bg-stone-100 font-sans text-stone-900 p-6 min-h-screen">

    <!-- Top Action Toolbar (Hidden in Print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 p-4 bg-white rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-base font-bold text-stone-900">Kanvas Siap Cetak Plakat Meja QR Code</h1>
            <p class="text-xs text-stone-500">Gunakan browser Print (Ctrl + P) untuk menyimpan sebagai PDF atau langsung mencetak ke kertas tebal/stiker.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('manager.tables.index') }}" class="min-h-[44px] px-4 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold inline-flex items-center gap-1">
                ← Kembali ke Manajemen Meja
            </a>
            <button 
                type="button" 
                onclick="window.print()" 
                class="min-h-[44px] px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold inline-flex items-center gap-2 shadow-xs cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.056-.367-2.155-.367-3.28 0-4.004 2.774-7.36 6.547-8.298M16.5 13.829c.24-1.056.367-2.155.367-3.28 0-4.004-2.774-7.36-6.547-8.298m0 0a9.02 9.02 0 010 16.596M12 21a9 9 0 01-9-9c0-1.874.57-3.616 1.547-5.06"/></svg>
                <span>Cetak Semua QR Sekarang (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Printable Grid of Acrylic Standee Tent Cards -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($tables as $tbl)
            @php
                $orderUrl = \App\Models\CafeTable::signedOrderUrl($tbl->table_number);
                $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($orderUrl);
            @endphp
            <div class="qr-card bg-white rounded-2xl border-2 border-stone-900 p-6 flex flex-col items-center text-center justify-between shadow-xs relative overflow-hidden">
                
                <!-- Brand Header -->
                <div class="w-full pb-3 border-b border-stone-200">
                    <span class="text-[10px] text-stone-400 font-bold tracking-widest uppercase block">SELF-ORDER APPLICATION</span>
                    <h2 class="text-sm font-extrabold tracking-tight text-stone-900">KOPI & MIE NUSANTARA</h2>
                </div>

                <!-- Big Table Badge -->
                <div class="my-4 p-3 bg-stone-900 text-white rounded-xl w-full">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest block">NOMOR MEJA</span>
                    <span class="text-3xl font-black text-emerald-400 tracking-tight leading-none block mt-1">
                        MEJA {{ $tbl->table_number }}
                    </span>
                </div>

                <!-- QR Code Box -->
                <div class="p-3 bg-stone-50 border border-stone-200 rounded-xl my-2">
                    <img 
                        src="{{ $qrApiUrl }}" 
                        alt="QR Code Meja {{ $tbl->table_number }}" 
                        width="180" 
                        height="180" 
                        class="w-44 h-44 object-contain mx-auto" />
                </div>

                <!-- Instructions -->
                <div class="w-full mt-3 pt-3 border-t border-stone-200 space-y-1 text-left">
                    <div class="flex items-center gap-2 text-[11px] text-stone-700">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-bold flex items-center justify-center shrink-0">1</span>
                        <span>Pindai QR ini dengan kamera HP</span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-stone-700">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-bold flex items-center justify-center shrink-0">2</span>
                        <span>Pilih varian pedas & kirim pesanan</span>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-stone-700">
                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-bold flex items-center justify-center shrink-0">3</span>
                        <span>Bayar via QRIS / Tunai di Kasir</span>
                    </div>
                </div>

                <div class="w-full mt-3 text-center">
                    <span class="text-[9px] text-stone-400 truncate block">
                        {{ $orderUrl }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

</body>
</html>

