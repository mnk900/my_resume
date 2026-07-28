# Project Documentation: Multi-Tenant Portfolio Builder SaaS

This document provides a comprehensive technical overview and architecture mapping of the Multi-Tenant Portfolio Builder SaaS project. All subsequent models, subagents, and developers working on this codebase must refer to this documentation to maintain architectural consistency and avoid redundant development.

---

## 🛠️ Technology Stack & Dependencies

*   **Backend Framework**: Laravel 10 (running on PHP 8.2+)
*   **Frontend Templating**: Blade Templating Engine
*   **CSS Framework**: Bootstrap 5.3.3 (integrated via CDN links in theme layouts)
*   **Icons**: FontAwesome 6.4.0 (CDN link)
*   **Typography**: Google Fonts (using elegant pairings like *Cormorant Garamond* and *DM Sans*)
*   **PDF Generation**: `barryvdh/laravel-dompdf` (DomPDF wrapper)
*   **Word Export**: `phpoffice/phpword` (Office Open XML Word processing library)
*   **Database**: Normalized Relational Schema (typically MySQL or SQLite)

---

## 📂 Architecture & Directory Layout

The codebase follows the standard Laravel MVC architectural pattern, layered with specialized services and controllers:

*   **Models (`app/Models/`)**: Eloquent models mapped to corresponding database tables.
*   **Controllers (`app/Http/Controllers/`)**:
    *   [PortfolioController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/PortfolioController.php): Controls editing views, updates, public viewing, and privacy checks.
    *   [PortfolioModuleController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/PortfolioModuleController.php): Manages CRUD operations for individual CMS sections (skills, projects, experiences, etc.).
    *   [CVController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/CVController.php): Directs PDF and DOCX CV compilation and downloads.
    *   [AdminController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/AdminController.php): Operates platform stats, user management, and broadcast features.
    *   [ConnectionController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/ConnectionController.php): Governs networking requests and visibility handshakes.
    *   [MessageController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/MessageController.php): Stores and replies to contact form submissions.
*   **Services (`app/Services/`)**:
    *   [PortfolioService](file:///c:/xampp/htdocs/my_resume/app/Services/PortfolioService.php): Handles core business logic, file storage paths, and the caching abstraction layer.
*   **Middleware (`app/Http/Middleware/`)**:
    *   `IsAdmin`: Restricts admin endpoints strictly to users with the `admin` role.

---

## 💾 Database Schema & Eloquent Relationships

The application schema is divided into users, portfolios, specialized sections, networking connections, and messaging elements:

### 1. Core Authentication & Configuration
*   **User (`app/Models/User.php`)**: Represents registration accounts.
    *   `role` column controls access level (`user` or `admin`).
    *   Defines relationship `portfolio()` (`HasOne` with automatic creation triggered on the `booted()` created event).
    *   Manages connection hooks (`connectionsSent()`, `connectionsReceived()`, `acceptedConnections()`).
*   **Portfolio (`app/Models/Portfolio.php`)**: Stores user-scoped settings, flags, and personal details.
    *   `is_active` (boolean) & `is_public` (boolean).
    *   Visibility toggles for all sub-sections (e.g. `show_skills`, `show_projects`, etc.).
    *   `theme` column (defines the layout string).
    *   Maintains references to all child CMS entities.

### 2. CMS Dynamic Modules (HasMany Relationships to Portfolio)
Each portfolio possesses individual records for:
*   [Skill](file:///c:/xampp/htdocs/my_resume/app/Models/Skill.php): `name`, `percentage`, `category`, `icon`.
*   [Project](file:///c:/xampp/htdocs/my_resume/app/Models/Project.php): `title`, `description`, `link`, `image_path` (stored under `projects/` disk directory).
*   [Experience](file:///c:/xampp/htdocs/my_resume/app/Models/Experience.php): `company`, `position`, `start_date`, `end_date`, `description`.
*   [Education](file:///c:/xampp/htdocs/my_resume/app/Models/Education.php): `institution`, `degree`, `start_date`, `end_date`.
*   [Certification](file:///c:/xampp/htdocs/my_resume/app/Models/Certification.php): `name`, `issuer`, `date`, `link`.
*   [Training](file:///c:/xampp/htdocs/my_resume/app/Models/Training.php): `title`, `institution`, `date`.
*   [Service](file:///c:/xampp/htdocs/my_resume/app/Models/Service.php): `title`, `description`, `icon`.
*   [Achievement](file:///c:/xampp/htdocs/my_resume/app/Models/Achievement.php): `title`, `description`.
*   [Contribution](file:///c:/xampp/htdocs/my_resume/app/Models/Contribution.php): `title`, `description`, `link`.
*   [Testimonial](file:///c:/xampp/htdocs/my_resume/app/Models/Testimonial.php): `client_name`, `designation`, `content`.
*   [Media](file:///c:/xampp/htdocs/my_resume/app/Models/Media.php): `type` (tv or oped), `title`, `channel_platform`, `newspaper_name`, `date`, `link`.
*   [Publication](file:///c:/xampp/htdocs/my_resume/app/Models/Publication.php): `type`, `authors`, `year`, `title`, `publisher`, `link`, `report_path`.

---

## 🛣️ Routing Layout & Wildcard Ordering Rules

The application defines its web routing system inside [web.php](file:///c:/xampp/htdocs/my_resume/routes/web.php) and [auth.php](file:///c:/xampp/htdocs/my_resume/routes/auth.php):

*   **Authentication Routes**: Standard authentication views and logic are loaded via `require __DIR__.'/auth.php';` and `Auth::routes(['verify' => true]);`.
*   **Wildcard Overrides Danger**: Since the application matches portfolios using `Route::get('/{username}', ...)` as a dynamic slug, **all custom routes (including authentication, dashboard, or profiles) must be declared BEFORE the wildcard `{username}` route**. Declaring routes after `/{username}` will cause those URLs to be intercepted by the portfolio wildcard parser, resulting in unnecessary database hits and `404` errors for valid pages.

---

## ⚡ Intelligent Caching Architecture

To prevent database overhead on high-traffic public views, portfolios are aggressively cached using Laravel's Cache facade:

*   **Cache Key**: `portfolio_{username}`
*   **Caching Strategy**: Eager loads all associated sections (`sections`, `skills`, `projects`, etc.) for 3600 seconds (1 hour).
*   **Cache Busting**: Whenever a user updates their profile, adds sections, or alters sub-modules, `PortfolioService->clearCache($username)` is executed to clear the cache. Developers must always execute this bust after write/delete database queries.

---

## 🎨 Theme Engine & Blade Templates

The layout utilizes a multi-theme runtime selector:

1.  **Layout Wrappers (`resources/views/portfolio/themes/`)**:
    *   [classic.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/classic.blade.php): Clean layout with light backgrounds.
    *   [elegant.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/elegant.blade.php): Indigo-accented layout focusing on DM Sans & Cormorant Garamond typography.
    *   [premium.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/premium.blade.php): Dark mode glassmorphism layout with modern UI highlights.
2.  **Theme Rendering Logic**:
    *   [public.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/public.blade.php) checks `$portfolio->theme` (or defaults to `classic`) and extends the target layout view wrapper (`@extends('portfolio.themes.' . $theme)`).
    *   Conditional directives (`@if($theme === 'premium')` etc.) switch structure and UI layouts inside `public.blade.php`.

---

## 🔒 Security, Connections & Privacy Matrix

Portfolios enforce role-based access controls and connection status constraints:

*   **Public vs. Private (`is_public` column)**:
    *   If `is_public` is `true`, anyone can view the portfolio profile.
    *   If `is_public` is `false`, access is guarded.
*   **Authorization Rules for Private Portfolios**:
    A user can only view a private portfolio if:
    1.  They are the owner of the portfolio.
    2.  They are an administrator (`role === 'admin'`).
    3.  They are logged in and have an `accepted` relationship in the `connections` table with the owner.
*   **Connection Requests**:
    Managed by the `connections` table (`sender_id`, `receiver_id`, `status` [pending, accepted]). Blocked for admin requests and self-connection.

---

## 📄 CV Export Engine

Users can download automated resumes in two formats:

1.  **PDF Download (`/cv/pdf` via `downloadPDF()`)**:
    *   Uses DomPDF mapping data to the [cv.template](file:///c:/xampp/htdocs/my_resume/resources/views/cv/template.blade.php) view.
    *   Constrains sections using `page-break-inside: avoid` to prevent mid-row splits.
    *   Implements base64 encoding for profile pictures to support secure image parsing in PDF runtimes.
2.  **Word Document (`/cv/word` via `downloadWord()`)**:
    *   Builds files dynamically using `PhpOffice\PhpWord` API styles.
    *   Configures PclZip fallback (`Settings::setZipClass(Settings::PCLZIP)`) for environments missing native `ZipArchive` extensions.
    *   Trims HTML bullet lists into clean Word document bullet structures.

---

## ⚙️ Admin Control Center

The administration hub provides macro moderation options:
*   **Statistics**: Real-time aggregation of users, active portfolios, verified emails, and themes.
*   **User Management**: Toggle user role (regular user <=> admin), toggle verification status, and hard delete. Self-deletion and self-demotion are strictly blocked.
*   **Email Operations**: Uses [AdminUserEmail](file:///c:/xampp/htdocs/my_resume/app/Mail/AdminUserEmail.php) to send direct mail messages to a specific user, or bulk mailings to all registered members.
*   **Invoice & Billing**: Uses [AdminInvoiceController](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/AdminInvoiceController.php) to generate, list, view, print, and compile PDF invoices for portfolio client users. Generates sequential invoice IDs (`INV-{year}-{seq}`), provides a javascript-powered client auto-filler, auto-calculates discounts/taxes, and supports printable formatting.

---

## 🧪 Testing and Verification

A comprehensive PHPUnit suite tests backend features:
*   **Feature Tests Directory**: `tests/Feature/`
    *   `AdminTest.php`: Moderation, stats, safety blocks.
    *   `ConnectionTest.php`: Privacy, requests, handshake accepted/ignored scenarios.
    *   `ProjectTest.php`: Project uploads, edits.
    *   `ContactFormTest.php` & `ProfileTest.php`.
*   **Execution**:
    ```bash
    php artisan test
    ```
