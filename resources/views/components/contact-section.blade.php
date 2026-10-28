@props(['isStandalone' => false])

@php
    // Ensure contact settings are resolved with fallback to FooterService if not injected
    $settings = $contactSettings ?? app(\App\Services\FooterService::class)->getSettings();
    $socials = $footerSocialLinks ?? app(\App\Services\FooterService::class)->getSocialLinks();

    $showAddress = !empty($settings['show_address']);
    $showEmail = !empty($settings['show_email']);
    $showPhone = !empty($settings['show_phone']);
    $showMaps = !empty($settings['show_maps']) && !empty($settings['maps_url']) && preg_match('/^https?:\/\//i', $settings['maps_url']);

    // Build hierarchical location display
    $locationPieces = array_unique(array_filter([
        $settings['city'] ?? '',
        $settings['county'] ?? '',
        $settings['country'] ?? ''
    ]));
    $locationSub = implode(', ', $locationPieces);
    $primaryAddress = !empty($settings['raw_address']) ? $settings['raw_address'] : ($settings['display_address'] ?? '');
@endphp

<section id="contact" class="contact-section {{ $isStandalone ? 'contact-section-standalone' : 'reveal' }}">
    @if(!$isStandalone)
        <div class="contact-section-heading">
            <span class="section-tag">Let's Connect</span>
            <h2 class="section-title">Get In Touch &amp; Build Together</h2>
            <p class="section-description">
                Whether you have an enterprise platform to architect, a high-throughput payment integration, or a consulting engagement, send a direct message below.
            </p>
        </div>
    @endif

    <div class="contact-two-column-layout">
        <!-- =========================================================================
             LEFT COLUMN: Admin-Managed Contact Information Panel
             ========================================================================= -->
        <div class="contact-info-panel">
            <div class="contact-info-bg-accent" aria-hidden="true"></div>

            <div class="contact-info-header">
                <div class="contact-status-pill">
                    <span class="contact-pulse-dot" aria-hidden="true"></span>
                    <span>Available for Contracts &amp; Roles</span>
                </div>
                <h3 class="contact-info-title">Let's Connect</h3>
                <p class="contact-info-intro">
                    Reach out directly through official engineering channels or use the inquiry form. I typically review and reply within 24 to 48 hours.
                </p>
            </div>

            <div class="contact-channels-container">
                <!-- Location Channel (Admin-Managed) -->
                @if($showAddress)
                    <div class="contact-channel-item">
                        <div class="contact-channel-icon location-icon" aria-hidden="true">
                            <span class="material-symbols-outlined">location_on</span>
                        </div>
                        <div class="contact-channel-body">
                            <span class="contact-channel-label">Base Location &amp; Address</span>
                            @if(!empty($primaryAddress))
                                <div class="contact-channel-value">{{ $primaryAddress }}</div>
                            @endif
                            @if(!empty($locationSub) && $locationSub !== $primaryAddress)
                                <div class="contact-channel-sub">{{ $locationSub }}</div>
                            @endif

                            @if($showMaps)
                                <a href="{{ $settings['maps_url'] }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="contact-map-btn"
                                   title="Open location on Google Maps">
                                    <span class="material-symbols-outlined map-btn-icon" aria-hidden="true">map</span>
                                    <span>View on Google Maps</span>
                                    <span class="material-symbols-outlined map-arrow-icon" aria-hidden="true">open_in_new</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Direct Email Channel (Admin-Managed) -->
                @if($showEmail)
                    <div class="contact-channel-item">
                        <div class="contact-channel-icon email-icon" aria-hidden="true">
                            <span class="material-symbols-outlined">alternate_email</span>
                        </div>
                        <div class="contact-channel-body">
                            <span class="contact-channel-label">Direct Email Inquiries</span>
                            <a href="mailto:{{ $settings['email'] }}" class="contact-channel-link">
                                {{ $settings['email'] }}
                            </a>
                            <span class="contact-channel-meta">Recommended for project briefs &amp; contracts</span>
                        </div>
                    </div>
                @endif

                <!-- Phone Channel (Admin-Managed) -->
                @if($showPhone)
                    <div class="contact-channel-item">
                        <div class="contact-channel-icon phone-icon" aria-hidden="true">
                            <span class="material-symbols-outlined">call</span>
                        </div>
                        <div class="contact-channel-body">
                            <span class="contact-channel-label">Phone &amp; WhatsApp</span>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $settings['phone']) }}" class="contact-channel-link">
                                {{ $settings['phone'] }}
                            </a>
                            <span class="contact-channel-meta">Direct voice, SMS, or WhatsApp messaging</span>
                        </div>
                    </div>
                @endif

                <!-- Standalone Maps Action (If address hidden but map URL enabled) -->
                @if(!$showAddress && $showMaps)
                    <div class="contact-channel-item">
                        <div class="contact-channel-icon location-icon" aria-hidden="true">
                            <span class="material-symbols-outlined">map</span>
                        </div>
                        <div class="contact-channel-body">
                            <span class="contact-channel-label">Geographic Location</span>
                            <a href="{{ $settings['maps_url'] }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="contact-map-btn">
                                <span class="material-symbols-outlined map-btn-icon" aria-hidden="true">pin_drop</span>
                                <span>Explore Interactive Map</span>
                                <span class="material-symbols-outlined map-arrow-icon" aria-hidden="true">open_in_new</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Operational Meta Cards -->
            <div class="contact-operational-grid">
                <div class="contact-op-card">
                    <span class="material-symbols-outlined op-icon" aria-hidden="true">schedule</span>
                    <div>
                        <strong class="op-title">Fast Turnaround</strong>
                        <span class="op-subtitle">Responses within 24–48h</span>
                    </div>
                </div>
                <div class="contact-op-card">
                    <span class="material-symbols-outlined op-icon" aria-hidden="true">public</span>
                    <div>
                        <strong class="op-title">Working Hours</strong>
                        <span class="op-subtitle">Kenya &bull; UTC+3 (EAT)</span>
                    </div>
                </div>
            </div>

            <!-- Social Links Integration -->
            @if(isset($socials) && $socials->isNotEmpty())
                <div class="contact-social-footer">
                    <span class="contact-social-heading">Verified Profiles &amp; Repositories:</span>
                    <div class="contact-social-links">
                        @foreach($socials as $social)
                            <a href="{{ $social->url }}" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="contact-social-icon-btn" 
                               title="{{ $social->label ?: $social->platform }}"
                               aria-label="{{ $social->label ?: $social->platform }}">
                                <x-social-icon :icon="$social->icon" :size="20" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- =========================================================================
             RIGHT COLUMN: Preserved, Modernized Working Contact Form
             ========================================================================= -->
        <div class="contact-form-card">
            <div class="contact-form-header">
                <h3 class="contact-form-heading">Send a Message</h3>
                <p class="contact-form-subtext">
                    Fill out the brief form below. All fields marked with <span class="required-indicator">*</span> are required.
                </p>
            </div>

            <!-- Live Form Feedback Container (Used by both AJAX JS and Session Redirects) -->
            <div id="contact-form-feedback" class="contact-form-feedback">
                @if(session('success'))
                    <div class="form-alert form-alert-success" role="alert">
                        <span class="material-symbols-outlined alert-icon" aria-hidden="true">check_circle</span>
                        <div>
                            <strong>Message Delivered!</strong>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="form-alert form-alert-error" role="alert">
                        <span class="material-symbols-outlined alert-icon" aria-hidden="true">error</span>
                        <div>
                            <strong>Delivery Issue</strong>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Preserved Form Action, Method, Payload and IDs -->
            <form id="portfolio-contact-form" action="{{ route('contact.submit') }}" method="POST" novalidate>
                @csrf

                <!-- Name Row (First Name + Last Name) -->
                <div class="form-row-duo">
                    <div class="form-group">
                        <label class="form-label" for="first_name">
                            First Name <span class="required-indicator">*</span>
                        </label>
                        <div class="input-shell @error('first_name') has-error @enderror">
                            <span class="material-symbols-outlined input-adornment" aria-hidden="true">person</span>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   class="form-control @error('first_name') is-invalid @enderror" 
                                   required 
                                   placeholder="e.g. John" 
                                   value="{{ old('first_name') }}"
                                   autocomplete="given-name">
                        </div>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="last_name">
                            Last Name <span class="required-indicator">*</span>
                        </label>
                        <div class="input-shell @error('last_name') has-error @enderror">
                            <span class="material-symbols-outlined input-adornment" aria-hidden="true">person_outline</span>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   class="form-control @error('last_name') is-invalid @enderror" 
                                   required 
                                   placeholder="e.g. Doe" 
                                   value="{{ old('last_name') }}"
                                   autocomplete="family-name">
                        </div>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contact Row (Email + Phone Number) -->
                <div class="form-row-duo">
                    <div class="form-group">
                        <label class="form-label" for="email">
                            Email Address <span class="required-indicator">*</span>
                        </label>
                        <div class="input-shell @error('email') has-error @enderror">
                            <span class="material-symbols-outlined input-adornment" aria-hidden="true">mail</span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   required 
                                   placeholder="john@example.com" 
                                   value="{{ old('email') }}"
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone_number">
                            Phone Number <span class="optional-tag">(Optional)</span>
                        </label>
                        <div class="input-shell @error('phone_number') has-error @enderror">
                            <span class="material-symbols-outlined input-adornment" aria-hidden="true">call</span>
                            <input type="tel" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   class="form-control @error('phone_number') is-invalid @enderror" 
                                   placeholder="+254 7XX XXX XXX" 
                                   value="{{ old('phone_number') }}"
                                   autocomplete="tel">
                        </div>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Message Group -->
                <div class="form-group message-form-group">
                    <label class="form-label" for="message">
                        Your Message or Project Brief <span class="required-indicator">*</span>
                    </label>
                    <div class="textarea-shell @error('message') has-error @enderror">
                        <textarea id="message" 
                                  name="message" 
                                  class="form-control form-textarea @error('message') is-invalid @enderror" 
                                  rows="5" 
                                  required 
                                  placeholder="Describe your project, systems architecture requirements, timeline, or engineering inquiry...">{{ old('message') }}</textarea>
                    </div>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submission Controls & Privacy Trust Note -->
                <div class="form-submit-row">
                    <button type="submit" class="btn-hero-primary contact-submit-btn">
                        <span class="material-symbols-outlined" style="font-size: 20px;" aria-hidden="true">send</span>
                        <span>Send Message</span>
                    </button>

                    <div class="contact-trust-note">
                        <span class="material-symbols-outlined" style="font-size: 16px; color: var(--success);" aria-hidden="true">lock</span>
                        <span>Your contact details are encrypted and never shared.</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
