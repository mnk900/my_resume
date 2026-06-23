<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submits_successfully_with_correct_fields(): void
    {
        $user = User::factory()->create();
        $portfolio = $user->portfolio; // Assuming automatically created

        if (!$portfolio) {
            $portfolio = Portfolio::create([
                'user_id' => $user->id,
                'theme' => 'classic',
                'title' => 'My Portfolio',
            ]);
        }

        $response = $this->post(route('portfolio.contact.store', $portfolio->id), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'message-sent');

        $this->assertDatabaseHas('messages', [
            'portfolio_id' => $portfolio->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
        ]);
    }

    public function test_contact_form_fails_validation_with_missing_fields(): void
    {
        $user = User::factory()->create();
        $portfolio = $user->portfolio;

        if (!$portfolio) {
            $portfolio = Portfolio::create([
                'user_id' => $user->id,
                'theme' => 'classic',
                'title' => 'My Portfolio',
            ]);
        }

        $response = $this->post(route('portfolio.contact.store', $portfolio->id), [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_owner_can_delete_message(): void
    {
        $user = User::factory()->create();
        $portfolio = $user->portfolio;
        if (!$portfolio) {
            $portfolio = Portfolio::create([
                'user_id' => $user->id,
                'theme' => 'classic',
                'title' => 'My Portfolio',
            ]);
        }

        $message = $portfolio->messages()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Test message to delete',
            'subject' => 'Test',
        ]);

        $response = $this->actingAs($user)->delete(route('messages.destroy', $message->id));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'message-deleted');
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_non_owner_cannot_delete_message(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $portfolio = $owner->portfolio;
        if (!$portfolio) {
            $portfolio = Portfolio::create([
                'user_id' => $owner->id,
                'theme' => 'classic',
                'title' => 'My Portfolio',
            ]);
        }

        $message = $portfolio->messages()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Test message to delete',
            'subject' => 'Test',
        ]);

        $response = $this->actingAs($otherUser)->delete(route('messages.destroy', $message->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }
}
