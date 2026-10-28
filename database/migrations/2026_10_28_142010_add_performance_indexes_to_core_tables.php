<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add optimized indexes for public pages and admin queries.
     */
    public function up(): void
    {
        // 1. Projects table
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                // Optimizes: Project::where('is_published', true)->orderBy('created_at', 'desc')
                $table->index(['is_published', 'created_at'], 'idx_projects_is_published_created_at');
                // Optimizes: Project::orderBy('created_at', 'desc')
                $table->index(['created_at'], 'idx_projects_created_at');
            });
        }

        // 2. Project Gallery table
        if (Schema::hasTable('project_gallery')) {
            Schema::table('project_gallery', function (Blueprint $table) {
                // Optimizes: Eager-loading gallery sorted by sort_order
                $table->index(['project_id', 'sort_order'], 'idx_project_gallery_project_sort');
            });
        }

        // 3. Skills table
        if (Schema::hasTable('skills')) {
            Schema::table('skills', function (Blueprint $table) {
                // Optimizes: Skill::where('is_published', true)->orderBy('category')->orderBy('proficiency', 'desc')
                $table->index(['is_published', 'category', 'proficiency'], 'idx_skills_published_cat_prof');
                // Optimizes: Skill::orderBy('category')->orderBy('proficiency', 'desc')
                $table->index(['category', 'proficiency'], 'idx_skills_category_proficiency');
            });
        }

        // 4. Testimonials table
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                // Optimizes: Testimonial::published()->orderBy('display_order', 'asc')->orderBy('created_at', 'desc')
                $table->index(['is_published', 'display_order', 'created_at'], 'idx_testimonials_pub_order_created');
                // Optimizes: Testimonial::orderBy('display_order', 'asc')->orderBy('created_at', 'desc')
                $table->index(['display_order', 'created_at'], 'idx_testimonials_display_created');
            });
        }

        // 5. Contacts table
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                // Optimizes: Contact::where('ip_address', $ip)->where('created_at', '>=', ...)->count()
                $table->index(['ip_address', 'created_at'], 'idx_contacts_ip_created');
                // Optimizes: Contact::orderBy('created_at', 'desc')->paginate(20)
                $table->index(['created_at'], 'idx_contacts_created_at');
            });
        }

        // 6. News table
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                // Optimizes: News::where('is_published', true)->orderBy('created_at', 'desc')
                $table->index(['is_published', 'created_at'], 'idx_news_is_published_created_at');
                // Optimizes: News::orderBy('created_at', 'desc')->paginate(15)
                $table->index(['created_at'], 'idx_news_created_at');
            });
        }

        // 7. Roadmaps table
        if (Schema::hasTable('roadmaps')) {
            Schema::table('roadmaps', function (Blueprint $table) {
                // Optimizes: Roadmap::whereNull('parent_id')->where('is_published', true)->orderBy('target_date', 'desc')
                $table->index(['parent_id', 'is_published', 'target_date'], 'idx_roadmaps_parent_pub_date');
                // Optimizes: Roadmap::orderBy('target_date', 'desc')
                $table->index(['target_date'], 'idx_roadmaps_target_date');
            });
        }

        // 8. Social Links table
        if (Schema::hasTable('social_links')) {
            Schema::table('social_links', function (Blueprint $table) {
                // Optimizes: SocialLink::where('is_active', true)->orderBy('display_order', 'asc')
                $table->index(['is_active', 'display_order'], 'idx_social_links_active_display');
                // Optimizes: SocialLink::orderBy('display_order', 'asc')
                $table->index(['display_order'], 'idx_social_links_display_order');
            });
        }

        // 9. Chat Messages table
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                // Optimizes: ChatMessage session history lookup ordered chronologically
                $table->index(['session_id', 'timestamp'], 'idx_chat_messages_session_time');
            });
        }
    }

    /**
     * Reverse the migrations cleanly by index name.
     */
    public function down(): void
    {
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropIndex('idx_chat_messages_session_time');
            });
        }

        if (Schema::hasTable('social_links')) {
            Schema::table('social_links', function (Blueprint $table) {
                $table->dropIndex('idx_social_links_active_display');
                $table->dropIndex('idx_social_links_display_order');
            });
        }

        if (Schema::hasTable('roadmaps')) {
            Schema::table('roadmaps', function (Blueprint $table) {
                $table->dropIndex('idx_roadmaps_parent_pub_date');
                $table->dropIndex('idx_roadmaps_target_date');
            });
        }

        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex('idx_news_is_published_created_at');
                $table->dropIndex('idx_news_created_at');
            });
        }

        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropIndex('idx_contacts_ip_created');
                $table->dropIndex('idx_contacts_created_at');
            });
        }

        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropIndex('idx_testimonials_pub_order_created');
                $table->dropIndex('idx_testimonials_display_created');
            });
        }

        if (Schema::hasTable('skills')) {
            Schema::table('skills', function (Blueprint $table) {
                $table->dropIndex('idx_skills_published_cat_prof');
                $table->dropIndex('idx_skills_category_proficiency');
            });
        }

        if (Schema::hasTable('project_gallery')) {
            Schema::table('project_gallery', function (Blueprint $table) {
                $table->dropIndex('idx_project_gallery_project_sort');
            });
        }

        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropIndex('idx_projects_is_published_created_at');
                $table->dropIndex('idx_projects_created_at');
            });
        }
    }
};
