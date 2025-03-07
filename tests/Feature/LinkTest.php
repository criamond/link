<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Link;
use App\Models\User;

class LinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_link()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('links', ['original_url' => 'https://example.com']);
    }

    public function test_user_can_delete_link()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $link = Link::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/delete_link/{$link->short_code}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('links', ['id' => $link->short_code]);
    }
}
