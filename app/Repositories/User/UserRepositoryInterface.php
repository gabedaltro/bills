<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function create(array $payload): User;

    public function update(int $id, array $payload): User;

    public function find(array $query): Collection;

    public function findById(int $id): User;

    public function delete(int $id): void;

    public function findByUsername(string $username): User | null;
}
