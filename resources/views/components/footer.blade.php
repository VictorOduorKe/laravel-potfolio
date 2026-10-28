<footer style="background: #111728; color: #a0aec0; padding: 64px 0 32px 0; border-top: 1px solid #1e293b; margin-top: 80px;">
    <div class="container-layout">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 40px; margin-bottom: 48px;">
            <!-- Column 1: Brand Info -->
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <img src="{{ asset('logo.svg') }}" alt="Logo" style="height: 32px; filter: brightness(0) invert(1);" onerror="this.src='{{ asset('logo.png') }}'">
                    <span style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #ffffff;">TheTechCraft</span>
                </div>
                <p style="font-size: 14px; line-height: 1.6; color: #94a3b8; margin-bottom: 20px;">
                    Architecting resilient web infrastructure, secure enterprise PHP/Laravel platforms, automated payment gateways, and AI-grounded systems.
                </p>
                @if(isset($footerSocialLinks) && $footerSocialLinks->isNotEmpty())
                    <div style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
                        @foreach($footerSocialLinks as $social)
                            <a href="{{ $social->url }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               style="color: #cbd5e1; font-size: 20px; display: inline-flex; align-items: center; justify-content: center; transition: color 0.2s ease, transform 0.2s ease;" 
                               class="footer-social-link"
                               aria-label="{{ $social->label ?: $social->platform }}"
                               title="{{ $social->label ?: $social->platform }}">
                                <x-social-icon :icon="$social->icon" :size="22" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h4 style="color: #ffffff; font-size: 15px; margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.5px;">Navigation</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 10px; font-size: 14px;">
                    <li><a href="{{ route('home') }}#projects" style="color: #94a3b8;">Selected Projects</a></li>
                    <li><a href="{{ route('home') }}#skills" style="color: #94a3b8;">Skill Domains</a></li>
                    <li><a href="{{ route('home') }}#roadmap" style="color: #94a3b8;">Engineering Roadmap</a></li>
                    <li><a href="{{ route('news') }}" style="color: #94a3b8;">Tech News &amp; Insights</a></li>
                    <li><a href="{{ route('home') }}#contact" style="color: #94a3b8;">Contact Form</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Details -->
            <div>
                <h4 style="color: #ffffff; font-size: 15px; margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.5px;">Direct Inquiries</h4>
                @if(!empty($footerSettings['show_address']))
                    <p style="font-size: 14px; color: #94a3b8; margin-bottom: 12px;">
                        <strong>Location:</strong> {{ $footerSettings['display_address'] }}
                    </p>
                @endif
                <p style="font-size: 14px; color: #94a3b8; margin-bottom: 12px;">
                    <strong>Specialization:</strong> Full Stack, Cloud, InfoSec
                </p>
                @if(!empty($footerSettings['show_email']))
                    <p style="font-size: 14px; color: #94a3b8; margin-bottom: 12px;">
                        <strong>Email:</strong> <a href="mailto:{{ $footerSettings['email'] }}" style="color: var(--secondary);">{{ $footerSettings['email'] }}</a>
                    </p>
                @endif
                @if(!empty($footerSettings['show_phone']))
                    <p style="font-size: 14px; color: #94a3b8; margin-bottom: 12px;">
                        <strong>Phone:</strong> <a href="tel:{{ preg_replace('/[^\d+]/', '', $footerSettings['phone']) }}" style="color: var(--secondary);">{{ $footerSettings['phone'] }}</a>
                    </p>
                @endif
                @if(!empty($footerSettings['show_maps']))
                    <p style="font-size: 14px; margin-bottom: 12px;">
                        <a href="{{ $footerSettings['maps_url'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--secondary); display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">map</span> View on Google Maps
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 24px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; font-size: 13px; color: #64748b;">
            <p>&copy; {{ date('Y') }} Victor Oduor &bull; TheTechCraft. Built with Laravel 11.</p>
            <div>
                <a href="{{ route('admin.login') }}" style="color: #64748b; text-decoration: underline;">Admin Portal</a>
            </div>
        </div>
    </div>
</footer>
