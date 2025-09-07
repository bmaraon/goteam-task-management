<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasksCount = 5;
        $users = User::all();
        
        foreach ($users as $user) {
            for ($priority = 1; $priority <= $tasksCount; $priority++) {
                Task::factory()->create([
                    'user_id' => $user->id,
                    'priority' => $priority
                ]);
            }
        }
    }
}
