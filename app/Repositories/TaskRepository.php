<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    /**
     * Class constructor
     *
     * @var Task
     *
     * @return void
     */
    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    /**
     * All
     */
    public function all(): Collection
    {
        return $this->setMainQuery()
            ->orderBy('priority') // always arrange by priority
            ->get();
    }

    /**
     * Search data
     *
     * @var array
     */
    public function search(array $filters): Collection
    {
        return $this->setMainQuery($filters)
            ->orderBy('priority') // always arrange by priority
            ->get();
    }

    /**
     * Max priority
     *
     * @var array
     */
    public function maxPriority(array $filters = []): int
    {
        unset($filters['search']); // this will mess up the getting of max priority

        return $this->setMainQuery()->max('priority') ?? 0;
    }

    /**
     * Set Main Query
     *
     * @var array
     */
    private function setMainQuery(array $filters = []): Builder
    {
        return $this->model->where('user_id', auth()->id())
            ->when(! empty($filters), function ($query) use ($filters) {
                foreach ($filters as $key => $value) {
                    if ($key === 'scheduled_at') {
                        $query->whereDate($key, $value);
                    }

                    if ($key === 'task') {
                        $query->where($key, 'like', "%{$value}%");
                    }
                }
            });
    }

    /**
     * Find data
     *
     * @var @int
     */
    public function find(int $id): Task
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create data
     *
     * @var array
     */
    public function create(array $data): Task
    {
        $data['user_id'] = auth()->id();

        return $this->model->create($data);
    }

    /**
     * Update data
     *
     * @var Task
     * @var array
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    /**
     * Delete data
     *
     * @var Task
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Adjust Priorities
     *
     * @var object|null
     */
    public function adjustPriorities(?object $deletedTask = null): void
    {
        $newPriority = 1;

        if ($deletedTask) {
            $this->model->where('user_id', auth()->id())
                ->whereDate('scheduled_at', $deletedTask->scheduled_at)
                ->each(function ($task) use (&$newPriority) {
                    $task->update(['priority' => $newPriority]);
                    $newPriority++;
                });
        }
    }
}
