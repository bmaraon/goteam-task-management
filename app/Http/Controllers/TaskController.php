<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskController extends Controller
{
    protected TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $taskRepository) {
        $this->repository = $taskRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if (request()->has('task')) {
            $filters['task'] = request()->get('task');
        }

        if (!empty($filters)) {
            return TaskResource::collection($this->repository->search($filters));
        }
        
        return TaskResource::collection($this->repository->all());
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
        $this->authorize('delete', $task);
        $this->repository->delete($task);

        return response()->noContent();
    }
}
