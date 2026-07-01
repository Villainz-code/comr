<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add sizes to products
        Schema::table('products', function (Blueprint $table) {
            $table->json('sizes')->nullable()->after('image');
        });

        // Add new fields to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('selected_size')->nullable()->after('quantity');
            $table->string('recipient_name')->nullable()->after('shipping_address');
            $table->string('phone')->nullable()->after('recipient_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('city')->nullable()->after('email');
            $table->string('country')->default('Indonesia')->after('city');
            $table->string('shipping_method')->default('regular')->after('country');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_method');
            $table->string('payment_method')->nullable()->after('shipping_cost');
            $table->string('payment_channel')->nullable()->after('payment_method');
            $table->text('shipping_note')->nullable()->after('payment_channel');
        });

        // Modify status enum to include 'cancelled'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processed', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sizes');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'selected_size', 'recipient_name', 'phone', 'email',
                'city', 'country', 'shipping_method', 'shipping_cost',
                'payment_method', 'payment_channel', 'shipping_note',
            ]);
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processed', 'completed') DEFAULT 'pending'");
    }
};
