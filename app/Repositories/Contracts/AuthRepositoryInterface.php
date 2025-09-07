<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function create(array $data);
    public function findByEmail(string $email);
}