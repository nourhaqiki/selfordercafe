<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        // A table number in the URL must come from a valid, signed QR link.
        if ($request->has('table')) {
            abort_unless($request->hasValidSignatureWhileIgnoring(['category', 'q']), 403);

            $table = CafeTable::where('table_number', trim($request->query('table')))
                ->where('is_active', true)
                ->firstOrFail();
            session(['table_number' => $table->table_number]);
        }

        $tableNumber = session('table_number', '01');

        // Check if store traffic is paused by manager
        $isOrderPaused = StoreSetting::isOrderPaused();

        // Fetch categories with available menu items
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->with(['activeMenus' => function ($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])
            ->get();

        // Active category filter
        $activeCategorySlug = $request->query('category', 'all');
        $searchKeyword = trim($request->query('q', ''));

        $query = Menu::where('is_available', true)->with('category');

        if ($activeCategorySlug !== 'all') {
            $query->whereHas('category', function ($q) use ($activeCategorySlug) {
                $q->where('slug', $activeCategorySlug);
            });
        }

        if ($searchKeyword !== '') {
            $query->where(function ($q) use ($searchKeyword) {
                $q->where('name', 'like', "%{$searchKeyword}%")
                  ->orWhere('description', 'like', "%{$searchKeyword}%");
            });
        }

        $menus = $query->orderBy('sort_order')->orderBy('name')->get();

        // Signature / Favorite menu item for visual hierarchy break
        $signatureMenu = Menu::where('is_available', true)
            ->where('is_favorite', true)
            ->first();

        // Cart items from session
        $cart = session('cart', []);
        $cartTotal = 0;
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
            $cartCount += $item['quantity'];
        }

        // Active pending or preparing order in session
        $activeOrderCode = session('active_order_code');
        $activeOrder = null;
        if ($activeOrderCode) {
            $activeOrder = Order::where('order_code', $activeOrderCode)
                ->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PAID, Order::STATUS_PREPARING])
                ->first();
        }

        return view('customer.catalog', compact(
            'categories',
            'menus',
            'signatureMenu',
            'tableNumber',
            'isOrderPaused',
            'activeCategorySlug',
            'searchKeyword',
            'cart',
            'cartTotal',
            'cartCount',
            'activeOrder'
        ));
    }

    public function addToCart(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'nullable|integer|min:1|max:50',
            'spicy_level' => 'nullable|integer|min:0|max:8',
            'notes' => 'nullable|string|max:200',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $quantity = $validated['quantity'] ?? 1;
        $spicyLevel = $validated['spicy_level'] ?? 0;
        $notes = trim($validated['notes'] ?? '');

        // Cart key combines menu_id + spicy_level to allow multiple variants in cart
        $cartKey = $menu->id . '_lvl_' . $spicyLevel . '_' . md5($notes);

        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'quantity' => $quantity,
                'spicy_level' => $spicyLevel,
                'notes' => $notes,
                'image_url' => $menu->image_url,
                'has_spicy_levels' => $menu->has_spicy_levels,
            ];
        }

        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            $totalCount = array_sum(array_column($cart, 'quantity'));
            $totalAmount = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            return response()->json([
                'success' => true,
                'message' => "{$menu->name} berhasil ditambahkan ke keranjang.",
                'cart_count' => $totalCount,
                'cart_total' => $totalAmount,
                'formatted_total' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
            ]);
        }

        return back()->with('success', "{$menu->name} ditambahkan ke pesanan.");
    }

    public function updateCart(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $cartKey = $validated['cart_key'];
        $quantity = $validated['quantity'];
        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            if ($quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $quantity;
            }
            session(['cart' => $cart]);
        }

        if ($request->wantsJson()) {
            $totalCount = array_sum(array_column($cart, 'quantity'));
            $totalAmount = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            return response()->json([
                'success' => true,
                'cart_count' => $totalCount,
                'cart_total' => $totalAmount,
                'formatted_total' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
            ]);
        }

        return back();
    }

    public function clearCart(): RedirectResponse
    {
        session()->forget('cart');
        return back()->with('info', 'Keranjang pesanan telah dikosongkan.');
    }

    public function checkout(): View|RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            $activeOrderCode = session('active_order_code');
            if ($activeOrderCode) {
                $activeOrder = Order::where('order_code', $activeOrderCode)
                    ->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PAID, Order::STATUS_PREPARING])
                    ->first();
                if ($activeOrder) {
                    return redirect()->route('customer.track', ['orderCode' => $activeOrder->order_code]);
                }
            }
            return redirect()->route('customer.catalog')->with('error', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        if (StoreSetting::isOrderPaused()) {
            return redirect()->route('customer.catalog')->with('error', 'Maaf, dapur sedang sibuk dan pemesanan baru dijeda sementara.');
        }

        $tableNumber = session('table_number', '01');

        $cartTotal = 0;
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
            $cartCount += $item['quantity'];
        }

        return view('customer.checkout', compact('cart', 'cartTotal', 'cartCount', 'tableNumber'));
    }

    public function storeOrder(Request $request): RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.catalog')->with('error', 'Keranjang belanja kosong.');
        }

        if (StoreSetting::isOrderPaused()) {
            return redirect()->route('customer.catalog')->with('error', 'Pemesanan baru dijeda sementara.');
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'payment_method' => 'required|in:qris,cash,transfer,debit',
            'order_notes' => 'nullable|string|max:300',
        ]);

        $tableNumber = session('table_number', '01');

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Generate unique order code: ORD-YYYYMMDD-XXXX
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $order = Order::create([
            'order_code' => $orderCode,
            'table_number' => $tableNumber,
            'customer_name' => $validated['customer_name'] ?? 'Pelanggan Meja ' . $tableNumber,
                        'status' => $validated['payment_method'] === 'cash' ? Order::STATUS_PAID : Order::STATUS_PENDING_PAYMENT,
            'paid_at' => $validated['payment_method'] === 'cash' ? now() : null,
            'payment_method' => $validated['payment_method'],
            'total_amount' => $totalAmount,
            'notes' => $validated['order_notes'] ?? null,
            'customer_paid_at' => $validated['payment_method'] === 'cash' ? now() : null,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'menu_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'spicy_level' => $item['spicy_level'] ?? 0,
                'notes' => $item['notes'] ?? null,
                'subtotal' => $item['price'] * $item['quantity'],
            ]);
        }

        // Clear session cart and remember active order
        session()->forget('cart');
        session(['active_order_code' => $orderCode]);

        return redirect()->route('customer.track', ['orderCode' => $orderCode])
            ->with('success', 'Pesanan Anda berhasil dikirim! Silakan selesaikan pembayaran.');
    }

    public function receipt(string $orderCode): View
    {
        $order = Order::with('items')->where('order_code', $orderCode)->firstOrFail();
        return view('customer.receipt', compact('order'));
    }

    public function track(string $orderCode): View
    
    {
        $order = Order::with('items')->where('order_code', $orderCode)->firstOrFail();
        session(['active_order_code' => $orderCode]);
        return view('customer.track', compact('order'));
    }

    public function checkStatus(string $orderCode): JsonResponse
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();
        return response()->json([
            'order_code' => $order->order_code,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'is_paid' => in_array($order->status, [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED]),
            'is_completed' => $order->status === Order::STATUS_COMPLETED,
        ]);
    }

    public function simulateCustomerPay(string $orderCode): RedirectResponse
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();
        $order->update(['customer_paid_at' => now()]);
        
        return back()->with('info', 'Konfirmasi pembayaran telah dikirim ke Kasir. Menunggu verifikasi staf kasir.');
    }

    /**
     * Cancel an order that is still pending payment.
     * Uses the active order code stored in session if no code is supplied.
     */
    public function cancelPending(Request $request): RedirectResponse
    {
        $orderCode = $request->input('order_code') ?? session('active_order_code');
        if (!$orderCode) {
            return back()->with('error', 'Tidak ada pesanan yang dapat dibatalkan.');
        }
        $order = Order::where('order_code', $orderCode)->firstOrFail();
        if ($order->status !== Order::STATUS_PENDING_PAYMENT) {
            return back()->with('error', 'Pesanan sudah diproses dan tidak dapat dibatalkan.');
        }
        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'notes' => trim($order->notes . ' [Dibatalkan oleh pelanggan]'),
        ]);
        session()->forget(['active_order_code', 'cart']);
        return redirect()->route('customer.catalog')->with('info', "Pesanan {$order->order_code} berhasil dibatalkan.");
    }

    public function cancelOrder(string $orderCode): RedirectResponse
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        if ($order->status === Order::STATUS_PENDING_PAYMENT) {
            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'notes' => trim($order->notes . ' [Dibatalkan oleh pelanggan]'),
            ]);
            session()->forget('active_order_code');
            return redirect()->route('customer.catalog')->with('info', "Pesanan {$order->order_code} berhasil dibatalkan.");
        }

        return back()->with('error', 'Pesanan yang sudah diproses tidak dapat dibatalkan.');
    }
}

