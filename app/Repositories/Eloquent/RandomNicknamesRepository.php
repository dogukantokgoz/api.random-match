<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\RandomNicknames;
use App\Repositories\Interfaces\RandomNicknamesInterface;

class RandomNicknamesRepository implements RandomNicknamesInterface
{
    protected $model;


    public function __construct(RandomNicknames $model)
    {
        $this->model = $model;
    }

    public function getRandomNickname()
    {
        return $this->model->inRandomOrder()->first();
    }
} 