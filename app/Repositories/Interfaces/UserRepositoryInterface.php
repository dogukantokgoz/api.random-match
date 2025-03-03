<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function findByEmail(string $email);
    public function update(int $id, array $data);
    public function delete(int $id);
} 