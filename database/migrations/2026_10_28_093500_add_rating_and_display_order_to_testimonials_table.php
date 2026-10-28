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
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('testimonials', 'display_order')) {
                $table->integer('display_order')->default(0)->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'display_order')) {
                $table->dropColumn('display_order');
            }
            if (Schema::hasColumn('testimonials', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};
