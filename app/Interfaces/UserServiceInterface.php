<?php

namespace App\Interfaces;

use App\Core\Logic\Result;

interface UserServiceInterface
{
    public function getAll(): Result;
    public function getById($id): Result;
    public function create($data): Result;
    public function update($data, $id): Result;
    public function delete($id): Result;
}
