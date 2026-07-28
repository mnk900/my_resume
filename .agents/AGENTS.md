# Agent Guidelines for Multi-Tenant Portfolio Builder SaaS

Before starting any task, creating a plan, or making edits in this codebase, you MUST read the comprehensive project documentation located at [.agents/PROJECT_DOCUMENTATION.md](file:///c:/xampp/htdocs/my_resume/.agents/PROJECT_DOCUMENTATION.md).

## Critical Rules for all models:
1. **Never Start From Scratch**: Always read the existing documentation first and check current file contents. Do not assume or rewrite core logic unless explicitly asked.
2. **Respect the Technology Stack**: This is a Laravel 10 application running on PHP 8.2 with a Bootstrap 5.3.3 frontend. Do not introduce other frontend frameworks (like Tailwind or React) unless explicitly requested.
3. **Themes & Views**: The frontend utilizes a multi-theme engine (Classic, Elegant, Premium) inside a single large view file [public.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/public.blade.php) extending [classic.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/classic.blade.php), [elegant.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/elegant.blade.php), or [premium.blade.php](file:///c:/xampp/htdocs/my_resume/resources/views/portfolio/themes/premium.blade.php).
4. **Cache Policy**: The application heavily caches portfolio retrievals. When modifying portfolio data, make sure to invoke the cache busting mechanism via `PortfolioService->clearCache($username)`.
5. **PDF & Word Export Integrity**: The CV export logic is implemented in [CVController.php](file:///c:/xampp/htdocs/my_resume/app/Http/Controllers/CVController.php) utilizing DomPDF for PDFs and PhpOffice\PhpWord for Word documents. Keep these in sync when updating fields.
6. **Connection System**: Portfolios are private by default. Access to private portfolios is restricted to connected users (accepted connection status in the `connections` table) or administrators.
