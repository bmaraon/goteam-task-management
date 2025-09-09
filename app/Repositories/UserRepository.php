<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    /**
     * Class constructor
     *
     * @var User
     *
     * @return void
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * All
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find data
     *
     * @var @int
     */
    public function find(int $id): User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create data
     *
     * @var array
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update data
     *
     * @var User
     * @var array
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    /**
     * Delete data
     *
     * @var User
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
