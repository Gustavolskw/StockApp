<?php

namespace App\Interfaces;

interface ProductTypeInterface
{
    public function index(): array;
    public function store(array $prodType): array;
    public function delete(int $id): array;
    public function update(array $prodTypeUpdatem, int $id): array;
}
