<?php

$migrations = [
    '2026_05_09_160001_create_product_variants_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_variants', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('product_id');
            \$table->string('sku')->unique();
            \$table->string('name');
            \$table->decimal('price', 10, 2);
            \$table->integer('stock_quantity')->default(0);
            \$table->decimal('weight', 8, 2)->nullable();
            \$table->timestamps();

            \$table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('product_variants'); }
};
EOT,

    '2026_05_09_160002_create_inventory_movements_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_movements', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('product_variant_id');
            \$table->string('movement_type'); // purchase, refund, restock, adjustment, cancellation
            \$table->integer('quantity');
            \$table->string('reference_type')->nullable();
            \$table->uuid('reference_id')->nullable();
            \$table->timestamps();

            \$table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
            
            \$table->index(['product_variant_id', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('inventory_movements'); }
};
EOT,

    '2026_05_09_160003_create_customers_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->string('name');
            \$table->string('email')->nullable();
            \$table->string('phone')->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('customers'); }
};
EOT,

    '2026_05_09_160004_create_addresses_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('addresses', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('customer_id');
            \$table->string('label')->nullable();
            \$table->string('recipient_name');
            \$table->string('phone')->nullable();
            \$table->text('address_line');
            \$table->string('city');
            \$table->string('province');
            \$table->string('postal_code');
            \$table->string('country');
            \$table->boolean('is_default')->default(false);
            \$table->timestamps();

            \$table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('addresses'); }
};
EOT,

    '2026_05_09_160005_create_carts_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carts', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('customer_id')->nullable();
            \$table->timestamp('expires_at')->nullable();
            \$table->timestamps();

            \$table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('carts'); }
};
EOT,

    '2026_05_09_160006_create_cart_items_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cart_items', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('cart_id');
            \$table->uuid('product_variant_id');
            \$table->integer('quantity');
            \$table->decimal('unit_price', 10, 2);
            \$table->timestamps();

            \$table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            \$table->foreign('product_variant_id')->references('id')->on('product_variants')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('cart_items'); }
};
EOT,

    '2026_05_09_160007_create_orders_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->uuid('customer_id');
            \$table->uuid('address_id')->nullable();
            \$table->string('order_number')->unique();
            \$table->string('status')->default('pending'); // pending, processing, completed, cancelled
            \$table->decimal('subtotal', 10, 2);
            \$table->decimal('tax_total', 10, 2)->default(0);
            \$table->decimal('shipping_total', 10, 2)->default(0);
            \$table->decimal('discount_total', 10, 2)->default(0);
            \$table->decimal('grand_total', 10, 2);
            \$table->timestamp('placed_at')->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            \$table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            \$table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();

            \$table->index(['organization_id', 'status']);
            \$table->index(['customer_id', 'created_at']);
            \$table->index(['placed_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
EOT,

    '2026_05_09_160008_create_order_items_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('order_id');
            \$table->uuid('product_variant_id')->nullable();
            \$table->string('product_name');
            \$table->string('sku')->nullable();
            \$table->integer('quantity');
            \$table->decimal('unit_price', 10, 2);
            \$table->decimal('total_price', 10, 2);
            \$table->timestamps();

            \$table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            \$table->foreign('product_variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};
EOT,

    '2026_05_09_160009_create_payments_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('order_id');
            \$table->string('gateway'); // stripe, paypal, manual
            \$table->string('transaction_id')->unique()->nullable();
            \$table->string('status')->default('pending'); // pending, paid, failed, refunded
            \$table->decimal('amount', 10, 2);
            \$table->json('gateway_payload')->nullable();
            \$table->timestamp('paid_at')->nullable();
            \$table->timestamps();

            \$table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            
            \$table->index(['status', 'paid_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
EOT,

    '2026_05_09_160010_create_shipments_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shipments', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('order_id');
            \$table->string('courier')->nullable();
            \$table->string('tracking_number')->unique()->nullable();
            \$table->string('shipment_status')->default('pending'); // pending, shipped, delivered
            \$table->timestamp('shipped_at')->nullable();
            \$table->timestamp('delivered_at')->nullable();
            \$table->timestamps();

            \$table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('shipments'); }
};
EOT,

    '2026_05_09_160011_create_coupons_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupons', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->string('code')->unique();
            \$table->string('discount_type'); // percentage, fixed
            \$table->decimal('discount_value', 10, 2);
            \$table->decimal('minimum_order', 10, 2)->nullable();
            \$table->integer('usage_limit')->nullable();
            \$table->integer('used_count')->default(0);
            \$table->timestamp('expires_at')->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('coupons'); }
};
EOT,

    '2026_05_09_160012_create_order_coupon_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_coupon', function (Blueprint \$table) {
            \$table->uuid('order_id');
            \$table->uuid('coupon_id');
            \$table->timestamps();

            \$table->primary(['order_id', 'coupon_id']);
            \$table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            \$table->foreign('coupon_id')->references('id')->on('coupons')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('order_coupon'); }
};
EOT,

    '2026_05_09_160013_create_reviews_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('product_id');
            \$table->uuid('customer_id');
            \$table->integer('rating'); // 1-5
            \$table->text('review')->nullable();
            \$table->boolean('is_published')->default(true);
            \$table->timestamps();

            \$table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            \$table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
};
EOT,

    '2026_05_09_160014_create_subscriptions_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->string('provider'); // stripe, braintree, manual
            \$table->string('provider_subscription_id')->unique()->nullable();
            \$table->string('status')->default('active'); // active, canceled, past_due
            \$table->decimal('monthly_price', 10, 2);
            \$table->timestamp('trial_ends_at')->nullable();
            \$table->timestamp('renews_at')->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('subscriptions'); }
};
EOT,

    '2026_05_09_160015_create_invoices_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('subscription_id');
            \$table->string('invoice_number')->unique();
            \$table->decimal('total', 10, 2);
            \$table->string('status')->default('unpaid'); // unpaid, paid, void
            \$table->timestamp('issued_at')->nullable();
            \$table->timestamp('paid_at')->nullable();
            \$table->timestamps();

            \$table->foreign('subscription_id')->references('id')->on('subscriptions')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('invoices'); }
};
EOT,

    '2026_05_09_160016_create_support_tickets_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('support_tickets', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->uuid('customer_id');
            \$table->string('subject');
            \$table->string('priority')->default('normal'); // low, normal, high, urgent
            \$table->string('status')->default('open'); // open, pending, resolved, closed
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            \$table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('support_tickets'); }
};
EOT,

    '2026_05_09_160017_create_ticket_messages_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ticket_messages', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('support_ticket_id');
            \$table->uuid('sender_user_id')->nullable(); // nullable if sent by customer, or we use polymorphic sender
            \$table->text('message');
            \$table->timestamps();

            \$table->foreign('support_ticket_id')->references('id')->on('support_tickets')->cascadeOnDelete();
            \$table->foreign('sender_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('ticket_messages'); }
};
EOT,

    '2026_05_09_160018_create_notifications_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->string('type');
            \$table->string('notifiable_type');
            \$table->uuid('notifiable_id');
            \$table->json('data'); // Maps to payload in ERD
            \$table->timestamp('read_at')->nullable();
            \$table->timestamps();

            \$table->index(['notifiable_type', 'notifiable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('notifications'); }
};
EOT,

    '2026_05_09_160019_create_webhook_logs_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('webhook_logs', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->string('event');
            \$table->integer('response_status')->nullable();
            \$table->json('request_payload')->nullable();
            \$table->json('response_payload')->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('webhook_logs'); }
};
EOT,

    '2026_05_09_160020_create_audit_logs_table' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('audit_logs', function (Blueprint \$table) {
            \$table->uuid('id')->primary();
            \$table->uuid('organization_id');
            \$table->uuid('user_id')->nullable();
            \$table->string('event'); // created, updated, deleted
            \$table->string('auditable_type');
            \$table->uuid('auditable_id');
            \$table->json('old_values')->nullable();
            \$table->json('new_values')->nullable();
            \$table->string('ip_address', 45)->nullable();
            \$table->timestamps();

            \$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            \$table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            
            \$table->index(['auditable_type', 'auditable_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('audit_logs'); }
};
EOT,
];

foreach ($migrations as $name => $content) {
    file_put_contents(__DIR__ . '/database/migrations/' . $name . '.php', $content);
}
echo "Migrations created successfully.\n";
