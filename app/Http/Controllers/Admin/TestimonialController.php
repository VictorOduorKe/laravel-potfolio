<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'max:3072'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/testimonials', 'public');
            $imageUrl = '/storage/' . $path;
        }

        Testimonial::create([
            'client_name' => $validated['client_name'],
            'company' => $validated['company'] ?? null,
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'image_url' => $imageUrl,
            // Deliberate publication policy: defaults to unpublished unless explicitly set
            'is_published' => $request->boolean('is_published', false),
            'created_at' => now(),
        ]);

        Cache::forget('public_testimonials');

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image' => ['nullable', 'image', 'max:3072'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'remove_image' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $imageUrl = $testimonial->image_url;

        if ($request->boolean('remove_image')) {
            $this->deleteOldImageIfUnshared($testimonial->image_url, $testimonial->id);
            $imageUrl = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteOldImageIfUnshared($testimonial->image_url, $testimonial->id);
            $path = $request->file('image')->store('uploads/testimonials', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $imageUrl = $validated['image_url'];
        }

        $testimonial->update([
            'client_name' => $validated['client_name'],
            'company' => $validated['company'] ?? null,
            'content' => $validated['content'],
            'rating' => $validated['rating'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'image_url' => $imageUrl,
            'is_published' => $request->boolean('is_published'),
        ]);

        Cache::forget('public_testimonials');

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deleteOldImageIfUnshared($testimonial->image_url, $testimonial->id);
        $testimonial->delete();

        Cache::forget('public_testimonials');

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function togglePublish(Testimonial $testimonial, Request $request)
    {
        $testimonial->update([
            'is_published' => !$testimonial->is_published,
        ]);

        Cache::forget('public_testimonials');

        $statusMessage = $testimonial->is_published
            ? "Testimonial by {$testimonial->client_name} is now published and visible publicly."
            : "Testimonial by {$testimonial->client_name} has been unpublished.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_published' => $testimonial->is_published,
                'message' => $statusMessage,
            ]);
        }

        return redirect()->route('admin.testimonials.index')->with('success', $statusMessage);
    }

    /**
     * Delete an uploaded testimonial photo if it is stored locally and not shared by another testimonial.
     */
    private function deleteOldImageIfUnshared(?string $imageUrl, ?int $currentId = null): void
    {
        if (empty($imageUrl)) {
            return;
        }

        // Normalize path from /storage/uploads/testimonials/xyz.jpg to uploads/testimonials/xyz.jpg
        $normalized = ltrim($imageUrl, '/');
        if (str_starts_with($normalized, 'storage/')) {
            $normalized = substr($normalized, 8);
        }

        // Only manage files inside uploads/testimonials/
        if (!str_starts_with($normalized, 'uploads/testimonials/')) {
            return;
        }

        // Verify no other testimonial shares this exact image path
        $isShared = Testimonial::where('image_url', $imageUrl)
            ->when($currentId, fn ($q) => $q->where('id', '!=', $currentId))
            ->exists();

        if (!$isShared && Storage::disk('public')->exists($normalized)) {
            Storage::disk('public')->delete($normalized);
        }
    }
}
