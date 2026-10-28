<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Services\FooterService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function __construct(protected FooterService $footerService)
    {
    }

    /**
     * Display the Footer & Social Links settings page.
     */
    public function index(): View
    {
        $contactSettings = Setting::where('setting_key', 'like', 'footer_%')
            ->pluck('setting_value', 'setting_key')
            ->toArray();

        $socialLinks = SocialLink::ordered()->get();
        $supportedPlatforms = SocialLink::supportedPlatforms();
        $supportedIcons = SocialLink::supportedIcons();

        return view('admin.footer.index', compact(
            'contactSettings',
            'socialLinks',
            'supportedPlatforms',
            'supportedIcons'
        ));
    }

    /**
     * Update contact information and visibility toggles.
     */
    public function updateContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'footer_address' => ['nullable', 'string', 'max:255'],
            'footer_city' => ['nullable', 'string', 'max:100'],
            'footer_county' => ['nullable', 'string', 'max:100'],
            'footer_country' => ['nullable', 'string', 'max:100'],
            'footer_email' => ['nullable', 'email', 'max:255'],
            'footer_phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9+\s\-().]+$/'],
            'footer_maps_url' => ['nullable', 'url', 'regex:/^https?:\/\//i', 'max:500'],
            'footer_show_address' => ['nullable', 'boolean'],
            'footer_show_email' => ['nullable', 'boolean'],
            'footer_show_phone' => ['nullable', 'boolean'],
            'footer_show_maps' => ['nullable', 'boolean'],
        ], [
            'footer_phone.regex' => 'The phone number format is invalid. Use international format (e.g., +254 700 000 000).',
            'footer_maps_url.regex' => 'The map URL must start with http:// or https://.',
        ]);

        Setting::set('footer_address', $validated['footer_address'] ?? '');
        Setting::set('footer_city', $validated['footer_city'] ?? '');
        Setting::set('footer_county', $validated['footer_county'] ?? '');
        Setting::set('footer_country', $validated['footer_country'] ?? '');
        Setting::set('footer_email', $validated['footer_email'] ?? '');
        Setting::set('footer_phone', $validated['footer_phone'] ?? '');
        Setting::set('footer_maps_url', $validated['footer_maps_url'] ?? '');

        Setting::set('footer_show_address', $request->has('footer_show_address') ? '1' : '0');
        Setting::set('footer_show_email', $request->has('footer_show_email') ? '1' : '0');
        Setting::set('footer_show_phone', $request->has('footer_show_phone') ? '1' : '0');
        Setting::set('footer_show_maps', $request->has('footer_show_maps') ? '1' : '0');

        $this->footerService->clearCache();

        return redirect()->route('admin.footer.index')
            ->with('success', 'Footer contact information updated successfully.');
    }

    /**
     * Store a newly created social link.
     */
    public function storeSocial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => ['required', 'string', 'max:100'],
            'custom_platform' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'regex:/^https?:\/\//i', 'max:500'],
            'label' => ['nullable', 'string', 'max:150'],
            'icon' => ['required', 'string', 'max:50', 'alpha_dash'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'url.regex' => 'The destination URL must start with http:// or https://.',
        ]);

        $platform = $validated['platform'];
        if ($platform === 'Custom' && !empty($validated['custom_platform'])) {
            $platform = trim($validated['custom_platform']);
        }

        $order = isset($validated['display_order']) && $validated['display_order'] !== null && $validated['display_order'] !== ''
            ? (int) $validated['display_order']
            : (SocialLink::max('display_order') ?? 0) + 1;

        SocialLink::create([
            'platform' => $platform,
            'url' => $validated['url'],
            'label' => $validated['label'] ?? null,
            'icon' => $validated['icon'],
            'display_order' => $order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->footerService->clearCache();

        return redirect()->route('admin.footer.index')
            ->with('success', 'Social link added successfully.');
    }

    /**
     * Update an existing social link.
     */
    public function updateSocial(Request $request, SocialLink $socialLink): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => ['required', 'string', 'max:100'],
            'custom_platform' => ['nullable', 'string', 'max:100'],
            'url' => ['required', 'url', 'regex:/^https?:\/\//i', 'max:500'],
            'label' => ['nullable', 'string', 'max:150'],
            'icon' => ['required', 'string', 'max:50', 'alpha_dash'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'url.regex' => 'The destination URL must start with http:// or https://.',
        ]);

        $platform = $validated['platform'];
        if ($platform === 'Custom' && !empty($validated['custom_platform'])) {
            $platform = trim($validated['custom_platform']);
        }

        $order = isset($validated['display_order']) && $validated['display_order'] !== null && $validated['display_order'] !== ''
            ? (int) $validated['display_order']
            : $socialLink->display_order;

        $socialLink->update([
            'platform' => $platform,
            'url' => $validated['url'],
            'label' => $validated['label'] ?? null,
            'icon' => $validated['icon'],
            'display_order' => $order,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->footerService->clearCache();

        return redirect()->route('admin.footer.index')
            ->with('success', 'Social link updated successfully.');
    }

    /**
     * Delete a social link.
     */
    public function destroySocial(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        $this->footerService->clearCache();

        return redirect()->route('admin.footer.index')
            ->with('success', 'Social link deleted successfully.');
    }

    /**
     * Toggle active status of a social link.
     */
    public function toggleSocial(Request $request, SocialLink $socialLink): JsonResponse|RedirectResponse
    {
        $socialLink->update(['is_active' => !$socialLink->is_active]);

        $this->footerService->clearCache();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $socialLink->is_active,
                'message' => 'Status updated.',
            ]);
        }

        return redirect()->route('admin.footer.index')
            ->with('success', 'Social link status updated.');
    }

    /**
     * Reorder social links via position payload.
     */
    public function reorderSocial(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'integer', 'exists:social_links,id'],
            'orders.*.order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['orders'] as $item) {
            SocialLink::where('id', $item['id'])->update(['display_order' => $item['order']]);
        }

        $this->footerService->clearCache();

        return response()->json(['success' => true, 'message' => 'Display orders updated.']);
    }
}
