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
        Schema::create('revenue_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->enum('period_type', ['daily', 'weekly', 'monthly', 'yearly', 'custom']);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_revenue', 12, 2);
            $table->decimal('cost_of_goods', 12, 2)->default(0);
            $table->decimal('gross_profit', 12, 2);
            $table->decimal('tax_collected', 10, 2)->default(0);
            $table->decimal('shipping_fees', 10, 2)->default(0);
            $table->decimal('refunds', 10, 2)->default(0);
            $table->decimal('discounts', 10, 2)->default(0);
            $table->integer('orders_count')->default(0);
            $table->integer('products_sold')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('generated_by');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::create('product_sales_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revenue_report_id');
            $table->foreign('revenue_report_id')->references('id')->on('revenue_reports')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity_sold');
            $table->decimal('revenue', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sales_stats');
        Schema::dropIfExists('revenue_reports');
    }
};
