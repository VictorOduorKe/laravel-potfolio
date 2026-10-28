<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BackupController as AdminBackupController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FooterController as AdminFooterController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\RoadmapController as AdminRoadmapController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\UploadController;
use App\Models\News;
use App\Models\Project;
use App\Models\Roadmap;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Public Portfolio Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::post('/chat', [ChatController::class, 'chat'])->name('chat');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

/*
|--------------------------------------------------------------------------
| Storage & Uploads Direct File Delivery (Symlink & Shared-Hosting Fallback)
|--------------------------------------------------------------------------
*/
$safeStaticExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'avif', 'ico',
    'pdf', 'txt', 'mp4', 'webm', 'ogg', 'mp3', 'wav', 'zip',
];

Route::get('/storage/{path}', function (string $path) use ($safeStaticExtensions) {
    if (str_starts_with($path, '.') || str_contains($path, '/.') || str_contains($path, '\\.')) {
        abort(404);
    }

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (! in_array($extension, $safeStaticExtensions, true)) {
        abort(404);
    }

    $basePath = realpath(storage_path('app/public'));
    $targetPath = realpath($basePath . DIRECTORY_SEPARATOR . $path);

    if ($targetPath && $basePath && str_starts_with($targetPath, $basePath) && is_file($targetPath)) {
        return response()->file($targetPath);
    }
    abort(404);
})->where('path', '.*')->name('storage.stream');

Route::get('/uploads/{path}', function (string $path) use ($safeStaticExtensions) {
    if (str_starts_with($path, '.') || str_contains($path, '/.') || str_contains($path, '\\.')) {
        abort(404);
    }

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (! in_array($extension, $safeStaticExtensions, true)) {
        abort(404);
    }

    // 1. Check storage/app/public/uploads first (pointing uploads to storage)
    $storageBase = realpath(storage_path('app/public/uploads'));
    $targetStorage = $storageBase ? realpath($storageBase . DIRECTORY_SEPARATOR . $path) : false;

    if ($targetStorage && $storageBase && str_starts_with($targetStorage, $storageBase) && is_file($targetStorage)) {
        return response()->file($targetStorage);
    }

    // 2. Fallback to public/uploads
    $publicBase = realpath(public_path('uploads'));
    $targetPublic = $publicBase ? realpath($publicBase . DIRECTORY_SEPARATOR . $path) : false;

    if ($targetPublic && $publicBase && str_starts_with($targetPublic, $publicBase) && is_file($targetPublic)) {
        return response()->file($targetPublic);
    }

    abort(404);
})->where('path', '.*')->name('uploads.stream');

/*
|--------------------------------------------------------------------------
| Backward-Compatible API Routes (for any legacy fetch calls)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::get('/csrf-token', fn () => response()->json(['csrf_token' => csrf_token()]));
    Route::get('/projects', fn () => response()->json(Project::where('is_published', true)->with('gallery')->orderBy('created_at', 'desc')->get()));
    Route::get('/skills', fn () => response()->json(Skill::where('is_published', true)->orderBy('category')->orderBy('proficiency', 'desc')->get()));
    Route::get('/roadmaps', fn () => response()->json(Roadmap::where('is_published', true)->orderBy('target_date', 'desc')->get()));
    Route::get('/news', fn () => response()->json(News::where('is_published', true)->orderBy('created_at', 'desc')->get()));
    Route::get('/testimonials', function () {
        $testimonials = Cache::remember('public_testimonials', 3600, function () {
            return Testimonial::published()
                ->orderBy('display_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        });
        return response()->json($testimonials);
    });
    Route::post('/contact', [ContactController::class, 'submit']);
    Route::post('/chat', [ChatController::class, 'chat']);
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/verify', [AdminAuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [AdminAuthController::class, 'verify']);
    Route::post('/resend-otp', [AdminAuthController::class, 'resendOtp'])->name('resend-otp');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Projects CRUD
    Route::resource('projects', AdminProjectController::class)->except(['show']);
    Route::post('/projects/{project}/toggle', [AdminProjectController::class, 'togglePublish'])->name('projects.toggle');
    Route::delete('/gallery/{gallery}', [AdminProjectController::class, 'deleteGalleryImage'])->name('gallery.delete');

    // Skills CRUD
    Route::resource('skills', AdminSkillController::class)->except(['create', 'show', 'edit']);
    Route::post('/skills/{skill}/toggle', [AdminSkillController::class, 'togglePublish'])->name('skills.toggle');

    // Roadmaps CRUD
    Route::resource('roadmaps', AdminRoadmapController::class)->except(['create', 'show', 'edit']);
    Route::post('/roadmaps/{roadmap}/toggle', [AdminRoadmapController::class, 'togglePublish'])->name('roadmaps.toggle');

    // News CRUD & AI Trigger
    Route::resource('news', AdminNewsController::class)->except(['create', 'show', 'edit']);
    Route::post('/news/{news}/toggle', [AdminNewsController::class, 'togglePublish'])->name('news.toggle');
    Route::post('/news-generate', [AdminNewsController::class, 'generateNow'])->name('news.generate');

    // Testimonials CRUD
    Route::resource('testimonials', AdminTestimonialController::class)->except(['create', 'show', 'edit']);
    Route::post('/testimonials/{testimonial}/toggle', [AdminTestimonialController::class, 'togglePublish'])->name('testimonials.toggle');

    // Messages Inbox
    Route::resource('messages', AdminContactController::class)->only(['index', 'show', 'destroy']);

    // System Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Footer & Social Links Settings
    Route::get('/footer', [AdminFooterController::class, 'index'])->name('footer.index');
    Route::post('/footer/contact', [AdminFooterController::class, 'updateContact'])->name('footer.contact.update');
    Route::post('/footer/social', [AdminFooterController::class, 'storeSocial'])->name('footer.social.store');
    Route::put('/footer/social/{socialLink}', [AdminFooterController::class, 'updateSocial'])->name('footer.social.update');
    Route::delete('/footer/social/{socialLink}', [AdminFooterController::class, 'destroySocial'])->name('footer.social.destroy');
    Route::post('/footer/social/{socialLink}/toggle', [AdminFooterController::class, 'toggleSocial'])->name('footer.social.toggle');
    Route::post('/footer/social/reorder', [AdminFooterController::class, 'reorderSocial'])->name('footer.social.reorder');

    // Backups
    Route::get('/backup', [AdminBackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [AdminBackupController::class, 'create'])->name('backup.create');
    Route::get('/backup/download/{filename}', [AdminBackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [AdminBackupController::class, 'destroy'])->name('backup.destroy');

    // Activity Logs
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
    Route::post('/logs/clear', [AdminLogController::class, 'clear'])->name('logs.clear');

    // Image Upload helper
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
});


