<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function search(array $filters);
}