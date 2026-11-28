<?php

namespace Tests\Feature;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Models\User;
use Tests\TestCase;

class CafeSelfOrderTest extends TestCase
{
    private function cashierUser(): User
    {
        return User::where('role', User::ROLE_CASHIER)->first()
            ?? User::factory()->create(['role' => User::ROLE_CASHIER]);
    }

    private function managerUser(): User
    {
        return User::where('role', User::ROLE_MANAGER)->first()
            ?? User::factory()->create(['role' => User::ROLE_MANAGER]);
    }

    public function test_customer_catalog_renders_successfully(): void
    {
        $this->assertTrue(CafeTable::where('table_number', '05')->where('is_active', true)->exists());
        $response = $this->get(CafeTable::signedOrderUrl('05'));

        $response->assertStatus(200);
        $response->assertSee('Mie Nusantara');
        $response->assertSessionHas('table_number', '05');
        $response->assertSee('Meja');
        $response->assertDontSee('Ubah nomor meja');
        $response->assertDontSee('table-modal');
    }

    public function test_customer_cannot_change_the_table_in_a_signed_qr_link(): void
    {
        $signedUrl = CafeTable::signedOrderUrl('05');
        $tamperedUrl = str_replace('table=05', 'table=06', $signedUrl);

        $this->get($tamperedUrl)->assertForbidden();
        $this->get('/order?table=06')->assertForbidden();
    }

    public function test_customer_can_add_to_cart_and_checkout(): void
    {
        $this->assertTrue(CafeTable::where('table_number', '05')->where('is_active', true)->exists());
        $this->get(CafeTable::signedOrderUrl('05'))->assertOk();

        $menu = Menu::first();

        // Add to cart
        $response = $this->post('/cart/add', [
            'menu_id'     => $menu->id,
            'quantity'    => 2,
            'spicy_level' => 3,
            'notes'       => 'Ekstra renyah',
        ]);

        $response->assertRedirect();
        $this->assertTrue(session()->has('cart'));

        // View checkout
        $checkoutResponse = $this->get('/order/checkout');
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee($menu->name);
        $checkoutResponse->assertSee('Ekstra renyah');
        $checkoutResponse->assertSee('Meja Nomor 05');
        $checkoutResponse->assertDontSee('Ganti Meja');

        // Submit order
        $submitResponse = $this->post('/order/submit', [
            'table_number'  => '08',
            'customer_name' => 'Ahmad Dani',
            'payment_method' => 'qris',
            'order_notes'   => 'Jangan pakai sendok plastik',
        ]);

        $submitResponse->assertRedirect();

        // Assert Golden Rule: Order is created with PENDING_PAYMENT
        $order = Order::where('table_number', '05')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(Order::STATUS_PENDING_PAYMENT, $order->status);
        $this->assertEquals('qris', $order->payment_method);
        $this->assertCount(1, $order->items);

        // Track page renders correctly
        $trackResponse = $this->get("/order/track/{$order->order_code}");
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_code);
        $trackResponse->assertSee('Menunggu Pembayaran');
    }

    public function test_unauthenticated_cannot_access_staff_routes(): void
    {
        $this->get('/cashier')->assertRedirect('/login');
        $this->get('/manager')->assertRedirect('/login');
    }

    public function test_cashier_cannot_access_manager_routes(): void
    {
        $cashier = $this->cashierUser();

        $this->actingAs($cashier)->get('/cashier')->assertStatus(200);
        $this->actingAs($cashier)->get('/manager')->assertRedirect('/cashier');
    }

    public function test_cashier_flow_and_golden_rule_payment_approval(): void
    {
        $cashier = $this->cashierUser();

        $order = Order::create([
            'order_code'     => 'ORD-TEST-' . uniqid(),
            'table_number'   => '08',
            'customer_name'  => 'Rina Wijaya',
            'status'         => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'cash',
            'total_amount'   => 25000,
        ]);

        // Cashier visits index
        $cashierIndex = $this->actingAs($cashier)->get('/cashier');
        $cashierIndex->assertStatus(200);
        $cashierIndex->assertSee($order->order_code);

        // Golden Rule Approval: Cashier clicks APPROVE
        $approveResponse = $this->actingAs($cashier)->post("/cashier/order/{$order->id}/approve");
        $approveResponse->assertRedirect();

        $order->refresh();
        $this->assertEquals(Order::STATUS_PAID, $order->status);
        $this->assertNotNull($order->paid_at);

        // Kitchen updates to PREPARING
        $prepResponse = $this->actingAs($cashier)->post("/cashier/order/{$order->id}/status", [
            'status' => Order::STATUS_PREPARING,
        ]);
        $prepResponse->assertRedirect();
        $order->refresh();
        $this->assertEquals(Order::STATUS_PREPARING, $order->status);

        // Food completed
        $compResponse = $this->actingAs($cashier)->post("/cashier/order/{$order->id}/status", [
            'status' => Order::STATUS_COMPLETED,
        ]);
        $compResponse->assertRedirect();
        $order->refresh();
        $this->assertEquals(Order::STATUS_COMPLETED, $order->status);
        $this->assertNotNull($order->completed_at);
    }

    public function test_manager_dashboard_and_traffic_toggle(): void
    {
        $manager = $this->managerUser();

        // Manager dashboard renders
        $dashboardResponse = $this->actingAs($manager)->get('/manager');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Manager Overview');

        // Toggle pause order
        $initialState = StoreSetting::isOrderPaused();
        $this->actingAs($manager)->post('/manager/traffic/toggle')->assertRedirect();
        $this->assertEquals(!$initialState, StoreSetting::isOrderPaused());

        // Restore back to normal
        $this->actingAs($manager)->post('/manager/traffic/toggle')->assertRedirect();
        $this->assertFalse(StoreSetting::isOrderPaused());
    }

    public function test_manager_can_monitor_cashier_activity_without_leaving_manager_layout(): void
    {
        $manager = $this->managerUser();
        $order = Order::create([
            'order_code' => 'ORD-MONITOR-' . uniqid(),
            'table_number' => '08',
            'customer_name' => 'Pelanggan Monitor',
            'status' => Order::STATUS_PAID,
            'payment_method' => 'cash',
            'total_amount' => 25000,
        ]);

        $response = $this->actingAs($manager)->get('/manager/cashier/monitor');

        $response->assertOk();
        $response->assertSee('MANAGER PORTAL');
        $response->assertSee('Pantau Kegiatan Kasir');
        $response->assertSee($order->order_code);
        $response->assertSee('setInterval', false);
        $response->assertDontSee('KONSOL KASIR');
    }

    public function test_manager_menu_stock_toggle(): void
    {
        $manager = $this->managerUser();
        $menu = Menu::first();
        $initialStock = $menu->is_available;

        $this->actingAs($manager)->post("/manager/menus/{$menu->id}/toggle-stock")->assertRedirect();
        $menu->refresh();
        $this->assertEquals(!$initialStock, $menu->is_available);

        // Toggle back
        $this->actingAs($manager)->post("/manager/menus/{$menu->id}/toggle-stock")->assertRedirect();
        $menu->refresh();
        $this->assertEquals($initialStock, $menu->is_available);
    }

    public function test_manager_table_management_and_print_canvas(): void
    {
        $manager = $this->managerUser();

        $tablesIndex = $this->actingAs($manager)->get('/manager/tables');
        $tablesIndex->assertStatus(200);
        $tablesIndex->assertSee('Meja');

        // Mass generate
        $massResponse = $this->actingAs($manager)->post('/manager/tables/mass-generate', [
            'from'     => 40,
            'to'       => 42,
            'capacity' => 6,
        ]);
        $massResponse->assertRedirect();

        $this->assertDatabaseHas('tables', ['table_number' => '40']);
        $this->assertDatabaseHas('tables', ['table_number' => '41']);
        $this->assertDatabaseHas('tables', ['table_number' => '42']);

        // Print Canvas renders
        $printResponse = $this->actingAs($manager)->get('/manager/tables/print-qrs');
        $printResponse->assertStatus(200);
        $printResponse->assertSee('MEJA 40');
        $printResponse->assertSee('signature%3D', false);
    }

    public function test_manager_financial_reports(): void
    {
        $manager = $this->managerUser();

        $reportResponse = $this->actingAs($manager)->get('/manager/reports?range=today');
        $reportResponse->assertStatus(200);
        $reportResponse->assertSee('Total Omzet Bersih');
    }

    public function test_customer_can_cancel_order_during_payment_and_before_checkout(): void
    {
        $menu = Menu::first();
        $this->withSession(['table_number' => '05']);

        // 1. Cancel before checkout (cart clear)
        $this->post('/cart/add', ['menu_id' => $menu->id, 'quantity' => 1])->assertRedirect();
        $this->assertTrue(session()->has('cart'));
        $this->post('/cart/clear')->assertRedirect();
        $this->assertFalse(session()->has('cart'));

        // 2. Cancel in payment stage
        $order = Order::create([
            'order_code' => 'ORD-CANCEL-' . uniqid(),
            'table_number' => '05',
            'customer_name' => 'Budi',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'qris',
            'total_amount' => 30000,
        ]);

        $cancelResponse = $this->post("/order/cancel/{$order->order_code}");
        $cancelResponse->assertRedirect('/order');

        $order->refresh();
        $this->assertEquals(Order::STATUS_CANCELLED, $order->status);
    }

    public function test_cash_order_enters_cashier_directly_while_non_cash_waits_for_payment(): void
    {
        $cashier = $this->cashierUser();

        // Non-cash order without customer payment
        $qrisOrder = Order::create([
            'order_code' => 'ORD-QRIS-' . uniqid(),
            'table_number' => '02',
            'customer_name' => 'Doni',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'qris',
            'total_amount' => 45000,
            'customer_paid_at' => null,
        ]);

        // Cash order
        $cashOrder = Order::create([
            'order_code' => 'ORD-CASH-' . uniqid(),
            'table_number' => '03',
            'customer_name' => 'Sari',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'cash',
            'total_amount' => 20000,
            'customer_paid_at' => now(),
        ]);

        // Cashier sees cash order but does NOT see unpaid QRIS order
        $cashierView = $this->actingAs($cashier)->get('/cashier?status=PENDING_PAYMENT');
        $cashierView->assertSee($cashOrder->order_code);
        $cashierView->assertDontSee($qrisOrder->order_code);

        // When customer simulates payment for QRIS order
        $this->post("/order/simulate-pay/{$qrisOrder->order_code}")->assertRedirect();
        $qrisOrder->refresh();
        $this->assertNotNull($qrisOrder->customer_paid_at);

        // Now QRIS order appears in cashier
        $cashierViewAfterPay = $this->actingAs($cashier)->get('/cashier?status=PENDING_PAYMENT');
        $cashierViewAfterPay->assertSee($qrisOrder->order_code);
    }

    public function test_cashier_can_create_walk_in_direct_order_with_empty_table(): void
    {
        $cashier = $this->cashierUser();
        $menu = Menu::first();

        // Check create form loads available empty tables
        $formView = $this->actingAs($cashier)->get('/cashier/order/create');
        $formView->assertStatus(200);
        $formView->assertSee('Pemesanan Walk-in');

        // Submit walk-in order
        $storeResponse = $this->actingAs($cashier)->post('/cashier/order/store', [
            'table_number' => '01',
            'customer_name' => 'Pelanggan Meja 1',
            'payment_method' => 'cash',
            'items' => [
                [
                    'menu_id' => $menu->id,
                    'quantity' => 2,
                    'spicy_level' => 1,
                    'notes' => 'Langsung santap',
                ],
            ],
            'order_notes' => 'Pesanan walk-in counter',
        ]);

        $storeResponse->assertRedirect();

        $createdOrder = Order::where('table_number', '01')->latest()->first();
        $this->assertNotNull($createdOrder);
        $this->assertEquals(Order::STATUS_PAID, $createdOrder->status);
        $this->assertNotNull($createdOrder->paid_at);
        $this->assertNotNull($createdOrder->customer_paid_at);
        $this->assertCount(1, $createdOrder->items);
    }

    public function test_customer_active_order_preserved_on_back_and_refresh(): void
    {
        $order = Order::create([
            'order_code' => 'ORD-PERSIST-' . uniqid(),
            'table_number' => '05',
            'customer_name' => 'Tina',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'cash',
            'total_amount' => 50000,
        ]);

        // Simulating customer clicking back to checkout with empty cart
        $response = $this->withSession(['active_order_code' => $order->order_code, 'cart' => []])
            ->get('/order/checkout');

        // Redirects to track page instead of wiping order and going to empty catalog
        $response->assertRedirect("/order/track/{$order->order_code}");

        // Visiting catalog shows active order banner
        $catalogResponse = $this->withSession(['active_order_code' => $order->order_code])
            ->get('/order');
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee($order->order_code);
        $catalogResponse->assertSee('PESANAN ANDA SEDANG AKTIF');
    }

    public function test_all_customer_payment_methods_require_cashier_approval_before_paid_status(): void
    {
        $cashier = $this->cashierUser();
        $menu = Menu::first();

        foreach (['cash', 'qris', 'transfer', 'debit'] as $method) {
            $this->withSession(['table_number' => '05'])
                ->post('/cart/add', ['menu_id' => $menu->id, 'quantity' => 1])
                ->assertRedirect();

            $this->withSession(['table_number' => '05', 'cart' => session('cart')])
                ->post('/order/submit', [
                    'customer_name' => 'Test ' . strtoupper($method),
                    'payment_method' => $method,
                ])
                ->assertRedirect();

            $order = Order::where('customer_name', 'Test ' . strtoupper($method))->latest('id')->first();
            $this->assertNotNull($order);
            $this->assertEquals(Order::STATUS_PENDING_PAYMENT, $order->status);
            $this->assertNull($order->paid_at);

            $trackBefore = $this->get("/order/track/{$order->order_code}");
            $trackBefore->assertOk();
            $trackBefore->assertSee('BELUM DIBAYAR');
            $trackBefore->assertDontSee('SUDAH DIBAYAR');
            if ($method === 'debit') {
                $trackBefore->assertSee('Kartu Debit / mesin EDC');
                $trackBefore->assertDontSee('8920-1284-9900');
            }

            $this->actingAs($cashier)->post("/cashier/order/{$order->id}/approve")->assertRedirect();
            $order->refresh();
            $this->assertEquals(Order::STATUS_PAID, $order->status);
            $this->assertNotNull($order->paid_at);

            $trackAfter = $this->get("/order/track/{$order->order_code}");
            $trackAfter->assertOk();
            $trackAfter->assertSee('SUDAH DIBAYAR');

            $order->items()->delete();
            $order->delete();
        }
    }

    public function test_manager_can_configure_custom_max_spicy_level_on_create_and_edit(): void
    {
        $manager = $this->managerUser();
        $category = Category::first();

        $this->actingAs($manager)->post('/manager/menus', [
            'category_id' => $category->id,
            'name' => 'Mie Uji Level Pedas',
            'price' => 18000,
            'has_spicy_levels' => '1',
            'max_spicy_level' => 5,
            'is_available' => '1',
        ])->assertRedirect('/manager/menus');

        $menu = Menu::where('name', 'Mie Uji Level Pedas')->latest('id')->first();
        $this->assertNotNull($menu);
        $this->assertTrue($menu->has_spicy_levels);
        $this->assertEquals(5, $menu->max_spicy_level);

        $this->actingAs($manager)->put("/manager/menus/{$menu->id}", [
            'category_id' => $category->id,
            'name' => 'Mie Uji Level Pedas',
            'price' => 18000,
            'has_spicy_levels' => '1',
            'max_spicy_level' => 4,
            'is_available' => '1',
        ])->assertRedirect('/manager/menus');

        $menu->refresh();
        $this->assertEquals(4, $menu->max_spicy_level);

        $this->post('/cart/add', [
            'menu_id' => $menu->id,
            'quantity' => 1,
            'spicy_level' => 7,
        ])->assertRedirect();

        $cart = session('cart', []);
        $added = collect($cart)->firstWhere('menu_id', $menu->id);
        $this->assertNotNull($added);
        $this->assertEquals(4, $added['spicy_level']);

        $menu->delete();
    }
}
