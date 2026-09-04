<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/mock-interviews', [\App\Http\Controllers\MockInterviewController::class, 'index'])->name('mock-interviews.index');




// Public Messaging - Throttled against spam
Route::get('/contact/submit/{portfolio}', function() { return 'Contact endpoint is reachable'; });
Route::post('/contact/submit/{portfolio}', [\App\Http\Controllers\MessageController::class, 'store'])->middleware('throttle:6,1')->name('portfolio.contact.store');

// Laravel UI Routes
Auth::routes(['verify' => true]);

use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function() {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.index');
        }
        return redirect()->route('portfolio.edit');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/visibility', [App\Http\Controllers\ProfileVisibilityController::class, 'update'])->name('profile.visibility.update');
    Route::get('/portfolio/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::post('/portfolio', [PortfolioController::class, 'update'])->name('portfolio.update');

    // Specialized Module Routes
    Route::prefix('modules')->name('modules.')->group(function() {
        Route::post('/skills', [\App\Http\Controllers\PortfolioModuleController::class, 'storeSkill'])->name('skills.store');
        Route::post('/skills/category-update', [\App\Http\Controllers\PortfolioModuleController::class, 'updateCategorySkills'])->name('skills.category-update');
        Route::post('/skills/category-destroy', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyCategorySkills'])->name('skills.category-destroy');
        Route::match(['put', 'patch', 'post'], '/skills/{skill}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateSkill'])->name('skills.update');
        Route::delete('/skills/{skill}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroySkill'])->name('skills.destroy');
        
        Route::post('/projects', [\App\Http\Controllers\PortfolioModuleController::class, 'storeProject'])->name('projects.store');
        Route::match(['put', 'patch', 'post'], '/projects/{project}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{project}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyProject'])->name('projects.destroy');
        
        Route::post('/experiences', [\App\Http\Controllers\PortfolioModuleController::class, 'storeExperience'])->name('experiences.store');
        Route::match(['put', 'patch', 'post'], '/experiences/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateExperience'])->name('experiences.update');
        Route::delete('/experiences/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyExperience'])->name('experiences.destroy');

        Route::post('/experience', [\App\Http\Controllers\PortfolioModuleController::class, 'storeExperience'])->name('experience.store');
        Route::match(['put', 'patch', 'post'], '/experience/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateExperience'])->name('experience.update');
        Route::delete('/experience/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyExperience'])->name('experience.destroy');
        
        Route::post('/services', [\App\Http\Controllers\PortfolioModuleController::class, 'storeService'])->name('services.store');
        Route::match(['put', 'patch', 'post'], '/services/{service}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{service}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyService'])->name('services.destroy');
        
        Route::post('/certifications', [\App\Http\Controllers\PortfolioModuleController::class, 'storeCertification'])->name('certifications.store');
        Route::match(['put', 'patch', 'post'], '/certifications/{certification}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateCertification'])->name('certifications.update');
        Route::delete('/certifications/{certification}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyCertification'])->name('certifications.destroy');
        
        Route::post('/education', [\App\Http\Controllers\PortfolioModuleController::class, 'storeEducation'])->name('education.store');
        Route::match(['put', 'patch', 'post'], '/education/{education}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateEducation'])->name('education.update');
        Route::delete('/education/{education}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyEducation'])->name('education.destroy');
        
        Route::post('/achievements', [\App\Http\Controllers\PortfolioModuleController::class, 'storeAchievement'])->name('achievements.store');
        Route::match(['put', 'patch', 'post'], '/achievements/{achievement}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateAchievement'])->name('achievements.update');
        Route::delete('/achievements/{achievement}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyAchievement'])->name('achievements.destroy');
        
        Route::post('/contributions', [\App\Http\Controllers\PortfolioModuleController::class, 'storeContribution'])->name('contributions.store');
        Route::match(['put', 'patch', 'post'], '/contributions/{contribution}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateContribution'])->name('contributions.update');
        Route::delete('/contributions/{contribution}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyContribution'])->name('contributions.destroy');
        
        Route::post('/testimonials', [\App\Http\Controllers\PortfolioModuleController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::match(['put', 'patch', 'post'], '/testimonials/{testimonial}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateTestimonial'])->name('testimonials.update');
        Route::delete('/testimonials/{testimonial}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyTestimonial'])->name('testimonials.destroy');

        Route::post('/trainings', [\App\Http\Controllers\PortfolioModuleController::class, 'storeTraining'])->name('trainings.store');
        Route::match(['put', 'patch', 'post'], '/trainings/{training}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateTraining'])->name('trainings.update');
        Route::delete('/trainings/{training}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyTraining'])->name('trainings.destroy');

        Route::post('/media', [\App\Http\Controllers\PortfolioModuleController::class, 'storeMedia'])->name('media.store');
        Route::match(['put', 'patch', 'post'], '/media/{media}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateMedia'])->name('media.update');
        Route::delete('/media/{media}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyMedia'])->name('media.destroy');
        
        Route::post('/publications', [\App\Http\Controllers\PortfolioModuleController::class, 'storePublication'])->name('publications.store');
        Route::match(['put', 'patch', 'post'], '/publications/{publication}', [\App\Http\Controllers\PortfolioModuleController::class, 'updatePublication'])->name('publications.update');
        Route::delete('/publications/{publication}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyPublication'])->name('publications.destroy');
    });

    // Keep generic for backward compatibility or simple text sections
    Route::post('/portfolio/sections', [PortfolioController::class, 'storeSection'])->name('portfolio.sections.store');
    Route::post('/portfolio/sections/{section}', [PortfolioController::class, 'updateSection'])->name('portfolio.sections.update');
    Route::delete('/portfolio/sections/{section}', [PortfolioController::class, 'destroySection'])->name('portfolio.sections.destroy');

    // Visitor Portfolio Inquiry Message Management (Public Contact Form)
    Route::post('/messages/{message}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{message}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [\App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');

    // Platform User-to-User Direct Messaging System
    Route::get('/direct-messages', [\App\Http\Controllers\DirectMessageController::class, 'index'])->name('messages.index');
    Route::post('/direct-messages/start/{user}', [\App\Http\Controllers\DirectMessageController::class, 'startConversation'])->name('messages.start');
    Route::post('/direct-messages/{conversation}/send', [\App\Http\Controllers\DirectMessageController::class, 'sendMessage'])->name('messages.send');
    Route::get('/direct-messages/{conversation}/fetch', [\App\Http\Controllers\DirectMessageController::class, 'fetchMessages'])->name('messages.fetch');
    Route::get('/direct-messages/unread-count', [\App\Http\Controllers\DirectMessageController::class, 'unreadCount'])->name('messages.unread-count');

    // Connections Management
    Route::post('/connections/request/{user}', [\App\Http\Controllers\ConnectionController::class, 'sendRequest'])->name('connections.request');
    Route::post('/connections/accept/{connection}', [\App\Http\Controllers\ConnectionController::class, 'acceptRequest'])->name('connections.accept');
    Route::post('/connections/reject/{connection}', [\App\Http\Controllers\ConnectionController::class, 'rejectRequest'])->name('connections.reject');
    Route::post('/connections/cancel/{connection}', [\App\Http\Controllers\ConnectionController::class, 'cancelRequest'])->name('connections.cancel');
    Route::post('/connections/remove/{user}', [\App\Http\Controllers\ConnectionController::class, 'removeConnection'])->name('connections.remove');

    // Company & Organization Routes
    Route::get('/companies/create', [\App\Http\Controllers\CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [\App\Http\Controllers\CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [\App\Http\Controllers\CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [\App\Http\Controllers\CompanyController::class, 'update'])->name('companies.update');
    Route::get('/companies/{company}/dashboard', [\App\Http\Controllers\CompanyController::class, 'dashboard'])->name('companies.dashboard');

    // Job & Opportunity Management
    Route::get('/opportunities/create', [\App\Http\Controllers\OpportunityController::class, 'create'])->name('opportunities.create');
    Route::post('/opportunities', [\App\Http\Controllers\OpportunityController::class, 'store'])->name('opportunities.store');
    Route::get('/opportunities/{opportunity}/edit', [\App\Http\Controllers\OpportunityController::class, 'edit'])->name('opportunities.edit');
    Route::put('/opportunities/{opportunity}', [\App\Http\Controllers\OpportunityController::class, 'update'])->name('opportunities.update');
    Route::delete('/opportunities/{opportunity}', [\App\Http\Controllers\OpportunityController::class, 'destroy'])->name('opportunities.destroy');
    Route::post('/opportunities/{opportunity}/save', [\App\Http\Controllers\OpportunityController::class, 'toggleSave'])->name('opportunities.save');

    // Applications & ATS Workflow
    Route::post('/opportunities/{opportunity}/apply', [\App\Http\Controllers\JobApplicationController::class, 'store'])->name('applications.store');
    Route::get('/my-applications', [\App\Http\Controllers\JobApplicationController::class, 'indexCandidate'])->name('applications.candidate.index');
    Route::get('/opportunities/{opportunity}/applications', [\App\Http\Controllers\JobApplicationController::class, 'indexCompany'])->name('opportunities.applications');
    Route::get('/applications/{application}', [\App\Http\Controllers\JobApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [\App\Http\Controllers\JobApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::post('/applications/{application}/note', [\App\Http\Controllers\JobApplicationController::class, 'storeNote'])->name('applications.note');
    Route::post('/applications/{application}/shortlist', [\App\Http\Controllers\JobApplicationController::class, 'toggleShortlist'])->name('applications.shortlist');

    // AI Mock Interview Engine
    Route::post('/mock-interviews/start', [\App\Http\Controllers\MockInterviewController::class, 'start'])->name('mock-interviews.start');
    Route::get('/mock-interviews/{session}/take', [\App\Http\Controllers\MockInterviewController::class, 'take'])->name('mock-interviews.take');
    Route::post('/mock-interviews/{session}/submit', [\App\Http\Controllers\MockInterviewController::class, 'submit'])->name('mock-interviews.submit');
    Route::get('/mock-interviews/{session}/report', [\App\Http\Controllers\MockInterviewController::class, 'report'])->name('mock-interviews.report');

    // Professional Social Feed & Sharing
    Route::get('/feed', [\App\Http\Controllers\PostController::class, 'index'])->name('feed.index');
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/reshare', [\App\Http\Controllers\PostController::class, 'reshare'])->name('posts.reshare');
    Route::post('/opportunities/{opportunity}/share', [\App\Http\Controllers\PostController::class, 'shareOpportunity'])->name('posts.share-opportunity');
    Route::post('/posts/{post}/like', [\App\Http\Controllers\PostController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/comment', [\App\Http\Controllers\PostController::class, 'storeComment'])->name('posts.comment');
    Route::post('/comments/{comment}/like', [\App\Http\Controllers\PostController::class, 'toggleCommentLike'])->name('comments.like');

    // Career Preferences
    Route::get('/career-preferences', [\App\Http\Controllers\ProfessionalPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::post('/career-preferences', [\App\Http\Controllers\ProfessionalPreferenceController::class, 'update'])->name('preferences.update');

    // System Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Content Reports
    Route::post('/reports', [\App\Http\Controllers\ContentReportController::class, 'store'])->middleware('throttle:10,1')->name('reports.store');
});

// Public Discovery Routes (Rate-Limited)
Route::middleware('throttle:60,1')->group(function() {
    Route::get('/companies', [\App\Http\Controllers\CompanyController::class, 'index'])->name('companies.index');
    Route::get('/company/{slug}', [\App\Http\Controllers\CompanyController::class, 'show'])->name('companies.show');
    Route::get('/jobs', [\App\Http\Controllers\OpportunityController::class, 'index'])->name('opportunities.index');
    Route::get('/job/{slug}', [\App\Http\Controllers\OpportunityController::class, 'show'])->name('opportunities.show');
    Route::get('/talent', [\App\Http\Controllers\TalentDiscoveryController::class, 'index'])->name('talent.index');
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.index');
});

Route::get('/sitemap.xml', function() {
    $portfolios = \App\Models\Portfolio::where('is_active', true)->where('is_public', true)->with('user')->get();
    $jobs = \App\Models\Opportunity::where('status', 'published')->get();
    $companies = \App\Models\Company::where('verification_status', '!=', 'suspended')->get();

    $content = view('sitemap', compact('portfolios', 'jobs', 'companies'));
    return response($content, 200)->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');

    // Professionals Management
    Route::get('/professionals', [AdminController::class, 'professionals'])->name('admin.professionals.index');
    Route::get('/professionals/{user}', [AdminController::class, 'showProfessional'])->name('admin.professionals.show');
    Route::post('/professionals/{user}/suspend', [AdminController::class, 'toggleUserSuspension'])->name('admin.professionals.suspend');

    // Verification Center
    Route::get('/verification', [AdminController::class, 'verificationCenter'])->name('admin.verification.index');

    // Company Management
    Route::get('/companies', [AdminController::class, 'companies'])->name('admin.companies.index');
    Route::post('/companies/{company}/status', [AdminController::class, 'updateCompanyStatus'])->name('admin.companies.status');

    // Jobs & Opportunities Management
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('admin.jobs.index');
    Route::post('/opportunities/{opportunity}/status', [AdminController::class, 'updateJobStatus'])->name('admin.jobs.status');
    Route::post('/opportunities/{opportunity}/feature', [AdminController::class, 'toggleOpportunityFeatured'])->name('admin.opportunities.feature');

    // Applications Tracker
    Route::get('/applications', [AdminController::class, 'applications'])->name('admin.applications.index');

    // Moderation & Reports
    Route::get('/moderation', [AdminController::class, 'moderation'])->name('admin.moderation.index');
    Route::post('/reports/{report}/status', [AdminController::class, 'updateReportStatus'])->name('admin.reports.status');

    // Analytics Hub
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics.index');

    // Audit Logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit-logs.index');

    // Administrators & Roles
    Route::get('/administrators', [AdminController::class, 'administrators'])->name('admin.administrators.index');
    Route::post('/administrators/{user}/role', [AdminController::class, 'updateAdminRole'])->name('admin.administrators.update-role');

    // System Settings & Broadcasts
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::post('/settings/toggle-ai-mock', [AdminController::class, 'toggleAiMock'])->name('admin.settings.toggle-ai-mock');

    // Legacy Action Compatibility
    Route::post('/portfolio/{portfolio}/toggle', [AdminController::class, 'togglePortfolioStatus'])->name('admin.portfolio.toggle');
    Route::post('/notify', [AdminController::class, 'sendNotification'])->name('admin.notify');
    Route::post('/broadcast', [AdminController::class, 'broadcast'])->name('admin.broadcast');
    Route::post('/themes', [AdminController::class, 'storeTheme'])->name('admin.themes.store');
    Route::post('/themes/{theme}/toggle', [AdminController::class, 'toggleTheme'])->name('admin.themes.toggle');
    Route::post('/send-email', [AdminController::class, 'sendEmail'])->name('admin.send-email');
    Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleRole'])->name('admin.users.toggle-role');
    Route::post('/users/{user}/toggle-verification', [AdminController::class, 'toggleVerification'])->name('admin.users.toggle-verification');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    // Invoices routes
    Route::resource('invoices', \App\Http\Controllers\AdminInvoiceController::class);
    Route::get('/invoices/{invoice}/download/pdf', [\App\Http\Controllers\AdminInvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/email', [\App\Http\Controllers\AdminInvoiceController::class, 'emailInvoice'])->name('invoices.email');
});

require __DIR__.'/auth.php';

Route::get('/restricted', function () {
    \App\Services\SeoService::set([
        'title' => 'Access Restricted | MyResume.cloud',
        'robots' => 'noindex, nofollow'
    ]);
    return view('errors.restricted');
})->name('restricted');

Route::get('/{username}', [PortfolioController::class, 'show'])->name('portfolio.show');


// CV Download Routes
Route::get('/{username}/cv/pdf', [App\Http\Controllers\CVController::class, 'downloadPDF'])->name('cv.download.pdf');
Route::get('/{username}/cv/word', [App\Http\Controllers\CVController::class, 'downloadWord'])->name('cv.download.word');


