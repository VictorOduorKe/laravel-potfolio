<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('social_links')) {
            Schema::create('social_links', function (Blueprint $table) {
                $table->id();
                $table->string('platform', 100);
                $table->string('url', 500);
                $table->string('label', 150)->nullable();
                $table->string('icon', 50)->default('globe');
                $table->unsignedInteger('display_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Seed existing hardcoded social links safely
        $now = now();
        $socials = [
            [
                'platform' => 'GitHub',
                'url' => 'https://github.com/VictorOduorKe',
                'label' => 'GitHub Profile',
                'icon' => 'github',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'platform' => 'LinkedIn',
                'url' => 'https://linkedin.com/in/victor-oduor',
                'label' => 'LinkedIn Profile',
                'icon' => 'linkedin',
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($socials as $social) {
            DB::table('social_links')->updateOrInsert(
                ['url' => $social['url']],
                array_merge($social, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        // Seed existing hardcoded contact information into settings table
        $contactSettings = [
            'footer_address' => 'Mombasa, Kenya',
            'footer_city' => 'Mombasa',
            'footer_county' => 'Mombasa County',
            'footer_country' => 'Kenya',
            'footer_email' => 'victoroduor723@gmail.com',
            'footer_phone' => '',
            'footer_maps_url' => '',
            'footer_show_address' => '1',
            'footer_show_email' => '1',
            'footer_show_phone' => '0',
            'footer_show_maps' => '0',
        ];

        foreach ($contactSettings as $key => $val) {
            DB::table('settings')->updateOrInsert(
                ['setting_key' => $key],
                ['setting_value' => $val, 'updated_at' => $now]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
