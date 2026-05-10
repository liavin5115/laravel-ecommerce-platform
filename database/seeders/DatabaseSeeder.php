<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Role;
use App\Models\Shipment;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles & Permissions ──────────────────────────────
        $adminRole = Role::create(['id' => Str::uuid(), 'name' => 'admin', 'guard_name' => 'web']);
        $staffRole = Role::create(['id' => Str::uuid(), 'name' => 'staff', 'guard_name' => 'web']);
        $viewerRole = Role::create(['id' => Str::uuid(), 'name' => 'viewer', 'guard_name' => 'web']);

        $permissions = collect([
            'manage-products', 'manage-orders', 'manage-customers',
            'manage-coupons', 'manage-settings', 'view-analytics',
            'manage-inventory', 'manage-support-tickets',
        ])->map(fn ($name) => Permission::create(['id' => Str::uuid(), 'name' => $name, 'guard_name' => 'web']));

        // Assign all permissions to admin
        $adminRole->permissions()->attach($permissions->pluck('id'));
        // Staff gets limited
        $staffRole->permissions()->attach($permissions->take(4)->pluck('id'));

        // ── Users ────────────────────────────────────────────
        $admin = User::create([
            'id' => Str::uuid(),
            'name' => 'Admin Marketplace',
            'email' => 'admin@marketplace.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        $staffUser = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@marketplace.test',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // ── Organization ─────────────────────────────────────
        $org = Organization::create([
            'name' => 'TechVibe Inc.',
            'slug' => 'techvibe',
            'email' => 'hello@techvibe.test',
            'phone' => '+1-555-0100',
            'plan_type' => 'pro',
            'is_active' => true,
        ]);

        $org2 = Organization::create([
            'name' => 'Creative Goods Co.',
            'slug' => 'creative-goods',
            'email' => 'info@creativegoods.test',
            'phone' => '+1-555-0200',
            'plan_type' => 'basic',
            'is_active' => true,
        ]);

        // Memberships (pivot table, no uuid PK)
        \Illuminate\Support\Facades\DB::table('organization_user')->insert([
            ['organization_id' => $org->id, 'user_id' => $admin->id, 'role_id' => $adminRole->id, 'joined_at' => now()->subMonths(6)],
            ['organization_id' => $org->id, 'user_id' => $staffUser->id, 'role_id' => $staffRole->id, 'joined_at' => now()->subMonths(2)],
            ['organization_id' => $org2->id, 'user_id' => $admin->id, 'role_id' => $adminRole->id, 'joined_at' => now()->subMonths(3)],
        ]);

        // ── Subscriptions ────────────────────────────────────
        Subscription::create([
            'organization_id' => $org->id,
            'provider' => 'stripe',
            'provider_subscription_id' => 'sub_' . Str::random(14),
            'status' => 'active',
            'monthly_price' => 49.99,
            'trial_ends_at' => null,
            'renews_at' => now()->addMonth(),
        ]);

        // ── Stores ───────────────────────────────────────────
        $store1 = Store::create([
            'organization_id' => $org->id,
            'name' => 'TechVibe Electronics',
            'slug' => 'techvibe-electronics',
            'description' => 'Premium electronics and gaming accessories.',
            'is_active' => true,
        ]);
        $store2 = Store::create([
            'organization_id' => $org->id,
            'name' => 'TechVibe Lifestyle',
            'slug' => 'techvibe-lifestyle',
            'description' => 'Modern lifestyle products for everyday use.',
            'is_active' => true,
        ]);
        $store3 = Store::create([
            'organization_id' => $org2->id,
            'name' => 'Creative Studio',
            'slug' => 'creative-studio',
            'description' => 'Handcrafted creative tools and supplies.',
            'is_active' => true,
        ]);

        // ── Categories ───────────────────────────────────────
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $gaming = Category::create(['name' => 'Gaming', 'slug' => 'gaming', 'parent_id' => $electronics->id]);
        $audio = Category::create(['name' => 'Audio', 'slug' => 'audio', 'parent_id' => $electronics->id]);
        $lifestyle = Category::create(['name' => 'Lifestyle', 'slug' => 'lifestyle']);
        $accessories = Category::create(['name' => 'Accessories', 'slug' => 'accessories', 'parent_id' => $lifestyle->id]);
        $artSupplies = Category::create(['name' => 'Art Supplies', 'slug' => 'art-supplies']);

        // ── Products + Variants + Images ─────────────────────
        $productsData = [
            [
                'store' => $store1, 'category' => $gaming,
                'name' => 'Pro Gaming Keyboard', 'slug' => 'pro-gaming-keyboard',
                'description' => 'Mechanical gaming keyboard with RGB backlighting, hot-swappable switches, and aluminum frame.',
                'product_type' => 'physical', 'price' => 149.99, 'compare_price' => 199.99,
                'variants' => [
                    ['name' => 'Red Switch', 'sku' => 'KB-RED-001', 'price' => 149.99, 'stock' => 45, 'weight' => 1.2],
                    ['name' => 'Blue Switch', 'sku' => 'KB-BLUE-001', 'price' => 149.99, 'stock' => 32, 'weight' => 1.2],
                    ['name' => 'Brown Switch', 'sku' => 'KB-BROWN-001', 'price' => 159.99, 'stock' => 28, 'weight' => 1.2],
                ],
            ],
            [
                'store' => $store1, 'category' => $gaming,
                'name' => 'Ultra Gaming Mouse', 'slug' => 'ultra-gaming-mouse',
                'description' => 'Lightweight wireless gaming mouse with 25K DPI sensor and 80-hour battery life.',
                'product_type' => 'physical', 'price' => 79.99, 'compare_price' => 99.99,
                'variants' => [
                    ['name' => 'Black', 'sku' => 'MS-BLK-001', 'price' => 79.99, 'stock' => 120, 'weight' => 0.08],
                    ['name' => 'White', 'sku' => 'MS-WHT-001', 'price' => 79.99, 'stock' => 85, 'weight' => 0.08],
                ],
            ],
            [
                'store' => $store1, 'category' => $audio,
                'name' => 'Studio Wireless Headphones', 'slug' => 'studio-wireless-headphones',
                'description' => 'Active noise-cancelling wireless headphones with Hi-Res Audio support.',
                'product_type' => 'physical', 'price' => 299.99, 'compare_price' => 349.99,
                'variants' => [
                    ['name' => 'Matte Black', 'sku' => 'HP-MBLK-001', 'price' => 299.99, 'stock' => 60, 'weight' => 0.35],
                    ['name' => 'Silver', 'sku' => 'HP-SLV-001', 'price' => 299.99, 'stock' => 45, 'weight' => 0.35],
                ],
            ],
            [
                'store' => $store1, 'category' => $gaming,
                'name' => 'RGB Gaming Mousepad XL', 'slug' => 'rgb-gaming-mousepad-xl',
                'description' => 'Extended RGB mousepad with micro-textured surface and USB-C connection.',
                'product_type' => 'physical', 'price' => 39.99, 'compare_price' => null,
                'variants' => [
                    ['name' => 'XL (900x400mm)', 'sku' => 'MP-XL-001', 'price' => 39.99, 'stock' => 200, 'weight' => 0.5],
                ],
            ],
            [
                'store' => $store2, 'category' => $accessories,
                'name' => 'Minimalist Leather Wallet', 'slug' => 'minimalist-leather-wallet',
                'description' => 'Handcrafted genuine leather wallet with RFID blocking technology.',
                'product_type' => 'physical', 'price' => 59.99, 'compare_price' => 79.99,
                'variants' => [
                    ['name' => 'Cognac', 'sku' => 'WL-COG-001', 'price' => 59.99, 'stock' => 75, 'weight' => 0.1],
                    ['name' => 'Jet Black', 'sku' => 'WL-BLK-001', 'price' => 59.99, 'stock' => 90, 'weight' => 0.1],
                ],
            ],
            [
                'store' => $store2, 'category' => $lifestyle,
                'name' => 'Smart Water Bottle', 'slug' => 'smart-water-bottle',
                'description' => 'Temperature-tracking insulated water bottle with LED display.',
                'product_type' => 'physical', 'price' => 34.99, 'compare_price' => null,
                'variants' => [
                    ['name' => '500ml - White', 'sku' => 'WB-500W-001', 'price' => 34.99, 'stock' => 150, 'weight' => 0.35],
                    ['name' => '750ml - Black', 'sku' => 'WB-750B-001', 'price' => 39.99, 'stock' => 110, 'weight' => 0.45],
                ],
            ],
            [
                'store' => $store3, 'category' => $artSupplies,
                'name' => 'Professional Brush Set', 'slug' => 'professional-brush-set',
                'description' => 'Set of 12 professional-grade artist brushes with ergonomic handles.',
                'product_type' => 'physical', 'price' => 45.00, 'compare_price' => 60.00,
                'variants' => [
                    ['name' => 'Watercolor Set', 'sku' => 'BR-WC-001', 'price' => 45.00, 'stock' => 40, 'weight' => 0.3],
                    ['name' => 'Oil Painting Set', 'sku' => 'BR-OIL-001', 'price' => 49.99, 'stock' => 35, 'weight' => 0.35],
                ],
            ],
            [
                'store' => $store3, 'category' => $artSupplies,
                'name' => 'Premium Sketchbook A4', 'slug' => 'premium-sketchbook-a4',
                'description' => '200-page acid-free heavyweight paper sketchbook with lay-flat binding.',
                'product_type' => 'physical', 'price' => 24.99, 'compare_price' => null,
                'variants' => [
                    ['name' => 'Plain', 'sku' => 'SK-PLN-001', 'price' => 24.99, 'stock' => 300, 'weight' => 0.8],
                    ['name' => 'Dotted Grid', 'sku' => 'SK-DOT-001', 'price' => 26.99, 'stock' => 250, 'weight' => 0.8],
                ],
            ],
            [
                'store' => $store1, 'category' => $gaming,
                'name' => 'RGB Mechanical Gaming Chair', 'slug' => 'rgb-gaming-chair',
                'description' => 'Ergonomic gaming chair with integrated RGB lighting and memory foam cushions.',
                'product_type' => 'physical', 'price' => 299.00, 'compare_price' => 399.00,
                'variants' => [
                    ['name' => 'Carbon Fiber', 'sku' => 'CH-RGB-CAR', 'price' => 299.00, 'stock' => 15, 'weight' => 25.0],
                ],
            ],
            [
                'store' => $store1, 'category' => $electronics,
                'name' => 'Ultra-Slim 4K Monitor', 'slug' => 'slim-4k-monitor',
                'description' => '27-inch 4K IPS display with 144Hz refresh rate and USB-C power delivery.',
                'product_type' => 'physical', 'price' => 449.99, 'compare_price' => 549.99,
                'variants' => [
                    ['name' => '27 Inch', 'sku' => 'MON-4K-27', 'price' => 449.99, 'stock' => 20, 'weight' => 5.5],
                ],
            ],
            [
                'store' => $store2, 'category' => $lifestyle,
                'name' => 'Electric Coffee Grinder', 'slug' => 'electric-coffee-grinder',
                'description' => 'Precision burr grinder with 40 grind settings for the perfect brew.',
                'product_type' => 'physical', 'price' => 89.00, 'compare_price' => 120.00,
                'variants' => [
                    ['name' => 'Stainless Steel', 'sku' => 'CF-GRD-SS', 'price' => 89.00, 'stock' => 50, 'weight' => 2.1],
                ],
            ],
            [
                'store' => $store2, 'category' => $lifestyle,
                'name' => 'Bamboo Office Desk', 'slug' => 'bamboo-desk',
                'description' => 'Eco-friendly bamboo standing desk with motorized height adjustment.',
                'product_type' => 'physical', 'price' => 399.00, 'compare_price' => 499.00,
                'variants' => [
                    ['name' => '120x60cm', 'sku' => 'DSK-BAM-120', 'price' => 399.00, 'stock' => 10, 'weight' => 30.0],
                ],
            ],
            [
                'store' => $store1, 'category' => $audio,
                'name' => 'Noise Cancelling Earbuds', 'slug' => 'nc-earbuds',
                'description' => 'True wireless earbuds with industry-leading noise cancellation and spatial audio.',
                'product_type' => 'physical', 'price' => 199.99, 'compare_price' => 249.99,
                'variants' => [
                    ['name' => 'Midnight Blue', 'sku' => 'EB-NC-BLU', 'price' => 199.99, 'stock' => 100, 'weight' => 0.05],
                ],
            ],
            [
                'store' => $store3, 'category' => $artSupplies,
                'name' => 'Graphic Drawing Tablet', 'slug' => 'drawing-tablet',
                'description' => 'Professional pen display with 2K resolution and 8192 levels of pressure sensitivity.',
                'product_type' => 'physical', 'price' => 599.00, 'compare_price' => 699.00,
                'variants' => [
                    ['name' => '16-inch Pro', 'sku' => 'TAB-DRW-16', 'price' => 599.00, 'stock' => 12, 'weight' => 1.8],
                ],
            ],
            [
                'store' => $store1, 'category' => $electronics,
                'name' => 'Portable Power Bank 20k', 'slug' => 'power-bank-20k',
                'description' => 'High-capacity 20,000mAh power bank with 65W PD fast charging.',
                'product_type' => 'physical', 'price' => 65.00, 'compare_price' => 85.00,
                'variants' => [
                    ['name' => 'Black Stealth', 'sku' => 'PB-20K-BLK', 'price' => 65.00, 'stock' => 200, 'weight' => 0.4],
                ],
            ],
        ];

        // Add 12 more generic products to fill the space
        for ($i = 1; $i <= 12; $i++) {
            $productsData[] = [
                'store' => $i % 2 == 0 ? $store2 : $store1,
                'category' => $i % 3 == 0 ? $lifestyle : ($i % 2 == 0 ? $gaming : $electronics),
                'name' => 'Product Variation ' . $i,
                'slug' => 'product-variation-' . $i,
                'description' => 'A high-quality marketplace item designed for everyday use and maximum performance.',
                'product_type' => 'physical',
                'price' => rand(20, 200) + 0.99,
                'compare_price' => rand(210, 300),
                'variants' => [
                    ['name' => 'Standard Edition', 'sku' => 'PV-' . $i . '-STD', 'price' => rand(20, 200) + 0.99, 'stock' => rand(10, 100), 'weight' => 1.0],
                ],
            ];
        }

        $allVariants = collect();

        foreach ($productsData as $pd) {
            $product = Product::create([
                'store_id' => $pd['store']->id,
                'category_id' => $pd['category']->id,
                'name' => $pd['name'],
                'slug' => $pd['slug'],
                'description' => $pd['description'],
                'product_type' => $pd['product_type'],
                'price' => $pd['price'],
                'compare_price' => $pd['compare_price'],
                'is_active' => true,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'https://picsum.photos/seed/' . $pd['slug'] . '/600/450',
                'sort_order' => 0,
            ]);

            foreach ($pd['variants'] as $v) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $v['sku'],
                    'name' => $v['name'],
                    'price' => $v['price'],
                    'stock_quantity' => $v['stock'],
                    'weight' => $v['weight'],
                ]);
                $allVariants->push($variant);

                InventoryMovement::create([
                    'product_variant_id' => $variant->id,
                    'movement_type' => 'restock',
                    'quantity' => $v['stock'],
                    'reference_type' => 'initial_stock',
                    'reference_id' => $variant->id,
                ]);
            }
        }

        // ── Customers ────────────────────────────────────────
        $customersData = [
            ['org' => $org, 'name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone' => '+1-555-0301'],
            ['org' => $org, 'name' => 'Bob Williams', 'email' => 'bob@example.com', 'phone' => '+1-555-0302'],
            ['org' => $org, 'name' => 'Charlie Davis', 'email' => 'charlie@example.com', 'phone' => '+1-555-0303'],
            ['org' => $org, 'name' => 'Diana Martinez', 'email' => 'diana@example.com', 'phone' => '+1-555-0304'],
            ['org' => $org, 'name' => 'Ethan Brown', 'email' => 'ethan@example.com', 'phone' => '+1-555-0305'],
            ['org' => $org2, 'name' => 'Fiona Clark', 'email' => 'fiona@example.com', 'phone' => '+1-555-0306'],
            ['org' => $org2, 'name' => 'George Wilson', 'email' => 'george@example.com', 'phone' => '+1-555-0307'],
        ];

        $customers = collect();
        foreach ($customersData as $cd) {
            $customer = Customer::create([
                'organization_id' => $cd['org']->id,
                'name' => $cd['name'],
                'email' => $cd['email'],
                'phone' => $cd['phone'],
            ]);
            $customers->push($customer);

            Address::create([
                'customer_id' => $customer->id,
                'label' => 'Home',
                'recipient_name' => $cd['name'],
                'phone' => $cd['phone'],
                'address_line' => fake()->streetAddress(),
                'city' => fake()->city(),
                'province' => fake()->state(),
                'postal_code' => fake()->postcode(),
                'country' => 'US',
                'is_default' => true,
            ]);
        }

        // ── Orders ───────────────────────────────────────────
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $address = $customer->addresses()->first();
            $status = $statuses[array_rand($statuses)];
            $variant = $allVariants->random();
            $qty = rand(1, 3);
            $unitPrice = (float) $variant->price;
            $subtotal = round($unitPrice * $qty, 2);
            $tax = round($subtotal * 0.1, 2);
            $grandTotal = round($subtotal + $tax, 2);

            $order = Order::create([
                'organization_id' => $customer->organization_id,
                'customer_id' => $customer->id,
                'address_id' => $address?->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_total' => $tax,
                'shipping_total' => 0,
                'discount_total' => 0,
                'grand_total' => $grandTotal,
                'placed_at' => now()->subDays(rand(0, 60)),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                'product_name' => $variant->product->name . ' - ' . $variant->name,
                'sku' => $variant->sku,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $subtotal,
            ]);

            // Payment for non-cancelled
            if ($status !== 'cancelled') {
                Payment::create([
                    'order_id' => $order->id,
                    'gateway' => collect(['stripe', 'paypal', 'manual'])->random(),
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                    'status' => $status === 'pending' ? 'pending' : 'paid',
                    'amount' => $grandTotal,
                    'gateway_payload' => json_encode(['ref' => Str::random(8)]),
                    'paid_at' => $status !== 'pending' ? now()->subDays(rand(0, 30)) : null,
                ]);
            }

            // Shipment for shipped/delivered
            if (in_array($status, ['shipped', 'delivered'])) {
                Shipment::create([
                    'order_id' => $order->id,
                    'courier' => collect(['DHL', 'FedEx', 'UPS', 'USPS'])->random(),
                    'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
                    'shipment_status' => $status === 'delivered' ? 'delivered' : 'in_transit',
                    'shipped_at' => now()->subDays(rand(1, 10)),
                    'delivered_at' => $status === 'delivered' ? now()->subDays(rand(0, 3)) : null,
                ]);
            }
        }

        // ── Coupons ──────────────────────────────────────────
        Coupon::create([
            'organization_id' => $org->id,
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_order' => 50,
            'usage_limit' => 100,
            'used_count' => 12,
            'expires_at' => now()->addMonths(3),
        ]);
        Coupon::create([
            'organization_id' => $org->id,
            'code' => 'FLAT20',
            'discount_type' => 'fixed',
            'discount_value' => 20,
            'minimum_order' => 100,
            'usage_limit' => 50,
            'used_count' => 5,
            'expires_at' => now()->addMonth(),
        ]);

        // ── Reviews ──────────────────────────────────────────
        $products = Product::all();
        foreach ($products->take(5) as $product) {
            foreach ($customers->random(rand(1, 3)) as $customer) {
                Review::create([
                    'product_id' => $product->id,
                    'customer_id' => $customer->id,
                    'rating' => rand(3, 5),
                    'review' => fake()->paragraph(),
                    'is_published' => true,
                ]);
            }
        }

        // ── Support Tickets ──────────────────────────────────
        $ticketSubjects = [
            'Order not received yet',
            'Wrong item delivered',
            'Request for refund',
            'Product quality issue',
        ];
        foreach (array_slice($ticketSubjects, 0, 3) as $subject) {
            $customer = $customers->random();
            $ticket = SupportTicket::create([
                'organization_id' => $customer->organization_id,
                'customer_id' => $customer->id,
                'subject' => $subject,
                'priority' => collect(['low', 'medium', 'high'])->random(),
                'status' => collect(['open', 'in_progress', 'closed'])->random(),
            ]);
            TicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'sender_user_id' => $admin->id,
                'message' => fake()->paragraph(),
            ]);
        }

        // ── Notifications ────────────────────────────────────
        \Illuminate\Support\Facades\DB::table('notifications')->insert([
            [
                'id' => (string) Str::uuid(),
                'type' => 'order.placed',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode(['message' => 'New order ORD-ABCD1234 placed.']),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'ticket.new',
                'notifiable_type' => User::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode(['message' => 'New support ticket: Order not received yet']),
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── Audit Logs ───────────────────────────────────────
        AuditLog::create([
            'organization_id' => $org->id,
            'user_id' => $admin->id,
            'event' => 'product.created',
            'auditable_type' => Product::class,
            'auditable_id' => $products->first()->id,
            'old_values' => null,
            'new_values' => json_encode(['name' => $products->first()->name]),
            'ip_address' => '127.0.0.1',
        ]);
    }
}
