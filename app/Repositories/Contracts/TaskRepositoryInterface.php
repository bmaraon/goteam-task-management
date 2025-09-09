<?php

namespace App\Repositories\Contracts;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function all();

    public function find(int $id);

    public function create(array $data);

    public function update(Task $model, array $data);

    public function delete(Task $model);

    public function search(array $filters);

    public function maxPriority(array $filters = []);
}
