<?php

namespace App\Repositories;

use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{
    protected $model;

    /**
     * Class constructor
     * 
     * @var Task $model
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Register User
     * 
     * @var array $data
     * @return User
     */
    public function create(array $data): User
    {
        return  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Find User
     * 
     * @var string $email
     * @return User|null
     */
    public function findByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }
}