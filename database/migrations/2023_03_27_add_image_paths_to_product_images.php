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
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (!Schema::hasColumn('product_images', 'thumbnail_path')) {
                    $table->string('thumbnail_path')->nullable();
                }
                
                if (!Schema::hasColumn('product_images', 'gallery_path')) {
                    $table->string('gallery_path')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                if (Schema::hasColumn('product_images', 'thumbnail_path')) {
                    $table->dropColumn('thumbnail_path');
                }
                
                if (Schema::hasColumn('product_images', 'gallery_path')) {
                    $table->dropColumn('gallery_path');
                }
            });
        }
    }
}; 