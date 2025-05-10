<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons_test', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->string('type');
            $table->decimal('value', 10, 2); // Giá trị giảm
            $table->decimal('min_order_amount', 10, 2)->default(0); // Số tiền đơn hàng tối thiểu
            $table->integer('max_uses')->nullable(); // Số lần sử dụng tối đa
            $table->integer('used_count')->default(0); // Số lần đã sử dụng
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // Áp dụng cho danh mục
            $table->boolean('is_active')->default(true);
            $table->dateTime('valid_from');
            $table->dateTime('valid_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons_test');
    }
};
