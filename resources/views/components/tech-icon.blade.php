@props([
    'name' => '',
    'size' => 18,
    'color' => null
])

@php
    $normalized = strtolower(trim($name));
    $style = "width: {$size}px; height: {$size}px; flex-shrink: 0;";
@endphp

@if(str_contains($normalized, 'laravel') && !str_contains($normalized, 'blade'))
    <!-- Laravel Logo Mark -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M12 22V12" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M22 7L12 12L2 7" stroke="#f43f5e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M17 4.5L7 9.5" stroke="#f43f5e" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
@elseif(str_contains($normalized, 'php'))
    <!-- PHP Vector Logo -->
    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <rect width="32" height="32" rx="6" fill="#4F5D95" fill-opacity="0.2"/>
        <path d="M7 11H12.5C14.4 11 15.5 12 15.5 13.5C15.5 15.2 14.2 16.2 12.2 16.2H9.2L8.2 21H6L7 11ZM9.6 14.5H12C12.8 14.5 13.3 14.1 13.3 13.5C13.3 12.9 12.8 12.5 12 12.5H9.9L9.6 14.5Z" fill="#777BB4"/>
        <path d="M15 11H17.2L16.2 15.5H19.5L20.5 11H22.7L20.7 21H18.5L19.3 17H16L15 21H12.8L15 11Z" fill="#777BB4"/>
        <path d="M22 11H27.5C29.4 11 30.5 12 30.5 13.5C30.5 15.2 29.2 16.2 27.2 16.2H24.2L23.2 21H21L22 11ZM24.6 14.5H27C27.8 14.5 28.3 14.1 28.3 13.5C28.3 12.9 27.8 12.5 27 12.5H24.9L24.6 14.5Z" fill="#777BB4"/>
    </svg>
@elseif($normalized === 'react' || str_contains($normalized, 'react ') && !str_contains($normalized, 'native'))
    <!-- React Atom Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <ellipse cx="12" cy="12" rx="3.5" ry="9" stroke="#61DAFB" stroke-width="1.6" transform="rotate(30 12 12)"/>
        <ellipse cx="12" cy="12" rx="3.5" ry="9" stroke="#61DAFB" stroke-width="1.6" transform="rotate(90 12 12)"/>
        <ellipse cx="12" cy="12" rx="3.5" ry="9" stroke="#61DAFB" stroke-width="1.6" transform="rotate(150 12 12)"/>
        <circle cx="12" cy="12" r="1.8" fill="#61DAFB"/>
    </svg>
@elseif(str_contains($normalized, 'node'))
    <!-- Node.js Hexagon Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <path d="M12 2L21 7.2V16.8L12 22L3 16.8V7.2L12 2Z" stroke="#68A063" stroke-width="1.8" stroke-linejoin="round"/>
        <path d="M12 6.5V17.5M7.5 9L12 11.5L16.5 9M7.5 15L12 12.5L16.5 15" stroke="#68A063" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
@elseif(str_contains($normalized, 'express'))
    <!-- Express.js Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <rect x="2" y="4" width="20" height="16" rx="4" stroke="#e2e8f0" stroke-width="1.6"/>
        <path d="M7 9L11 15M11 9L7 15M13 15H17M13 12H16M13 9H17" stroke="#e2e8f0" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@elseif($normalized === 'javascript' || str_contains($normalized, 'js'))
    <!-- JavaScript Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <rect x="2" y="2" width="20" height="20" rx="4" fill="#F7DF1E" fill-opacity="0.15" stroke="#F7DF1E" stroke-width="1.5"/>
        <path d="M12 11V16.5C12 17.5 11.2 18 10 18C8.8 18 8 17.3 7.8 16.3" stroke="#F7DF1E" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M14.5 16.5C15 17.5 16 18 17.2 18C18.5 18 19.5 17.2 19.5 16C19.5 14.8 18.7 14.2 17.2 13.7C15.5 13.2 14.8 12.5 14.8 11.5C14.8 10.3 15.8 9.5 17 9.5C18 9.5 18.8 10 19.2 10.8" stroke="#F7DF1E" stroke-width="1.8" stroke-linecap="round"/>
    </svg>
@elseif(str_contains($normalized, 'mysql') || str_contains($normalized, 'sql'))
    <!-- MySQL Database Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <ellipse cx="12" cy="6" rx="8" ry="3" stroke="#00758F" stroke-width="1.6"/>
        <path d="M4 6V12C4 13.66 7.58 15 12 15C16.42 15 20 13.66 20 12V6" stroke="#00758F" stroke-width="1.6"/>
        <path d="M4 12V18C4 19.66 7.58 21 12 21C16.42 21 20 19.66 20 18V12" stroke="#F29111" stroke-width="1.6"/>
    </svg>
@elseif(str_contains($normalized, 'git'))
    <!-- Git Version Control Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <path d="M19.5 10.5L13.5 4.5C12.7 3.7 11.3 3.7 10.5 4.5L4.5 10.5C3.7 11.3 3.7 12.7 4.5 13.5L10.5 19.5C11.3 20.3 12.7 20.3 13.5 19.5L19.5 13.5C20.3 12.7 20.3 11.3 19.5 10.5Z" stroke="#F05032" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="12" cy="12" r="1.8" fill="#F05032"/>
        <circle cx="9" cy="9" r="1.8" fill="#F05032"/>
        <circle cx="15" cy="15" r="1.8" fill="#F05032"/>
        <path d="M9 9L15 15M9 9V14" stroke="#F05032" stroke-width="1.4"/>
    </svg>
@elseif(str_contains($normalized, 'html'))
    <!-- HTML5 Shield Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <path d="M4 3L5.6 20L12 22L18.4 20L20 3H4Z" stroke="#E34F26" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M7.5 7H16.5L16 11.5H8.5L8.8 15L12 16L15.2 15L15.4 13" stroke="#E34F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@elseif(str_contains($normalized, 'css'))
    <!-- CSS3 Shield Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <path d="M4 3L5.6 20L12 22L18.4 20L20 3H4Z" stroke="#1572B6" stroke-width="1.6" stroke-linejoin="round"/>
        <path d="M16.5 7H7.5L8 11.5H15.5L15 15.5L12 16.5L9 15.5" stroke="#1572B6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
@elseif(str_contains($normalized, 'daraja') || str_contains($normalized, 'm-pesa') || str_contains($normalized, 'mpesa'))
    <!-- M-PESA Payment Icon -->
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="{{ $style }}" aria-hidden="true">
        <rect x="2" y="4" width="20" height="16" rx="4" stroke="#22c55e" stroke-width="1.6"/>
        <path d="M2 9H22" stroke="#22c55e" stroke-width="1.6"/>
        <path d="M6 15H10M14 15H18" stroke="#e47429" stroke-width="2" stroke-linecap="round"/>
    </svg>
@else
    @php
        $matIcon = match(true) {
            str_contains($normalized, 'csrf') => 'fingerprint',
            str_contains($normalized, 'token') => 'key',
            str_contains($normalized, 'hardware') => 'devices',
            str_contains($normalized, 'windows') => 'desktop_windows',
            str_contains($normalized, 'linux') => 'terminal',
            str_contains($normalized, 'support') || str_contains($normalized, 'helpdesk') => 'support_agent',
            str_contains($normalized, 'troubleshoot') || str_contains($normalized, 'diagnostic') => 'build_circle',
            str_contains($normalized, 'tcp') || str_contains($normalized, 'dhcp') || str_contains($normalized, 'dns') => 'dns',
            str_contains($normalized, 'lan') => 'lan',
            str_contains($normalized, 'routing') || str_contains($normalized, 'switching') => 'router',
            str_contains($normalized, 'huawei') => 'hub',
            str_contains($normalized, 'infrastructure') => 'account_tree',
            str_contains($normalized, 'network') => 'lan',
            str_contains($normalized, 'security') || str_contains($normalized, 'cyber') => 'shield_lock',
            str_contains($normalized, 'vulnerability') => 'policy',
            str_contains($normalized, 'access') || str_contains($normalized, 'permission') => 'admin_panel_settings',
            str_contains($normalized, 'testing') || str_contains($normalized, 'lab') => 'science',
            str_contains($normalized, 'monitoring') || str_contains($normalized, 'incident') => 'monitoring',
            str_contains($normalized, 'aws') || str_contains($normalized, 'ec2') || str_contains($normalized, 's3') || str_contains($normalized, 'iam') => 'cloud_queue',
            str_contains($normalized, 'docker') || str_contains($normalized, 'container') => 'deployed_code',
            str_contains($normalized, 'actions') || str_contains($normalized, 'ci/cd') => 'published_with_changes',
            str_contains($normalized, 'ssl') || str_contains($normalized, 'tls') || str_contains($normalized, 'https') => 'lock',
            str_contains($normalized, 'hosting') || str_contains($normalized, 'domain') => 'public',
            str_contains($normalized, 'backup') || str_contains($normalized, 'restoration') => 'backup',
            str_contains($normalized, 'phpmyadmin') => 'database',
            str_contains($normalized, 'maintenance') || str_contains($normalized, 'update') => 'system_update_alt',
            str_contains($normalized, 'documentation') => 'description',
            str_contains($normalized, 'server') => 'dns',
            str_contains($normalized, 'environment') || str_contains($normalized, 'config') => 'tune',
            str_contains($normalized, 'production') => 'speed',
            str_contains($normalized, 'coding') => 'code',
            str_contains($normalized, 'api') || str_contains($normalized, 'rest') => 'api',
            str_contains($normalized, 'stk') => 'send_to_mobile',
            str_contains($normalized, 'webhook') => 'webhook',
            str_contains($normalized, 'pay') => 'payments',
            str_contains($normalized, 'ai ') || str_contains($normalized, 'prediction') => 'psychology',
            str_contains($normalized, 'puppeteer') || str_contains($normalized, 'browser') => 'travel_explore',
            str_contains($normalized, 'automation') => 'precision_manufacturing',
            str_contains($normalized, 'cpanel') => 'dns',
            str_contains($normalized, 'netlify') => 'cloud_upload',
            str_contains($normalized, 'devops') => 'deployed_code',
            str_contains($normalized, 'schema') || (str_contains($normalized, 'design') && str_contains($normalized, 'data')) => 'account_tree',
            str_contains($normalized, 'normaliz') => 'table_chart',
            str_contains($normalized, 'mvc') || str_contains($normalized, 'architecture') => 'architecture',
            str_contains($normalized, 'modular') || str_contains($normalized, 'reusable') => 'layers',
            str_contains($normalized, 'scalable') => 'trending_up',
            str_contains($normalized, 'blade') => 'view_quilt',
            str_contains($normalized, 'native') || str_contains($normalized, 'expo') => 'smartphone',
            str_contains($normalized, 'hci') || str_contains($normalized, 'usability') => 'touch_app',
            str_contains($normalized, 'ngo') || str_contains($normalized, 'police') || str_contains($normalized, 'qr') || str_contains($normalized, 'chat') || str_contains($normalized, 'cms') => 'verified',
            default => 'code'
        };
    @endphp
    <span class="material-symbols-outlined" style="font-size: {{ $size }}px; color: {{ $color ?? 'inherit' }}; display: inline-flex; align-items: center; justify-content: center;" aria-hidden="true">{{ $matIcon }}</span>
@endif
