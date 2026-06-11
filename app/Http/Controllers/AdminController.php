<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminUserEmail;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('portfolio')->latest()->get();
        $themes = Theme::all();
        
        $stats = [
            'total_users' => User::count(),
            'active_portfolios' => Portfolio::where('is_active', true)->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'total_themes' => Theme::count(),
        ];

        return view('admin.index', compact('users', 'themes', 'stats'));
    }

    public function togglePortfolioStatus(Portfolio $portfolio)
    {
        $portfolio->update([
            'is_active' => !$portfolio->is_active
        ]);

        return back()->with('status', 'portfolio-status-updated');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Mock sending email
        Log::info("Admin sent notification to {$user->email}: {$request->message}");

        return back()->with('status', 'notification-sent')->with('notified_user', $user->name);
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:50|unique:themes',
        ]);

        Theme::create($request->only('name', 'slug'));

        return back()->with('status', 'theme-added');
    }

    public function toggleTheme(Theme $theme)
    {
        $theme->update(['is_active' => !$theme->is_active]);
        return back()->with('status', 'theme-status-updated');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = User::all();
        
        foreach ($users as $user) {
            // Mock broadcast
            Log::info("Broadcast [{$request->subject}] to {$user->email}: {$request->message}");
        }

        return back()->with('status', 'broadcast-sent');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'recipient' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subject = $request->subject;
        $messageContent = $request->message;

        if ($request->recipient === 'all') {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new AdminUserEmail($subject, $messageContent));
                Log::info("Broadcast email sent from admin to {$user->email}: [Subject: {$subject}]");
            }
            return back()->with('status', 'broadcast-email-sent');
        } else {
            $user = User::findOrFail($request->recipient);
            Mail::to($user->email)->send(new AdminUserEmail($subject, $messageContent));
            Log::info("Direct email sent from admin to {$user->email}: [Subject: {$subject}]");
            return back()->with('status', 'direct-email-sent')->with('notified_user', $user->name);
        }
    }
}
