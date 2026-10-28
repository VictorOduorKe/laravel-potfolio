# Project Folder Structure & Deployment Architecture

This document defines the local repository structure, backend-only FTP deployment workflow, HostPinnacle cPanel server layout, private secrets management, and operational procedures for **TheTechCraft**.

---

## 1. Intended Local Repository Layout

The repository maintains both the React/Vite client (for local development) and the custom PHP backend. The client is deployed and served independently via a dedicated frontend repository and deployment pipeline; it is strictly excluded from all FTP deployment actions in this repository.

```text
/home/alpha/Projects/my-portfolio/ (Repository Root)
│
├── .github/
│   └── workflows/
│       └── deploy.yml              # Production Backend CI & FTPS Deployment Workflow
│
├── backend/                        # PHP REST API Backend Source Directory
│   ├── public/                     # Public Web Gateway (Target: Subdomain Document Root)
│   │   ├── index.php               # Front Controller, autoloader, and API route dispatcher
│   │   ├── .htaccess               # Apache rewrite rules, HTTPS enforcement, HTTP auth header
│   │   └── uploads/                # Media upload directory
│   │       └── .htaccess           # Security: Blocks script execution (.php, .phtml, etc.)
│   │
│   ├── src/                        # Core Application Code (Target: Private backend-app/src/)
│   │   ├── Config/
│   │   │   ├── Database.php        # PDO database connection manager
│   │   │   └── Env.php             # cPanel secrets loader (reads /home/USER/secrets/portfolio.env)
│   │   ├── Controllers/            # API REST route controllers
│   │   ├── Repositories/           # Data access layer and database queries
│   │   ├── Services/               # Gemini AI and third-party integration services
│   │   └── Utils/                  # Router, Response, Auth, CSRF, MailService, ActivityLogger
│   │
│   ├── cron/                       # Scheduled Background Tasks (Target: backend-app/cron/)
│   │   └── auto_news.php           # CLI script for automated tech news generation
│   │
│   ├── migrations/                 # Database Schema Migrations (Target: backend-app/migrations/)
│   │   ├── 001_ to 018_*.sql       # Versioned idempotent schema migrations
│   │   └── migrate.php             # CLI schema migration runner
│   │
│   ├── storage/                    # Application Storage (Target: backend-app/storage/)
│   │   ├── .htaccess               # "Require all denied" protection
│   │   ├── backups/                # Database backup dumps (.gitkeep)
│   │   └── logs/                   # Private activity logs (.gitkeep)
│   │
│   ├── .env.example                # Example environment template for documentation
│   └── .gitignore                  # Backend-specific ignore rules (.env, logs, backups, archives)
│
├── client/                         # React 19 + Vite Frontend (LOCAL DEVELOPMENT ONLY)
│   ├── public/                     # Static assets (favicon, brand icons)
│   ├── src/                        # React components, pages, design system
│   ├── index.html                  # HTML template
│   ├── package.json                # Frontend dependencies
│   ├── vite.config.js              # Vite bundler configuration (dev proxy to backend:8000)
│   └── .gitignore                  # Frontend ignore rules (node_modules, dist)
│
├── secrets/                        # LOCAL DEVELOPMENT ONLY (Ignored by Git & FTP)
│   └── portfolio.env               # Local test environment file
│
├── .gitignore                      # Top-level Git ignore (.env*, secrets/, staging/, *.zip)
├── DEPLOYMENT.md                   # Full operational deployment guide
├── folder-structure.md             # Architecture and folder layout specification (this file)
└── instructions.md                 # Development log and historical instruction records
```

---

## 2. Backend-Only FTP Source Directories (Staging Artifacts)

During CI execution, the workflow creates isolated staging directories containing only the backend files approved for deployment. No frontend files, local development configurations, or secrets are ever included in the staging artifacts.

```text
staging/
├── backend-public/                 # Uploaded to backend subdomain document root
│   ├── index.php                   # Public Front Controller
│   ├── .htaccess                   # Rewrite & security rules
│   └── uploads/
│       └── .htaccess               # Script execution denial
│
└── backend-private/                # Uploaded to private application folder
    ├── .htaccess                   # "Require all denied" (blocks all direct web requests)
    ├── src/                        # Full PHP application code (Config, Controllers, Repositories, etc.)
    ├── cron/                       # CLI cron tasks (auto_news.php)
    ├── migrations/                 # SQL migrations and migrate.php
    └── storage/
        ├── .htaccess               # "Require all denied"
        ├── logs/                   # Protected log directory
        └── backups/                # Protected backup directory
```

---

## 3. HostPinnacle cPanel Server Layout

The production hosting environment enforces structural physical isolation between the public document roots, private application code, and private environment secrets:

```text
HOSTING ACCOUNT ROOT: /home/{CPANEL_USER}/
│
├── backend.thetechcraft.co.ke/     # [CONFIRMED / TARGET] Backend Subdomain Document Root (chmod 755)
│   ├── index.php                   # Public API gateway entry point
│   ├── .htaccess                   # Front controller router & HTTPS enforcement
│   └── uploads/                    # Uploaded media assets (chmod 755)
│       └── .htaccess               # Security: Execution of PHP/CGI/scripts denied (chmod 644)
│
├── backend-app/                    # [PROPOSED / TARGET] Private Backend Application Code (chmod 750)
│   ├── .htaccess                   # "Require all denied" (defense-in-depth protection)
│   ├── src/                        # Controllers, Repositories, Services, Utils, Config (chmod 755)
│   ├── cron/                       # auto_news.php CLI script (chmod 700)
│   ├── migrations/                 # Versioned SQL migration files (chmod 700)
│   └── storage/                    # Storage directory (chmod 750)
│       ├── .htaccess               # "Require all denied"
│       ├── logs/                   # activity.log
│       └── backups/                # Database backup dumps
│
├── secrets/                        # [CONFIRMED / EXISTING] Private Configuration Directory (chmod 700)
│   └── portfolio.env               # Production environment secrets file (chmod 600)
│
└── thetechcraft.co.ke/             # [CONFIRMED] Main Frontend Document Root (INDEPENDENT)
    └── (Managed independently through the frontend repository and deployment pipeline)
```

> [!IMPORTANT]
> - Paths labeled **[CONFIRMED]** (`/home/{CPANEL_USER}/secrets/portfolio.env` and document root `thetechcraft.co.ke/`) are established on HostPinnacle.
> - Paths labeled **[PROPOSED / TARGET]** (`backend-app/` and `backend.thetechcraft.co.ke/`) represent the intended deployment targets and must be verified against actual cPanel subdomain configurations.

---

## 4. Private Secrets & Configuration Directory

The production configuration file is located at:
```text
/home/{CPANEL_USER}/secrets/portfolio.env
```

### Security Directives
1. **Outside Web Roots:** `secrets/portfolio.env` is situated at the root of `/home/{CPANEL_USER}/`, physically outside `backend.thetechcraft.co.ke/` and `thetechcraft.co.ke/`.
2. **Never Deployed:** Excluded from Git (`.gitignore`) and explicitly purged during GitHub Actions staging. It is managed manually in cPanel.
3. **Strict Permissions:**
   ```bash
   chmod 700 /home/{CPANEL_USER}/secrets
   chmod 600 /home/{CPANEL_USER}/secrets/portfolio.env
   ```
4. **Content Schema:**
   - `APP_ENV=production`
   - `DB_HOST="localhost"`
   - `DB_PORT="3306"`
   - `DB_NAME="{CPANEL_USER}_portfolio"`
   - `DB_USER="{CPANEL_USER}_dbuser"`
   - `DB_PASS="{YOUR_DATABASE_PASSWORD}"`
   - `GEMINI_API_KEY="{YOUR_GEMINI_KEY}"`
   - `GEMINI_MODEL="gemini-2.5-flash"`
   - `SMTP_HOST="smtp.gmail.com"`
   - `SMTP_PORT="587"`
   - `SMTP_USER="{YOUR_EMAIL}"`
   - `SMTP_PASS="{YOUR_16_CHAR_APP_PASSWORD}"`
   - `ADMIN_NOTIFICATION_EMAIL="victoroduor723@gmail.com"`
   - `CRON_SECRET="{RANDOM_32_CHAR_STRING}"`
   - `ALLOWED_ORIGINS="https://thetechcraft.co.ke,https://www.thetechcraft.co.ke"`

---

## 5. Relationship Between Public Entry Point and Private PHP Code

### 5.1. Request Flow
```text
Client Browser / Frontend SPA (https://thetechcraft.co.ke)
      │
      ▼ HTTP REST Request (e.g., GET /api/projects)
HostPinnacle Apache Web Server (backend.thetechcraft.co.ke)
      │
      ▼ [Rewritten by backend.thetechcraft.co.ke/.htaccess]
/home/{CPANEL_USER}/backend.thetechcraft.co.ke/index.php
      │
      ├── 1. Autoloader resolves App\* namespace to:
      │      /home/{CPANEL_USER}/backend-app/src/
      │
      ├── 2. Early Bootstrap loads configuration from:
      │      /home/{CPANEL_USER}/secrets/portfolio.env
      │
      ├── 3. Enforces CORS whitelist & security headers
      │
      ├── 4. Initializes Router & dispatches Controller in:
      │      /home/{CPANEL_USER}/backend-app/src/Controllers/
      │
      └── 5. Connects to MySQL using PDO & returns JSON response
```

### 5.2. Dynamic `CPANEL_HOME` Resolution in `App\Config\Env`
The backend does not guess hardcoded usernames. Instead, `App\Config\Env::resolveCpanelHome()` resolves the cPanel home directory using a multi-strategy fallback:
1. Explicit environment variable or constant `CPANEL_HOME` (if configured).
2. Derived relative path from `Env.php` (`dirname(__DIR__, 3)` which resolves `/home/{USER}/backend-app/src/Config` to `/home/{USER}`).
3. Derived parent path from `$_SERVER['DOCUMENT_ROOT']` (`dirname($_SERVER['DOCUMENT_ROOT'])` which resolves `/home/{USER}/backend.thetechcraft.co.ke` to `/home/{USER}`).
4. Regex matching standard Linux `/home\d*/{USER}` on `__DIR__` or `DOCUMENT_ROOT`.
5. Standard POSIX `HOME` environment variable (if matching `/home*/{USER}`).

### 5.3. Precedence Rules for Environment Variables
When fetching configuration through `App\Config\Env::get($key, $default)`:
1. **Server Environment Variable:** `getenv($key)` takes top precedence if defined by the web server or PHP runtime.
2. **`$_ENV[$key]`:** Superglobal environment value.
3. **`$_SERVER[$key]`:** Superglobal server value.
4. **Parsed `portfolio.env`:** Values loaded from `/home/{USER}/secrets/portfolio.env`.
5. **Default Value:** `$default` provided in the method call.

Existing hosting environment variables are never overwritten.

---

## 6. Division of Responsibilities: GitHub Actions vs. Manual cPanel

| Component / Task | Handled By | Details |
| :--- | :--- | :--- |
| **Backend Public Files (`index.php`, `.htaccess`)** | **GitHub Actions (FTPS)** | Staged from `backend/public/` to `backend.thetechcraft.co.ke/`. |
| **Uploads Protection (`uploads/.htaccess`)** | **GitHub Actions (FTPS)** | Deploys script-execution blocking `.htaccess` to `uploads/`. Existing media is preserved. |
| **Private Application Code (`src/`, `cron/`)** | **GitHub Actions (FTPS)** | Staged from `backend/src/` and `backend/cron/` to `backend-app/`. |
| **Database Migrations (`migrations/*.sql`)** | **GitHub Actions (FTPS)** | Uploaded to `backend-app/migrations/` for manual execution in phpMyAdmin or CLI runner. |
| **Subdomain Setup (`backend.thetechcraft.co.ke`)** | **Manual (cPanel)** | Created in **cPanel > Domains** with document root set to `backend.thetechcraft.co.ke/`. |
| **Private Secrets (`secrets/portfolio.env`)** | **Manual (cPanel)** | Created in **cPanel > File Manager** with permissions `0600`. Never touched by Git/FTP. |
| **Database & User Provisioning** | **Manual (cPanel)** | Created via **cPanel > MySQL Database Wizard**. |
| **Database Migration Execution** | **Manual (cPanel)** | Imported via **phpMyAdmin** or executed via terminal: `php backend-app/migrations/migrate.php`. |
| **Cron Job Scheduling (`auto_news.php`)** | **Manual (cPanel)** | Added in **cPanel > Cron Jobs** pointing to `/home/{USER}/backend-app/cron/auto_news.php`. |
| **Frontend Deployment** | **Independent Repository** | Handled through separate frontend repository and deployment pipeline. |

---

## 7. Required GitHub Actions Repository Secrets

Configure the following secrets in **GitHub > Repository Settings > Secrets and variables > Actions**:

| Secret Name | Status | Description |
| :--- | :--- | :--- |
| `FTP_SERVER` | **Required** | HostPinnacle FTP server hostname or server IP (e.g., `ftp.thetechcraft.co.ke`). |
| `FTP_USERNAME` | **Required** | cPanel FTP user account with access to cPanel home (e.g., `deployer@thetechcraft.co.ke`). |
| `FTP_PASSWORD` | **Required** | Password for the FTP deployment user account. |
| `FTP_BACKEND_PUBLIC_DIR` | **Required** | FTP-relative path to backend subdomain document root (`backend.thetechcraft.co.ke/`). |
| `FTP_BACKEND_PRIVATE_DIR` | Optional | FTP-relative path to private application directory (`backend-app/`). Set to `skip` if using a jailed FTP account. |

> [!NOTE]
> All FTP secrets use a single unambiguous variable name with no fallbacks. Port (`21`), protocol (`ftps`), and security mode (`strict`) are configured as constants in the workflow. Legacy secrets (`FTP_*_FRONTEND`, `FTP_FRONTEND_DIR`, `FTP_*_BACKEND`, `FTP_PRIVATE_DIR`) are no longer used.

---

## 8. cPanel Settings to Verify Manually

1. **Subdomain Document Root:**
   - In **cPanel > Domains**, ensure `backend.thetechcraft.co.ke` has Document Root set to `backend.thetechcraft.co.ke` (or `public_html/backend` depending on host setup).
   - Ensure it does **not** share a document root with `thetechcraft.co.ke`.
2. **PHP Version & Modules:**
   - In **cPanel > MultiPHP Manager**, select PHP 8.2 for `backend.thetechcraft.co.ke`.
   - In **cPanel > Select PHP Version / Extensions**, ensure `pdo_mysql`, `curl`, `json`, `mbstring`, `fileinfo`, `gd`, and `openssl` are enabled.
3. **FTP Account Scope (Jailed vs. Unjailed):**
   - In **cPanel > FTP Accounts**, check the directory of your deployment FTP user:
     - **Unjailed (Recommended):** Directory is `/home/{CPANEL_USER}/`. The user can deploy to both `backend.thetechcraft.co.ke/` and `backend-app/`.
     - **Jailed:** Directory is `/home/{CPANEL_USER}/backend.thetechcraft.co.ke/`. If jailed, set `FTP_BACKEND_PUBLIC_DIR` to `./` and `FTP_BACKEND_PRIVATE_DIR` to `skip`. Private code must then be uploaded manually via cPanel File Manager.
4. **File Permissions on Secrets:**
   - Verify `/home/{CPANEL_USER}/secrets/` has mode `0700`.
   - Verify `/home/{CPANEL_USER}/secrets/portfolio.env` has mode `0600`.
5. **Apache Modules:**
   - Verify `mod_rewrite` and `mod_headers` are active (standard on HostPinnacle).

---

## 9. Safe Deployment and Rollback Procedures

### 9.1. Dry Run Deployment
Test the pipeline before making changes to remote files:
1. In GitHub, go to **Actions > Production Backend CI & Deployment**.
2. Click **Run workflow**.
3. Check **Simulate deployment without modifying remote files (Dry Run)**.
4. Click **Run workflow**.
5. Inspect the logs to verify file staging and path normalization without any remote file writes.

### 9.2. Production Release
1. Push changes to `main` (ignoring changes in `client/**`).
2. GitHub Actions runs PHP syntax validation, core autoloader checks, staging isolation checks, and deploys via encrypted FTPS.

### 9.3. Rollback Procedure
If an issue occurs after deployment:
1. **Application Code Rollback:**
   - Revert the commit on `main` to trigger automated redeployment of the previous release.
   - Alternatively, open **cPanel > File Manager**, navigate to `/home/{CPANEL_USER}/backend-app/`, and replace `src/` with the previous backup or CI artifact.
2. **Public Gateway Rollback:**
   - In **cPanel > File Manager**, restore `backend.thetechcraft.co.ke/index.php`.
3. **Database Rollback:**
   - In **cPanel > phpMyAdmin**, select the database, click **Import**, and upload the latest backup from `/home/{CPANEL_USER}/backend-app/storage/backups/`.
4. Verify recovery at `https://backend.thetechcraft.co.ke/api/health`.

---

## 10. Files and Directories That Must Never Be Publicly Exposed

The following must never be placed inside `backend.thetechcraft.co.ke/`, `thetechcraft.co.ke/`, or any public document root:

1. `/home/{CPANEL_USER}/secrets/` and all `.env` files (`portfolio.env`, `.env`, `.env.local`).
2. `/home/{CPANEL_USER}/backend-app/` and any raw PHP application source files.
3. Database backups (`*.sql`), migration files, and database dump archives.
4. Application log files (`storage/logs/activity.log`, error logs).
5. Git repository metadata (`.git/`, `.gitignore`).
6. GitHub workflows (`.github/`).
7. Node.js dependencies (`node_modules/`) and package manager files.
8. Test fixtures, scratch scripts (`test_grounding.php`, `auto_news_bot.sh`).
9. Compressed archives (`*.zip`, `*.tar.gz`).

---

## 11. Verification Checklist

Execute these checks after deployment to verify operational health:

| Test Item | Verification Command / URL | Expected Outcome |
| :--- | :--- | :--- |
| **Backend Subdomain Root** | `curl -i https://backend.thetechcraft.co.ke/` | `HTTP/1.1 200 OK`<br>`{"status":"ok","service":"TheTechCraft Backend API","health":"/api/health"}` |
| **API Health Endpoint** | `curl -i https://backend.thetechcraft.co.ke/api/health` | `HTTP/1.1 200 OK`<br>`{"status":"ok","message":"Backend API is running","environment":"production"}` |
| **CORS Preflight** | `curl -X OPTIONS -H "Origin: https://thetechcraft.co.ke" -i https://backend.thetechcraft.co.ke/api/projects` | `HTTP/1.1 204 No Content`<br>`Access-Control-Allow-Origin: https://thetechcraft.co.ke`<br>`Access-Control-Allow-Credentials: true` |
| **Database Connectivity** | `curl -i https://backend.thetechcraft.co.ke/api/projects` | `HTTP/1.1 200 OK`<br>JSON array of projects without database connection errors. |
| **CORS Rejection** | `curl -X OPTIONS -H "Origin: https://malicious-site.com" -i https://backend.thetechcraft.co.ke/api/projects` | `HTTP/1.1 403 Forbidden` |
| **Public Secrets Inaccessibility** | `curl -i https://backend.thetechcraft.co.ke/secrets/portfolio.env`<br>`curl -i https://backend.thetechcraft.co.ke/.env` | `HTTP/1.1 403 Forbidden` or `HTTP/1.1 404 Not Found` (Never 200 OK). |
| **Upload Directory Script Protection** | `curl -i https://backend.thetechcraft.co.ke/uploads/test.php` | `HTTP/1.1 403 Forbidden` (Script execution blocked by `.htaccess`). |
| **Admin OTP Authentication** | Submit valid login at `https://thetechcraft.co.ke/admin` | OTP code dispatched to `victoroduor723@gmail.com` via pure PHP SMTP socket client. |
| **AI Chat Assistant** | Send query through floating chat widget on frontend | Gemini response generated citing live portfolio context. |
| **Contact Form & CSRF** | Fetch CSRF token and submit contact message | Submission recorded in database with IP and email notification sent. |
