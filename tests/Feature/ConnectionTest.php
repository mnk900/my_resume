<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Portfolio;
use App\Models\Connection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_is_private_by_default(): void
    {
        $user = User::factory()->create();
        $portfolio = $user->portfolio;

        // By default, is_public should be false
        $this->assertFalse((bool)$portfolio->is_public);
    }

    public function test_guest_cannot_view_private_portfolio(): void
    {
        $owner = User::factory()->create();
        
        $response = $this->get("/{$owner->username}");

        $response->assertOk();
        $response->assertViewIs('portfolio.private');
        $response->assertSee('This Portfolio is Private');
        $response->assertSee('Log In to Connect');
    }

    public function test_logged_in_unconnected_user_cannot_view_private_portfolio_but_can_request_connection(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->get("/{$owner->username}");

        $response->assertOk();
        $response->assertViewIs('portfolio.private');
        $response->assertSee('Send Connection Request');
    }

    public function test_connected_user_can_view_private_portfolio(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();

        Connection::create([
            'sender_id' => $viewer->id,
            'receiver_id' => $owner->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($viewer)->get("/{$owner->username}");

        $response->assertOk();
        $response->assertViewIs('portfolio.public');
    }

    public function test_sending_connection_request_creates_pending_record(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->post(route('connections.request', $owner->id));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('connections', [
            'sender_id' => $viewer->id,
            'receiver_id' => $owner->id,
            'status' => 'pending',
        ]);
    }

    public function test_accepting_connection_request_updates_status(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($receiver)->post(route('connections.accept', $connection->id));

        $response->assertRedirect();
        $this->assertEquals('accepted', $connection->fresh()->status);
    }

    public function test_rejecting_connection_request_deletes_record(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($receiver)->post(route('connections.reject', $connection->id));

        $response->assertRedirect();
        $this->assertNull($connection->fresh());
    }

    public function test_cancelling_sent_request_deletes_record(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($sender)->post(route('connections.cancel', $connection->id));

        $response->assertRedirect();
        $this->assertNull($connection->fresh());
    }

    public function test_removing_connection_deletes_record(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $connection = Connection::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($user1)->post(route('connections.remove', $user2->id));

        $response->assertRedirect();
        $this->assertNull($connection->fresh());
    }

    public function test_owner_can_toggle_portfolio_privacy(): void
    {
        $user = User::factory()->create();
        $portfolio = $user->portfolio;

        $this->assertFalse((bool)$portfolio->is_public);

        $response = $this->actingAs($user)->post(route('portfolio.update'), [
            'title' => 'Updated Title',
            'theme' => 'classic',
            'is_active' => 'active',
            'is_public' => 'public', // set to public
            'show_email' => 'show',
            'show_phone' => 'show',
            'show_linkedin' => 'show',
            'show_skills' => 'show',
            'show_projects' => 'show',
            'show_experience' => 'show',
            'show_education' => 'show',
            'show_services' => 'show',
            'show_certifications' => 'show',
            'show_trainings' => 'show',
            'show_achievements' => 'show',
            'show_contributions' => 'show',
            'show_testimonials' => 'show',
            'show_media' => 'show',
            'show_publications' => 'show',
        ]);

        $response->assertRedirect();
        $this->assertTrue((bool)$portfolio->fresh()->is_public);
    }
}
