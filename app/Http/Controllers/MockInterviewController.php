<?php

namespace App\Http\Controllers;

use App\Models\MockInterview;
use App\Models\Opportunity;
use App\Services\MockInterviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MockInterviewController extends Controller
{
    protected MockInterviewService $interviewService;

    public function __construct(MockInterviewService $interviewService)
    {
        $this->interviewService = $interviewService;

        $this->middleware(function ($request, $next) {
            if (!\App\Models\SystemSetting::isAiMockEnabled()) {
                $previousUrl = url()->previous();
                if (empty($previousUrl) || $previousUrl === $request->fullUrl()) {
                    return redirect()->route('welcome')->with('error', 'This feature has been restricted by the administrator.');
                }
                return redirect()->back()->with('error', 'This feature has been restricted by the administrator.');
            }
            return $next($request);
        });
    }

    /**
     * Display candidate's mock interviews history.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $interviews = MockInterview::where('user_id', Auth::id())
            ->with(['opportunity', 'questions'])
            ->latest()
            ->paginate(10);

        \App\Services\SeoService::set([
            'title' => 'AI Mock Interviews | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return view('mock_interviews.index', compact('interviews'));
    }

    /**
     * Start a mock interview session for an opportunity or custom job title.
     */
    public function start(Request $request)
    {
        $opportunityId = $request->input('opportunity_id');
        $customTitle = $request->input('job_title');

        $opportunity = $opportunityId ? Opportunity::find($opportunityId) : null;

        $session = $this->interviewService->generateSession(Auth::user(), $opportunity, $customTitle);

        return redirect()->route('mock-interviews.take', $session->id);
    }

    /**
     * Show mock interview question screen.
     */
    public function take(MockInterview $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        if ($session->status === 'completed') {
            return redirect()->route('mock-interviews.report', $session->id);
        }

        $session->load('questions');

        return view('mock_interviews.take', compact('session'));
    }

    /**
     * Submit interview answers and generate report.
     */
    public function submit(Request $request, MockInterview $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $answers = $request->input('answers', []); // array of question_id => answer text

        $evaluatedSession = $this->interviewService->evaluateAnswers($session, $answers);

        return redirect()->route('mock-interviews.report', $evaluatedSession->id)->with('success', 'Mock interview completed successfully!');
    }

    /**
     * View detailed evaluation report.
     */
    public function report(MockInterview $session)
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $session->load('questions');

        return view('mock_interviews.report', compact('session'));
    }
}
