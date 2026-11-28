<?php

namespace App\Http\Controllers;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ManagerController extends Controller
{
    public function dashboard(): View
    {
        $today = Carbon::today();

        $todayRevenue = Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED])
            ->whereDate('paid_at', $today)
            ->sum('total_amount');

        $todayOrdersCount = Order::whereDate('created_at', $today)->count();
        $todayPaidOrdersCount = Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED])
            ->whereDate('paid_at', $today)
            ->count();

        $totalMenus = Menu::count();
        $availableMenus = Menu::where('is_available', true)->count();
        $totalTables = CafeTable::where('is_active', true)->count();

        $isOrderPaused = StoreSetting::isOrderPaused();

        // Top 5 selling items
        $topItems = OrderItem::selectRaw('menu_name, SUM(quantity) as total_qty, SUM(subtotal) as total_sales')
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('items')->latest()->limit(8)->get();

        return view('manager.dashboard', compact(
            'todayRevenue',
            'todayOrdersCount',
            'todayPaidOrdersCount',
            'totalMenus',
            'availableMenus',
            'totalTables',
            'isOrderPaused',
            'topItems',
            'recentOrders'
        ));
    }

    public function toggleTraffic(Request $request): RedirectResponse
    {
        $current = StoreSetting::isOrderPaused();
        $newVal = $current ? '0' : '1';
        StoreSetting::set('is_order_paused', $newVal);

        $statusText = $newVal === '1' ? 'DIJEDA SEMENTARA (Pause Order)' : 'DIBUKA NORMAL';
        return back()->with('success', "Status penerimaan pesanan cafe berhasil diubah menjadi: {$statusText}.");
    }

    // --- MENU MASTER DATA MANAGEMENT ---

    public function menuIndex(Request $request): View
    {
        $query = Menu::with('category');

        if ($request->has('category') && $request->query('category') !== 'all') {
            $query->where('category_id', $request->query('category'));
        }

        if ($request->has('q') && trim($request->query('q')) !== '') {
            $keyword = trim($request->query('q'));
            $query->where('name', 'like', "%{$keyword}%");
        }

        $menus = $query->orderBy('category_id')->orderBy('sort_order')->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('manager.menu.index', compact('menus', 'categories'));
    }

    public function menuCreate(): View
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('manager.menu.create', compact('categories'));
    }

    public function menuStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'is_available' => 'nullable|boolean',
            'has_spicy_levels' => 'nullable|boolean',
            'max_spicy_level' => 'nullable|integer|min:1|max:20',
            'is_favorite' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_available'] = $request->has('is_available');
        $validated['has_spicy_levels'] = $request->has('has_spicy_levels');
        $validated['max_spicy_level'] = (int) ($validated['max_spicy_level'] ?? 8);
        $validated['is_favorite'] = $request->has('is_favorite');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Menu::create($validated);

        return redirect()->route('manager.menu.index')->with('success', "Menu {$validated['name']} berhasil ditambahkan.");
    }

    public function menuEdit(int $id): View
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('manager.menu.edit', compact('menu', 'categories'));
    }

    public function menuUpdate(Request $request, int $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'max_spicy_level' => 'nullable|integer|min:1|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['has_spicy_levels'] = $request->has('has_spicy_levels');
        $validated['max_spicy_level'] = (int) ($validated['max_spicy_level'] ?? ($menu->max_spicy_level ?? 8));
        $validated['is_favorite'] = $request->has('is_favorite');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $menu->update($validated);

        return redirect()->route('manager.menu.index')->with('success', "Menu {$menu->name} berhasil diperbarui.");
    }

    public function toggleMenuStock(int $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_available' => !$menu->is_available]);

        $statusStr = $menu->is_available ? 'TERSEDIA' : 'HABIS';
        return back()->with('success', "Status stok menu {$menu->name} diubah menjadi: {$statusStr}.");
    }

    public function menuDestroy(int $id): RedirectResponse
    {
        $menu = Menu::findOrFail($id);
        $name = $menu->name;
        $menu->delete();

        return redirect()->route('manager.menu.index')->with('warning', "Menu {$name} telah dihapus.");
    }

    // --- CATEGORY MANAGEMENT ---

    public function categoriesIndex(): View
    {
        $categories = Category::withCount('menus')->orderBy('sort_order')->get();
        return view('manager.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = true;

        Category::create($validated);

        return back()->with('success', "Kategori {$validated['name']} berhasil ditambahkan.");
    }

    public function categoryDestroy(int $id): RedirectResponse
    {
        $cat = Category::withCount('menus')->findOrFail($id);
        if ($cat->menus_count > 0) {
            return back()->with('error', "Kategori {$cat->name} tidak dapat dihapus karena masih memuat {$cat->menus_count} menu.");
        }

        $cat->delete();
        return back()->with('warning', "Kategori telah dihapus.");
    }

    // --- TABLE MANAGEMENT & QR CODE GENERATOR ---

    public function tablesIndex(): View
    {
        $tables = CafeTable::orderBy('table_number')->get();
        return view('manager.tables.index', compact('tables'));
    }

    public function tableStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:10|unique:tables,table_number',
            'capacity' => 'nullable|integer|min:1|max:20',
        ]);

        $num = trim($validated['table_number']);
        CafeTable::create([
            'table_number' => $num,
            'capacity' => $validated['capacity'] ?? 4,
            'qr_payload' => CafeTable::signedOrderUrl($num),
            'is_active' => true,
        ]);

        return back()->with('success', "Meja {$num} berhasil ditambahkan dengan binding QR.");
    }

    public function massGenerateTables(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from' => 'required|integer|min:1|max:100',
            'to' => 'required|integer|min:1|max:100|gte:from',
            'capacity' => 'nullable|integer|min:1|max:20',
        ]);

        $from = $validated['from'];
        $to = $validated['to'];
        $capacity = $validated['capacity'] ?? 4;
        $createdCount = 0;

        for ($i = $from; $i <= $to; $i++) {
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            $exists = CafeTable::where('table_number', $num)->exists();
            if (!$exists) {
                CafeTable::create([
                    'table_number' => $num,
                    'capacity' => $capacity,
                    'qr_payload' => CafeTable::signedOrderUrl($num),
                    'is_active' => true,
                ]);
                $createdCount++;
            }
        }

        return back()->with('success', "Berhasil men-generate {$createdCount} meja baru (Rentang: Meja {$from} sampai {$to}).");
    }

    public function printQRs(Request $request): View
    {
        $tables = CafeTable::where('is_active', true)->orderBy('table_number')->get();
        return view('manager.tables.print', compact('tables'));
    }

    public function tableDestroy(int $id): RedirectResponse
    {
        $table = CafeTable::findOrFail($id);
        $hasActiveOrders = Order::where('table_number', $table->table_number)
            ->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PAID, Order::STATUS_PREPARING])
            ->exists();

        if ($hasActiveOrders) {
            return back()->with('error', "Meja {$table->table_number} tidak dapat dihapus karena masih ada pesanan aktif.");
        }

        $num = $table->table_number;
        $table->delete();
        return back()->with('warning', "Meja {$num} telah dihapus.");
    }

    public function monitorCashier(Request $request): View
    {
        if ($request->has('status')) {
            session(['cashier_status_filter' => $request->query('status')]);
        }
        $statusFilter = $request->query('status', session('cashier_status_filter', 'all'));

        $query = Order::with('items')->latest();
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        $orders = $query->paginate(20)->withQueryString();

        $selectedOrderId = $request->query('selected', session('cashier_selected_order_id'));
        $selectedOrder = $selectedOrderId ? Order::with('items')->find($selectedOrderId) : null;
        if (!$selectedOrder && $orders->isNotEmpty()) {
            $selectedOrder = $orders->first();
        }

        $counts = [
            'pending'   => Order::where('status', Order::STATUS_PENDING_PAYMENT)->count(),
            'paid'      => Order::where('status', Order::STATUS_PAID)->count(),
            'preparing' => Order::where('status', Order::STATUS_PREPARING)->count(),
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->whereDate('updated_at', Carbon::today())->count(),
        ];

        $todayRevenue = Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED])
            ->whereDate('paid_at', Carbon::today())
            ->sum('total_amount');

        return view('manager.cashier.monitor', compact('orders', 'selectedOrder', 'statusFilter', 'counts', 'todayRevenue'));
    }


    public function reports(Request $request): View
    {
        $range = $request->query('range', 'today');
        $query = Order::with('items')->whereIn('status', [Order::STATUS_PAID, Order::STATUS_PREPARING, Order::STATUS_COMPLETED]);

        if ($range === 'today') {
            $query->whereDate('paid_at', Carbon::today());
        } elseif ($range === 'week') {
            $query->where('paid_at', '>=', Carbon::now()->subDays(7));
        } elseif ($range === 'month') {
            $query->where('paid_at', '>=', Carbon::now()->subDays(30));
        }

        $orders = (clone $query)->latest('paid_at')->paginate(20)->withQueryString();

        $totalRevenue = (clone $query)->sum('total_amount');
        $totalTransactions = (clone $query)->count();

        // Breakdown by payment method
        $paymentBreakdown = (clone $query)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as amount')
            ->groupBy('payment_method')
            ->get();

        return view('manager.reports.index', compact('orders', 'totalRevenue', 'totalTransactions', 'paymentBreakdown', 'range'));
    }
}

