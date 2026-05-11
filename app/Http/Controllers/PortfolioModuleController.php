<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Project;
use App\Models\Experience;
use App\Models\Service;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Achievement;
use App\Models\Contribution;
use App\Models\Testimonial;
use App\Models\Training;
use App\Services\PortfolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioModuleController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    private function bustCache()
    {
        $this->portfolioService->clearCache(Auth::user()->username);
    }

    private function authorizePortfolioOwner($model)
    {
        abort_unless($model->portfolio->user_id === Auth::id(), 403);
    }
    public function storeSkill(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'category' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100'
        ]);
        Auth::user()->portfolio->skills()->create($request->only(['name', 'percentage', 'category', 'icon']));
        $this->bustCache();
        return back()->with('status', 'skill-added');
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string',
            'image' => 'nullable|image|max:5120'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('projects', 'public');
        }

        Auth::user()->portfolio->projects()->create($data);
        $this->bustCache();
        return back()->with('status', 'project-added');
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string'
        ]);
        Auth::user()->portfolio->experiences()->create($request->only(['company', 'position', 'start_date', 'end_date', 'description']));
        $this->bustCache();
        return back()->with('status', 'experience-added');
    }

    public function storeService(Request $request)
    {
        $request->validate(['title' => 'required|string', 'description' => 'required|string']);
        Auth::user()->portfolio->services()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'service-added');
    }

    public function storeCertification(Request $request)
    {
        $request->validate(['name' => 'required|string', 'issuer' => 'required|string']);
        Auth::user()->portfolio->certifications()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'certification-added');
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        Auth::user()->portfolio->education()->create($request->only(['institution', 'degree', 'start_date', 'end_date']));
        $this->bustCache();
        return back()->with('status', 'education-added');
    }

    public function storeAchievement(Request $request)
    {
        $request->validate(['title' => 'required|string']);
        Auth::user()->portfolio->achievements()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'achievement-added');
    }

    public function storeContribution(Request $request)
    {
        $request->validate(['title' => 'required|string', 'description' => 'required|string']);
        Auth::user()->portfolio->contributions()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'contribution-added');
    }

    public function storeTraining(Request $request)
    {
        $request->validate(['title' => 'required|string', 'institution' => 'required|string']);
        Auth::user()->portfolio->trainings()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'training-added');
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate(['client_name' => 'required|string', 'content' => 'required|string']);
        Auth::user()->portfolio->testimonials()->create($request->all());
        $this->bustCache();
        return back()->with('status', 'testimonial-added');
    }

    // Update methods
    public function updateSkill(Request $request, Skill $skill)
    {
        $this->authorizePortfolioOwner($skill);
        $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
            'category' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
        ]);
        $skill->update($request->only(['name', 'percentage', 'category', 'icon']));
        $this->bustCache();
        return back()->with('status', 'skill-updated');
    }

    public function updateProject(Request $request, Project $project)
    {
        $this->authorizePortfolioOwner($project);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['title', 'description', 'link']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);
        $this->bustCache();
        return back()->with('status', 'project-updated');
    }

    public function updateExperience(Request $request, Experience $experience)
    {
        $this->authorizePortfolioOwner($experience);
        $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);
        $experience->update($request->only(['company', 'position', 'start_date', 'end_date', 'description']));
        $this->bustCache();
        return back()->with('status', 'experience-updated');
    }

    public function updateService(Request $request, Service $service)
    {
        $this->authorizePortfolioOwner($service);
        $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string']);
        $service->update($request->only(['title', 'description']));
        $this->bustCache();
        return back()->with('status', 'service-updated');
    }

    public function updateCertification(Request $request, Certification $certification)
    {
        $this->authorizePortfolioOwner($certification);
        $request->validate(['name' => 'required|string|max:255', 'issuer' => 'required|string|max:255']);
        $certification->update($request->only(['name', 'issuer']));
        $this->bustCache();
        return back()->with('status', 'certification-updated');
    }

    public function updateEducation(Request $request, Education $education)
    {
        $this->authorizePortfolioOwner($education);
        $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $education->update($request->only(['institution', 'degree', 'start_date', 'end_date']));
        $this->bustCache();
        return back()->with('status', 'education-updated');
    }

    public function updateAchievement(Request $request, Achievement $achievement)
    {
        $this->authorizePortfolioOwner($achievement);
        $request->validate(['title' => 'required|string|max:255']);
        $achievement->update($request->only(['title']));
        $this->bustCache();
        return back()->with('status', 'achievement-updated');
    }

    public function updateContribution(Request $request, Contribution $contribution)
    {
        $this->authorizePortfolioOwner($contribution);
        $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string']);
        $contribution->update($request->only(['title', 'description']));
        $this->bustCache();
        return back()->with('status', 'contribution-updated');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $this->authorizePortfolioOwner($testimonial);
        $request->validate(['client_name' => 'required|string|max:255', 'content' => 'required|string']);
        $testimonial->update($request->only(['client_name', 'content']));
        $this->bustCache();
        return back()->with('status', 'testimonial-updated');
    }

    public function updateTraining(Request $request, Training $training)
    {
        $this->authorizePortfolioOwner($training);
        $request->validate(['title' => 'required|string|max:255', 'institution' => 'required|string|max:255']);
        $training->update($request->only(['title', 'institution']));
        $this->bustCache();
        return back()->with('status', 'training-updated');
    }

    // Destroy methods
    public function destroySkill(Skill $skill)
    {
        $this->authorizePortfolioOwner($skill);
        $skill->delete();
        $this->bustCache();
        return back()->with('status', 'skill-deleted');
    }

    public function destroyProject(Project $project)
    {
        $this->authorizePortfolioOwner($project);
        $project->delete();
        $this->bustCache();
        return back()->with('status', 'project-deleted');
    }

    public function destroyExperience(Experience $experience)
    {
        $this->authorizePortfolioOwner($experience);
        $experience->delete();
        $this->bustCache();
        return back()->with('status', 'experience-deleted');
    }

    public function destroyService(Service $service)
    {
        $this->authorizePortfolioOwner($service);
        $service->delete();
        $this->bustCache();
        return back()->with('status', 'service-deleted');
    }

    public function destroyCertification(Certification $certification)
    {
        $this->authorizePortfolioOwner($certification);
        $certification->delete();
        $this->bustCache();
        return back()->with('status', 'certification-deleted');
    }

    public function destroyEducation(Education $education)
    {
        $this->authorizePortfolioOwner($education);
        $education->delete();
        $this->bustCache();
        return back()->with('status', 'education-deleted');
    }

    public function destroyAchievement(Achievement $achievement)
    {
        $this->authorizePortfolioOwner($achievement);
        $achievement->delete();
        $this->bustCache();
        return back()->with('status', 'achievement-deleted');
    }

    public function destroyContribution(Contribution $contribution)
    {
        $this->authorizePortfolioOwner($contribution);
        $contribution->delete();
        $this->bustCache();
        return back()->with('status', 'contribution-deleted');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $this->authorizePortfolioOwner($testimonial);
        $testimonial->delete();
        $this->bustCache();
        return back()->with('status', 'testimonial-deleted');
    }

    public function destroyTraining(Training $training)
    {
        $this->authorizePortfolioOwner($training);
        $training->delete();
        $this->bustCache();
        return back()->with('status', 'training-deleted');
    }
}
