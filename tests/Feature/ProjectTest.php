<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default themes
        Theme::create(['name' => 'Premium Theme', 'slug' => 'premium', 'is_active' => true]);
    }

    public function test_user_can_add_project_with_image_and_link(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        // Use create() which creates a dummy file with MIME type instead of needing GD library
        $image = UploadedFile::fake()->create('project_showcase.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('modules.projects.store'), [
            'title' => 'My Showcase Project',
            'link' => 'https://github.com/my/project',
            'image' => $image,
            'description' => 'Detailed description of this flagship project.'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'project-added');

        $project = Project::first();
        $this->assertNotNull($project);
        $this->assertEquals('My Showcase Project', $project->title);
        $this->assertEquals('https://github.com/my/project', $project->link);
        $this->assertEquals('Detailed description of this flagship project.', $project->description);
        $this->assertNotNull($project->image_path);

        Storage::disk('public')->assertExists($project->image_path);
    }

    public function test_user_can_update_project_with_image_and_link(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $project = $user->portfolio->projects()->create([
            'title' => 'Original Title',
            'description' => 'Original description',
            'link' => 'https://github.com/orig',
            'image_path' => 'projects/orig.jpg'
        ]);

        $newImage = UploadedFile::fake()->create('updated_showcase.png', 150, 'image/png');

        $response = $this->actingAs($user)->patch(route('modules.projects.update', $project), [
            'title' => 'Updated Title',
            'link' => 'https://github.com/updated',
            'image' => $newImage,
            'description' => 'Updated description'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'project-updated');

        $project->refresh();
        $this->assertEquals('Updated Title', $project->title);
        $this->assertEquals('https://github.com/updated', $project->link);
        $this->assertEquals('Updated description', $project->description);
        $this->assertNotEquals('projects/orig.jpg', $project->image_path);

        Storage::disk('public')->assertExists($project->image_path);
    }
}
