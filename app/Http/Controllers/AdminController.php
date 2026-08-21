<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Theme;
use App\Models\Company;
use App\Models\Opportunity;
use App\Models\JobApplication;
use App\Models\ContentReport;
use App\Models\AuditLog;
use App\Models\Post;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\AdminUserEmail;

class AdminController extends Controller
{
    /**
     * Operational Command Center Overview
     */
    public function index()
    {
        $users = User::with('portfolio')->latest()->take(10)->get();
        $themes = Theme::all();
        $messages = \App\Models\Message::with('portfolio.user')->latest()->take(5)->get();
        $invoices = \App\Models\Invoice::with('user')->latest()->take(5)->get();
        
        $pendingCompanies = Company::where('verification_status', 'pending')->latest()->take(5)->get();
        $pendingUsers = User::whereNull('email_verified_at')->latest()->take(5)->get();
        $pendingOpportunities = Opportunity::where('status', 'draft')->orWhere('status', 'pending')->latest()->take(5)->get();
        $reports = ContentReport::with('reporter')->where('status', 'pending')->latest()->take(5)->get();
        $recentAuditLogs = AuditLog::with('user')->latest()->take(8)->get();

        $stats = [
            'total_users' => User::count(),
            'active_portfolios' => Portfolio::where('is_active', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'suspended_users' => User::where('account_status', 'suspended')->count(),
            'total_companies' => Company::count(),
            'verified_companies' => Company::where('verification_status', 'verified')->count(),
            'pending_verifications' => Company::where('verification_status', 'pending')->count(),
            'total_opportunities' => Opportunity::count(),
            'published_opportunities' => Opportunity::where('status', 'published')->count(),
            'total_applications' => JobApplication::count(),
            'total_reports' => ContentReport::count(),
            'pending_reports' => ContentReport::where('status', 'pending')->count(),
        ];

        // Requires Attention Task Counts
        $attentionCounts = [
            'pending_companies' => $stats['pending_verifications'],
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'pending_opportunities' => Opportunity::where('status', 'draft')->orWhere('status', 'pending')->count(),
            'pending_reports' => $stats['pending_reports'],
        ];

        return view('admin.dashboard', compact(
            'users', 'themes', 'stats', 'messages', 'invoices',
            'pendingCompanies', 'pendingUsers', 'pendingOpportunities',
            'reports', 'recentAuditLogs', 'attentionCounts'
        ));
    }

    /**
     * Cross-platform Global Search across Professionals, Companies, Jobs, Applications, Posts
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        
        $professionals = collect();
        $companies = collect();
        $jobs = collect();
        $applications = collect();
        $posts = collect();

        if (!empty($query)) {
            $professionals = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->with('portfolio')
                ->latest()
                ->take(10)
                ->get();

            $companies = Company::where('name', 'like', "%{$query}%")
                ->orWhere('industry', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%")
                ->latest()
                ->take(10)
                ->get();

            $jobs = Opportunity::where('title', 'like', "%{$query}%")
                ->orWhere('category', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%")
                ->with('company')
                ->latest()
                ->take(10)
                ->get();

            $applications = JobApplication::whereHas('user', function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orWhereHas('opportunity', function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%");
                })
                ->with(['user', 'opportunity.company'])
                ->latest()
                ->take(10)
                ->get();

            $posts = Post::where('content', 'like', "%{$query}%")
                ->with('user')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('admin.search', compact('query', 'professionals', 'companies', 'jobs', 'applications', 'posts'));
    }

    /**
     * Professional Management Directory
     */
    public function professionals(Request $request)
    {
        $query = User::with('portfolio');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'suspended') {
                $query->where('account_status', 'suspended');
            } elseif ($status === 'unverified') {
                $query->whereNull('email_verified_at');
            } elseif ($status === 'verified') {
                $query->whereNotNull('email_verified_at');
            }
        }

        $professionals = $query->latest()->paginate(15)->withQueryString();

        return view('admin.professionals.index', compact('professionals'));
    }

    /**
     * Show Professional Detail Inspector
     */
    public function showProfessional(User $user)
    {
        $user->load(['portfolio.skills', 'portfolio.projects', 'portfolio.experiences', 'portfolio.education', 'jobApplications.opportunity', 'posts']);
        $userAuditLogs = AuditLog::where('target_type', User::class)->where('target_id', $user->id)->latest()->take(10)->get();

        return view('admin.professionals.show', compact('user', 'userAuditLogs'));
    }

    /**
     * Toggle User Suspension
     */
    public function toggleUserSuspension(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $newStatus = $user->account_status === 'suspended' ? 'active' : 'suspended';
        $user->update(['account_status' => $newStatus]);

        AuditLogService::log("user.{$newStatus}", $user, [
            'admin' => auth()->user()->name,
            'reason' => $request->get('reason', 'Administrative decision'),
        ]);

        return back()->with('status', "User {$user->name} account status updated to {$newStatus}.");
    }

    /**
     * Centralized Verification Center Queue
     */
    public function verificationCenter()
    {
        $pendingCompanies = Company::where('verification_status', 'pending')->latest()->get();
        $unverifiedUsers = User::whereNull('email_verified_at')->with('portfolio')->latest()->get();
        $pendingJobs = Opportunity::where('status', 'pending')->orWhere('status', 'draft')->with('company')->latest()->get();

        return view('admin.verification.index', compact('pendingCompanies', 'unverifiedUsers', 'pendingJobs'));
    }

    /**
     * Company Directory & Management
     */
    public function companies(Request $request)
    {
        $query = Company::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('verification_status', $status);
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Update Company Status
     */
    public function updateCompanyStatus(Request $request, Company $company)
    {
        $request->validate([
            'status' => 'required|string|in:pending,verified,rejected,suspended',
        ]);

        $company->update([
            'verification_status' => $request->status,
            'verified_at' => $request->status === 'verified' ? now() : null,
        ]);

        AuditLogService::log("company.{$request->status}", $company, [
            'admin' => auth()->user()->name,
            'status' => $request->status,
        ]);

        return back()->with('status', 'company-status-updated');
    }

    /**
     * Jobs & Opportunities Management Directory
     */
    public function jobs(Request $request)
    {
        $query = Opportunity::with('company');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $opportunities = $query->latest()->paginate(15)->withQueryString();

        return view('admin.jobs.index', compact('opportunities'));
    }

    /**
     * Update Job Status
     */
    public function updateJobStatus(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'status' => 'required|string|in:draft,published,paused,closed,archived,suspended',
        ]);

        $opportunity->update(['status' => $request->status]);

        AuditLogService::log("opportunity.{$request->status}", $opportunity, [
            'admin' => auth()->user()->name,
            'status' => $request->status,
        ]);

        return back()->with('status', 'opportunity-status-updated');
    }

    /**
     * Feature Job Toggle
     */
    public function toggleOpportunityFeatured(Opportunity $opportunity)
    {
        $opportunity->update([
            'is_featured' => !$opportunity->is_featured,
        ]);

        AuditLogService::log("opportunity.featured_toggle", $opportunity, [
            'is_featured' => $opportunity->is_featured,
        ]);

        return back()->with('status', 'opportunity-featured-updated');
    }

    /**
     * Job Applications Tracking Activity Log
     */
    public function applications(Request $request)
    {
        $query = JobApplication::with(['user', 'opportunity.company']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Content Moderation & Reports Queue
     */
    public function moderation(Request $request)
    {
        $query = ContentReport::with('reporter');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.moderation.index', compact('reports'));
    }

    /**
     * Update Moderation Report Status
     */
    public function updateReportStatus(Request $request, ContentReport $report)
    {
        $request->validate([
            'status' => 'required|string|in:pending,reviewed,dismissed,actioned',
            'admin_notes' => 'nullable|string',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        AuditLogService::log("moderation.report_{$request->status}", $report, [
            'admin' => auth()->user()->name,
            'notes' => $request->admin_notes,
        ]);

        return back()->with('status', 'report-status-updated');
    }

    /**
     * Platform Analytics Hub
     */
    public function analytics()
    {
        $userGrowth = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(14)
            ->get();

        $companiesByIndustry = Company::select('industry', DB::raw('count(*) as count'))
            ->whereNotNull('industry')
            ->groupBy('industry')
            ->orderBy('count', 'desc')
            ->take(6)
            ->get();

        $jobsByLocation = Opportunity::select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->take(6)
            ->get();

        $topSkills = \App\Models\Skill::select('name', DB::raw('count(*) as count'))
            ->groupBy('name')
            ->orderBy('count', 'desc')
            ->take(8)
            ->get();

        return view('admin.analytics.index', compact('userGrowth', 'companiesByIndustry', 'jobsByLocation', 'topSkills'));
    }

    /**
     * Immutable Audit Logs Search & Inspector
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($search = $request->get('search')) {
            $query->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
        }

        $auditLogs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.audit_logs.index', compact('auditLogs'));
    }

    /**
     * Administrators Management & Role Assignment
     */
    public function administrators()
    {
        $administrators = User::where('role', 'admin')
            ->orWhereNotNull('admin_role')
            ->latest()
            ->get();

        $allUsers = User::latest()->take(50)->get();

        return view('admin.administrators.index', compact('administrators', 'allUsers'));
    }

    /**
     * Update Administrator Role Assignment
     */
    public function updateAdminRole(Request $request, User $user)
    {
        $request->validate([
            'admin_role' => 'required|string|in:super_admin,admin,moderator,support,none',
        ]);

        if ($user->id === auth()->id() && $request->admin_role !== 'super_admin') {
            return back()->with('error', 'demote-self-blocked');
        }

        $adminRole = $request->admin_role === 'none' ? null : $request->admin_role;
        $role = $adminRole ? 'admin' : 'user';

        $user->update([
            'admin_role' => $adminRole,
            'role' => $role,
        ]);

        AuditLogService::log("admin.role_update", $user, [
            'new_admin_role' => $adminRole,
            'performed_by' => auth()->user()->name,
        ]);

        return back()->with('status', 'admin-role-updated');
    }

    /**
     * System Settings & Announcements Dispatcher
     */
    public function settings()
    {
        $themes = Theme::all();

        return view('admin.settings.index', compact('themes'));
    }

    /**
     * Save System Settings & Send Broadcast
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'action_type' => 'required|string',
        ]);

        if ($request->action_type === 'broadcast') {
            $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            Log::info("Admin broadcast sent by " . auth()->user()->name . ": [{$request->subject}]");

            AuditLogService::log("system.broadcast", null, [
                'subject' => $request->subject,
            ]);

            return back()->with('status', 'broadcast-sent');
        }

        return back()->with('status', 'settings-updated');
    }

    /**
     * Single Administrator Control: Toggle Global AI Mock Interview Visibility & Access
     */
    public function toggleAiMock(Request $request)
    {
        $currentStatus = \App\Models\SystemSetting::isAiMockEnabled();
        $newStatus = $currentStatus ? '0' : '1';

        \App\Models\SystemSetting::set(
            'ai_mock_interview_enabled',
            $newStatus,
            'Controls global visibility and access to AI Mock Interview navigation and sections'
        );

        AuditLogService::log("system.toggle_ai_mock", null, [
            'enabled' => $newStatus === '1',
            'updated_by' => auth()->user()->name,
        ]);

        $statusMessage = $newStatus === '1'
            ? 'AI Mock Interview feature has been ENABLED across the platform.'
            : 'AI Mock Interview feature has been DISABLED and hidden across all sections and navigation.';

        return back()->with('status', 'ai-mock-toggled')->with('ai_mock_message', $statusMessage);
    }

    // Backward Compatibility Action Helpers
    public function togglePortfolioStatus(Portfolio $portfolio)
    {
        $portfolio->update(['is_active' => !$portfolio->is_active]);
        AuditLogService::log("portfolio.toggle_active", $portfolio);
        return back()->with('status', 'portfolio-status-updated');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);
        $user = User::findOrFail($request->user_id);
        Log::info("Admin sent notification to {$user->email}: {$request->message}");
        AuditLogService::log("notification.sent", $user, ['message' => $request->message]);
        return back()->with('status', 'notification-sent')->with('notified_user', $user->name);
    }

    public function broadcast(Request $request)
    {
        return $this->updateSettings($request->merge(['action_type' => 'broadcast']));
    }

    public function storeTheme(Request $request)
    {
        $request->validate(['name' => 'required|string|max:50', 'slug' => 'required|string|max:50|unique:themes']);
        Theme::create($request->only('name', 'slug'));
        AuditLogService::log("theme.created", null, ['name' => $request->name, 'slug' => $request->slug]);
        return back()->with('status', 'theme-added');
    }

    public function toggleTheme(Theme $theme)
    {
        $theme->update(['is_active' => !$theme->is_active]);
        AuditLogService::log("theme.toggle", $theme);
        return back()->with('status', 'theme-status-updated');
    }

    public function sendEmail(Request $request)
    {
        $request->validate(['recipient' => 'required|string', 'subject' => 'required|string|max:255', 'message' => 'required|string']);
        $subject = $request->subject;
        $messageContent = $request->message;

        try {
            if ($request->recipient === 'all') {
                $users = User::all();
                foreach ($users as $user) {
                    Mail::to($user->email)->send(new AdminUserEmail($subject, $messageContent, $user->name));
                }
                AuditLogService::log("email.broadcast", null, ['subject' => $subject]);
                return back()->with('status', 'broadcast-email-sent');
            } else {
                $user = User::findOrFail($request->recipient);
                Mail::to($user->email)->send(new AdminUserEmail($subject, $messageContent, $user->name));
                AuditLogService::log("email.direct", $user, ['subject' => $subject]);
                return back()->with('status', 'direct-email-sent')->with('notified_user', $user->name);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Admin sendEmail failed: " . $e->getMessage());
            return back()->with('error', 'Email delivery failed: ' . $e->getMessage());
        }
    }

    public function toggleRole(User $user)
    {
        return $this->updateAdminRole(new Request(['admin_role' => $user->role === 'admin' ? 'none' : 'admin']), $user);
    }

    public function toggleVerification(User $user)
    {
        $user->email_verified_at = $user->email_verified_at ? null : now();
        $user->save();
        AuditLogService::log("user.verification_toggle", $user, ['verified' => (bool)$user->email_verified_at]);
        return back()->with('status', 'verification-updated')->with('notified_user', $user->name);
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'delete-self-blocked');
        }

        AuditLogService::log("user.deleted", $user, ['name' => $user->name, 'email' => $user->email]);

        if ($user->portfolio) {
            $user->portfolio->delete();
        }
        $user->delete();

        return back()->with('status', 'user-deleted');
    }
}
