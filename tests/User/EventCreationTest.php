<?php

namespace Tests\User;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that event creation requires authentication.
     */
    public function test_event_creation_requires_authentication()
    {
        $response = $this->post('/events', [
            'title' => 'Test Event',
            'description' => 'This is a test event description.',
        ]);

        $response->assertRedirect('/login');
    }

    /**
     * Test that event creation requires title.
     */
    public function test_event_creation_requires_title()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)
            ->post('/events', [
                'description' => 'This is a test event description.',
            ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Test that event creation requires description.
     */
    public function test_event_creation_requires_description()
    {
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)
            ->post('/events', [
                'title' => 'Test Event',
            ]);

        $response->assertSessionHasErrors('description');
    }
}
