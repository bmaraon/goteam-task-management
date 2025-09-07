<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $taskRepository) {
        $this->repository = $taskRepository;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): UserResource
    {
        $user = $this->repository->find($id);

        $this->authorize('view', $user);
        
        return new UserResource($user);
    }
}
