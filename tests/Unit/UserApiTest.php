<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_cannot_show_a_user()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/users/{$user2->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }
}
