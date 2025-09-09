<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateUserRequest;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $repository;

    /**
     * Class Constructor
     *
     * @return void
     */
    public function __construct(AuthRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Register User
     *
     * @var Request
     */
    public function register(Request $request): Response|JsonResponse
    {
        $user = $this->repository->create($request->all());
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Login User
     *
     * @var AuthenticateUserRequest
     */
    public function login(AuthenticateUserRequest $request): Response|JsonResponse
    {
        $validated = $request->validated();
        $user = $this->repository->findByEmail($validated['email']);

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    /**
     * Logout User
     *
     * @var Request
     */
    public function logout(Request $request): Response|JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
