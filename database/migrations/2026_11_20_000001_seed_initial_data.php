<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Insert default category if not exists
        DB::table('categories')->updateOrInsert(
            ['slug' => 'default'],
            [
                'name' => 'Default',
                'description' => 'Default category',
                'sort_order' => 0,
                'is_active' => true,
                'slug' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Insert default menu item matching test expectation
        DB::table('menus')->updateOrInsert(
            ['name' => 'Mie Nusantara'],
            [
                'category_id' => DB::table('categories')->where('slug', 'default')->value('id'),
                'name' => 'Mie Nusantara',
                'description' => 'Delicious noodles',
                'price' => 25000,
                'is_available' => true,
                'has_spicy_levels' => false,
                'is_favorite' => false,
                'sort_order' => 0,
                'slug' => 'mie-nusantara',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Insert default active table with number '05'
        DB::table('tables')->updateOrInsert(
            ['table_number' => '05'],
            [
                'capacity' => 4,
                'qr_payload' => 'signed_url_placeholder', // placeholder, not used in tests
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

    }

    public function down(): void
    {
        DB::table('menus')->where('name', 'Mie Nusantara')->delete();
        DB::table('categories')->where('name', 'Default')->delete();
        DB::table('tables')->where('table_number', '05')->delete();
    }
};
