<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function store(Model $user): array;
    public function delete(int $user): array;
    public function active(int $user): array;
    public function update(array $updtUser, int $id): array;
    public function index(): array;
    public function showById(int $id): array;
    public function showByEmail(string $email): array;
}
