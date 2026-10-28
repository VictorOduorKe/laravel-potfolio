# cPanel Subdirectory Deployment Guide: Laravel Portfolio alongside Existing Website

This guide explains how to deploy your Laravel portfolio to cPanel at `/home/yourcpanel/laravel-portfolio` while keeping your existing website in `/home/yourcpanel/public_html/` 100% intact and untouched.

Visitors will access your portfolio at:
```text
https://yourdomain.com/portfolio/
```
(and all subpaths like `/portfolio/news`, `/portfolio/admin`, `/portfolio/contact`).

---

## 1. Target Directory Architecture on cPanel

```text
/home/yourcpanel/
├── laravel-portfolio/              # PRIVATE: Laravel Project (outside public_html)
│   ├── app/                        # Controllers, Models, Services, Console
│   ├── bootstrap/                  # app.php & cache
│   ├── config/                     # Configuration
│   ├── database/                   # Migrations & seeders
│   ├── resources/                  # Views (Blade) & raw assets
│   ├── routes/                     # web.php & console.php
│   ├── storage/                    # Logs, uploads, backups (chmod 775)
│   ├── vendor/                     # Composer dependencies
│   ├── .env                        # Private environment secrets (chmod 600)
│   └── public/                     # Local public entry point
│
└── public_html/                    # EXISTING WEBSITE (Untouched!)
    ├── index.php / index.html      # Existing website files (DO NOT MODIFY)
    ├── ...                         # All your existing site files & folders
    │
    └── portfolio/                  # NEW SUBDIRECTORY FOR PORTFOLIO
        ├── index.php               # Bridge pointing to ../../laravel-portfolio/
        ├── .htaccess               # Apache RewriteBase /portfolio/
        ├── css/                    # Static styles (app.css)
        ├── js/                     # Client scripts (app.js)
        ├── assets/                 # Brand assets
        └── uploads/                # Media uploads
```

---

## 2. Step-by-Step Deployment Instructions

### Step 1: Upload the Private Laravel Core
1. In cPanel **File Manager** (or via SFTP/SSH), navigate to your home directory (`/home/yourcpanel/`).
2. Create a folder named `laravel-portfolio` (or upload the project ZIP and extract it here).
3. Ensure the folder contents include:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `vendor/`
   - `artisan`
   - `composer.json`
4. Set permissions:
   - `storage/` and `bootstrap/cache/`: `chmod -R 775` (or writable by web server).
   - `.env`: `chmod 600` (readable only by your cPanel user).

---

### Step 2: Configure `/home/yourcpanel/laravel-portfolio/.env`
Create or edit `.env` inside `/home/yourcpanel/laravel-portfolio/`:

```ini
APP_NAME=TheTechCraft
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com/portfolio

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yourcpanel_portfolio
DB_USERNAME=yourcpanel_dbuser
DB_PASSWORD=your_secure_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
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

> **Tip:** If you need to generate an `APP_KEY`, run in cPanel Terminal / SSH:
> ```bash
> cd /home/yourcpanel/laravel-portfolio
> php artisan key:generate
> ```

---

### Step 3: Link Public Directory inside `public_html/`

You have two choices to connect `public_html/portfolio` to the Laravel app:

#### Option A: Instant Symlink (Recommended if cPanel SSH/Terminal is available)
In cPanel **Terminal** or via SSH, run:
```bash
ln -s /home/yourcpanel/laravel-portfolio/public /home/yourcpanel/public_html/portfolio
```
*That's it! Your entire Laravel public folder is now live at `https://yourdomain.com/portfolio`!*

---

#### Option B: Standalone Bridge Folder (No SSH required)
If your host restricts symlinks:
1. In cPanel **File Manager**, enter `/home/yourcpanel/public_html/`.
2. Create a new folder named `portfolio`.
3. Copy the contents of `cpanel_subfolder_bridge/` into `/home/yourcpanel/public_html/portfolio/`:
   - `index.php` (already configured to load `../../laravel-portfolio/`)
   - `.htaccess` (already configured with `RewriteBase /portfolio/`)
4. Copy the assets from `/home/yourcpanel/laravel-portfolio/public/` into `/home/yourcpanel/public_html/portfolio/`:
   - `css/`
   - `js/`
   - `assets/`
   - `uploads/`
   - `favicon.svg`, `logo.svg`, `profile.jpg`

---

### Step 4: Schedule the News Bot in cPanel Cron Jobs
1. Go to **cPanel > Advanced > Cron Jobs**.
2. Select Interval: **Once Per Day** (e.g. at midnight `0 0 * * *`).
3. Enter Command:
   ```bash
   /usr/local/bin/php /home/yourcpanel/laravel-portfolio/artisan news:auto-generate > /dev/null 2>&1
   ```
4. Click **Add New Cron Job**.

---

## 3. Verification & Acceptance Checklist

Once files are placed, test the following endpoints:

| URL | Expected Behavior |
| :--- | :--- |
| `https://yourdomain.com/` | Your **existing website** loads normally with zero changes. |
| `https://yourdomain.com/portfolio` | The **new Laravel portfolio** loads immediately with hero, projects, skills bento, roadmap, news preview, and contact form. |
| `https://yourdomain.com/portfolio/news` | The dedicated tech news feed loads with paginated articles. |
| `https://yourdomain.com/portfolio/admin/login` | Step 1 admin login loads securely. |
| `https://yourdomain.com/portfolio/admin/verify` | Step 2 OTP verification code submission. |
| `https://yourdomain.com/portfolio/admin/dashboard` | Admin control panel (projects, skills, roadmap, news trigger, backup, logs). |
| Contact form submission | Submits via AJAX, persists in DB, dispatches email to admin & visitor. |
| AI Chat assistant widget | Click the bottom-right orange badge; chat with the Gemini assistant grounded in your portfolio. |
