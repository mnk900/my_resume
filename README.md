# Multi-Tenant Portfolio Builder SaaS

A professional, high-performance portfolio builder built with Laravel 10, PHP 8.2, and Bootstrap 5. This platform allows users to register and instantly receive a personalized, SEO-friendly portfolio website with 13 editable CMS modules and 4 selection themes.

## 🚀 Key Features

- **Multi-Tenant Architecture**: Automated unique URL slugs (e.g., `domain.com/john-doe`).
- **13 Dynamic CMS Modules**: About, Skills, Projects, Experience, Services, Certifications, Education, Achievements, Contributions, Testimonials, Contact, and Resume/CV Upload.
- **Theme Engine**: 4 distinct, runtime-switchable themes (Classic, Modern, Elegant, Vibrant).
- **Admin Intelligence Center**: Real-time stats, user moderation, portfolio toggling, and global email broadcasts.
- **Performance Optimized**: Intelligent caching layer, eager loading for relationships, and symbols storage links.
- **Security First**: CSRF protection, RBAC (Role-Based Access Control), and secure file/media handling.
- **REST API**: JSON endpoints for headless data consumption.

## 🛠️ Technical Stack

- **Backend**: Laravel 10 (PHP 8.2)
- **Frontend**: Blade Templating, Bootstrap 5.3.3
- **Database**: Relational Schema (Normalized for Skills, Projects, etc.)
- **Media**: Local Disk Storage (Linked to public)

## 📥 Setup Instructions

### 1. Requirements
- PHP 8.2+
- Composer
- MySQL or SQLite

### 2. Installation
```bash
# Clone the repository and enter the directory
cd my_resume

# Install dependencies
composer install

# Create environment file
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

### 3. Database & Seeding
Configure your database in `.env`, then run:
```bash
# Run migrations and seed with Admin + Dummy data
php artisan migrate:fresh --seed
```

### 4. Media Configuration
```bash
# Link storage to public
php artisan storage:link
```

### 5. Start the Server
```bash
php artisan serve
```

## 🔐 Credentials (Seeded Data)

- **Admin User**:
  - Email: `admin@portfolio.com`
  - Password: `admin123`
- **Demo Users**:
  - Check the landing page search results to see the 5 auto-generated portfolios.

## 📂 Project Structure

- `app/Services/PortfolioService.php`: Core business logic and caching.
- `app/Http/Controllers/Api/`: REST API controllers.
- `resources/views/portfolio/themes/`: Individual blade layouts for each theme.
- `database/migrations/`: Normalized schema definitions.

---
Built with ❤️ by Antigravity AI for Advanced Agentic Coding.
