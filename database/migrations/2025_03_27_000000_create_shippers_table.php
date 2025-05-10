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
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('id_card')->nullable()->comment('Căn cước công dân');
            $table->string('company')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('status')->default(true);
            $table->string('avatar')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        // Thêm cột shipper_id vào bảng shipments
        Schema::table('shipments', function (Blueprint $table) {
            $table->unsignedBigInteger('shipper_id')->nullable()->after('order_id');
            $table->foreign('shipper_id')->references('id')->on('shippers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['shipper_id']);
            $table->dropColumn('shipper_id');
        });
        
        Schema::dropIfExists('shippers');
    }
}; 