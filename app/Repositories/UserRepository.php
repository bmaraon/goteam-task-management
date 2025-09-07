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
     * @var int $id
     * @var array $data
     * 
     * @return User
     */
    public function update(int $id, array $data): User
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    /**
     * Delete data
     * 
     * @var int $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->find($id)->delete();
    }
}