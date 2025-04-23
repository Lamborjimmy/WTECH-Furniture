<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->text('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles');
      }); 

        Schema::create('delivery_details', function (Blueprint $table) {
            $table->id();
            $table->text('fullname');
            $table->text('email');
            $table->text('phone_number');
            $table->text('street_and_number');
            $table->text('city');
            $table->text('post_code');
            $table->text('country');
        });

        Schema::create('payment_options', function (Blueprint $table) {
            $table->id();
            $table->text('name');
        });

        Schema::create('delivery_options', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->float('price');
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->integer('discount');
            $table->integer('amount');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestampTz('processed_date');
            $table->foreignId('delivery_detail_id')->constrained('delivery_details');
            $table->foreignId('payment_option_id')->constrained('payment_options');
            $table->foreignId('delivery_option_id')->constrained('delivery_options');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
        });

        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('hex_string');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('name');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->text('name');
        });

        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->text('name');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->foreignId('color_id')->constrained('colors');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('material_id')->constrained('materials');
            $table->foreignId('placement_id')->constrained('placements');
            $table->float('price');
            $table->integer('in_stock');
            $table->boolean('valid');
            $table->integer('width');
            $table->integer('length');
            $table->integer('depth');
            $table->timestampTz('added_date');
            $table->timestampTz('modified_date')->nullable();
            $table->text('code');
        });

        Schema::create('carts_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
            $table->foreign('order_id')->references('id')->on('orders');
        });

        Schema::create('image_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->text('title');
            $table->text('path');
            $table->boolean('is_main');
        });
    }

    public function down(): void {
        Schema::dropIfExists('image_references');
        Schema::dropIfExists('orders_products');
        Schema::dropIfExists('carts_products');
        Schema::dropIfExists('products');
        Schema::dropIfExists('placements');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('delivery_options');
        Schema::dropIfExists('payment_options');
        Schema::dropIfExists('delivery_details');
    }
};
