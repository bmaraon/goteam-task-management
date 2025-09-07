<?php

namespace App\Repositories;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    /**
     * Class constructor
     * 
     * @var Task $model
     * @return void
     */
    public function __construct(Task $model)
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
        return $this->model->where('user_id', auth()->id())
            ->orderBy('priority') // always arrange by priority
            ->get();
    }

    /**
     * Search data
     * 
     * @var array $filters
     * @return Collection
     */
    public function search(array $filters): Collection
    {
        return $this->model->where('user_id', auth()->id())
            ->when(!empty($filters['task']), function ($query) use ($filters) {
                $query->where('task', 'like', "%{$filters['task']}%");
            })
            ->orderBy('priority') // always arrange by priority
            ->get();
    }

    /**
     * Find data
     * 
     * @var @int $id
     * @return Task
     */
    public function find(int $id): Task
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create data
     * 
     * @var array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        $data['user_id'] = auth()->id();
        
        return $this->model->create($data);
    }

    /**
     * Update data
     * 
     * @var int $id
     * @var array $data
     * 
     * @return Task
     */
    public function update(int $id, array $data): Task
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