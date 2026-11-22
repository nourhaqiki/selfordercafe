@extends('layouts.cashier')

@section('title', 'Scan Barcode Pesanan - Konsol Kasir')

@section('cashier-content')
<div class="max-w-lg mx-auto space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('cashier.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-900 min-h-[44px] p-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <h1 class="text-sm font-black text-stone-900 uppercase tracking-wider">Scan Pesanan</h1>
    </div>

    {{-- Camera Scanner --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-stone-900 text-white">
            <span class="text-xs font-black uppercase tracking-wider text-emerald-400">Kamera Scanner</span>
        </div>
        <div class="p-4 space-y-3">
            <div id="qr-reader" class="w-full rounded-xl overflow-hidden bg-stone-100 min-h-[280px] flex items-center justify-center">
                <span class="text-xs text-stone-400 font-bold" id="qr-placeholder">Memuat kamera...</span>
            </div>
            <div id="qr-result" class="hidden p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-900 text-center"></div>
        </div>
    </div>

    {{-- Manual Input --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-stone-50 border-b border-stone-200">
            <span class="text-xs font-black text-stone-900 uppercase tracking-wider">Input Manual</span>
        </div>
        <form action="{{ route('cashier.scan.lookup') }}" method="GET" class="p-4 flex gap-2">
            <input type="text" name="code" placeholder="Ketik kode pesanan (ORD-...)" required
                   class="flex-1 min-h-[44px] px-3 py-2 rounded-xl border border-stone-300 text-sm font-bold text-stone-900 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <button type="submit"
                    class="min-h-[44px] px-4 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-black cursor-pointer transition-colors">
                Cari
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
'use strict';
const lookupUrl = "{{ route('cashier.scan.lookup') }}";

document.addEventListener('DOMContentLoaded', function() {
    const reader = new Html5Qrcode("qr-reader");
    const placeholder = document.getElementById('qr-placeholder');
    const resultDiv = document.getElementById('qr-result');

    function extractCode(text) {
        // If scanned text is a URL with ?code=, extract it
        try {
            const url = new URL(text);
            const code = url.searchParams.get('code');
            if (code) return code;
        } catch {}
        // Otherwise treat the whole text as the code
        return text.trim();
    }

    reader.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            reader.stop().catch(() => {});
            const code = extractCode(decodedText);
            resultDiv.textContent = 'Ditemukan: ' + code + ' — Memuat...';
            resultDiv.classList.remove('hidden');
            window.location.href = lookupUrl + '?code=' + encodeURIComponent(code);
        },
        () => {}
    ).then(() => {
        if (placeholder) placeholder.textContent = '';
    }).catch(err => {
        if (placeholder) placeholder.textContent = 'Kamera tidak tersedia. Gunakan input manual.';
    });
});
</script>
@endpush
@endsection
