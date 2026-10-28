<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\MailService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display the standalone Contact page.
     */
    public function index(): View
    {
        return view('pages.contact');
    }

    /**
     * Handle visitor contact form submission
     */
    public function submit(Request $request): JsonResponse|RedirectResponse
    {
        // 1. Validate input
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-Z\s\-\'\p{L}]+$/u'],
            'last_name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-Z\s\-\'\p{L}]+$/u'],
            'phone_number' => ['nullable', 'string', 'max:30', 'regex:/^[\+\d\s\-\(\)]*$/'],
            'email' => ['required', 'email:rfc,dns', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
            'phone_number.regex' => 'Please provide a valid phone number format.',
            'email.email' => 'Please provide a valid email address.',
            'message.min' => 'Please include at least 10 characters in your message.',
        ]);

        // 2. Client IP extraction
        $ip = $request->ip() ?: '127.0.0.1';

        // 3. Rate limiting check (max 3 messages per hour per IP)
        $oneHourAgo = now()->subHour();
        $recentCount = Contact::where('ip_address', $ip)
            ->where('created_at', '>=', $oneHourAgo)
            ->count();

        if ($recentCount >= 3) {
            $msg = 'Rate limit reached. You can submit up to 3 messages per hour. Please try again later.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                ], 429);
            }
            return back()->withInput()->with('error', $msg);
        }

        // 4. Persist contact record
        $contact = Contact::create([
            'first_name' => strip_tags(trim($validated['first_name'])),
            'last_name' => strip_tags(trim($validated['last_name'])),
            'phone_number' => !empty($validated['phone_number']) ? strip_tags(trim($validated['phone_number'])) : null,
            'email' => strtolower(trim($validated['email'])),
            'message' => strip_tags(trim($validated['message'])),
            'ip_address' => $ip,
            'created_at' => now(),
        ]);

        // 5. Send notifications
        MailService::handleContactSubmission($contact);
        Log::info("New contact submission received from {$contact->first_name} ({$contact->email}) [IP: {$ip}]");

        $successMsg = 'Thank you! Your message has been sent successfully. Victor will respond within 24-48 hours.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
            ]);
        }

        return back()->with('success', $successMsg);
    }
}
