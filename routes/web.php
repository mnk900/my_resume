<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MessageController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');




// Public Messaging - Absolute simplest path
Route::get('/contact/submit/{portfolio}', function() { return 'Contact endpoint is reachable'; });
Route::post('/contact/submit/{portfolio}', [\App\Http\Controllers\MessageController::class, 'store'])->name('portfolio.contact.store');

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
        Route::patch('/skills/{skill}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateSkill'])->name('skills.update');
        Route::delete('/skills/{skill}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroySkill'])->name('skills.destroy');
        
        Route::post('/projects', [\App\Http\Controllers\PortfolioModuleController::class, 'storeProject'])->name('projects.store');
        Route::patch('/projects/{project}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateProject'])->name('projects.update');
        Route::delete('/projects/{project}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyProject'])->name('projects.destroy');
        
        Route::post('/experiences', [\App\Http\Controllers\PortfolioModuleController::class, 'storeExperience'])->name('experiences.store');
        Route::patch('/experiences/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateExperience'])->name('experiences.update');
        Route::delete('/experiences/{experience}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyExperience'])->name('experiences.destroy');
        
        Route::post('/services', [\App\Http\Controllers\PortfolioModuleController::class, 'storeService'])->name('services.store');
        Route::patch('/services/{service}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{service}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyService'])->name('services.destroy');
        
        Route::post('/certifications', [\App\Http\Controllers\PortfolioModuleController::class, 'storeCertification'])->name('certifications.store');
        Route::patch('/certifications/{certification}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateCertification'])->name('certifications.update');
        Route::delete('/certifications/{certification}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyCertification'])->name('certifications.destroy');
        
        Route::post('/education', [\App\Http\Controllers\PortfolioModuleController::class, 'storeEducation'])->name('education.store');
        Route::patch('/education/{education}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateEducation'])->name('education.update');
        Route::delete('/education/{education}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyEducation'])->name('education.destroy');
        
        Route::post('/achievements', [\App\Http\Controllers\PortfolioModuleController::class, 'storeAchievement'])->name('achievements.store');
        Route::patch('/achievements/{achievement}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateAchievement'])->name('achievements.update');
        Route::delete('/achievements/{achievement}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyAchievement'])->name('achievements.destroy');
        
        Route::post('/contributions', [\App\Http\Controllers\PortfolioModuleController::class, 'storeContribution'])->name('contributions.store');
        Route::patch('/contributions/{contribution}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateContribution'])->name('contributions.update');
        Route::delete('/contributions/{contribution}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyContribution'])->name('contributions.destroy');
        
        Route::post('/testimonials', [\App\Http\Controllers\PortfolioModuleController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::patch('/testimonials/{testimonial}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateTestimonial'])->name('testimonials.update');
        Route::delete('/testimonials/{testimonial}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyTestimonial'])->name('testimonials.destroy');

        Route::post('/trainings', [\App\Http\Controllers\PortfolioModuleController::class, 'storeTraining'])->name('trainings.store');
        Route::patch('/trainings/{training}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateTraining'])->name('trainings.update');
        Route::delete('/trainings/{training}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyTraining'])->name('trainings.destroy');

        Route::post('/media', [\App\Http\Controllers\PortfolioModuleController::class, 'storeMedia'])->name('media.store');
        Route::patch('/media/{media}', [\App\Http\Controllers\PortfolioModuleController::class, 'updateMedia'])->name('media.update');
        Route::delete('/media/{media}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyMedia'])->name('media.destroy');
        
        Route::post('/publications', [\App\Http\Controllers\PortfolioModuleController::class, 'storePublication'])->name('publications.store');
        Route::patch('/publications/{publication}', [\App\Http\Controllers\PortfolioModuleController::class, 'updatePublication'])->name('publications.update');
        Route::delete('/publications/{publication}', [\App\Http\Controllers\PortfolioModuleController::class, 'destroyPublication'])->name('publications.destroy');
    });

    // Keep generic for backward compatibility or simple text sections
    Route::post('/portfolio/sections', [PortfolioController::class, 'storeSection'])->name('portfolio.sections.store');
    Route::post('/portfolio/sections/{section}', [PortfolioController::class, 'updateSection'])->name('portfolio.sections.update');
    Route::delete('/portfolio/sections/{section}', [PortfolioController::class, 'destroySection'])->name('portfolio.sections.destroy');

    // Message Management
    Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    // Connections Management
    Route::post('/connections/request/{user}', [\App\Http\Controllers\ConnectionController::class, 'sendRequest'])->name('connections.request');
    Route::post('/connections/accept/{connection}', [\App\Http\Controllers\ConnectionController::class, 'acceptRequest'])->name('connections.accept');
    Route::post('/connections/reject/{connection}', [\App\Http\Controllers\ConnectionController::class, 'rejectRequest'])->name('connections.reject');
    Route::post('/connections/cancel/{connection}', [\App\Http\Controllers\ConnectionController::class, 'cancelRequest'])->name('connections.cancel');
    Route::post('/connections/remove/{user}', [\App\Http\Controllers\ConnectionController::class, 'removeConnection'])->name('connections.remove');
});

// Public Messaging (Moved to top)

Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/portfolio/{portfolio}/toggle', [AdminController::class, 'togglePortfolioStatus'])->name('admin.portfolio.toggle');
    Route::post('/notify', [AdminController::class, 'sendNotification'])->name('admin.notify');
    Route::post('/broadcast', [AdminController::class, 'broadcast'])->name('admin.broadcast');
    Route::post('/themes', [AdminController::class, 'storeTheme'])->name('admin.themes.store');
    Route::post('/themes/{theme}/toggle', [AdminController::class, 'toggleTheme'])->name('admin.themes.toggle');
    Route::post('/send-email', [AdminController::class, 'sendEmail'])->name('admin.send-email');
    
    // User Action routes
    Route::post('/users/{user}/toggle-role', [AdminController::class, 'toggleRole'])->name('admin.users.toggle-role');
    Route::post('/users/{user}/toggle-verification', [AdminController::class, 'toggleVerification'])->name('admin.users.toggle-verification');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

Route::get('/{username}', [PortfolioController::class, 'show'])->name('portfolio.show');

// CV Download Routes
Route::get('/{username}/cv/pdf', [App\Http\Controllers\CVController::class, 'downloadPDF'])->name('cv.download.pdf');
Route::get('/{username}/cv/word', [App\Http\Controllers\CVController::class, 'downloadWord'])->name('cv.download.word');
