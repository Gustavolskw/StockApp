<?php

namespace App\Interfaces;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

interface UserRepositoryInterface
{
    public function store(Model $user): array;
    public function destroy(int $id): array;
    public function index(): array;
    public function showById(int $id): array;
    public function showByEmail(string $email): array;
}
