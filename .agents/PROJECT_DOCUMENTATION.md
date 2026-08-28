# Project Documentation: Multi-Tenant Portfolio Builder SaaS

**Last Updated**: 2026-08-02
**Documented by**: Antigravity AI (full codebase audit)

> **MANDATORY FIRST STEP**: Any agent or developer starting work in this codebase MUST read this entire document before making any changes. This project has many non-obvious architectural decisions that will cause bugs if ignored.

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Technology Stack & Dependencies](#2-technology-stack--dependencies)
3. [Local Environment Setup](#3-local-environment-setup)
4. [Directory Structure](#4-directory-structure)
5. [Database Schema & Eloquent Relationships](#5-database-schema--eloquent-relationships)
6. [Routing Architecture](#6-routing-architecture)
7. [Core Controllers](#7-core-controllers)
8. [Services Layer](#8-services-layer)
9. [Caching Architecture](#9-caching-architecture)
10. [Theme Engine & Frontend Views](#10-theme-engine--frontend-views)
11. [Security, Privacy & Connections System](#11-security-privacy--connections-system)
12. [CV Export Engine](#12-cv-export-engine)
13. [Admin Control Center](#13-admin-control-center)
14. [Invoice System](#14-invoice-system)
15. [Messaging & Email System](#15-messaging--email-system)
16. [Middleware](#16-middleware)
17. [Known Gotchas & Critical Rules](#17-known-gotchas--critical-rules)
18. [Testing Suite](#18-testing-suite)
19. [Common Development Workflows](#19-common-development-workflows)

---

## 1. Project Overview

**MyResumes** (app name: `MyResumes`) is a **multi-tenant SaaS** platform where each registered user gets a personal, fully-customizable portfolio/CV website. The platform is accessible at `http://localhost` (local) or `http://myresume.cloud` (production).

**Core User Journey:**
1. User registers (email verification required)
2. A `Portfolio` record is auto-created via `User::booted()` event
3. User fills in profile, skills, projects, experience etc. at `/portfolio/edit`
4. User's public page is accessible at `/{username}`
5. CV can be exported at `/{username}/cv/pdf` or `/{username}/cv/word`

**Admin Journey:**
- Admin users access `/admin` dashboard
- Manage users, toggle roles, view stats, create invoices, send emails
- Admin profiles are NOT publicly visible (return 404 on `/{username}`)

---

## 2. Technology Stack & Dependencies

| Layer | Technology |
|---|---|
| Backend | Laravel 10 (PHP ^8.1) |
| Auth | Laravel UI 4.6 (NOT Breeze!) with `MustVerifyEmail` |
| Frontend Styling | Bootstrap 5.3.3 via CDN |
| Icons | FontAwesome 6.4.0 via CDN |
| Typography | Google Fonts (Cormorant Garamond, DM Sans) |
| PDF Export | `barryvdh/laravel-dompdf` ^3.1 |
| Word Export | `phpoffice/phpword` ^1.1 |
| API Tokens | Laravel Sanctum ^3.2 |
| Testing | PHPUnit ^10.0 |
| Build Tool | Vite (via `vite.config.js`) |

> **Important**: Auth system uses `laravel/ui` with Bootstrap, NOT Breeze or Jetstream. Do not introduce Tailwind CSS or React unless explicitly requested.

---

## 3. Local Environment Setup

**Platform**: XAMPP on Windows (PHP 8.2, MySQL, Apache)

**Key .env values:**
```
APP_NAME=MyResumes
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_DATABASE=my_resume
DB_USERNAME=root
DB_PASSWORD=           <- empty on XAMPP
CACHE_DRIVER=file      <- file-based cache, NOT Redis
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=info@myresume.cloud
MAIL_ENCRYPTION=ssl
```

**Essential commands:**
```bash
php artisan migrate            # run migrations
php artisan storage:link       # required for uploaded image serving
php artisan test               # run test suite
php artisan cache:clear        # clear file cache
```

---

## 4. Directory Structure

```
my_resume/
├── .agents/
│   └── PROJECT_DOCUMENTATION.md        <- YOU ARE HERE
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php
│   │   │   ├── AdminInvoiceController.php
│   │   │   ├── CVController.php
│   │   │   ├── ConnectionController.php
│   │   │   ├── HomeController.php
│   │   │   ├── MessageController.php
│   │   │   ├── PortfolioController.php
│   │   │   ├── PortfolioModuleController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ProfileVisibilityController.php
│   │   └── Middleware/
│   │       └── IsAdmin.php
│   ├── Mail/
│   │   ├── AdminUserEmail.php
│   │   ├── ClientInvoiceEmail.php
│   │   ├── PortfolioMessageNotification.php
│   │   └── PortfolioMessageReply.php
│   ├── Models/
│   │   ├── User.php, Portfolio.php, Connection.php, Message.php
│   │   ├── Skill, Project, Experience, Education, Certification
│   │   ├── Training, Service, Achievement, Contribution, Testimonial
│   │   ├── Media, Publication, PortfolioSection, PortfolioSetting
│   │   ├── Invoice.php, InvoiceItem.php, Theme.php
│   └── Services/
│       └── PortfolioService.php
├── database/migrations/           <- 28 migration files
├── resources/views/
│   ├── welcome.blade.php          <- Landing page (search + portfolio grid)
│   ├── admin/
│   │   ├── index.blade.php        <- Admin dashboard (~50KB)
│   │   └── invoices/
│   │       ├── create.blade.php, show.blade.php, pdf.blade.php
│   ├── portfolio/
│   │   ├── edit.blade.php         <- Portfolio CMS editor (~140KB)
│   │   ├── public.blade.php       <- Public profile (~184KB, all 3 themes)
│   │   ├── private.blade.php      <- Private access denied page
│   │   └── themes/
│   │       ├── classic.blade.php, elegant.blade.php, premium.blade.php
│   ├── cv/
│   │   ├── template.blade.php     <- DomPDF CV template
│   └── emails/
│       ├── admin/ (user_email.blade.php, invoice_email.blade.php)
│       └── portfolio/ (notification.blade.php, reply.blade.php)
├── routes/
│   ├── web.php                    <- All routes
│   └── auth.php                   <- Laravel UI auth routes
└── tests/Feature/
    ├── AdminTest.php, ConnectionTest.php, ContactFormTest.php
    ├── InvoiceTest.php, ProfileTest.php, ProjectTest.php
```

---

## 5. Database Schema & Eloquent Relationships

### Users Table (`users`)
| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `name` | string | Full name |
| `username` | string unique | URL slug (/{username}) |
| `email` | string unique | |
| `password` | string | bcrypt |
| `role` | string | 'user' or 'admin' |
| `email_verified_at` | timestamp | null = unverified |

**Key User methods:**
- `isAdmin()` -> `$this->role === 'admin'`
- `portfolio()` -> HasOne Portfolio
- `connectionWith(User $other)` -> Connection|null (either direction)
- `isConnectionWith(User $other)` -> bool (accepted only)
- `hasPendingRequestFrom(User)` / `hasPendingRequestTo(User)` -> bool
- `acceptedConnections()` -> User collection (both directions)

**Auto-create Portfolio on registration (booted() event):**
```php
static::created(function ($user) {
    $user->portfolio()->create([
        'title' => $user->name . "'s Portfolio",
        'description' => 'Welcome to my portfolio.',
        'theme' => 'premium',   // <- DEFAULT THEME IS 'premium'
    ]);
});
```

### Portfolios Table (`portfolios`)
| Column | Type | Notes |
|---|---|---|
| `user_id` | FK -> users | |
| `title` | string | Portfolio title |
| `description` | text | Short bio/summary |
| `detailed_bio` | text | Extended bio |
| `theme` | string | 'classic', 'elegant', or 'premium' |
| `is_active` | boolean | Admin can deactivate |
| `is_public` | boolean | Privacy (default: private/false) |
| `position` | string nullable | Job title |
| `city`, `organization`, `country` | string nullable | |
| `contact_number`, `linkedin_url` | string nullable | |
| `profile_image` | string nullable | Relative path in storage/app/public/ |
| `show_contact_info` | boolean | |
| `show_email`, `show_phone`, `show_linkedin` | boolean | |
| `show_skills`, `show_projects`, `show_experience` | boolean | |
| `show_education`, `show_services`, `show_certifications` | boolean | |
| `show_trainings`, `show_achievements`, `show_contributions` | boolean | |
| `show_testimonials`, `show_media`, `show_publications` | boolean | |

All boolean fields are cast to boolean in `$casts`.

### CMS Module Tables (HasMany -> Portfolio)
| Model | Table | Key Fields |
|---|---|---|
| Skill | skills | portfolio_id, name, percentage(0-100), category, icon |
| Project | projects | portfolio_id, title, description, link, image_path |
| Experience | experiences | portfolio_id, company, position, start_date, end_date, description |
| Education | education | portfolio_id, institution, degree, start_date, end_date |
| Certification | certifications | portfolio_id, name, issuer, date, link |
| Training | trainings | portfolio_id, title, institution, date |
| Service | services | portfolio_id, title, description, icon |
| Achievement | achievements | portfolio_id, title, description |
| Contribution | contributions | portfolio_id, title, description, link |
| Testimonial | testimonials | portfolio_id, client_name, designation, content |
| Media | media | portfolio_id, type(tv/oped), title, channel_platform, newspaper_name, date, link |
| Publication | publications | portfolio_id, type, authors, year, title, publisher, link, report_path |
| PortfolioSection | portfolio_sections | portfolio_id, type, title, content, image_path, file_path, order |

### Connections Table (`connections`)
| Column | Notes |
|---|---|
| sender_id | FK -> users (who initiated) |
| receiver_id | FK -> users (who received) |
| status | 'pending' or 'accepted' |

### Messages Table (`messages`)
| Column | Notes |
|---|---|
| portfolio_id | FK -> portfolios |
| name, email | Sender's info |
| subject | Always "Message from Portfolio" |
| message | text |
| reply | text nullable (owner's reply) |
| is_read | boolean |

### Invoices Tables (`invoices`, `invoice_items`)
Invoice format: `INV-{YEAR}-{SEQ}` (e.g., INV-2026-001)

Default payment details (hardcoded in migration defaults):
- Bank: Habib Bank Limited
- Account Title: Muhammad Naeem Khan
- IBAN: PK10HABB0050757901822803

### Supporting Tables
- `themes` -> (id, name, slug, is_active) - admin-managed
- `portfolio_settings` -> generic key-value per portfolio
- `failed_jobs`, `personal_access_tokens`, `password_resets` -> Laravel standard

---

## 6. Routing Architecture

**File**: `routes/web.php`

### CRITICAL: Wildcard Route Must Always Be Last
`Route::get('/{username}', ...)` is a catch-all. It is declared at line 132 (end of web.php).
Any route declared AFTER `/{username}` will be intercepted by it and return 404.
Always add new routes BEFORE line 132.

### Route Groups Summary
| Group | Prefix | Middleware |
|---|---|---|
| Landing | / | none |
| Auth | various | none (Laravel UI) |
| Portfolio edit | /portfolio, /profile | auth, verified |
| CMS modules | /modules/ | auth, verified |
| Connections | /connections/ | auth, verified |
| Messages | /messages/ | auth, verified |
| Admin | /admin | auth, verified, IsAdmin |
| Invoices | /admin/invoices | auth, verified, IsAdmin (resource) |
| Public contact | /contact/submit/{portfolio} | NONE - no auth required |
| Public portfolio | /{username} | none (LAST ROUTE) |
| CV download | /{username}/cv/pdf, /{username}/cv/word | none |

### Named Routes Reference
```
welcome                          GET /
portfolio.edit                   GET /portfolio/edit
portfolio.update                 POST /portfolio
portfolio.show                   GET /{username}
portfolio.contact.store          POST /contact/submit/{portfolio}
profile.edit / update / destroy  GET/PATCH/DELETE /profile
dashboard                        GET /dashboard (redirect only)
admin.index                      GET /admin
admin.portfolio.toggle           POST /admin/portfolio/{portfolio}/toggle
admin.users.toggle-role          POST /admin/users/{user}/toggle-role
admin.users.toggle-verification  POST /admin/users/{user}/toggle-verification
admin.users.destroy              DELETE /admin/users/{user}
admin.send-email                 POST /admin/send-email
admin.broadcast                  POST /admin/broadcast
invoices.index/create/store      GET/GET/POST /admin/invoices
invoices.show                    GET /admin/invoices/{invoice}
invoices.pdf                     GET /admin/invoices/{invoice}/download/pdf
invoices.email                   POST /admin/invoices/{invoice}/email
invoices.destroy                 DELETE /admin/invoices/{invoice}
modules.skills.store/update/destroy      POST/PATCH/DELETE /modules/skills[/{skill}]
modules.projects.store/update/destroy   ... (same for all 12 module types)
connections.request              POST /connections/request/{user}
connections.accept               POST /connections/accept/{connection}
connections.reject               POST /connections/reject/{connection}
connections.cancel               POST /connections/cancel/{connection}
connections.remove               POST /connections/remove/{user}
messages.reply                   POST /messages/{message}/reply
messages.read                    POST /messages/{message}/read
messages.destroy                 DELETE /messages/{message}
cv.download.pdf                  GET /{username}/cv/pdf
cv.download.word                 GET /{username}/cv/word
```

---

## 7. Core Controllers

### PortfolioController
- `show($username)`: Loads via PortfolioService, force-loads all relations fresh, checks admin block + privacy rules.
- `edit()`: Editor view with portfolio, all relations, active themes, pending/sent connections, search results.
- `update(Request)`: Converts 'show'/'hide' -> bool, 'active'/'inactive' -> bool, delegates to PortfolioService.
- `storeSection/updateSection/destroySection`: Generic PortfolioSection CRUD.

### PortfolioModuleController
Handles CRUD for all 12 CMS module types. Every module follows this exact pattern:
```php
storeXxx()    // validate -> create -> bustCache() -> back()
updateXxx()   // authorizePortfolioOwner() -> validate -> update -> bustCache() -> back()
destroyXxx()  // authorizePortfolioOwner() -> delete -> bustCache() -> back()
```
Private helpers:
- `bustCache()` -> `$this->portfolioService->clearCache(Auth::user()->username)`
- `authorizePortfolioOwner($model)` -> `abort_unless($model->portfolio->user_id === Auth::id(), 403)`

File storage:
- Project images -> `storage/app/public/projects/`
- Publication reports -> `storage/app/public/reports/`

### AdminController
- `index()`: Returns admin.index with users, themes, stats, last-5 messages, paginated invoices.
- `toggleRole()`: user <-> admin. BLOCKS self-demotion.
- `destroyUser()`: BLOCKS self-deletion. Deletes portfolio first, then user.
- `sendEmail()`: recipient='all' -> broadcast; recipient=user_id -> direct email.
- `broadcast()` / `sendNotification()`: MOCK ONLY - logs to Log::info(), does NOT send emails.

### CVController - See Section 12
### AdminInvoiceController - See Section 14

### ConnectionController
- `sendRequest()`: Blocks self + admin connections. Checks for existing.
- `acceptRequest()`: Only receiver_id can accept.
- `rejectRequest()`: Only receiver_id can reject (deletes record).
- `cancelRequest()`: Only sender_id can cancel (deletes record).
- `removeConnection()`: Either party can remove accepted connection.

### MessageController
- `store(Portfolio)`: Creates message, emails owner via PortfolioMessageNotification.
- `reply(Message)`: Ownership check, saves reply, emails sender via PortfolioMessageReply.
- `markAsRead()` / `destroy()`: Ownership-gated operations.

### HomeController
Landing page. Queries active, non-admin portfolios (12/page). Full-text search on title, description, position, org, city, country, user name, sections. Filters: skills, position, city, organization, country.

---

## 8. Services Layer

### PortfolioService (`app/Services/PortfolioService.php`)

```php
getByUsername(string $username): Portfolio
```
Wraps in `Cache::remember("portfolio_{$username}", 3600, ...)`.
Eager-loads: user, sections, skills, projects, experiences, testimonials, services, certifications, education, achievements, contributions.
DOES NOT load: trainings, media, publications (these are loaded in PortfolioController@show via ->load()).

```php
clearCache(string $username): void
```
`Cache::forget("portfolio_{$username}")`.
MUST be called after every write on portfolio or its modules.

```php
updatePortfolio(Portfolio $portfolio, array $data): Portfolio
```
Handles UploadedFile for profile_image -> stores to 'profile_images' disk.
Calls update() then clearCache() automatically.

```php
addSection(Portfolio $portfolio, array $data): PortfolioSection
```
Handles image/file uploads. Auto-calculates order.

---

## 9. Caching Architecture

| Setting | Value |
|---|---|
| Driver | file (CACHE_DRIVER=file) |
| Cache Key | portfolio_{username} |
| TTL | 3600 seconds (1 hour) |
| Location | storage/framework/cache/data/ |

ALWAYS bust cache after writes. PortfolioModuleController uses bustCache() automatically.
PortfolioService->updatePortfolio() busts automatically.

Cache Staleness Note: PortfolioController@show calls $portfolio->load([...all relations...]) after getByUsername(). This intentionally re-fetches fresh data for display, bypassing cached stale relations. The cache is primarily for the base portfolio object.

---

## 10. Theme Engine & Frontend Views

### Three Themes
| Slug | File | Description |
|---|---|---|
| classic | themes/classic.blade.php | Clean light layout |
| elegant | themes/elegant.blade.php | Indigo-accented, Cormorant Garamond + DM Sans |
| premium | themes/premium.blade.php | Dark mode glassmorphism |

### Theme Selection Logic
In public.blade.php:
```php
@php $theme = $portfolio->theme ?? 'classic'; @endphp
@extends('portfolio.themes.' . $theme)
```
Then content sections use @if($theme === 'premium') / @elseif('elegant') / @else blocks.

DEFAULT THEME for new users: 'premium' (set in User::booted()).

### Large Monolithic View Files
- portfolio/edit.blade.php: ~140KB - Full CMS editor with tabs for all 12 modules
- portfolio/public.blade.php: ~184KB - All three theme variants in one file
- admin/index.blade.php: ~50KB - Admin dashboard with tabs

When editing these files: be surgical. Do not restructure unless explicitly asked.

---

## 11. Security, Privacy & Connections System

### Portfolio Visibility Logic
```
is_public = true  -> Anyone can view (no auth)
is_public = false -> Access granted only if:
    1. Viewer is owner (currentUser->id === user->id)
    2. Viewer is admin (currentUser->isAdmin())
    3. Viewer has 'accepted' connection with owner
Otherwise -> shows portfolio/private.blade.php (with connection request option)
```

### Admin Portfolio Block
Admin users' portfolios abort(404) on public access. This is intentional.

### Connection Flow
```
sendRequest -> status: 'pending'
acceptRequest -> status: 'accepted'  (or rejectRequest -> record deleted)
removeConnection -> record deleted
cancelRequest (by sender) -> record deleted
```

Constraints: no self-connect, no admin-connect, no duplicate requests.

### Dashboard Redirect
/dashboard redirects admin -> /admin, users -> /portfolio/edit.

---

## 12. CV Export Engine

### PDF Export (/{username}/cv/pdf)
- barryvdh/laravel-dompdf with isHtml5ParserEnabled, isPhpEnabled, isRemoteEnabled
- Template: resources/views/cv/template.blade.php
- Profile image base64-encoded for PDF
- Paper: A4, portrait

### Word Export (/{username}/cv/word)
- PhpOffice\PhpWord with PclZip fallback (required on XAMPP/Windows):
  ```php
  \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);
  ```
- Streams DOCX, deletes temp file after send.

### CV Data Limits in prepareCVData()
| Section | Limit |
|---|---|
| Skills categories | 5 max |
| Projects | 4 most recent |
| Certifications | 5 (by date desc) |
| Trainings | 5 (by date desc) |
| Education | 5 (by start_date desc) |
| Experiences | All; only first 6 have has_details=true |

CV respects show_email, show_phone, show_linkedin visibility flags.
Default location fallback: 'Gilgit-Baltistan, Pakistan' (hardcoded in prepareCVData()).

---

## 13. Admin Control Center

### Dashboard Stats
```php
$stats = [
    'total_users'       => User::count(),
    'active_portfolios' => Portfolio::where('is_active', true)->count(),
    'verified_users'    => User::whereNotNull('email_verified_at')->count(),
    'total_themes'      => Theme::count(),
];
```

### User Management Safety Guards
- Cannot toggle own role (self-demotion blocked)
- Cannot delete own account (self-deletion blocked)

### Email Operations
- POST /admin/send-email with recipient='all' -> broadcast to ALL users via AdminUserEmail
- POST /admin/send-email with recipient=user_id -> direct email to that user

### Theme Management
Admin creates themes (name + slug) in themes table. Active themes shown in user portfolio editor.

---

## 14. Invoice System

### Invoice Number Format: INV-{YEAR}-{SEQ} (e.g., INV-2026-001)
Sequence auto-increments per year.

### Routes (admin-only resource)
- GET/POST /admin/invoices - index/create/store
- GET /admin/invoices/{invoice} - show
- GET /admin/invoices/{invoice}/download/pdf - PDF download
- POST /admin/invoices/{invoice}/email - email invoice
- DELETE /admin/invoices/{invoice} - destroy
NOTE: edit (GET /admin/invoices/{invoice}/edit) and update (PUT/PATCH) are NOT implemented.

### PDF & Email
- PDF uses DomPDF with logo from public/images/itechgb_logo.png (base64 encoded)
- Email uses ClientInvoiceEmail with PDF attached in memory
- Email goes to $invoice->email (may differ from registered user's email)

### Default Line Items on Create Form
1. Website UI/UX Design
2. Front-End Development
3. Back-End Development & CMS
4. Database Design & Integration
5. Responsive Design & Cross-Browser Compatibility
6. Testing, Bug Fixing & Deployment
7. Hosting & Domain - PKR 5,000

---

## 15. Messaging & Email System

### Mail Classes
| Class | Trigger |
|---|---|
| AdminUserEmail | Admin direct/broadcast email |
| ClientInvoiceEmail | Invoice email with PDF attachment |
| PortfolioMessageNotification | Owner notified of new contact form message |
| PortfolioMessageReply | Reply sent to contact form sender |

### SMTP Config (Production)
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=info@myresume.cloud
MAIL_FROM_ADDRESS=info@myresume.cloud
```
Do NOT change MAIL_MAILER to 'log' in production.

---

## 16. Middleware

### IsAdmin (app/Http/Middleware/IsAdmin.php)
```php
if (!auth()->check() || !auth()->user()->isAdmin()) {
    abort(403, 'Unauthorized access.');
}
```
Applied to /admin route group in web.php AND via constructor in AdminInvoiceController.

### Email Verification
User implements MustVerifyEmail. All auth routes require ['auth', 'verified']. Unverified users cannot access dashboard or portfolio editor.

---

## 17. Known Gotchas & Critical Rules

### RULE 1: Always Bust Cache After ANY Write
```php
$this->portfolioService->clearCache($username);
// or in PortfolioModuleController:
$this->bustCache();
```
Failure to do this means users see stale data for up to 1 hour.

### RULE 2: Wildcard Route /{username} MUST Be Last in web.php
Any route declared after line 132 (the /{username} route) will be intercepted by it.
Always insert new routes BEFORE the wildcard route.

### RULE 3: Admin Portfolios Abort 404
Intentional. Admin users do not have public portfolio pages.

### RULE 4: Use PclZip for Word Documents
```php
\PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);
```
Required on XAMPP/Windows where ZipArchive extension may be missing. Do not remove.

### RULE 5: Default Theme is 'premium' Not 'classic'
New user portfolios default to 'premium' theme (set in User::booted()).
Do not assume 'classic' as default.

### RULE 6: Portfolio Form Sends Strings Not Booleans
The portfolio update form sends 'show'/'hide' and 'active'/'inactive' and 'public'/'private'.
PortfolioController@update converts these to booleans:
```php
$data['show_skills'] = $request->input('show_skills') === 'show';
$data['is_active']   = $request->input('is_active') === 'active';
$data['is_public']   = $request->input('is_public') === 'public';
```

### RULE 7: PortfolioService Cache Misses trainings/media/publications
getByUsername() does NOT eager-load trainings, media, publications.
They are loaded in PortfolioController@show via ->load().
When adding a new module: add to BOTH the Service with() AND the Controller load().

### RULE 8: Contact Form is Public (No Auth)
POST /contact/submit/{portfolio} requires no authentication.
Anyone can submit, and it emails the portfolio owner regardless of public/private status.

### RULE 9: Invoice edit/update Routes Not Implemented
AdminInvoiceController uses resource routing but edit() and update() methods do not exist.
Do not expect GET /admin/invoices/{invoice}/edit to work.

### RULE 10: Storage Symlink Required for Images
php artisan storage:link must be run once.
Without it, uploaded profile/project images won't display.

### RULE 11: File-Based Cache on Windows
Cache in storage/framework/cache/data/. May have permission issues on Windows.
If cache behaves oddly: php artisan cache:clear

### RULE 12: No Conflicting IDs in public.blade.php
All three theme variants live in one file. Bootstrap modal/tab/accordion IDs must be unique across all three theme blocks.

---

## 18. Testing Suite

Tests are in tests/Feature/. They use SQLite in-memory (check phpunit.xml).

| File | Coverage |
|---|---|
| AdminTest.php | Stats, user moderation, self-delete/demote safety |
| ConnectionTest.php | Privacy, request/accept/reject/cancel/remove flows |
| ContactFormTest.php | Public contact form submission |
| InvoiceTest.php | Invoice create/view/PDF/email |
| ProfileTest.php | Profile update, account deletion |
| ProjectTest.php | Project CRUD with file upload |

```bash
php artisan test                              # all tests
php artisan test tests/Feature/AdminTest.php  # specific file
```

---

## 19. Common Development Workflows

### Adding a New CMS Module (e.g., "Awards")
1. Migration: create `awards` table with portfolio_id FK
2. Model: App\Models\Award with belongsTo(Portfolio::class)
3. Portfolio.php: add `awards()` relation + add to $fillable if show_awards needed
4. Route: add in web.php modules prefix group (store/update/destroy)
5. PortfolioModuleController: add storeAward/updateAward/destroyAward
6. PortfolioService@getByUsername: add 'awards' to ->with([...])
7. PortfolioController@show: add 'awards' to ->load([...])
8. PortfolioController@edit: add 'awards' to ->with([...])
9. Migration: add show_awards column to portfolios table
10. Portfolio $fillable + $casts for show_awards
11. portfolio/edit.blade.php: add CRUD UI
12. portfolio/public.blade.php: add display for all 3 themes
13. php artisan migrate

### Modifying Portfolio Fields
1. Migration for new column
2. Portfolio::$fillable
3. Portfolio::$casts if boolean
4. Validation in PortfolioController@update
5. $data extraction in PortfolioController@update
6. UI in portfolio/edit.blade.php
7. Display in portfolio/public.blade.php (all 3 themes)
8. CVController@prepareCVData() if CV-relevant
9. CVController@createWordDocument() if Word-relevant

### Creating Admin via Tinker
```bash
php artisan tinker
>>> App\Models\User::where('email', 'x@x.com')->update(['role' => 'admin'])
```

### Debugging Missing Images
1. Run: php artisan storage:link
2. Check: storage/app/public/profile_images/ or projects/
3. Blade: {{ asset('storage/' . $portfolio->profile_image) }}

### Clearing All Caches
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```