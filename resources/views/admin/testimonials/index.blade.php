@extends('layouts.admin')

@section('title', 'Manage Testimonials')
@section('header', 'Client Testimonials Management')

@section('content')
<!-- Metric Summary Cards -->
<div class="admin-grid-stats" style="margin-bottom: 28px;">
    <div class="admin-card" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(33, 45, 82, 0.08); display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <span class="material-symbols-outlined" style="font-size: 26px;">reviews</span>
        </div>
        <div>
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Total Testimonials</span>
            <h3 style="font-size: 24px; font-weight: 800; color: var(--primary); margin: 2px 0 0 0;">{{ $testimonials->count() }}</h3>
        </div>
    </div>

    <div class="admin-card" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(19, 115, 51, 0.1); display: flex; align-items: center; justify-content: center; color: #137333;">
            <span class="material-symbols-outlined" style="font-size: 26px;">visibility</span>
        </div>
        <div>
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Published (Live)</span>
            <h3 style="font-size: 24px; font-weight: 800; color: #137333; margin: 2px 0 0 0;">{{ $testimonials->where('is_published', true)->count() }}</h3>
        </div>
    </div>

    <div class="admin-card" style="display: flex; align-items: center; gap: 16px; padding: 20px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(228, 116, 41, 0.1); display: flex; align-items: center; justify-content: center; color: var(--secondary);">
            <span class="material-symbols-outlined" style="font-size: 26px;">visibility_off</span>
        </div>
        <div>
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px;">Unpublished (Draft)</span>
            <h3 style="font-size: 24px; font-weight: 800; color: var(--secondary); margin: 2px 0 0 0;">{{ $testimonials->where('is_published', false)->count() }}</h3>
        </div>
    </div>
</div>

<div class="admin-grid-split">
    <!-- Left Column: Testimonials Table -->
    <div class="admin-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0;">Testimonials Records</h3>
                <span style="font-size: 13px; color: var(--text-muted);">Manage publishing status, client feedback, ratings, and avatars</span>
            </div>
            <span class="tag-badge" style="font-size: 11px;">Sorted by Display Order &amp; Date</span>
        </div>

        <div class="admin-table-responsive">
            <table class="data-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="min-width: 170px;">Client</th>
                        <th style="min-width: 180px;">Feedback Quote</th>
                        <th style="width: 90px;">Rating</th>
                        <th style="width: 60px; text-align: center;">Order</th>
                        <th style="width: 110px;">Status</th>
                        <th style="min-width: 150px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                    <tr>
                        <!-- Client & Organization with Avatar -->
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($t->image_url)
                                    <img src="{{ asset(ltrim($t->image_url, '/')) }}" alt="{{ $t->client_name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border); flex-shrink: 0;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--surface-variant); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary); font-size: 14px; flex-shrink: 0; border: 2px solid var(--border);">
                                        {{ $t->initials }}
                                    </div>
                                @endif
                                <div style="min-width: 0;">
                                    <strong style="color: var(--primary); display: block; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $t->client_name }}</strong>
                                    <span style="font-size: 12px; color: var(--text-muted); display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $t->company ?: 'Independent Collaborator' }}</span>
                                    <span style="font-size: 11px; color: var(--text-light); display: block;">{{ $t->created_at ? $t->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Feedback Quote -->
                        <td>
                            <p style="margin: 0; font-size: 13px; color: var(--text); line-height: 1.5; font-style: italic; max-width: 320px;">
                                &ldquo;{{ Str::limit($t->content, 110) }}&rdquo;
                            </p>
                        </td>

                        <!-- Rating Stars -->
                        <td>
                            @if($t->rating)
                                <div style="display: flex; align-items: center; gap: 2px;" title="{{ $t->rating }} / 5 Stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined" style="font-size: 16px; color: {{ $i <= $t->rating ? '#f59e0b' : 'var(--border)' }};">
                                            star
                                        </span>
                                    @endfor
                                    <span style="font-size: 11px; font-weight: 700; color: #b45309; margin-left: 4px;">{{ $t->rating }}.0</span>
                                </div>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted); font-style: italic;">No rating</span>
                            @endif
                        </td>

                        <!-- Order -->
                        <td style="text-align: center;">
                            <span class="tag-badge" style="font-size: 11px; font-weight: 600;">#{{ $t->display_order ?? 0 }}</span>
                        </td>

                        <!-- Publication Status -->
                        <td>
                            @if($t->is_published)
                                <span class="status-badge status-completed" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">check_circle</span>
                                    Published
                                </span>
                            @else
                                <span class="status-badge status-planned" style="display: inline-flex; align-items: center; gap: 4px; font-size: 11px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">visibility_off</span>
                                    Unpublished
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td style="text-align: right;">
                            <div style="display: inline-flex; align-items: center; gap: 6px;">
                                <!-- Toggle Publication Form -->
                                <form action="{{ route('admin.testimonials.toggle', $t) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @if($t->is_published)
                                        <button type="submit" class="admin-btn-action" title="Unpublish from live website" style="background: rgba(228, 116, 41, 0.1); color: var(--secondary); border: 1px solid rgba(228, 116, 41, 0.2); padding: 5px 8px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">visibility_off</span>
                                            <span>Unpublish</span>
                                        </button>
                                    @else
                                        <button type="submit" class="admin-btn-action" title="Publish to live website" style="background: rgba(19, 115, 51, 0.1); color: #137333; border: 1px solid rgba(19, 115, 51, 0.2); padding: 5px 8px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                                            <span>Publish</span>
                                        </button>
                                    @endif
                                </form>

                                <!-- Edit Button -->
                                <button type="button" onclick="openEditModal({{ json_encode($t) }})" title="Edit Testimonial" style="background: rgba(33, 45, 82, 0.08); color: var(--primary); border: 1px solid var(--border); padding: 5px 8px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                    <span>Edit</span>
                                </button>

                                <!-- Delete Confirmation Button -->
                                <button type="button" onclick="openDeleteModal('{{ route('admin.testimonials.destroy', $t) }}', '{{ addslashes($t->client_name) }}')" title="Delete Testimonial" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); padding: 5px 8px; border-radius: var(--radius-sm); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-light);">
                            <span class="material-symbols-outlined" style="font-size: 40px; color: var(--text-muted); display: block; margin-bottom: 10px;">rate_review</span>
                            <strong style="color: var(--primary); display: block; margin-bottom: 4px;">No testimonials found</strong>
                            <span>Add your first client testimonial using the form on the right.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Add Testimonial Form -->
    <div class="admin-card">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0; border-bottom: 1px solid var(--border-light); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="font-size: 20px; color: var(--secondary);">add_comment</span>
            Add Testimonial
        </h3>

        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Client / Author Name -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="add_client_name">Client / Author Name *</label>
                <input type="text" id="add_client_name" name="client_name" class="form-control" required placeholder="e.g. Peter Kamau" value="{{ old('client_name') }}">
            </div>

            <!-- Company / Role -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="add_company">Organization / Role (Optional)</label>
                <input type="text" id="add_company" name="company" class="form-control" placeholder="e.g. Modern Street Rehani / Lead Architect" value="{{ old('company') }}">
            </div>

            <!-- Rating & Display Order Row -->
            <div class="admin-form-row-2col" style="margin-bottom: 16px;">
                <div>
                    <label class="form-label" for="add_rating">Rating (Optional)</label>
                    <select id="add_rating" name="rating" class="form-control">
                        <option value="">No Rating</option>
                        <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars (Exceptional)</option>
                        <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars (Great)</option>
                        <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars (Good)</option>
                        <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars (Fair)</option>
                        <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star (Basic)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="add_display_order">Display Order</label>
                    <input type="number" id="add_display_order" name="display_order" class="form-control" min="0" max="9999" value="{{ old('display_order', 0) }}">
                </div>
            </div>

            <!-- Testimonial Quote -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="add_content">Testimonial Quote / Feedback *</label>
                <textarea id="add_content" name="content" class="form-control" rows="4" required placeholder="Describe the project outcome, collaboration experience, or engineering feedback...">{{ old('content') }}</textarea>
            </div>

            <!-- Client Photo Upload -->
            <div style="margin-bottom: 18px;">
                <label class="form-label" for="add_image">Client Profile Photo (Optional)</label>
                <input type="file" id="add_image" name="image" class="form-control" accept="image/*">
                <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 4px;">PNG, JPG, or WebP up to 3MB. Fallback initial bubble is generated automatically.</span>
            </div>

            <!-- Deliberate Publication Checkbox (Unchecked by default) -->
            <div style="margin-bottom: 24px; background: var(--background); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border);">
                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Publish immediately to live website</span>
                        <span style="display: block; font-size: 11px; font-weight: normal; color: var(--text-muted); margin-top: 2px;">
                            Leave unchecked to save as an unpublished draft for review.
                        </span>
                    </div>
                </label>
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 12px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                Add Testimonial
            </button>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL: EDIT TESTIMONIAL
     ========================================================================= -->
<div id="edit-testimonial-modal" class="hidden" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="admin-card" style="width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto; margin-bottom: 0; box-shadow: var(--shadow-xl);">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-light); padding-bottom: 12px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 22px; color: var(--primary);">edit</span>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Edit Testimonial</h3>
            </div>
            <button type="button" onclick="closeEditModal()" style="background: transparent; border: none; color: var(--text-light); cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="edit-testimonial-form" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Client Name -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_client_name">Client / Author Name *</label>
                <input type="text" id="edit_client_name" name="client_name" class="form-control" required>
            </div>

            <!-- Company / Role -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_company">Organization / Role</label>
                <input type="text" id="edit_company" name="company" class="form-control">
            </div>

            <!-- Rating & Order -->
            <div class="admin-form-row-2col" style="margin-bottom: 16px;">
                <div>
                    <label class="form-label" for="edit_rating">Rating (Optional)</label>
                    <select id="edit_rating" name="rating" class="form-control">
                        <option value="">No Rating</option>
                        <option value="5">5 Stars (Exceptional)</option>
                        <option value="4">4 Stars (Great)</option>
                        <option value="3">3 Stars (Good)</option>
                        <option value="2">2 Stars (Fair)</option>
                        <option value="1">1 Star (Basic)</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="edit_display_order">Display Order</label>
                    <input type="number" id="edit_display_order" name="display_order" class="form-control" min="0" max="9999">
                </div>
            </div>

            <!-- Testimonial Quote -->
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="edit_content">Testimonial Quote / Feedback *</label>
                <textarea id="edit_content" name="content" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Current Photo Preview & Upload -->
            <div style="margin-bottom: 20px;">
                <label class="form-label">Client Profile Photo</label>
                <div id="edit-current-image-wrapper" style="display: flex; align-items: center; gap: 14px; margin-bottom: 10px; padding: 10px; background: var(--background); border-radius: var(--radius-md); border: 1px solid var(--border);">
                    <img id="edit-preview-img" src="" alt="Avatar" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                    <div style="flex: 1;">
                        <span style="font-size: 12px; font-weight: 600; color: var(--text); display: block;">Current Image Attached</span>
                        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: #dc2626; cursor: pointer; margin-top: 4px;">
                            <input type="checkbox" id="edit_remove_image" name="remove_image" value="1">
                            <span>Remove photo (revert to initials avatar)</span>
                        </label>
                    </div>
                </div>

                <input type="file" id="edit_image" name="image" class="form-control" accept="image/*">
                <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 4px;">Select a new file to replace the existing photo.</span>
            </div>

            <!-- Publication Status -->
            <div style="margin-bottom: 24px; background: var(--background); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border);">
                <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text);">
                    <input type="checkbox" id="edit_is_published" name="is_published" value="1" style="width: 18px; height: 18px; margin-top: 2px;">
                    <div>
                        <span>Published to live website</span>
                        <span style="display: block; font-size: 11px; font-weight: normal; color: var(--text-muted); margin-top: 2px;">
                            Uncheck to hide immediately from the public carousel without deleting data.
                        </span>
                    </div>
                </label>
            </div>

            <!-- Modal Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-light); padding-top: 16px;">
                <button type="button" onclick="closeEditModal()" style="padding: 10px 18px; border: 1px solid var(--border); background: var(--surface); color: var(--text); border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" class="btn-hero-primary" style="padding: 10px 22px; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">check</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     MODAL: DELETE CONFIRMATION
     ========================================================================= -->
<div id="delete-testimonial-modal" class="hidden" style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="admin-card" style="width: 100%; max-width: 440px; margin-bottom: 0; box-shadow: var(--shadow-xl); text-align: center; padding: 28px;">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); color: #dc2626; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
            <span class="material-symbols-outlined" style="font-size: 30px;">warning</span>
        </div>

        <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Delete Testimonial?</h3>
        <p style="font-size: 14px; color: var(--text-light); line-height: 1.5; margin: 0 0 20px 0;">
            Are you sure you want to permanently delete the testimonial from <strong id="delete-client-name" style="color: var(--primary);"></strong>?
            This will remove it from the database and clean up any associated uploaded photo.
        </p>

        <form id="delete-testimonial-form" method="POST">
            @csrf
            @method('DELETE')
            <div style="display: flex; justify-content: center; gap: 12px;">
                <button type="button" onclick="closeDeleteModal()" style="padding: 10px 18px; border: 1px solid var(--border); background: var(--surface); color: var(--text); border-radius: var(--radius-md); font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding: 10px 22px; background: #dc2626; color: #ffffff; border: none; border-radius: var(--radius-md); font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">delete_forever</span>
                    Yes, Delete Testimonial
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Edit Modal Logic
    function openEditModal(testimonial) {
        const modal = document.getElementById('edit-testimonial-modal');
        const form = document.getElementById('edit-testimonial-form');
        form.action = `/admin/testimonials/${testimonial.id}`;

        document.getElementById('edit_client_name').value = testimonial.client_name || '';
        document.getElementById('edit_company').value = testimonial.company || '';
        document.getElementById('edit_content').value = testimonial.content || '';
        document.getElementById('edit_rating').value = testimonial.rating || '';
        document.getElementById('edit_display_order').value = testimonial.display_order ?? 0;
        document.getElementById('edit_is_published').checked = Boolean(testimonial.is_published);
        document.getElementById('edit_remove_image').checked = false;

        const imgWrapper = document.getElementById('edit-current-image-wrapper');
        const previewImg = document.getElementById('edit-preview-img');
        if (testimonial.image_url) {
            previewImg.src = testimonial.image_url.startsWith('http') || testimonial.image_url.startsWith('/') 
                ? testimonial.image_url 
                : '/' + testimonial.image_url;
            imgWrapper.style.display = 'flex';
        } else {
            imgWrapper.style.display = 'none';
        }

        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-testimonial-modal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    // Delete Modal Logic
    function openDeleteModal(deleteUrl, clientName) {
        const modal = document.getElementById('delete-testimonial-modal');
        const form = document.getElementById('delete-testimonial-form');
        form.action = deleteUrl;
        document.getElementById('delete-client-name').textContent = clientName;

        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-testimonial-modal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
        const editModal = document.getElementById('edit-testimonial-modal');
        const deleteModal = document.getElementById('delete-testimonial-modal');
        if (e.target === editModal) closeEditModal();
        if (e.target === deleteModal) closeDeleteModal();
    });

    // Close on Escape key
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endsection
