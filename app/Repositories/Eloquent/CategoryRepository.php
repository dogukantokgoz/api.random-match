<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;
    protected $userModel;

    public function __construct(Category $model, User $userModel)
    {
        $this->model = $model;
        $this->userModel = $userModel;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->findById($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    public function syncUserCategories(int $userId, array $categoryIds)
    {
        $user = $this->userModel->find($userId);
        if ($user) {
            $user->categories()->sync($categoryIds);
            return $user->categories;
        }
        return null;
    }
} 