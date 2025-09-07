<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SanctumAuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_register_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Sample User',
            'email' => 'user1@example.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email'],
                     'token'
                 ]);

        $this->assertDatabaseHas('users', [ 'email' => 'user1@example.com' ]);
    }

    #[Test]
    public function it_can_login_user()
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    #[Test]
    public function it_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    #[Test]
    public function it_can_access_protected_routes()
    {
        $user = User::factory()->create();

        Task::factory()->count(2)->create([ 'user_id' => $user->id ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }

    #[Test]
    public function it_can_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout')
          ->assertStatus(200)
          ->assertJson(['message' => 'Logged out']);

        $this->assertCount(0, $user->tokens()->get());
    }
}