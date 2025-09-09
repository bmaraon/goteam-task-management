<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'User one',
            'email' => 'user.one@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->command->info('User one created: user.one@example.com / password123');
        $this->command->info('Token: '.$admin->createToken('admin-token')->plainTextToken);

        $admin = User::factory()->create([
            'name' => 'User two',
            'email' => 'user.two@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->command->info('User two created: user.two@example.com / password123');
        $this->command->info('Token: '.$admin->createToken('admin-token')->plainTextToken);
    }
}
