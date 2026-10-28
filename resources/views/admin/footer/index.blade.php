@extends('layouts.admin')

@section('title', 'Footer & Social Link Settings')
@section('header', 'Footer Settings & Social Links')

@section('content')
<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- =========================================================================
         AREA A: CONTACT INFORMATION & VISIBILITY CONTROLS
         ========================================================================= -->
    <div class="admin-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 14px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 4px 0;">Contact Details &amp; Public Visibility</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin: 0;">Configure your physical location, direct inquiries, and choose which items render publicly in the website footer.</p>
            </div>
            <span class="status-badge status-progress">Public Footer</span>
        </div>

        <form action="{{ route('admin.footer.contact.update') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 24px;">
                <!-- Physical Address -->
                <div>
                    <label class="form-label" for="footer_address">Physical / Street Address</label>
                    <input type="text" id="footer_address" name="footer_address" class="form-control" 
                           value="{{ old('footer_address', $contactSettings['footer_address'] ?? '') }}" 
                           placeholder="e.g. Mombasa, Kenya">
                    <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Primary physical street or compound reference.</span>
                </div>

                <!-- City / Town -->
                <div>
                    <label class="form-label" for="footer_city">City or Town</label>
                    <input type="text" id="footer_city" name="footer_city" class="form-control" 
                           value="{{ old('footer_city', $contactSettings['footer_city'] ?? '') }}" 
                           placeholder="e.g. Mombasa">
                </div>

                <!-- County / Region -->
                <div>
                    <label class="form-label" for="footer_county">County or Region</label>
                    <input type="text" id="footer_county" name="footer_county" class="form-control" 
                           value="{{ old('footer_county', $contactSettings['footer_county'] ?? '') }}" 
                           placeholder="e.g. Mombasa County / Coast Province">
                </div>

                <!-- Country -->
                <div>
                    <label class="form-label" for="footer_country">Country</label>
                    <input type="text" id="footer_country" name="footer_country" class="form-control" 
                           value="{{ old('footer_country', $contactSettings['footer_country'] ?? '') }}" 
                           placeholder="e.g. Kenya">
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="form-label" for="footer_email">Contact Email Address</label>
                    <input type="email" id="footer_email" name="footer_email" class="form-control" 
                           value="{{ old('footer_email', $contactSettings['footer_email'] ?? '') }}" 
                           placeholder="e.g. victoroduor723@gmail.com">
                    <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Renders as a direct <code>mailto:</code> link when enabled.</span>
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="form-label" for="footer_phone">Contact Phone Number</label>
                    <input type="text" id="footer_phone" name="footer_phone" class="form-control" 
                           value="{{ old('footer_phone', $contactSettings['footer_phone'] ?? '') }}" 
                           placeholder="e.g. +254 700 000 000">
                    <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Renders as a direct <code>tel:</code> link when enabled.</span>
                </div>
            </div>

            <!-- Google Maps URL -->
            <div style="margin-bottom: 28px;">
                <label class="form-label" for="footer_maps_url">Google Maps URL (Optional)</label>
                <input type="url" id="footer_maps_url" name="footer_maps_url" class="form-control" 
                       value="{{ old('footer_maps_url', $contactSettings['footer_maps_url'] ?? '') }}" 
                       placeholder="https://maps.google.com/?q=Mombasa+Kenya">
                <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Must begin with <code>http://</code> or <code>https://</code>. Opens securely in a new tab.</span>
            </div>

            <!-- Visibility Controls -->
            <h4 style="font-size: 15px; font-weight: 700; color: var(--primary); margin: 0 0 16px 0;">Public Visibility Controls</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; background: var(--background); padding: 18px 20px; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 28px;">
                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" name="footer_show_address" value="1" 
                           {{ old('footer_show_address', $contactSettings['footer_show_address'] ?? '1') == '1' ? 'checked' : '' }} 
                           style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Show Location / Address</span>
                        <span style="display: block; font-size: 12px; font-weight: normal; color: var(--text-muted);">Displays formatted city and country.</span>
                    </div>
                </label>

                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" name="footer_show_email" value="1" 
                           {{ old('footer_show_email', $contactSettings['footer_show_email'] ?? '1') == '1' ? 'checked' : '' }} 
                           style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Show Email Address</span>
                        <span style="display: block; font-size: 12px; font-weight: normal; color: var(--text-muted);">Displays email as clickable mailto link.</span>
                    </div>
                </label>

                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" name="footer_show_phone" value="1" 
                           {{ old('footer_show_phone', $contactSettings['footer_show_phone'] ?? '0') == '1' ? 'checked' : '' }} 
                           style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Show Phone Number</span>
                        <span style="display: block; font-size: 12px; font-weight: normal; color: var(--text-muted);">Displays phone with tel protocol.</span>
                    </div>
                </label>

                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" name="footer_show_maps" value="1" 
                           {{ old('footer_show_maps', $contactSettings['footer_show_maps'] ?? '0') == '1' ? 'checked' : '' }} 
                           style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Show Google Maps Link</span>
                        <span style="display: block; font-size: 12px; font-weight: normal; color: var(--text-muted);">Displays link when URL is populated.</span>
                    </div>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-hero-primary" style="padding: 12px 32px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Save Contact Information
                </button>
            </div>
        </form>
    </div>

    <!-- =========================================================================
         AREA B: SOCIAL LINKS MANAGEMENT
         ========================================================================= -->
    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start;">
        
        <!-- Left Column: Existing Social Links Table & Reordering -->
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; background: #ffffff;">
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0 0 4px 0;">Active &amp; Configured Social Links</h3>
                    <span style="font-size: 12px; color: var(--text-muted);">Links appear in the public footer in the order listed below.</span>
                </div>
                <button type="button" id="btn-save-order" class="btn-hero-primary" style="display: none; padding: 6px 14px; font-size: 12px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">save</span> Save New Order
                </button>
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table" id="social-links-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">Order</th>
                            <th style="width: 48px; text-align: center;">Icon</th>
                            <th>Platform &amp; Label</th>
                            <th>Destination URL</th>
                            <th style="width: 90px; text-align: center;">Status</th>
                            <th style="width: 140px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="social-links-tbody">
                        @forelse($socialLinks as $index => $social)
                        <tr data-id="{{ $social->id }}" data-order="{{ $social->display_order }}">
                            <!-- Reorder Order Input & Controls -->
                            <td style="text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                    <input type="number" 
                                           class="order-input" 
                                           data-id="{{ $social->id }}" 
                                           value="{{ $social->display_order }}" 
                                           min="0" 
                                           max="999" 
                                           style="width: 44px; text-align: center; padding: 4px; border: 1px solid var(--border); border-radius: 4px; font-size: 12px; font-weight: 600;">
                                </div>
                            </td>

                            <!-- Icon Visual -->
                            <td style="text-align: center; vertical-align: middle;">
                                <div style="width: 32px; height: 32px; border-radius: 6px; background: #111728; color: #cbd5e1; display: inline-flex; align-items: center; justify-content: center;">
                                    <x-social-icon :icon="$social->icon" :size="18" />
                                </div>
                            </td>

                            <!-- Platform & Label -->
                            <td>
                                <strong style="color: var(--primary); display: block; font-size: 14px;">{{ $social->platform }}</strong>
                                @if($social->label)
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ $social->label }}</span>
                                @endif
                            </td>

                            <!-- Destination URL -->
                            <td>
                                <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-size: 13px; text-decoration: none; word-break: break-all; display: inline-flex; align-items: center; gap: 4px;">
                                    <span>{{ Str::limit($social->url, 40) }}</span>
                                    <span class="material-symbols-outlined" style="font-size: 14px;">open_in_new</span>
                                </a>
                            </td>

                            <!-- Status Toggle -->
                            <td style="text-align: center;">
                                <form action="{{ route('admin.footer.social.toggle', $social) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="status-badge {{ $social->is_active ? 'status-completed' : 'status-planned' }}" 
                                            style="border: none; cursor: pointer; transition: opacity 0.2s ease;"
                                            title="Click to toggle visibility">
                                        {{ $social->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions: Edit & Delete -->
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; align-items: center; gap: 6px;">
                                    <!-- Edit Trigger -->
                                    <button type="button" 
                                            class="btn-edit-social" 
                                            data-id="{{ $social->id }}"
                                            data-platform="{{ $social->platform }}"
                                            data-url="{{ $social->url }}"
                                            data-label="{{ $social->label }}"
                                            data-icon="{{ $social->icon }}"
                                            data-order="{{ $social->display_order }}"
                                            data-active="{{ $social->is_active ? '1' : '0' }}"
                                            style="background: rgba(52, 139, 203, 0.1); color: var(--accent); padding: 6px 8px; border-radius: 4px; border: none; cursor: pointer;"
                                            title="Edit Social Link">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    </button>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.footer.social.destroy', $social) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Delete this social link from the footer?');" 
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 6px 8px; border-radius: 4px; border: none; cursor: pointer;"
                                                title="Delete Social Link">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-light);">
                                <span class="material-symbols-outlined" style="font-size: 36px; color: var(--text-muted); display: block; margin-bottom: 8px;">link_off</span>
                                No social links configured. Add one using the form on the right.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Add New Social Link Form -->
        <div class="admin-card">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0 0 16px 0; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">
                Add Social Link
            </h3>

            <form action="{{ route('admin.footer.social.store') }}" method="POST" id="add-social-form">
                @csrf

                <!-- Platform Selection -->
                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="add_platform">Platform *</label>
                    <select id="add_platform" name="platform" class="form-control" required onchange="handlePlatformChange(this, 'add')">
                        @foreach($supportedPlatforms as $key => $p)
                            <option value="{{ $key }}" data-icon="{{ $p['icon'] }}">{{ $p['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Platform Name (Conditional) -->
                <div id="add_custom_platform_wrapper" style="margin-bottom: 16px; display: none;">
                    <label class="form-label" for="add_custom_platform">Custom Platform Name *</label>
                    <input type="text" id="add_custom_platform" name="custom_platform" class="form-control" placeholder="e.g. Mastodon, Substack, Dribbble">
                </div>

                <!-- Destination URL -->
                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="add_url">Destination URL *</label>
                    <input type="url" id="add_url" name="url" class="form-control" required placeholder="https://github.com/your-username">
                    <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Must start with <code>http://</code> or <code>https://</code>.</span>
                </div>

                <!-- Accessible Display Label -->
                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="add_label">Accessible Label (Optional)</label>
                    <input type="text" id="add_label" name="label" class="form-control" placeholder="e.g. Victor Oduor on GitHub">
                    <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">Used for tooltips and screen-reader accessibility.</span>
                </div>

                <!-- Icon Identifier -->
                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="add_icon">Icon Identifier *</label>
                    <select id="add_icon" name="icon" class="form-control" required>
                        @foreach($supportedIcons as $iconKey => $iconTitle)
                            <option value="{{ $iconKey }}">{{ $iconTitle }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Display Order -->
                <div style="margin-bottom: 16px;">
                    <label class="form-label" for="add_display_order">Display Order</label>
                    <input type="number" id="add_display_order" name="display_order" class="form-control" min="0" max="9999" value="{{ (int)($socialLinks->max('display_order') ?? 0) + 1 }}">
                </div>

                <!-- Active Toggle -->
                <div style="margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                        <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                        <span>Publish &amp; Show in Footer</span>
                    </label>
                </div>

                <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 12px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add_link</span>
                    Add Social Link
                </button>
            </form>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: EDIT SOCIAL LINK
     ========================================================================= -->
<div id="edit-social-modal" class="hidden" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="admin-card" style="width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; margin-bottom: 0; box-shadow: var(--shadow-xl);">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 20px;">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Edit Social Link</h3>
            <button type="button" onclick="closeEditModal()" style="background: transparent; border: none; color: var(--text-light); cursor: pointer; padding: 4px;">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="edit-social-form" method="POST">
            @csrf
            @method('PUT')

            <!-- Platform -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_platform">Platform *</label>
                <select id="edit_platform" name="platform" class="form-control" required onchange="handlePlatformChange(this, 'edit')">
                    @foreach($supportedPlatforms as $key => $p)
                        <option value="{{ $key }}" data-icon="{{ $p['icon'] }}">{{ $p['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Custom Platform Input -->
            <div id="edit_custom_platform_wrapper" style="margin-bottom: 16px; display: none;">
                <label class="form-label" for="edit_custom_platform">Custom Platform Name *</label>
                <input type="text" id="edit_custom_platform" name="custom_platform" class="form-control">
            </div>

            <!-- Destination URL -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_url">Destination URL *</label>
                <input type="url" id="edit_url" name="url" class="form-control" required>
            </div>

            <!-- Accessible Label -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_label">Accessible Label (Optional)</label>
                <input type="text" id="edit_label" name="label" class="form-control">
            </div>

            <!-- Icon -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_icon">Icon Identifier *</label>
                <select id="edit_icon" name="icon" class="form-control" required>
                    @foreach($supportedIcons as $iconKey => $iconTitle)
                        <option value="{{ $iconKey }}">{{ $iconTitle }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Display Order -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_display_order">Display Order</label>
                <input type="number" id="edit_display_order" name="display_order" class="form-control" min="0" max="9999">
            </div>

            <!-- Active Checkbox -->
            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" style="width: 18px; height: 18px;">
                    <span>Active and Visible in Footer</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeEditModal()" class="btn-hero-primary" style="background: var(--surface-variant); color: var(--text); border: 1px solid var(--border); padding: 10px 20px;">
                    Cancel
                </button>
                <button type="submit" class="btn-hero-primary" style="padding: 10px 24px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Update Link
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function handlePlatformChange(selectElem, prefix) {
    const customWrapper = document.getElementById(prefix + '_custom_platform_wrapper');
    const iconSelect = document.getElementById(prefix + '_icon');
    const selectedOption = selectElem.options[selectElem.selectedIndex];

    if (selectElem.value === 'Custom') {
        if (customWrapper) customWrapper.style.display = 'block';
    } else {
        if (customWrapper) customWrapper.style.display = 'none';
        const defaultIcon = selectedOption.getAttribute('data-icon');
        if (defaultIcon && iconSelect) {
            iconSelect.value = defaultIcon;
        }
    }
}

// Edit Modal Handler
const editModal = document.getElementById('edit-social-modal');
const editForm = document.getElementById('edit-social-form');

document.querySelectorAll('.btn-edit-social').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const platform = button.getAttribute('data-platform');
        const url = button.getAttribute('data-url');
        const label = button.getAttribute('data-label') || '';
        const icon = button.getAttribute('data-icon');
        const order = button.getAttribute('data-order');
        const active = button.getAttribute('data-active') === '1';

        editForm.action = `/admin/footer/social/${id}`;

        const platformSelect = document.getElementById('edit_platform');
        const customWrapper = document.getElementById('edit_custom_platform_wrapper');
        const customInput = document.getElementById('edit_custom_platform');

        let matched = false;
        for (let i = 0; i < platformSelect.options.length; i++) {
            if (platformSelect.options[i].value.toLowerCase() === platform.toLowerCase()) {
                platformSelect.selectedIndex = i;
                matched = true;
                break;
            }
        }

        if (!matched) {
            platformSelect.value = 'Custom';
            customWrapper.style.display = 'block';
            customInput.value = platform;
        } else {
            customWrapper.style.display = 'none';
            customInput.value = '';
        }

        document.getElementById('edit_url').value = url;
        document.getElementById('edit_label').value = label;
        document.getElementById('edit_icon').value = icon;
        document.getElementById('edit_display_order').value = order;
        document.getElementById('edit_is_active').checked = active;

        editModal.style.display = 'flex';
        editModal.classList.remove('hidden');
    });
});

function closeEditModal() {
    if (editModal) {
        editModal.style.display = 'none';
        editModal.classList.add('hidden');
    }
}

// Close modal on Escape or backdrop click
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && editModal && !editModal.classList.contains('hidden')) {
        closeEditModal();
    }
});
if (editModal) {
    editModal.addEventListener('click', (e) => {
        if (e.target === editModal) closeEditModal();
    });
}

// Order changes tracking & AJAX batch update
const orderInputs = document.querySelectorAll('.order-input');
const btnSaveOrder = document.getElementById('btn-save-order');

orderInputs.forEach(input => {
    input.addEventListener('change', () => {
        if (btnSaveOrder) btnSaveOrder.style.display = 'inline-flex';
    });
});

if (btnSaveOrder) {
    btnSaveOrder.addEventListener('click', async () => {
        const payload = [];
        orderInputs.forEach(input => {
            payload.push({
                id: parseInt(input.getAttribute('data-id'), 10),
                order: parseInt(input.value, 10) || 0
            });
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const res = await fetch('{{ route("admin.footer.social.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ orders: payload })
            });

            if (res.ok) {
                window.location.reload();
            } else {
                alert('Failed to update orders. Please try again.');
            }
        } catch (err) {
            console.error(err);
            alert('Network error while saving order.');
        }
    });
}
</script>
@endpush
@endsection
