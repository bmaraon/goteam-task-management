<?php

namespace App\Http\Controllers;

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
        $task = $this->repository->create($request->validated());

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): TaskResource
    {
        $task = $this->repository->find($id);

        $this->authorize('view', $task);
        
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        return new TaskResource($this->repository->update($id, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $task = $this->repository->find($id);

        $this->authorize('delete', $task);
        $task->delete();

        return response()->noContent();
    }
}
