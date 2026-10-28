<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FooterService
{
    public const CACHE_SETTINGS_KEY = 'portfolio_footer_settings';
    public const CACHE_SOCIALS_KEY = 'portfolio_footer_social_links';
    public const CACHE_TTL_SECONDS = 86400; // 24 hours

    /**
     * Retrieve normalized footer contact settings with caching.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        try {
            return Cache::remember(self::CACHE_SETTINGS_KEY, self::CACHE_TTL_SECONDS, function () {
                $settings = Setting::where('setting_key', 'like', 'footer_%')
                    ->pluck('setting_value', 'setting_key')
                    ->toArray();

                $address = trim($settings['footer_address'] ?? '');
                $city = trim($settings['footer_city'] ?? '');
                $county = trim($settings['footer_county'] ?? '');
                $country = trim($settings['footer_country'] ?? '');
                $email = trim($settings['footer_email'] ?? '');
                $phone = trim($settings['footer_phone'] ?? '');
                $mapsUrl = trim($settings['footer_maps_url'] ?? '');

                $showAddress = filter_var($settings['footer_show_address'] ?? '1', FILTER_VALIDATE_BOOLEAN);
                $showEmail = filter_var($settings['footer_show_email'] ?? '1', FILTER_VALIDATE_BOOLEAN);
                $showPhone = filter_var($settings['footer_show_phone'] ?? '0', FILTER_VALIDATE_BOOLEAN);
                $showMaps = filter_var($settings['footer_show_maps'] ?? '0', FILTER_VALIDATE_BOOLEAN);

                // Construct formatted address display
                $addressParts = array_filter([$address, $city, $county, $country], fn ($part) => !empty($part));
                // De-duplicate if address already includes city/country
                $displayAddress = $address;
                if (empty($displayAddress)) {
                    $locationPieces = array_filter([$city, $county, $country], fn ($p) => !empty($p));
                    $displayAddress = implode(', ', array_unique($locationPieces));
                }

                return [
                    'raw_address' => $address,
                    'city' => $city,
                    'county' => $county,
                    'country' => $country,
                    'display_address' => $displayAddress,
                    'email' => $email,
                    'phone' => $phone,
                    'maps_url' => $mapsUrl,
                    'show_address' => $showAddress && !empty($displayAddress),
                    'show_email' => $showEmail && !empty($email),
                    'show_phone' => $showPhone && !empty($phone),
                    'show_maps' => $showMaps && !empty($mapsUrl),
                ];
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Unable to load footer settings: ' . $e->getMessage());
            return [
                'raw_address' => '',
                'city' => '',
                'county' => '',
                'country' => '',
                'display_address' => '',
                'email' => '',
                'phone' => '',
                'maps_url' => '',
                'show_address' => false,
                'show_email' => false,
                'show_phone' => false,
                'show_maps' => false,
            ];
        }
    }

    /**
     * Retrieve active, ordered social links with caching.
     *
     * @return Collection<int, SocialLink>
     */
    public function getSocialLinks(): Collection
    {
        try {
            return Cache::remember(self::CACHE_SOCIALS_KEY, self::CACHE_TTL_SECONDS, function () {
                return SocialLink::active()->ordered()->get();
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Unable to load footer social links: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Clear all cached footer settings and social links.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_SETTINGS_KEY);
        Cache::forget(self::CACHE_SOCIALS_KEY);
    }
}
