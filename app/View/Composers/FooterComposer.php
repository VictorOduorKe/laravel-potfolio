<?php

namespace App\View\Composers;

use App\Services\FooterService;
use Illuminate\View\View;

class FooterComposer
{
    public function __construct(protected FooterService $footerService)
    {
    }

    /**
     * Bind footer data to the view.
     */
    public function compose(View $view): void
    {
        $settings = $this->footerService->getSettings();
        $socialLinks = $this->footerService->getSocialLinks();

        $view->with([
            'footerSettings' => $settings,
            'contactSettings' => $settings,
            'footerSocialLinks' => $socialLinks,
        ]);
    }
}
