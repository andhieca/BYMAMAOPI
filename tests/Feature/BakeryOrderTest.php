<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BakeryOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        StoreSetting::set('store_whatsapp', '6281234567890');
        StoreSetting::set('default_daily_quota', 15);
        StoreSetting::set('min_order_lead_days', 1);

        $cat = Category::create([
            'name' => 'Cookies',
            'slug' => 'cookies',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $cat->id,
            'name' => 'NYC Classic Cookie',
            'slug' => 'nyc-classic-cookie',
            'price' => 35000,
            'is_available' => true,
        ]);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('BYMAMAOPI');
        $response->assertSee('NYC Classic Cookie');
    }

    public function test_calendar_api_returns_dates_and_quotas(): void
    {
        $response = $this->getJson('/api/calendar-data?month='.now()->month.'&year='.now()->year);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'month',
            'year',
            'days' => [
                '*' => ['day', 'date', 'is_selectable', 'max_quota', 'booked_count', 'remaining'],
            ],
        ]);
    }

    public function test_customer_can_create_order_and_receive_whatsapp_link(): void
    {
        $product = Product::first();
        $targetDate = Carbon::today()->addDays(2)->toDateString();

        $response = $this->postJson('/order/create', [
            'customer_name' => 'Siti Nurhaliza',
            'customer_phone' => '081234567899',
            'delivery_method' => 'pickup',
            'delivery_date' => $targetDate,
            'delivery_time_slot' => '10.00 - 12.00 WIB',
            'greeting_notes' => 'Tolong lilin ya',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'order_code',
            'whatsapp_url',
            'redirect_url',
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Siti Nurhaliza',
            'total_amount' => 70000,
        ]);
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@bymamaopi.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->post('/admin/login', [
            'email' => 'admin@bymamaopi.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin);
        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Ringkasan & Metrik Bisnis');
    }
}
