# Project Build Instructions & Development Log

This document serves as a historical log of the instructions and feature requests implemented during the development of the PHP API Backend and the React (Vite) Frontend.

## Backend Initialization & Security
1. **Modify Default Credentials**: Updated the default administrator login email to `victoroduor723@gmail.com` and securely hashed the password to `Admin123@`.
2. **Mail Service & SMTP Fixes**: Diagnosed and resolved local `sendmail` issues by converting the mailing system to a robust, pure PHP SMTP socket client (`src/Utils/MailService.php`) supporting `STARTTLS` for secure email delivery.
3. **Database Settings Table**: Created a `settings` table to securely store dynamic Mail Keys (SMTP Host, Port, User, App Password).
4. **Test Email Delivery**: Successfully verified the delivery of 6-digit OTP verification codes to the target email.
5. **Token Expiration Fix**: Resolved an issue where authentication tokens were prematurely expiring by migrating the expiration logic to rely directly on the MySQL server clock (`DATE_ADD(NOW(), INTERVAL 15 MINUTE)`).

## Frontend Architecture & Routing
6. **Implement Admin Dashboard UI**: Scaffolded the React frontend architecture, establishing the layouts for the `Projects`, `Settings`, and `Backup Manager` tools.
7. **Public Portfolio Setup**: Extracted the design assets from the `stitch_human_centric_it_portfolio` reference directory.
8. **Directory Restructuring**: Separated logic into distinct `src/pages/admin` and `src/pages/visitor` folders. Configured `react-router-dom` to handle secure routing between the public view (`/`) and the admin view (`/admin`).
9. **Import Path Corrections**: Resolved structural import path errors that occurred during the directory restructuring.

## Styling & Design System
10. **Tailwind CSS v4 Migration**: Configured the frontend styling. Transitioned from manual Vanilla CSS to a fully integrated **Tailwind CSS v4** setup natively using `@tailwindcss/vite`, ensuring modern utility classes apply instantly.
11. **Reusable UI Components**: Abstracted the frontend styling into reusable React components (`Button.jsx`, `Card.jsx`, `Input.jsx`) located in `src/components/ui/` to ensure design consistency and eliminate repetitive HTML across the admin pages.

## Layout & Navigation Enhancements
12. **Reusable Header & Footer**: Created `Header.jsx` and `Footer.jsx` for the public portfolio. Added a subtle "Admin Login" redirect link to allow the owner seamless access to the dashboard.
13. **Responsive Navigation Setup**: Upgraded both the Admin Dashboard and Visitor Portfolio to be fully responsive.
    - **Visitor**: Implemented a responsive hamburger drop-down menu on mobile screens.
    - **Admin**: Refactored the dashboard layout to utilize a slide-in off-canvas sidebar menu on mobile devices.
14. **Full-Screen Hero Layout**: Expanded the public landing page hero section to span the entire height of the viewport (`min-h-screen`). Added secondary Call-to-Action (CTA) buttons like "Explore Skills".

## API Expansion & Dynamic Content
15. **New Backend Endpoints**: Built and registered new database migrations, PHP Repositories, and REST Controllers to support:
    - **Skills**: Managing technical expertise, categories, and proficiency bars.
    - **Roadmap**: Managing planned, in-progress, and completed lifecycle milestones.
    - **News**: Managing a simple blog/update feed.
16. **Frontend API Consumption**: Updated `src/services/api.js` to map all the new endpoints. Built new `Skills.jsx`, `Roadmaps.jsx`, and `News.jsx` admin panels. Finally, updated `PortfolioApp.jsx` to dynamically fetch and render all this data onto the public-facing application upon load.

## Gemini AI Chat Integration
17. **Backend Gemini Service (`src/Services/GeminiService.php`)**: Built a curl-based service layer interacting with the Gemini API (using the standard and high-performance `gemini-2.5-flash` model). Reads the secure `GEMINI_API_KEY` from the environment configuration `.env` file (wrapped in double quotes to satisfy the `parse_ini_file` parser constraints).
18. **Chat Logging & Rate Limiting Schema (`migrations/008_create_chat_tables.sql`)**: Generated migrations for the chat system creating `chat_messages` to persist conversation sessions and `chat_rate_limits` to enforce a strict protection threshold of 20 requests per minute per IP address.
19. **Chat API Endpoint (`src/Controllers/ChatController.php`)**: Implemented a chat endpoint `/api/chat` that validates inputs, dynamically injects the live portfolio projects, skills, and roadmap items into the Gemini system prompt context to prevent hallucinations, calls the Gemini model, and saves messages to the database.
20. **Floating AI Chat Widget (`src/components/ui/ChatWidget.jsx`)**: Designed an elegant floating AI portfolio assistant widget with quick-start suggested queries, auto-scrolling message streams, loading states, and full conversation session history saved to `localStorage` (supporting reset/clear functionality).
21. **Premium Stylings Refactor & Skill Descriptions**:
    - **Database Migration (`migrations/009_add_description_to_skills.sql`)**: Extended the skills schema by adding a nullable `description` column.
    - **Repository & Admin Panel**: Updated `ContentRepository.php` to handle skill descriptions during CRUD operations and added a text area for skill descriptions in the admin `Skills.jsx` form.
    - **Visitor UI Refactoring (`PortfolioApp.jsx`)**: Redesigned the projects section from a grid into stacked full-width horizontal row cards with micro-hover lift transitions matching the references. Grouped skills by category inside distinct, beautifully aligned Bento grid panel cards displaying detailed descriptions under skill names where available.
22. **Advanced Layout Upgrades & Hierarchy**:
    - **Database Migration (`migrations/010_add_impact_line_to_projects.sql`)**: Added nullable `impact_line` column to projects schema. Modified `ProjectRepository.php` and the Admin form in `Projects.jsx` to manage the impact line in CRUD operations.
    - **3-Level Projects Hierarchy**: Upgraded the projects section to render the newest build as a massive Level 1 Featured Card featuring a bold impact line, tech badges, and deep description; followed by Level 2 Core Implementations (2-column layout); and Level 3 Additional Projects (minimal 3-column layout).
    - **Structured Bento Skills Grid**: Replaced all corporate KPI progress bars with card groups organized into a 3-column domain layout (Backend Engineering, Frontend Engineering, Systems & Security). Dynamically maps each skill to custom experience badges ("5+ projects"), descriptive tag lists ("OWASP Top 10", "REST APIs", "B2C"), and short details.
    - **Story Layering & Impact Strip**: Added mini story headers between page sections to guide visitor attention, and introduced a high-contrast "Featured Impact Strip" highlighting system stats (e.g. 12+ Systems, 3+ Automations, 99.9% Uptime) to break visual monotony.
