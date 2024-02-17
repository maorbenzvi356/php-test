<?php

declare(strict_types=1);

namespace App\Dao;

interface DAOInterface
{
    public function listAll(): array;

    public function add(array $data): int|false;

    public function delete(int $id): int|false;
}
