<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if (request()->has('search')) {
            $filters['task'] = request()->get('search');
        }

        if (request()->has('date')) {
            $filters['scheduled_at'] = request()->get('date');
        }

        if (! empty($filters)) {
            return TaskResource::collection($this->repository->search($filters))
                ->additional([
                    'meta' => [
                        'max_priority' => $this->repository->maxPriority($filters),
                    ],
                ]);
        }

        return TaskResource::collection($this->repository->all())
            ->additional([
                'meta' => [
                    'max_priority' => $this->repository->maxPriority(),
                ],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        return new TaskResource($this->repository->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        return new TaskResource($this->repository->update($task, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $deletedTask = clone $task;
            $this->authorize('delete', $task);
            $this->repository->delete($task);
            $this->repository->adjustPriorities($deletedTask);
        } catch (\Exception $e) {
            dd($e->getMessage());

            return response()->json([
                'message' => 'Internal server error.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent();
    }
}
