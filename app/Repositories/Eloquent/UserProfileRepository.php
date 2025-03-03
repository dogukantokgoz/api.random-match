<?php

namespace App\Repositories\Eloquent;

use App\Models\UserProfile;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    protected $model;

    public function __construct(UserProfile $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function updateAvatar(int $id, string $avatarIcon)
    {
        $profile = $this->model->find($id);
        if ($profile) {
            $profile->update(['avatar_icon' => $avatarIcon]);
            return $profile;
        }
        return null;
    }

    public function addLike(int $id)
    {
        $profile = $this->model->find($id);
        if ($profile) {
            $profile->addLike();
            return $profile->fresh();
        }
        return null;
    }

    public function addExperiencePoint(int $id)
    {
        $profile = $this->model->find($id);
        if ($profile) {
            $profile->addExperiencePoint();
            return $profile->fresh();
        }
        return null;
    }

    public function update(int $id, array $data)
    {
        $profile = $this->model->find($id);
        if ($profile) {
            $profile->update($data);
            return $profile;
        }
        return null;
    }
} 