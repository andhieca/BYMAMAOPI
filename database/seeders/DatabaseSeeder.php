<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DailyQuota;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@bymamaopi.com'],
            [
                'name' => 'Mama Opi Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Store Settings
        $settings = [
            'store_name' => ['BYMAMAOPI', 'Nama Brand Toko', 'general'],
            'store_tagline' => ['Artisan Cookies, Cake, Bolu & Brownies', 'Slogan / Tagline Toko', 'general'],
            'store_whatsapp' => ['6281234567890', 'Nomor WhatsApp Pemesanan (Admin)', 'contact'],
            'store_address' => ['Jl. Ranca Manyar No. 88, Kitchen House Lt. 1, Bandung', 'Alamat Kitchen / Base Toko', 'contact'],
            'store_city' => ['Bandung', 'Kota Kitchen', 'contact'],
            'store_operating_hours' => ['Senin - Minggu: 08.00 - 18.00 WIB', 'Jam Operasional Kitchen', 'general'],
            'default_daily_quota' => ['15', 'Kapasitas Kuota Default Harian (Pesanan)', 'order'],
            'min_order_lead_days' => ['1', 'Minimum Lead Time Pre-Order (Hari)', 'order'],
            'bank_name' => ['BCA', 'Nama Bank Pembayaran', 'payment'],
            'bank_account_number' => ['8290123456', 'Nomor Rekening Bank', 'payment'],
            'bank_account_holder' => ['MAMA OPI ARTISAN BAKERY', 'Nama Pemilik Rekening', 'payment'],
            'qris_image' => ['images/qris-bymamaopi.jpg', 'Gambar Barcode QRIS Toko', 'payment'],
            'qris_info' => ['Tersedia QRIS statis & dinamis via WhatsApp', 'Informasi QRIS', 'payment'],
            'pickup_note' => ['Pesanan dapat diambil mandiri di kitchen kami pada slot jam yang telah dipilih. Tunjukkan nomor pesanan pada kasir/baker.', 'Petunjuk Pengambilan Self Pickup', 'order'],
            'courier_note' => ['Pengiriman menggunakan Gosend / GrabExpress Instant / Paxel Next Day. Pemesan dapat memanggil kurir sendiri atau admin siap bantu pesankan setelah konfirmasi.', 'Petunjuk Pengiriman Kurir', 'order'],
        ];

        foreach ($settings as $key => [$value, $label, $group]) {
            StoreSetting::set($key, $value, $label, $group);
        }

        // 3. Categories
        $categoriesData = [
            [
                'name' => 'Cookies',
                'slug' => 'cookies',
                'icon' => 'cookie',
                'description' => 'Chewy & crunchy NYC style cookies dengan 100% Belgian chocolate & Wisman butter',
                'sort_order' => 1,
            ],
            [
                'name' => 'Cake',
                'slug' => 'cake',
                'icon' => 'cake',
                'description' => 'Custom & celebratory cakes lembut dengan bahan premium dan desain elegan',
                'sort_order' => 2,
            ],
            [
                'name' => 'Bolu',
                'slug' => 'bolu',
                'icon' => 'bread',
                'description' => 'Bolu jadul keju gondrong & bolu gulung nougat legendaris resep turun-temurun',
                'sort_order' => 3,
            ],
            [
                'name' => 'Brownies',
                'slug' => 'brownies',
                'icon' => 'square',
                'description' => 'Fudgy shiny crust brownies lezat dengan limpahan topping premium pilihan',
                'sort_order' => 4,
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $cat) {
            $categoryModels[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Products
        $productsData = [
            // Cookies
            [
                'category_id' => $categoryModels['cookies']->id,
                'name' => 'NYC Classic Choco Chip Cookie',
                'slug' => 'nyc-classic-choco-chip-cookie',
                'description' => 'Jumbo New York style cookies bertekstur crispy di luar dan super chewy di dalam, dengan lelehan belgian dark chocolate melimpah.',
                'price' => 35000,
                'image' => 'images/cookies.jpg',
                'badge' => 'Best Seller',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryModels['cookies']->id,
                'name' => 'Red Velvet Cream Cheese Cookie',
                'slug' => 'red-velvet-cream-cheese-cookie',
                'description' => 'Adonan red velvet beraroma butter khas dengan melted cream cheese anchor di bagian tengah.',
                'price' => 38000,
                'image' => 'images/cookies.jpg',
                'badge' => 'Signature',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryModels['cookies']->id,
                'name' => 'Lotus Biscoff Crumble Cookie',
                'slug' => 'lotus-biscoff-crumble-cookie',
                'description' => 'Chewy cookie dengan isian spread Lotus hangat dan taburan remahan biskuit karamel Lotus renyah.',
                'price' => 38000,
                'image' => 'images/cookies.jpg',
                'badge' => 'Favorit',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'category_id' => $categoryModels['cookies']->id,
                'name' => 'Nastar Butter Wisman Box 500gr',
                'slug' => 'nastar-butter-wisman-box-500gr',
                'description' => 'Nastar lumer lembut dengan 100% Wijsman butter asli dan selai nanas segar homemade manis legit.',
                'price' => 135000,
                'image' => 'images/cookies.jpg',
                'badge' => 'Spesial',
                'is_featured' => true,
                'sort_order' => 4,
            ],

            // Cake
            [
                'category_id' => $categoryModels['cake']->id,
                'name' => 'Signature Belgian Chocolate Fudge Cake',
                'slug' => 'signature-belgian-chocolate-fudge-cake',
                'description' => 'Kue cokelat tiga lapis dengan ganache cokelat Callebaut Belgia 70% dan taburan edible gold leaf mewah (D: 16cm).',
                'price' => 220000,
                'image' => 'images/cake.jpg',
                'badge' => 'Best Seller',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryModels['cake']->id,
                'name' => 'Korean Aesthetic Bento Cake (10cm)',
                'slug' => 'korean-aesthetic-bento-cake',
                'description' => 'Mini lunchbox cake aesthetic bergaya Korea. Bebas request tulisan ucapan cantik untuk surprise orang tersayang.',
                'price' => 95000,
                'image' => 'images/cake.jpg',
                'badge' => 'Trending',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryModels['cake']->id,
                'name' => 'Tiramisu Mascarpone Tres Leches',
                'slug' => 'tiramisu-mascarpone-tres-leches',
                'description' => 'Perpaduan ladyfinger berselimut espresso blend Arabika, krim mascarpone lembut Italia, dan bubuk cokelat Valrhona.',
                'price' => 240000,
                'image' => 'images/cake.jpg',
                'badge' => 'Signature',
                'is_featured' => false,
                'sort_order' => 3,
            ],

            // Bolu
            [
                'category_id' => $categoryModels['bolu']->id,
                'name' => 'Bolu Jadul Keju Gondrong (20x20cm)',
                'slug' => 'bolu-jadul-keju-gondrong',
                'description' => 'Sponge cake jadul ekstra lembut dengan olesan buttercream lembut gurih dan limpahan parutan keju cheddar melimpah ruah.',
                'price' => 95000,
                'image' => 'images/bolu.jpg',
                'badge' => 'Best Seller',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryModels['bolu']->id,
                'name' => 'Bolu Gulung Nougat Mocha Premium',
                'slug' => 'bolu-gulung-nougat-mocha-premium',
                'description' => 'Bolu gulung aroma moka asli bertabur karamel kacang nougat garing yang renyah dan wangi butter.',
                'price' => 115000,
                'image' => 'images/bolu.jpg',
                'badge' => 'Favorit',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryModels['bolu']->id,
                'name' => 'Bolu Karamel Sarang Semut Legit',
                'slug' => 'bolu-karamel-sarang-semut-legit',
                'description' => 'Bolu karamel tradisional berongga sarang semut sempurna, kenyal, legit, dan tidak pahit.',
                'price' => 85000,
                'image' => 'images/bolu.jpg',
                'badge' => null,
                'is_featured' => false,
                'sort_order' => 3,
            ],

            // Brownies
            [
                'category_id' => $categoryModels['brownies']->id,
                'name' => 'Fudgy Shiny Crust Brownies (25 Sekat)',
                'slug' => 'fudgy-shiny-crust-brownies-25-sekat',
                'description' => 'Ukuran 20x20cm isi 25 sekat siap santap dengan 5 aneka topping: Roasted Almond, Chocochip, Cheese, Lotus Biscoff, & Oreo.',
                'price' => 110000,
                'image' => 'images/brownies.jpg',
                'badge' => 'Best Seller',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_id' => $categoryModels['brownies']->id,
                'name' => 'Cream Cheese Swirl Brownies',
                'slug' => 'cream-cheese-swirl-brownies',
                'description' => 'Fudgy brownies tebal dengan perpaduan marmer cream cheese gurih asin yang mengimbangi legitnya dark chocolate Belgia.',
                'price' => 95000,
                'image' => 'images/brownies.jpg',
                'badge' => 'Signature',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_id' => $categoryModels['brownies']->id,
                'name' => 'Nutella Melted Brownies Bar',
                'slug' => 'nutella-melted-brownies-bar',
                'description' => 'Brownies lembut lumer dengan lelehan selai hazelnut Nutella murni di setiap gigitannya.',
                'price' => 90000,
                'image' => 'images/brownies.jpg',
                'badge' => null,
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        $productModels = [];
        foreach ($productsData as $prod) {
            $productModels[] = Product::firstOrCreate(['slug' => $prod['slug']], $prod);
        }

        // 5. Daily Quotas for next 14 days
        $today = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i);
            $dateStr = $date->toDateString();

            DailyQuota::updateOrCreate(
                ['date' => $dateStr],
                [
                    'max_quota' => 15,
                    'booked_count' => 0,
                    'is_closed' => false,
                    'close_reason' => null,
                ]
            );
        }

        // 6. Sample Orders
        $order1 = Order::firstOrCreate(
            ['order_code' => 'BMO-20260924-001'],
            [
                'customer_name' => 'Clarissa Putri',
                'customer_phone' => '081298765432',
                'delivery_method' => 'pickup',
                'delivery_address' => null,
                'delivery_date' => $today->copy()->addDays(1)->toDateString(),
                'delivery_time_slot' => '13.00 - 15.00 WIB',
                'greeting_notes' => 'Tolong lilin 2 batang ya kak, terima kasih!',
                'subtotal' => 255000,
                'delivery_fee' => 0,
                'total_amount' => 255000,
                'status' => 'processing',
                'payment_method' => 'whatsapp_manual',
                'payment_status' => 'paid',
                'admin_notes' => 'Pembayaran via transfer BCA terverifikasi.',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_name' => 'Signature Belgian Chocolate Fudge Cake'],
            [
                'product_id' => $productModels[4]->id,
                'price' => 220000,
                'quantity' => 1,
                'subtotal' => 220000,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_name' => 'NYC Classic Choco Chip Cookie'],
            [
                'product_id' => $productModels[0]->id,
                'price' => 35000,
                'quantity' => 1,
                'subtotal' => 35000,
            ]
        );

        $order2 = Order::firstOrCreate(
            ['order_code' => 'BMO-20260924-002'],
            [
                'customer_name' => 'Dimas Anggara',
                'customer_phone' => '087712349988',
                'delivery_method' => 'courier',
                'delivery_address' => 'Jl. Dago Asri No. 15B, Coblong, Kota Bandung',
                'delivery_date' => $today->copy()->addDays(2)->toDateString(),
                'delivery_time_slot' => '10.00 - 12.00 WIB',
                'greeting_notes' => 'Tolong tulis di kartu: "Happy Sweet 17th Kirana! Stay sweet & awesome"',
                'subtotal' => 205000,
                'delivery_fee' => 20000,
                'total_amount' => 225000,
                'status' => 'pending_payment',
                'payment_method' => 'whatsapp_manual',
                'payment_status' => 'unpaid',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_name' => 'Korean Aesthetic Bento Cake (10cm)'],
            [
                'product_id' => $productModels[5]->id,
                'price' => 95000,
                'quantity' => 1,
                'subtotal' => 95000,
            ]
        );
        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_name' => 'Fudgy Shiny Crust Brownies (25 Sekat)'],
            [
                'product_id' => $productModels[8]->id,
                'price' => 110000,
                'quantity' => 1,
                'subtotal' => 110000,
            ]
        );
    }
}
