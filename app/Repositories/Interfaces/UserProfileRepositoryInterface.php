<?php

namespace App\Repositories\Interfaces;

interface UserProfileRepositoryInterface
{
    public function create(array $data);
    public function findByUserId(int $userId);
    public function updateAvatar(int $id, string $avatarIcon);
    public function addLike(int $id);
    public function addExperiencePoint(int $id);
    public function update(int $id, array $data);
} 