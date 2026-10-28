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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'live_url')) {
                $table->string('live_url', 255)->nullable()->after('github_repo_url');
            }
            if (!Schema::hasColumn('projects', 'technologies')) {
                $table->string('technologies', 255)->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'live_url')) {
                $table->dropColumn('live_url');
            }
            if (Schema::hasColumn('projects', 'technologies')) {
                $table->dropColumn('technologies');
            }
        });
    }
};
