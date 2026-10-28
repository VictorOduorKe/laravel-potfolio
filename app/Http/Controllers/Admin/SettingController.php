<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('setting_value', 'setting_key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, SeoService $seoService): RedirectResponse
    {
        $validated = $request->validate([
            'site_title' => 'nullable|string|max:120',
            'admin_notification_email' => 'nullable|email|max:120',
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:350',
            'seo_og_image' => 'nullable|string|max:255',
            'google_search_console_token' => 'nullable|string|max:120',
            'news_auto_update_enabled' => 'nullable|in:0,1',
            'news_update_interval_hours' => 'nullable|integer|min:1|max:168',
        ]);

        // Checkboxes: if unchecked in form, set to '0'
        if (!$request->has('news_auto_update_enabled')) {
            $validated['news_auto_update_enabled'] = '0';
        }

        foreach ($validated as $key => $val) {
            Setting::set($key, $val !== null ? (string) $val : '');
        }

        // Flush SEO and Sitemap caches
        $seoService->clearCache();
        Cache::forget('portfolio_xml_sitemap');

        return redirect()->route('admin.settings.index')->with('success', 'System and SEO settings updated successfully.');
    }
}
