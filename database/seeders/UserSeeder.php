<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->command->info("Admin user created: admin@example.com / password123");
        $this->command->info("Token: " . $admin->createToken('admin-token')->plainTextToken);

        $admin = User::factory()->create([
            'name' => 'User one',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->command->info("Admin user created: user1@example.com / password123");
        $this->command->info("Token: " . $admin->createToken('admin-token')->plainTextToken);
    }
}
