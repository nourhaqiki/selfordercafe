<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->has('status')) {
            session(['cashier_status_filter' => $request->query('status')]);
        }
        $statusFilter = $request->query('status', session('cashier_status_filter', 'PENDING_PAYMENT'));

        $query = Order::forCashier()->where('status','!=',Order::STATUS_PENDING_PAYMENT)->with('items')->latest();

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $orders = $query->paginate(20)->withQueryString();

        if ($request->has('selected')) {
            session(['cashier_selected_order_id' => $request->query('selected')]);
        }
        $selectedOrderId = $request->query('selected', session('cashier_selected_order_id'));
        $selectedOrder = null;
        if ($selectedOrderId) {
            $selectedOrder = Order::forCashier()->with('items')->find($selectedOrderId);
        }
        if (!$selectedOrder && $orders->isNotEmpty()) {
            $selectedOrder = $orders->first();
        }

        $counts = [
            'pending' => Order::forCashier()->where('status', Order::STATUS_PENDING_PAYMENT)->count(),
            'paid' => Order::forCashier()->where('status', Order::STATUS_PAID)->count(),
            'preparing' => Order::forCashier()->where('status', Order::STATUS_PREPARING)->count(),
            'completed' => Order::forCashier()->where('status', Order::STATUS_COMPLETED)->whereDate('updated_at', Carbon::today())->count(),
        ];

        $todayRevenue = Order::forCashier()->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED])
            ->whereDate('paid_at', Carbon::today())
            ->sum('total_amount');

        return view('cashier.index', compact('orders', 'selectedOrder', 'statusFilter', 'counts', 'todayRevenue'));
    }

    public function createOrder(): View
    {
        $occupiedTables = Order::whereIn('status', [
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_PAID,
            Order::STATUS_PREPARING,
        ])->pluck('table_number')->unique()->toArray();

        $availableTables = CafeTable::where('is_active', true)
            ->whereNotIn('table_number', $occupiedTables)
            ->orderBy('table_number')
            ->get();

        $allTables = CafeTable::where('is_active', true)->orderBy('table_number')->get();
        $recommendedTable = $availableTables->first()?->table_number ?? $allTables->first()?->table_number ?? '01';

        $categories = Category::where('is_active', true)
            ->with(['activeMenus' => function ($q) {
                $q->orderBy('sort_order')->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('cashier.create_order', compact('availableTables', 'allTables', 'recommendedTable', 'categories'));
    }

    public function storeDirectOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_number' => 'required|string',
            'customer_name' => 'nullable|string|max:100',
            'payment_method' => 'required|in:cash,qris,debit,transfer',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:0|max:50',
            'items.*.spicy_level' => 'nullable|integer|min:0|max:8',
            'items.*.notes' => 'nullable|string|max:200',
            'order_notes' => 'nullable|string|max:300',
        ]);

        $orderItemsData = array_filter($validated['items'], fn($item) => ($item['quantity'] ?? 0) > 0);
        if (empty($orderItemsData)) {
            return back()->with('error', 'Pilih minimal satu menu pesanan.')->withInput();
        }

        $menuIds = array_column($orderItemsData, 'menu_id');
        $menus = Menu::whereIn('id', $menuIds)->get()->keyBy('id');

        $totalAmount = 0;
        foreach ($orderItemsData as $item) {
            $menu = $menus->get($item['menu_id']);
            if ($menu) {
                $totalAmount += $menu->price * $item['quantity'];
            }
        }

        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $order = Order::create([
            'order_code' => $orderCode,
            'table_number' => $validated['table_number'],
            'customer_name' => $validated['customer_name'] ?? 'Pelanggan Kasir Meja ' . $validated['table_number'],
            'status' => Order::STATUS_PAID,
            'payment_method' => $validated['payment_method'],
            'total_amount' => $totalAmount,
            'notes' => $validated['order_notes'] ?? 'Pesanan langsung di kasir (Walk-in)',
            'paid_at' => now(),
            'customer_paid_at' => now(),
        ]);

        foreach ($orderItemsData as $item) {
            $menu = $menus->get($item['menu_id']);
            if ($menu) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'spicy_level' => $item['spicy_level'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                    'subtotal' => $menu->price * $item['quantity'],
                ]);
            }
        }

        session(['cashier_status_filter' => 'PAID', 'cashier_selected_order_id' => $order->id]);

        return redirect()->route('cashier.index', ['status' => 'PAID', 'selected' => $order->id])
            ->with('success', "Pesanan Walk-in Meja {$order->table_number} ({$order->order_code}) berhasil dibuat & diteruskan ke dapur!");
    }

    public function approvePayment(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING_PAYMENT) {
            return back()->with('error', "Pesanan {$order->order_code} sudah berstatus {$order->status_label}.");
        }

        $order->update([
            'status' => Order::STATUS_PAID,
            'paid_at' => now(),
            'customer_paid_at' => $order->customer_paid_at ?? now(),
        ]);

        return back()->with('success', "Pembayaran Meja {$order->table_number} ({$order->order_code}) sebesar {$order->formatted_total} BERHASIL DISETUJUI. Pesanan diteruskan ke dapur.");
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:PAID,PREPARING,COMPLETED,CANCELLED',
        ]);

        $order = Order::findOrFail($id);
        $newStatus = $validated['status'];

        $updateData = ['status' => $newStatus];

        if ($newStatus === Order::STATUS_COMPLETED && !$order->completed_at) {
            $updateData['completed_at'] = now();
        }

        if ($newStatus === Order::STATUS_PAID && !$order->paid_at) {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        return back()->with('success', "Status pesanan {$order->order_code} diperbarui menjadi {$order->status_label}.");
    }

    public function rejectOrder(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'notes' => trim($order->notes . ' [Dibatalkan oleh kasir: ' . ($request->input('reason', 'Verifikasi pembayaran gagal') . ']')),
        ]);

        return back()->with('warning', "Pesanan {$order->order_code} telah dibatalkan.");
    }

    public function scanPage(): View
    {
        return view('cashier.scan');
    }

    public function scanLookup(Request $request): View
    {
        $code = $request->query('code', '');
        // Extract order code from URL if scanned QR contains full URL
        if (str_contains($code, '/scan/lookup')) {
            parse_str(parse_url($code, PHP_URL_QUERY) ?? '', $params);
            $code = $params['code'] ?? $code;
        }

        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        return view('cashier.scan_result', compact('order'));
    }

    public function pollPending(): JsonResponse
    {
        $pendingQuery = Order::forCashier()->where('status', Order::STATUS_PENDING_PAYMENT);
        $pendingCount = $pendingQuery->count();
        $latestPending = $pendingQuery->latest()->first();

        return response()->json([
            'pending_count' => $pendingCount,
            'has_pending' => $pendingCount > 0,
            'latest_order_code' => $latestPending?->order_code,
            'latest_table' => $latestPending?->table_number,
            'latest_amount' => $latestPending?->formatted_total,
        ]);
    }
}
