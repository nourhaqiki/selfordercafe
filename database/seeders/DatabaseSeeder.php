<?php

namespace Database\Seeders;

use App\Models\CafeTable;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Staff Users
        User::updateOrCreate(
            ['email' => 'kasir@cafe.com'],
            [
                'name' => 'Staf Kasir Utama',
                'password' => Hash::make('kasir123'),
                'role' => User::ROLE_CASHIER,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@cafe.com'],
            [
                'name' => 'Manager Operasional Cafe',
                'password' => Hash::make('manager123'),
                'role' => User::ROLE_MANAGER,
            ]
        );

        // Store Settings
        StoreSetting::set('cafe_name', 'Kopi & Mie Nusantara');
        StoreSetting::set('is_order_paused', '0');
        StoreSetting::set('store_address', 'Jl. Merdeka No. 45, Kota Malang');
        StoreSetting::set('wifi_ssid', 'KopiMie_FreeWiFi');
        StoreSetting::set('wifi_pass', 'nikmatpedas');

        // Categories
        $catMie = Category::create([
            'name' => 'Mie Pedas Nusantara',
            'slug' => 'mie-pedas',
            'description' => 'Mie kenyal olahan tangan dengan bumbu rempah cabai rawit segar dan level pedas pilihan.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catDimsum = Category::create([
            'name' => 'Dimsum & Gorengan',
            'slug' => 'dimsum-gorengan',
            'description' => 'Gorengan renyah keemasan dan dimsum kukus lembut dengan isian daging ayam udang asli.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catDrink = Category::create([
            'name' => 'Minuman & Kopi',
            'slug' => 'minuman-kopi',
            'description' => 'Minuman segar pelepas dahaga pedas dan racikan kopi susu gula aren mantap.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catKombo = Category::create([
            'name' => 'Paket Hemat Kombo',
            'slug' => 'paket-hemat',
            'description' => 'Pilihan paket lengkap hemat mie, pendamping dimsum, dan minuman dingin.',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Menus
        $menus = [
            // Mie
            [
                'category_id' => $catMie->id,
                'name' => 'Mie Hompimpa Pedas Asin',
                'slug' => 'mie-hompimpa-pedas-asin',
                'description' => 'Mie kenyal gurih asin dengan taburan ayam cincang halus, daun bawang, dan 2 keripik pangsit renyah.',
                'price' => 14500,
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => true,
                'is_favorite' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $catMie->id,
                'name' => 'Mie Gacoan Pedas Manis',
                'slug' => 'mie-gacoan-pedas-manis',
                'description' => 'Mie kenyal berpadu kecap manis spesial dan sambal cabai giling segar, bertabur ayam cincang gurih.',
                'price' => 14500,
                'image_url' => 'https://images.unsplash.com/photo-1552611052-33e04de081de?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => true,
                'is_favorite' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $catMie->id,
                'name' => 'Mie Polos Tanpa Cabai (Level 0)',
                'slug' => 'mie-polos-tanpa-cabai',
                'description' => 'Pilihan ramah anak dan non-pedas: mie minyak wijen gurih dengan ayam cincang dan selada segar.',
                'price' => 13000,
                'image_url' => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 3,
            ],

            // Dimsum
            [
                'category_id' => $catDimsum->id,
                'name' => 'Pangsit Goreng Renyah (5 pcs)',
                'slug' => 'pangsit-goreng-renyah-5-pcs',
                'description' => 'Kulit pangsit keemasan isi adonan ayam udang lembut, disajikan dengan saus bangkok asam manis.',
                'price' => 12000,
                'image_url' => 'https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => true,
                'sort_order' => 4,
            ],
            [
                'category_id' => $catDimsum->id,
                'name' => 'Udang Keju Lumer (3 pcs)',
                'slug' => 'udang-keju-lumer-3-pcs',
                'description' => 'Olahan udang segar cincang berisi keju mozarella meleleh saat digigit renyah di mulut.',
                'price' => 11500,
                'image_url' => 'https://images.unsplash.com/photo-1496116218417-1a781b1c416c?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => true,
                'sort_order' => 5,
            ],
            [
                'category_id' => $catDimsum->id,
                'name' => 'Udang Rambutan Crispy (3 pcs)',
                'slug' => 'udang-rambutan-crispy-3-pcs',
                'description' => 'Bola olahan udang ayam dibalut serabut pangsit kriuk renyah maksimal.',
                'price' => 11500,
                'image_url' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 6,
            ],
            [
                'category_id' => $catDimsum->id,
                'name' => 'Siomay Ayam Kukus (3 pcs)',
                'slug' => 'siomay-ayam-kukus-3-pcs',
                'description' => 'Dimsum siomay ayam lembut berbalut kulit tipis dengan parutan wortel manis kukus higienis.',
                'price' => 10500,
                'image_url' => 'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 7,
            ],

            // Minuman
            [
                'category_id' => $catDrink->id,
                'name' => 'Es Gobak Sodor Segar',
                'slug' => 'es-gobak-sodor-segar',
                'description' => 'Perpaduan sirup buah tropis merah, cincau hitam kenyal, nata de coco, dan es serut dingin peredam pedas.',
                'price' => 9500,
                'image_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => true,
                'sort_order' => 8,
            ],
            [
                'category_id' => $catDrink->id,
                'name' => 'Es Teklek Cincau Selasih',
                'slug' => 'es-teklek-cincau-selasih',
                'description' => 'Kombinasi cincau hitam lembut, biji selasih dingin, dan kuah santan gula kelapa murni.',
                'price' => 8500,
                'image_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 9,
            ],
            [
                'category_id' => $catDrink->id,
                'name' => 'Es Teh Manis Melati Jumbo',
                'slug' => 'es-teh-manis-melati-jumbo',
                'description' => 'Teh seduh daun melati wangi khas nusantara disajikan manis segar dingin dalam gelas jumbo.',
                'price' => 5000,
                'image_url' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 10,
            ],
            [
                'category_id' => $catDrink->id,
                'name' => 'Kopi Susu Gula Aren Asli',
                'slug' => 'kopi-susu-gula-aren-asli',
                'description' => 'Espresso robusta mantap berpadu susu segar gurih dan lelehan gula aren organik kelapa.',
                'price' => 13000,
                'image_url' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => true,
                'sort_order' => 11,
            ],

            // Kombo
            [
                'category_id' => $catKombo->id,
                'name' => 'Kombo Juara: Mie Hompimpa + Pangsit + Es Teh',
                'slug' => 'kombo-juara-mie-pangsit-esteh',
                'description' => 'Paket paling digemari: 1 Mie Hompimpa (Pilih Level), 1 Porsi Pangsit Goreng Renyah, dan 1 Es Teh Jumbo.',
                'price' => 28500,
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&auto=format&fit=crop&q=80',
                'is_available' => true,
                'has_spicy_levels' => true,
                'is_favorite' => true,
                'sort_order' => 12,
            ],
        ];

        foreach ($menus as $m) {
            Menu::create($m);
        }

        // Tables (Table 01 to Table 20)
        for ($i = 1; $i <= 20; $i++) {
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            CafeTable::create([
                'table_number' => $num,
                'capacity' => 4,
                'qr_payload' => CafeTable::signedOrderUrl($num),
                'is_active' => true,
            ]);
        }

        // Sample Orders for immediate operational testing
        // Order 1: Pending Payment (Kasir testing)
        $ord1 = Order::create([
            'order_code' => 'ORD-' . date('Ymd') . '-001',
            'table_number' => '04',
            'customer_name' => 'Budi Santoso',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_method' => 'qris',
            'total_amount' => 41000,
            'notes' => 'Tolong pangsitnya dipisah sausnya',
            'created_at' => now()->subMinutes(6),
        ]);
        OrderItem::create([
            'order_id' => $ord1->id,
            'menu_id' => 1,
            'menu_name' => 'Mie Hompimpa Pedas Asin',
            'price' => 14500,
            'quantity' => 2,
            'spicy_level' => 3,
            'notes' => 'Level 3 pedas',
            'subtotal' => 29000,
        ]);
        OrderItem::create([
            'order_id' => $ord1->id,
            'menu_id' => 4,
            'menu_name' => 'Pangsit Goreng Renyah (5 pcs)',
            'price' => 12000,
            'quantity' => 1,
            'spicy_level' => 0,
            'notes' => 'Saus pisah',
            'subtotal' => 12000,
        ]);

        // Order 2: Paid / Preparing in Kitchen
        $ord2 = Order::create([
            'order_code' => 'ORD-' . date('Ymd') . '-002',
            'table_number' => '07',
            'customer_name' => 'Siti Rahma',
            'status' => Order::STATUS_PREPARING,
            'payment_method' => 'cash',
            'total_amount' => 38000,
            'notes' => 'Jangan pakai daun bawang',
            'paid_at' => now()->subMinutes(12),
            'created_at' => now()->subMinutes(15),
        ]);
        OrderItem::create([
            'order_id' => $ord2->id,
            'menu_id' => 2,
            'menu_name' => 'Mie Gacoan Pedas Manis',
            'price' => 14500,
            'quantity' => 1,
            'spicy_level' => 2,
            'notes' => 'Tanpa daun bawang',
            'subtotal' => 14500,
        ]);
        OrderItem::create([
            'order_id' => $ord2->id,
            'menu_id' => 5,
            'menu_name' => 'Udang Keju Lumer (3 pcs)',
            'price' => 11500,
            'quantity' => 1,
            'spicy_level' => 0,
            'notes' => null,
            'subtotal' => 11500,
        ]);
        OrderItem::create([
            'order_id' => $ord2->id,
            'menu_id' => 8,
            'menu_name' => 'Es Gobak Sodor Segar',
            'price' => 9500,
            'quantity' => 1,
            'spicy_level' => 0,
            'notes' => 'Es sedikit',
            'subtotal' => 9500,
        ]);

        // Order 3: Completed
        $ord3 = Order::create([
            'order_code' => 'ORD-' . date('Ymd') . '-003',
            'table_number' => '12',
            'customer_name' => 'Dimas Arya',
            'status' => Order::STATUS_COMPLETED,
            'payment_method' => 'qris',
            'total_amount' => 28500,
            'notes' => null,
            'paid_at' => now()->subMinutes(45),
            'completed_at' => now()->subMinutes(20),
            'created_at' => now()->subMinutes(50),
        ]);
        OrderItem::create([
            'order_id' => $ord3->id,
            'menu_id' => 12,
            'menu_name' => 'Kombo Juara: Mie Hompimpa + Pangsit + Es Teh',
            'price' => 28500,
            'quantity' => 1,
            'spicy_level' => 4,
            'notes' => 'Mie Level 4',
            'subtotal' => 28500,
        ]);
    }
}
