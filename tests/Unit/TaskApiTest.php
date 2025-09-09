<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_cannot_list_tasks()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    #[Test]
    public function it_can_list_tasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function it_can_list_tasks_by_filter()
    {
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $maxTaskCount = 3;

        for ($i = 1; $i <= $maxTaskCount; $i++) {
            Task::factory()->create([
                'user_id' => $user->id,
                'priority' => $i,
                'task' => "User 1 task {$i}",
                'scheduled_at' => $today,
            ]);
        }

        for ($i = 1; $i <= $maxTaskCount; $i++) {
            Task::factory()->create([
                'user_id' => $user->id,
                'priority' => $i,
                'task' => "User 1 task {$i}",
                'scheduled_at' => $yesterday,
            ]);
        }

        for ($i = 1; $i <= $maxTaskCount; $i++) {
            Task::factory()->create([
                'user_id' => $user2->id,
                'priority' => $i,
                'task' => "User 2 task {$i}",
                'scheduled_at' => $today,
            ]);
        }

        $filters = http_build_query(['search' => 'User 1']);
        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks?{$filters}");

        $response->assertStatus(200)
            ->assertJsonCount(6, 'data');

        $filters = http_build_query([
            'search' => 'User 1',
            'date' => $today,
        ]);
        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks?{$filters}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $filters = http_build_query([
            'search' => 'User 1',
            'date' => $yesterday,
        ]);
        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks?{$filters}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $filters = http_build_query(['search' => 'User 2']);
        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks?{$filters}");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    #[Test]
    public function it_can_list_tasks_by_ascending_order()
    {
        $user = User::factory()->create();
        $maxTaskCount = 3;
        $taskCount = 3;

        for ($i = 1; $i <= $maxTaskCount; $i++) {
            Task::factory()->create([
                'user_id' => $user->id,
                'priority' => $taskCount,
            ]);

            $taskCount--;
        }

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/tasks');

        $responseData = json_decode($response->getContent())->data;

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $this->assertTrue($responseData[0]->priority === 1);
        $this->assertTrue($responseData[1]->priority === 2);
        $this->assertTrue($responseData[2]->priority === 3);
    }

    #[Test]
    public function it_can_create_a_task()
    {
        $user = User::factory()->create();

        $payload = [
            'task' => 'New Task',
            'priority' => 1,
            'is_completed' => 0,
            'scheduled_at' => Carbon::parse(now())->toDateString(),
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['task' => 'New Task']);

        $this->assertDatabaseHas('tasks', $payload);
    }

    #[Test]
    public function it_cannot_show_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function it_can_show_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $task->id]);
    }

    #[Test]
    public function it_cannot_update_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['task' => 'New Task']);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", []);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task' => 'New Task',
        ]);
    }

    #[Test]
    public function it_can_update_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'task' => 'New Task',
            'priority' => 1,
            'is_completed' => 0,
            'scheduled_at' => Carbon::parse(now())->toDateString(),
        ]);

        $payload = [
            'task' => 'Update Task',
            'priority' => 2,
            'is_completed' => 1,
            'scheduled_at' => Carbon::parse(now())->toDateString(),
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'task' => 'Update Task',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task' => 'Update Task',
        ]);
    }

    #[Test]
    public function it_cannot_delete_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_can_delete_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
