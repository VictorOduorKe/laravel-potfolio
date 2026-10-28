# Comprehensive Laravel Migration Plan

## Executive Summary
This document defines the architectural blueprint and operational roadmap for migrating **TheTechCraft** portfolio application from its existing decoupled architecture (React 19 + Vite frontend, custom PHP 8.2+ REST API backend, and MySQL database) into a unified, high-performance, monolithic **Laravel application**.

The target deployment environment is **HostPinnacle cPanel Shared Hosting** running Apache, PHP 8.2/8.4 with standard PDO extensions, and MySQL.

---

## 1. Current Architecture vs. Target Architecture

### 1.1. Current Architecture
```
                                 [Visitor Browser]
                                        │
                    ┌───────────────────┴───────────────────┐
                    ▼                                       ▼
     [Static React 19 Frontend SPA]             [Custom PHP REST API]
     - Vite 8 bundling                          - Front Controller (index.php)
     - Tailwind CSS v4                          - Custom Router & Controllers
     - React Router v7                          - Custom PDO Repositories
     - Client-side fetch() to /api              - Custom Auth / OTP / Session
     - Floating AI Chat (React)                 - GeminiService (cURL)
                                                - MailService (Socket SMTP)
                                                            │
                                                            ▼
                                                    [MySQL Database]
                                                    (12 tables)
```
- **Limitations of Current Setup:**
  - Decoupled deployment requires managing two domains (`thetechcraft.co.ke` and `backend.thetechcraft.co.ke`) with complex cross-origin (CORS) preflight requests.
  - SEO for the public landing page depends on client-side rendering or static HTML meta tags, with dynamic content (projects, skills, roadmaps, news) loaded asynchronously after page load.
  - Custom PHP router and repositories lack built-in CSRF lifecycle management, standard Eloquent ORM advantages, Form Requests, robust middleware pipelines, and automated testing capabilities.

### 1.2. Target Architecture (Unified Laravel Monolith)
```
                                 [Visitor / Admin Browser]
                                             │
                                             ▼
                                     [Apache / cPanel]
                                             │ (DocumentRoot -> public/)
                                             ▼
                             [Laravel Application Core]
        ┌────────────────────────────────────┼────────────────────────────────────┐
        ▼                                    ▼                                    ▼
[Blade View Layer]                  [Controller & Routing Layer]         [Security & Middleware]
- Layouts: app, admin               - Web Routes (web.php)               - CSRF Protection (built-in)
- Components: header, footer,       - API Compatibility (api.php)        - Admin Auth Middleware
  card, button, input, alert,       - Page Controllers: HomeController,  - Rate Limiting (Throttle)
  bento-skill, roadmap-tree           NewsController, Admin Controllers  - Input Sanitization
- Vite Assets: CSS & interactive    - GeminiService & MailService
  vanilla JS (Chat assistant,       - Console Commands (News Bot)
  mobile drawer, counters)                   │
        │                                    ▼
        │                            [Eloquent ORM]
        │                            - Models & Relationships
        │                            - Existing MySQL Database
        └────────────────────────────────────┘
```
- **Key Architectural Advantages:**
  - Single application deployable to cPanel without cross-subdomain CORS complexities.
  - Immediate server-side rendering (SSR) of all public projects, skills, roadmaps, news, and testimonials via Blade, maximizing SEO and Core Web Vitals (LCP/INP).
  - Modern interactive experiences (Gemini AI chat widget, responsive mobile drawer, animated counters, contact form AJAX submission) implemented cleanly with lightweight ES modules and Vite.
  - Eloquent ORM representing all 12 existing tables without data destruction or schema recreation.
  - Native Laravel session authentication for admin with the exact 2-step OTP email verification workflow.
  - Artisan command for automated news generation (`php artisan news:auto-generate`), easily scheduled in cPanel.

---

## 2. Feature & Laravel Equivalent Mapping

| Existing Feature | Current Implementation | Target Laravel Implementation |
| :--- | :--- | :--- |
| **Visitor Landing Page** | `client/src/pages/visitor/PortfolioApp.jsx` | `HomeController@index` rendering `views/pages/home.blade.php` |
| **News Feed Page** | `client/src/pages/visitor/NewsPage.jsx` | `NewsController@index` rendering `views/pages/news.blade.php` |
| **404 Not Found Page** | `client/src/pages/visitor/NotFoundPage.jsx` | Laravel fallback route rendering `views/errors/404.blade.php` |
| **Admin 2FA Login Flow** | `AdminApp.jsx` + `AuthController.php` | `Admin\AuthController` (`login`, `sendOtp`, `verifyOtp`, `logout`) with Blade views |
| **Admin Dashboard** | `DashboardLayout.jsx` + React Tabs | `Admin\DashboardController` + Blade tabs with `views/layouts/admin.blade.php` |
| **Projects Management** | `Projects.jsx` + `ProjectController.php` | `Admin\ProjectController` (CRUD, image upload, status toggle) |
| **Skills Bento Grid Admin**| `Skills.jsx` + `ContentController.php` | `Admin\SkillController` (CRUD, tags, badges, categories) |
| **Roadmap Hierarchy Admin**| `Roadmaps.jsx` + `ContentController.php`| `Admin\RoadmapController` (CRUD, tree nesting with `parent_id`) |
| **News & Blog Admin** | `News.jsx` + `ContentController.php` | `Admin\NewsController` (CRUD, manual trigger, interval config) |
| **Testimonials Admin** | `Testimonials.jsx` + `ContentController.php`| `Admin\TestimonialController` (CRUD, publish toggle) |
| **Contact Form & Messages**| `PortfolioApp.jsx` + `ContactController`| `ContactController@submit` + `Admin\ContactController@index` |
| **Contact Notifications** | `MailService.php` | Laravel Mailable / Notification (`ContactReceivedAdminMail`, `ContactConfirmationVisitorMail`) |
| **AI Assistant (Gemini)** | `ChatWidget.jsx` + `ChatController.php` | `ChatController@chat` + `App\Services\GeminiService` + Vanilla JS widget |
| **Automated Tech News Bot**| `backend/cron/auto_news.php` & `.sh` | Artisan Command `news:auto-generate` + `cron/scheduler` in cPanel |
| **Database Backup Tool** | `BackupManager.jsx` + `BackupController` | `Admin\BackupController@create` using PDO/mysqldump to `storage/app/backups` |
| **Activity Log Viewer** | `ActivityLogs.jsx` + `SettingsController` | `Admin\LogController@index` parsing `storage/logs/activity.log` |
| **System Settings** | `Settings.jsx` + `SettingsController` | `Admin\SettingController@index` / `update` |
| **Media Uploads** | `UploadController.php` | `UploadController@upload` with strict MIME validation & GD image resize |

---

## 3. Page & Route Mapping

### 3.1. Public Web Routes (`routes/web.php`)

| HTTP Method | URI Pattern | Target Controller Action | Description |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | `HomeController@index` | Public portfolio homepage (SSR with Blade) |
| `GET` | `/news` | `NewsController@index` | Public news feed (SSR with Blade) |
| `POST` | `/contact` | `ContactController@submit` | Contact form submission with CSRF and rate limiting |
| `GET` | `/csrf-token` | `ContactController@csrfToken` | Dynamic CSRF token refresh endpoint |
| `POST` | `/chat` | `ChatController@chat` | Gemini AI assistant message exchange |
| `GET` | `/health` | `HealthController@check` | Health check endpoint |
| `GET` | `/sitemap.xml`| `SeoController@sitemap` | Dynamic XML sitemap |
| `GET` | `/robots.txt` | `SeoController@robots` | Robots.txt crawler directives |

### 3.2. Admin Authentication & Dashboard Routes (`routes/web.php` with prefix `admin`)

| HTTP Method | URI Pattern | Target Controller Action | Middleware | Description |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/admin/login` | `Admin\AuthController@showLogin` | `guest:admin` | Admin login page (Step 1) |
| `POST` | `/admin/login` | `Admin\AuthController@login` | `guest:admin`, `throttle:5,15` | Validate credentials & send OTP |
| `GET` | `/admin/verify`| `Admin\AuthController@showVerify` | `guest:admin` | OTP verification page (Step 2) |
| `POST` | `/admin/verify`| `Admin\AuthController@verifyOtp` | `guest:admin`, `throttle:5,15` | Verify OTP code & authenticate |
| `POST` | `/admin/logout`| `Admin\AuthController@logout` | `auth:admin` | Invalidate session & logout |
| `GET` | `/admin` | `Admin\DashboardController@index` | `auth:admin` | Admin dashboard overview |
| `GET/POST`| `/admin/projects/*` | `Admin\ProjectController` | `auth:admin` | Project CRUD operations |
| `GET/POST`| `/admin/skills/*` | `Admin\SkillController` | `auth:admin` | Skill CRUD operations |
| `GET/POST`| `/admin/roadmaps/*` | `Admin\RoadmapController` | `auth:admin` | Roadmap CRUD operations |
| `GET/POST`| `/admin/news/*` | `Admin\NewsController` | `auth:admin` | News CRUD & auto-generate trigger |
| `GET/POST`| `/admin/testimonials/*` | `Admin\TestimonialController` | `auth:admin` | Testimonials CRUD operations |
| `GET` | `/admin/messages` | `Admin\ContactController@index` | `auth:admin` | Visitor messages inbox |
| `GET/POST`| `/admin/settings` | `Admin\SettingController` | `auth:admin` | System & news auto-update settings |
| `GET/POST`| `/admin/backup` | `Admin\BackupController` | `auth:admin` | Database backup manager |
| `GET` | `/admin/logs` | `Admin\LogController@index` | `auth:admin` | Activity log viewer |
| `POST` | `/admin/upload` | `Admin\UploadController@upload` | `auth:admin` | Media image uploader |

### 3.3. API Backward Compatibility Routes (`routes/api.php`)
To guarantee zero disruption for any external systems, webhooks, or mobile apps querying the previous REST endpoints:
- `GET /api/projects`, `GET /api/project`
- `GET /api/skills`
- `GET /api/roadmaps`
- `GET /api/news`, `GET /api/news/auto-update-config`, `POST /api/news/auto-generate`
- `GET /api/testimonials`
- `POST /api/contact`, `GET /api/csrf-token`
- `POST /api/chat`
- `POST /api/auth/login`, `POST /api/auth/verify`
- `GET /api/health`

---

## 4. Database Schema & Eloquent Model Mapping

All 12 tables in MySQL will be mapped directly to Eloquent models, preserving all primary keys, column types, and existing data without running destructive migrations:

| MySQL Table | Eloquent Model | Relationships & Casts |
| :--- | :--- | :--- |
| `admins` | `App\Models\Admin` | Authenticatable model (`username`, `email`, `password`, `login_token`, `login_token_expires_at`, `api_token`). Casts `login_token_expires_at` to `datetime`. |
| `projects` | `App\Models\Project` | `hasMany(ProjectGallery::class)`. Casts `is_published` to `boolean`, `created_at` and `updated_at` to `datetime`. |
| `project_gallery` | `App\Models\ProjectGallery` | `belongsTo(Project::class)`. |
| `skills` | `App\Models\Skill` | Casts `is_published` to `boolean`, `proficiency` to `integer`. |
| `roadmaps` | `App\Models\Roadmap` | `belongsTo(Roadmap::class, 'parent_id')`, `hasMany(Roadmap::class, 'parent_id')`. Casts `target_date` to `date`. |
| `news` | `App\Models\News` | Casts `published` and `is_published` to `boolean`, `created_at` to `datetime`. |
| `testimonials` | `App\Models\Testimonial` | Casts `is_published` to `boolean`. |
| `contacts` | `App\Models\Contact` | Casts `created_at` to `datetime`. |
| `settings` | `App\Models\Setting` | Helper methods `get(key, default)` and `set(key, value)`. |
| `chat_messages` | `App\Models\ChatMessage` | Casts `timestamp` to `datetime`. |
| `chat_rate_limits` | `App\Models\ChatRateLimit` | Composite or IP-based rate tracking. |
| `rate_limits` | `App\Models\RateLimit` | General IP/key rate tracking table. |

---

## 5. UI Component & Blade Template Mapping

The existing UI elements and design tokens (defined in `client/src/index.css`) will be mapped to modular Blade components and partials:

```
resources/views/
├── layouts/
│   ├── app.blade.php           # Main public portfolio layout (HTML5, SEO metadata, fonts, styles)
│   └── admin.blade.php         # Admin layout (responsive sidebar, navigation, user state)
├── components/
│   ├── header.blade.php        # Fixed header with logo, navigation links, and mobile menu
│   ├── footer.blade.php        # Footer with copyright, social links, admin link
│   ├── button.blade.php        # Reusable button with variants (primary, secondary, danger, outline)
│   ├── card.blade.php          # Reusable card container
│   ├── input.blade.php         # Form input with floating label and error feedback
│   ├── chat-widget.blade.php   # Floating Gemini AI assistant widget
│   └── toast.blade.php         # Floating dismissable toast notification
├── pages/
│   ├── home.blade.php          # Landing page (Hero, Projects, Skills, Roadmap, News, Contact, Testimonials)
│   ├── news.blade.php          # Dedicated news feed page
│   └── partials/
│       ├── hero.blade.php      # Hero section with animated profile image and CTAs
│       ├── story-strip.blade.php # Impact quotes and divider strip
│       ├── projects.blade.php  # Level 1 (Featured), Level 2 (Core), Level 3 (Additional)
│       ├── impact.blade.php    # System stats counter strip (12+ Systems, 99.9% Uptime, etc.)
│       ├── skills.blade.php    # 3-column Bento grid organized by operational domain
│       ├── roadmap.blade.php   # Nested hierarchical roadmap tree
│       ├── news-preview.blade.php # Latest 2 news cards with "Read all" link
│       ├── contact.blade.php   # Contact form with CSRF, validation, and feedback
│       └── testimonials.blade.php # Quotes and client recommendations
├── admin/
│   ├── auth/
│   │   ├── login.blade.php     # Step 1: Email and password login
│   │   └── verify.blade.php    # Step 2: 6-digit OTP verification
│   ├── dashboard.blade.php     # Admin dashboard tab container
│   ├── projects/               # Projects management list and form
│   ├── skills/                 # Skills management list and form
│   ├── roadmaps/               # Roadmap management list and form
│   ├── news/                   # News management, interval, auto-generate button
│   ├── testimonials/           # Testimonials management list and form
│   ├── messages/               # Contact submissions inbox
│   ├── settings/               # System and news automation settings
│   ├── backup/                 # Database backup generator
│   └── logs/                   # Structured activity log viewer
└── errors/
    └── 404.blade.php           # Custom 404 Not Found page
```

---

## 6. Frontend Assets & Styling Strategy (Vite + Tailwind CSS v4)

- **Vite Integration:** Use Laravel's official Vite plugin (`@vite(['resources/css/app.css', 'resources/js/app.js'])`).
- **Tailwind CSS v4 & Styling:** Reuse the exact color palette, typography tokens, custom button styles (`.btn-hero-primary`, `.btn-hero-secondary`), spacing scales, and scrollbar rules from `client/src/index.css`.
- **Fonts & Icons:** Google Fonts (`Inter`, `Rubik`) and `Material Symbols Outlined` with preconnect optimizations.
- **Client-Side Interactions (Lightweight Vanilla JS):**
  - **Animated Counters:** IntersectionObserver-driven smooth ease-out count animation.
  - **Scroll Reveal Animations:** IntersectionObserver triggering `.reveal.active`.
  - **Mobile Navigation Drawer:** Responsive slide-over menu with backdrop click-to-close.
  - **AI Chat Widget:** Floating widget with auto-scroll, message stream, session storage persistence, and Gemini API integration.
  - **Contact Form Submission:** AJAX submission with loading spinner, inline validation error rendering, and floating toast notifications.

---

## 7. Authentication & Authorization Mapping

- **Guard:** Dedicated `admin` guard using `App\Models\Admin` with session driver.
- **2FA Workflow:**
  1. Admin navigates to `/admin/login`.
  2. Submits email and password.
  3. Laravel verifies password with `Hash::check()`.
  4. Generates a cryptographically secure 6-digit OTP via `random_int(100000, 999999)`.
  5. Saves OTP and expiration (`now()->addMinutes(15)`) to the admin's database record.
  6. Dispatches OTP email using Laravel's Mail facade.
  7. Admin submits code on `/admin/verify`.
  8. Upon verification, OTP is cleared, `Auth::guard('admin')->login($admin)` initiates the session, and admin is redirected to `/admin`.
- **Public Protection:** Public portfolio pages require no login. Protected routes (`/admin/*`) are strictly guarded by `auth:admin` middleware.

---

## 8. Environment Configuration Requirements

The application will use standard Laravel `.env` configuration. All sensitive credentials will reside exclusively in `.env` (or cPanel server environment):

```ini
APP_NAME="TheTechCraft"
APP_ENV=production
APP_KEY=base64:GENERATE_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://thetechcraft.co.ke

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD="your_database_password"

SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Victor Oduor Portfolio"

ADMIN_NOTIFICATION_EMAIL=victoroduor723@gmail.com

GEMINI_API_KEY="your_gemini_api_key"
GEMINI_MODEL="gemini-2.5-flash"
CRON_SECRET="your_secure_32_char_cron_secret"
```

---

## 9. Potential Compatibility Problems & Mitigations

1. **cPanel Document Root Structure:**
   - *Risk:* In some cPanel accounts, the domain's document root points to `public_html/` rather than `public_html/public/`.
   - *Mitigation:* Provide two verified deployment configurations:
     - Method A (Recommended): Point document root directly to `public/`.
     - Method B: Standard cPanel secure separation placing the Laravel app in `/home/user/laravel-app/` and publishing the assets into `/home/user/public_html/` with adjusted index.php paths.
2. **PHP Version & Extensions:**
   - *Risk:* Incompatible PHP or missing extensions (`pdo_mysql`, `mbstring`, `fileinfo`, `curl`, `intl`).
   - *Mitigation:* Validated that PHP 8.4 is available locally and PHP 8.2+ is on HostPinnacle with all required extensions active.
3. **Existing MySQL Data Preservation:**
   - *Risk:* Running `php artisan migrate:fresh` or destructive drops would delete live portfolio data.
   - *Mitigation:* Use schema-safe migrations (`Schema::hasTable` checks) and strict non-destructive model bindings. Existing records remain completely untouched.
4. **Image Upload Paths:**
   - *Risk:* Existing project images reference `/uploads/...`.
   - *Mitigation:* Ensure Laravel serves uploaded media via `public/uploads/` matching the exact legacy paths, preserving all existing media links.

---

## 10. Testing & Acceptance Criteria

1. **Homepage Loading:** SSR renders hero, 12+ systems impact strip, featured project card, secondary projects, additional projects, skills bento grid, roadmap tree, latest news, contact form, and testimonials without console errors.
2. **Navigation & Responsiveness:** All internal anchors (`#projects`, `#skills`, `#roadmap`, `#contact`) and `/news` work smoothly across desktop, tablet, and mobile breakpoints.
3. **Contact Form:**
   - Client-side and server-side validation correctly detect invalid emails, missing names, or short messages.
   - Rate limiting allows a maximum of 3 messages per hour per IP.
   - Submissions persist into `contacts` table.
   - Admin receives notification email; visitor receives automated acknowledgment.
4. **AI Assistant Widget:** Floating chat assistant maintains conversational history, respects 20 requests/min rate limit, grounds answers strictly in live database portfolio content, and falls back gracefully when questions are out of scope.
5. **News Bot Execution:** `php artisan news:auto-generate` connects to Gemini, retrieves trending tech news with Google Search grounding, formats JSON, and publishes to the `news` table when enabled in settings.
6. **Admin 2FA Authentication:**
   - Valid credentials trigger OTP dispatch to admin email.
   - Incorrect credentials or expired OTPs are rejected.
   - Successful verification grants access to the dashboard.
   - Unauthenticated visitors attempting to access `/admin` are redirected to `/admin/login`.
7. **Admin CRUD Operations:** Projects, skills, roadmaps, news, testimonials can be viewed, created, updated, and deleted. Image uploads validate file size (<5MB) and MIME type.
8. **SEO & Performance:** Valid JSON-LD schema, canonical tags, Open Graph meta, dynamic sitemap.xml, and robots.txt.
9. **Zero Dependence on Old API:** No requests made to `backend.thetechcraft.co.ke`.

---

## 11. Rollback & Backup Procedures

1. **Database Safety:**
   - Take a full mysqldump before deploying:
     ```bash
     mysqldump -u [db_user] -p [db_name] > backup_pre_migration.sql
     ```
2. **Application Rollback:**
   - Keep the original `backend/` and `client/` files archived.
   - If rollback is required, restoring the previous files and pointing the web server root to the original public directories restores immediate operation.

---

## 12. Implementation Stages

- **Stage 1:** Initialize Laravel application structure with dependencies (`composer.json`, `package.json`, Vite, Tailwind CSS v4).
- **Stage 2:** Configure Eloquent models, database connections, and migrations.
- **Stage 3:** Implement Core Services (`GeminiService`, `MailService`, `ActivityLogger`).
- **Stage 4:** Build Blade layouts, reusable components, and Public Pages (`home.blade.php`, `news.blade.php`, `404.blade.php`).
- **Stage 5:** Implement client-side interactive modules (AI Chat widget, mobile navigation, animated counters, contact AJAX).
- **Stage 6:** Build Admin 2FA Authentication and Dashboard tools (Projects, Skills, Roadmaps, News, Testimonials, Messages, Settings, Backup, Logs).
- **Stage 7:** Implement Artisan commands (`news:auto-generate`) and API compatibility routes.
- **Stage 8:** Write automated tests (`tests/Feature/*`) and run test suites.
- **Stage 9:** Compile production assets via Vite and verify zero-error build.
- **Stage 10:** Create `LARAVEL_DEPLOYMENT_GUIDE.md` and complete documentation.
