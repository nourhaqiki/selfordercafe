<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kopi & Mie Nusantara - Cafe Self-Order')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-stone-50 text-stone-900 font-sans antialiased flex flex-col selection:bg-emerald-700 selection:text-white">
    
    <!-- Flash Messages / Toast Feedback -->
    @if(session('success'))
        <div id="toast-success" class="fixed top-4 right-4 z-50 max-w-md p-4 bg-emerald-800 text-white rounded-xl shadow-lg border border-emerald-700 flex items-start gap-3 transition-all duration-200" role="alert">
            <svg class="w-5 h-5 text-emerald-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white p-1 rounded-md" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="fixed top-4 right-4 z-50 max-w-md p-4 bg-red-800 text-white rounded-xl shadow-lg border border-red-700 flex items-start gap-3 transition-all duration-200" role="alert">
            <svg class="w-5 h-5 text-red-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-300 hover:text-white p-1 rounded-md" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div id="toast-warning" class="fixed top-4 right-4 z-50 max-w-md p-4 bg-amber-800 text-white rounded-xl shadow-lg border border-amber-700 flex items-start gap-3 transition-all duration-200" role="alert">
            <svg class="w-5 h-5 text-amber-300 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1 text-sm font-medium">{{ session('warning') }}</div>
            <button type="button" onclick="this.parentElement.remove()" class="text-amber-300 hover:text-white p-1 rounded-md" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>

