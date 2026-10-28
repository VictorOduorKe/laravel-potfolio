# Production Deployment Guide: TheTechCraft on HostPinnacle cPanel

**Production Frontend Domain:** `https://thetechcraft.co.ke`  
**Production Backend Subdomain:** `https://backend.thetechcraft.co.ke`  
**Hosting Environment:** HostPinnacle Shared Hosting with cPanel (Apache + PHP 8.2 with PDO, MySQL)  
**Stack:** React 19 + Vite 8 SPA (Frontend) | Custom PHP 8.2 REST API (Backend)  
**CI/CD Pipeline:** GitHub Actions via encrypted FTPS (`SamKirkland/FTP-Deploy-Action`)  

---

## 1. Production Architecture Overview

The application architecture enforces strict separation between the public frontend website, the public API gateway subdomain, the private PHP application code, and the private server runtime configuration:

```text
HOSTING ACCOUNT ROOT (/home/{cpanel_user}/)
│
├── thetechcraft.co.ke/             # PUBLIC: Frontend Document Root (https://thetechcraft.co.ke)
│   ├── index.html                  # Vite compiled SPA entry point
│   ├── assets/                     # Bundled JS, CSS, and web fonts with cache-busting hashes
│   ├── .htaccess                   # HTTPS, www redirect, SPA fallback, /api legacy forwarder
│   ├── robots.txt                  # Search engine crawler directives
│   ├── sitemap.xml                 # Canonical sitemap
│   └── static brand images         # profile.jpg, favicon.svg, logo.png, V.png, V.jpg
│
├── backend.thetechcraft.co.ke/     # PUBLIC: Backend Subdomain Document Root (https://backend.thetechcraft.co.ke)
│   ├── index.php                   # Public API Front Controller & bootstrap router
│   ├── .htaccess                   # HTTPS enforcement, Authorization header forwarding, mod_rewrite
│   └── uploads/                    # Uploaded portfolio media (chmod 755)
│       └── .htaccess               # Strictly blocks execution of scripts (.php, .phtml, etc.)
│
├── backend-app/                    # PRIVATE: PHP Application Code (Outside public document roots)
│   ├── .htaccess                   # Require all denied (blocks all direct web requests)
│   ├── src/                        # Controllers, Repositories, Services, Utils, Config
│   ├── migrations/                 # Versioned SQL migration files (001_ to 018_)
│   ├── cron/                       # Scheduled background tasks (auto_news.php)
│   └── storage/                    # Application storage (chmod 750)
│       ├── .htaccess               # Require all denied
│       ├── logs/                   # activity.log
│       └── backups/                # Database backups
│
└── secrets/                        # PRIVATE: Server Configuration (chmod 700)
    └── portfolio.env               # Database credentials, Gemini key, SMTP secrets (chmod 600)
```

### Critical Security Boundaries
1. **Frontend Document Root (`thetechcraft.co.ke/`):** Contains ONLY compiled static assets. No PHP files, backend source code, or secrets exist here.
2. **Backend Document Root (`backend.thetechcraft.co.ke/`):** Serves only API responses from `index.php` and static media from `uploads/`.
3. **Private Application Code (`backend-app/`):** Completely outside both public document roots. Even if Apache misconfiguration occurs, PHP files cannot be browsed directly.
4. **Private Runtime Secrets (`secrets/portfolio.env`):** Stored outside all web directories with `chmod 600` permissions. The frontend never accesses this file.

---

## 2. Frontend-to-Backend Communication & CORS Configuration

### 2.1. API URL Resolution
The frontend calls the backend REST API at:
```text
https://backend.thetechcraft.co.ke/api
```

In [client/src/services/api.js](file:///home/alpha/Projects/my-portfolio/client/src/services/api.js), the base API URL is dynamically determined:
- **Production (`import.meta.env.PROD`):** Uses `import.meta.env.VITE_API_URL` (injected during GitHub Actions build as `https://backend.thetechcraft.co.ke/api`), with fallback to `https://backend.thetechcraft.co.ke/api`.
- **Local Development:** Defaults to `/api`, which is forwarded to `http://127.0.0.1:8000` via the Vite dev proxy in [client/vite.config.js](file:///home/alpha/Projects/my-portfolio/client/vite.config.js).
- **Uploaded Media (`getImageUrl`):** Prepends `https://backend.thetechcraft.co.ke` to relative `/uploads/...` paths in production, ensuring images stored in `backend.thetechcraft.co.ke/uploads/` render seamlessly on the portfolio frontend.

### 2.2. CORS & Preflight Handling
Because the frontend and backend reside on separate subdomains, Cross-Origin Resource Sharing (CORS) is configured explicitly in [backend/public/index.php](file:///home/alpha/Projects/my-portfolio/backend/public/index.php):
- **Allowed Production Origins:**
  - `https://thetechcraft.co.ke`
  - `https://www.thetechcraft.co.ke`
  - Any extra origins specified in the `ALLOWED_ORIGINS` environment variable in `secrets/portfolio.env`.
- **Allowed Development Origins:**
  - `http://localhost:5173`, `http://127.0.0.1:5173`, `http://localhost:8000`, `http://127.0.0.1:8000` (enabled only when `APP_ENV=development`).
- **HTTP OPTIONS Preflight:**
  - Matches the requesting `Origin` against the whitelist.
  - Returns `204 No Content` with:
    - `Access-Control-Allow-Origin: <origin>`
    - `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
    - `Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-Token, X-Requested-With, X-Cron-Key`
    - `Access-Control-Max-Age: 86400`
    - `Access-Control-Allow-Credentials: true`
    - `Vary: Origin`
  - Rejects unknown origins with `403 Forbidden`.
- **Authentication:** Admin authentication uses Bearer tokens passed via the standard HTTP `Authorization` header (`Authorization: Bearer <token>`). Apache CGI/FastCGI directives in `backend.thetechcraft.co.ke/.htaccess` ensure this header is forwarded directly to PHP.

---

## 3. Server Configuration & Secrets (`secrets/portfolio.env`)

Backend runtime settings are loaded by [backend/src/Config/Env.php](file:///home/alpha/Projects/my-portfolio/backend/src/Config/Env.php).

Create this file in `/home/{cpanel_user}/secrets/portfolio.env`:

```ini
# ==============================================================================
# TheTechCraft Portfolio - Production Runtime Environment
# Server Path: /home/{cpanel_user}/secrets/portfolio.env (chmod 600)
# ==============================================================================

# Application Environment
APP_ENV=production

# Database Credentials (HostPinnacle cPanel MySQL)
DB_HOST="localhost"
DB_PORT="3306"
DB_NAME="yourcpaneluser_portfolio"
DB_USER="yourcpaneluser_dbuser"
DB_PASS="YOUR_STRONG_DATABASE_PASSWORD"

# Google Gemini API Key (AI Assistant chat & automated tech news)
GEMINI_API_KEY="YOUR_ACTUAL_GEMINI_API_KEY"

# SMTP Email Configuration (Admin OTP delivery & visitor contact alerts)
SMTP_HOST="smtp.gmail.com"
SMTP_PORT="587"
SMTP_USER="your_email@gmail.com"
SMTP_PASS="your_16_character_app_password"

# Contact Form Alerts Recipient
ADMIN_NOTIFICATION_EMAIL="victoroduor723@gmail.com"

# Automated Cron Task Secret
CRON_SECRET="GENERATE_A_RANDOM_32_CHARACTER_STRING"

# Allowed CORS Origins
ALLOWED_ORIGINS="https://thetechcraft.co.ke,https://www.thetechcraft.co.ke"
```

### File Permissions:
In cPanel Terminal or File Manager:
```bash
chmod 700 /home/{cpanel_user}/secrets
chmod 600 /home/{cpanel_user}/secrets/portfolio.env
```

---

## 4. Applying Database Migrations via phpMyAdmin

Because production deployments do not run destructive SQL automatically and SSH access may be restricted on shared hosting, manage database schema updates through **phpMyAdmin**:

1. Log into your **HostPinnacle cPanel**.
2. Under **Databases**, open **phpMyAdmin**.
3. Select your portfolio database (e.g., `yourcpaneluser_portfolio`) in the left navigation sidebar.
4. Click the **Import** tab on the top menu bar.
5. In **File to import**, click **Choose File** and upload the migration files from `backend/migrations/` sequentially:
   - `001_create_projects_table.sql`
   - `002_create_project_gallery_table.sql`
   - `003_create_settings_table.sql`
   - `004_create_admins_table.sql`
   - `005_create_skills_table.sql`
   - `006_create_roadmaps_table.sql`
   - `007_create_news_table.sql`
   - `008_create_chat_tables.sql`
   - `009_add_description_to_skills.sql`
   - `010_add_impact_line_to_projects.sql`
   - `011_add_tags_badge_to_skills.sql`
   - `012_create_testimonials_table.sql`
   - `013_add_source_to_news.sql`
   - `014_alter_source_url_type_in_news.sql`
   - `015_add_parent_id_to_roadmaps.sql`
   - `016_create_contacts_table.sql`
   - `017_add_ip_address_to_contacts.sql`
   - `018_create_rate_limits_table.sql`
6. Click **Import** (or **Go**).
7. Verify that all tables appear in the database list.

*(Note: All migration scripts include `IF NOT EXISTS` or idempotent constraints, making them safe to run without duplicating tables).*

---

## 5. Automated GitHub Actions CI/CD Pipeline

The production deployment workflow is defined in [`.github/workflows/deploy.yml`](file:///home/alpha/Projects/my-portfolio/.github/workflows/deploy.yml).

### 5.1. Pipeline Architecture
```text
┌─────────────────────────────────────────────────────────────┐
│ JOB 1: validate-and-build                                   │
│  1. Checkout code                                           │
│  2. Install client dependencies (npm ci)                    │
│  3. Frontend linting (npm run lint)                         │
│  4. Production build (VITE_API_URL=https://backend...)      │
│  5. Scan client/dist for test URLs, secrets, or leakages    │
│  6. Validate PHP 8.2 syntax across all backend files        │
│  7. Validate core PHP autoloader & configuration            │
│  8. Prepare 3 independent staging directories:              │
│       • staging/frontend/                                   │
│       • staging/backend-public/                             │
│       • staging/backend-private/                            │
│  9. Security audit: purge .env, logs, stray SQL dumps       │
│ 10. Upload staging artifact                                 │
└──────────────────────────────┬──────────────────────────────┘
                               │
                               ▼
┌─────────────────────────────────────────────────────────────┐
│ JOB 2: deploy-to-hostpinnacle (FTPS)                        │
│  1. Download staging artifact                               │
│  2. Validate secrets, deployment targets, and safe paths     │
│  3. Verify frontend and backend dirs are distinct           │
│  4. Verify private app code is outside document roots       │
│  5. Deploy Frontend -> FTP_FRONTEND_DIR                     │
│  6. Deploy Backend Public -> FTP_BACKEND_PUBLIC_DIR         │
│  7. Deploy Backend Private -> FTP_BACKEND_PRIVATE_DIR       │
└─────────────────────────────────────────────────────────────┘
```

### 5.2. Required GitHub Repository Secrets

Configure these secrets in **GitHub > Repository Settings > Secrets and variables > Actions**:

| Secret Name | Required? | Example Value | Description |
| :--- | :--- | :--- | :--- |
| `FTP_SERVER` | **Required** | `ftp.thetechcraft.co.ke` | HostPinnacle FTP server address or server IP. |
| `FTP_USERNAME_FRONTEND` | **Required** (for frontend) | `frontend_deployer@thetechcraft.co.ke` | cPanel FTP user account used to deploy frontend. *(Falls back to `FTP_USERNAME` if not set).* |
| `FTP_USERNAME_BACKEND` | **Required** (for backend) | `backend_deployer@thetechcraft.co.ke` | cPanel FTP user account used to deploy backend. *(Falls back to `FTP_USERNAME` if not set).* |
| `FTP_PASSWORD` | **Required** | `YourSecurePassword123!` | Default password for FTP deployment. |
| `FTP_PASSWORD_FRONTEND` | Optional | `YourFrontendFTPPassword123!` | Dedicated password for frontend FTP account if different from `FTP_PASSWORD`. |
| `FTP_PASSWORD_BACKEND` | Optional | `YourBackendFTPPassword123!` | Dedicated password for backend FTP account if different from `FTP_PASSWORD`. |
| `FTP_SERVER_FRONTEND` | Optional | `ftp.thetechcraft.co.ke` | Hostname for frontend FTP connection if different from `FTP_SERVER`. |
| `FTP_SERVER_BACKEND` | Optional | `ftp.thetechcraft.co.ke` | Hostname for backend FTP connection if different from `FTP_SERVER`. |
| `FTP_FRONTEND_DIR` | **Required** | `thetechcraft.co.ke/` (or `./`) | FTP-relative path to frontend document root (trailing slash required). Set to `./` if `FTP_USERNAME_FRONTEND` is jailed to the frontend folder. *(Also accepts legacy `FTP_PUBLIC_DIR`).* |
| `FTP_BACKEND_PUBLIC_DIR` | **Required** | `backend.thetechcraft.co.ke/` (or `./`) | FTP-relative path to backend subdomain document root (trailing slash required). Set to `./` if `FTP_USERNAME_BACKEND` is jailed to the backend folder. |
| `FTP_BACKEND_PRIVATE_DIR`| Optional | `backend-app/` | FTP-relative path to private application directory. Set to `skip` if using a jailed FTP account that cannot write outside the subdomain. *(Also accepts legacy `FTP_PRIVATE_DIR`).* |
| `FTP_PORT` | Optional | `21` | FTP port (defaults to `21`). |
| `FTP_PROTOCOL` | Optional | `ftps` | Connection protocol (`ftps` for TLS encryption, defaults to `ftps`). |
| `FTP_SECURITY` | Optional | `strict` | Certificate validation (`strict` required; keep certificate verification enabled). |

---

## 6. Verifying Actual FTP-Relative Paths in cPanel

To ensure GitHub Actions uploads files into the correct directories without creating duplicate or nested folders:

### Step 1: Inspect Your FTP Account Directories in cPanel
1. In cPanel, navigate to **FTP Accounts**.
2. Locate the FTP accounts used for deployment:
   - **Frontend Account (`FTP_USERNAME_FRONTEND`):**
     - If the account Directory is set to `/home/{cpanel_user}/thetechcraft.co.ke/`:
       - The FTP user is jailed directly to the frontend document root.
       - In GitHub Secrets, set `FTP_FRONTEND_DIR` to `./`.
     - If the account Directory is set to `/home/{cpanel_user}/`:
       - The FTP user is unjailed and sees all account directories.
       - In GitHub Secrets, set `FTP_FRONTEND_DIR` to `thetechcraft.co.ke/`.
   - **Backend Account (`FTP_USERNAME_BACKEND`):**
     - If the account Directory is set to `/home/{cpanel_user}/backend.thetechcraft.co.ke/`:
       - The FTP user is jailed to the backend subdomain root.
       - In GitHub Secrets, set `FTP_BACKEND_PUBLIC_DIR` to `./`.
       - Because this account cannot reach outside its jail to deploy private application code, set `FTP_BACKEND_PRIVATE_DIR` to `skip` (and upload private code once via cPanel File Manager).
     - If the account Directory is set to `/home/{cpanel_user}/`:
       - The FTP user is unjailed.
       - In GitHub Secrets, set `FTP_BACKEND_PUBLIC_DIR` to `backend.thetechcraft.co.ke/` and `FTP_BACKEND_PRIVATE_DIR` to `backend-app/`.
   - **Jailed (Restricted):** If the Directory is set to `/home/{cpanel_user}/thetechcraft.co.ke/`:
     - The FTP user is trapped inside the frontend folder and cannot see or write to `backend.thetechcraft.co.ke/` or `backend-app/`.
     - **Fix:** In cPanel > FTP Accounts, delete the restricted account and create a new FTP account where the Directory field is changed to `/home/{cpanel_user}/` (clear out the default domain name that cPanel auto-fills).

### Step 2: Test Directory Listing via FileZilla / Cyberduck
Log into the FTP account using FileZilla with FTPS:
- **Server:** `ftp.thetechcraft.co.ke`
- **Encryption:** Require explicit FTP over TLS
- **Port:** `21`

Upon connecting, verify you can see the top-level folders:
- `thetechcraft.co.ke/`
- `backend.thetechcraft.co.ke/`
- `backend-app/`
- `secrets/`

The exact names you see here are your FTP-relative paths!

---

## 7. Automated News Bot Cron Job Setup

To execute the automated tech news generator without background daemon processes:

1. In cPanel, open **Cron Jobs** (under Advanced).
2. Set Common Settings to **Once per day** (e.g., at 03:00 AM) or **Every 12 hours**:
   - Minute: `0`
   - Hour: `3`
   - Day: `*`
   - Month: `*`
   - Weekday: `*`
3. Enter Command (replace `{cpanel_user}` with your actual cPanel username):
   ```bash
   /usr/local/bin/php /home/{cpanel_user}/backend-app/cron/auto_news.php > /dev/null 2>&1
   ```
4. Click **Add New Cron Job**.
5. Log entries are recorded to `/home/{cpanel_user}/backend-app/storage/logs/activity.log`.

---

## 8. Safe Deployment Testing with Dry Run

Before modifying remote files on HostPinnacle, test the workflow using the `dry_run` simulation:

1. Open your repository on GitHub.
2. Click the **Actions** tab.
3. Select **Production CI & Deployment** from the left workflow sidebar.
4. Click **Run workflow**:
   - Check the box: **Simulate deployment without modifying remote files (Dry Run)**.
   - Component to deploy: `all`.
5. Click the green **Run workflow** button.
6. Open the workflow execution logs:
   - Verify that `validate-and-build` passes with all 3 staging directories created and verified.
   - Verify that `check-config` normalizes the paths and confirms:
     - `deploy_frontend=true`
     - `deploy_backend_public=true`
     - `deploy_backend_private=true`
   - Verify that the three deployment steps execute in dry-run mode, comparing remote files over FTPS without altering or deleting any files.

---

## 9. Comprehensive Post-Deployment Verification Checklist

After a live deployment, verify each component systematically:

| Check | URL / Action | Expected Result |
| :--- | :--- | :--- |
| **Frontend Root** | `https://thetechcraft.co.ke` | 200 OK. Portfolio hero section, Bento skills grid, featured projects, and impact stats render cleanly. |
| **Frontend SPA Routing** | `https://thetechcraft.co.ke/news` (Hit F5 Refresh) | 200 OK. Tech news feed reloads without 404 error. |
| **Frontend Admin View** | `https://thetechcraft.co.ke/admin` (Hit F5 Refresh) | 200 OK. Admin authentication login card displays. |
| **Frontend 404 Page** | `https://thetechcraft.co.ke/nonexistent-route` | Custom branded 404 page renders with button to return home. |
| **Backend Subdomain Root**| `https://backend.thetechcraft.co.ke` | 200 OK. JSON response: `{"status":"ok","service":"TheTechCraft Backend API","health":"/api/health"}`. |
| **Backend Health Endpoint**| `https://backend.thetechcraft.co.ke/api/health` | 200 OK. JSON response: `{"status":"ok","message":"Backend API is running","environment":"production"}`. |
| **Backend CORS Preflight**| `curl -X OPTIONS -H "Origin: https://thetechcraft.co.ke" -I https://backend.thetechcraft.co.ke/api/projects` | `HTTP/1.1 204 No Content`, `Access-Control-Allow-Origin: https://thetechcraft.co.ke`. |
| **SSL / HTTPS Redirect** | `http://thetechcraft.co.ke` | 301 Moved Permanently -> `https://thetechcraft.co.ke/`. |
| **www Redirect** | `https://www.thetechcraft.co.ke` | 301 Moved Permanently -> `https://thetechcraft.co.ke/`. |
| **Legacy /api Redirect** | `https://thetechcraft.co.ke/api/health` | 301 Moved Permanently -> `https://backend.thetechcraft.co.ke/api/health`. |
| **Admin OTP Authentication**| Log in at `https://thetechcraft.co.ke/admin` | OTP code arrives in `victoroduor723@gmail.com` inbox within seconds. Submitting token grants dashboard access. |
| **Gemini AI Assistant** | Click floating chat widget at bottom right; ask a question | Context-grounded response generated citing real portfolio projects. |
| **Contact Form** | Submit message via portfolio contact section | Form confirms delivery; submission logged in database with visitor IP. |
| **Uploaded Media** | Upload image via Admin Projects panel | Image saved to `backend.thetechcraft.co.ke/uploads/` and renders on frontend via `https://backend.thetechcraft.co.ke/uploads/...`. |
| **Upload Script Protection**| Request nonexistent or blocked script in uploads: `https://backend.thetechcraft.co.ke/uploads/test.php` | 403 Forbidden (Apache denies execution). |

---

## 10. Rollback & Emergency Recovery Procedure

If a failure occurs during a release:

1. **Rollback Frontend:**
   - In cPanel File Manager, open `/home/{cpanel_user}/thetechcraft.co.ke/`.
   - Restore the previous build's `assets/` and `index.html` from local backup or previous staging artifact.
2. **Rollback Backend Code:**
   - In cPanel File Manager, restore the previous `src/` directory in `/home/{cpanel_user}/backend-app/`.
3. **Rollback Database:**
   - In phpMyAdmin, select your database, click **Import**, and select the latest `.sql` dump from `/home/{cpanel_user}/backend-app/storage/backups/`.
4. Clear browser cache and verify health at `https://backend.thetechcraft.co.ke/api/health`.
