<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function save(Model $model)
    {
        return $model->save();
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }
}
