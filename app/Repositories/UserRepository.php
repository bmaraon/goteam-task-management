<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    /**
     * Class constructor
     * 
     * @var User $model
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * All
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find data
     * 
     * @var @int $id
     * @return User
     */
    public function find(int $id): User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create data
     * 
     * @var array $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update data
     * 
     * @var User $user
     * @var array $data
     * 
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        
        return $user;
    }

    /**
     * Delete data
     * 
     * @var User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}