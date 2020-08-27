<?php

namespace App\Repositories;

use App\Models\Planet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PlanetRepository extends BaseRepository
{
    private const DEFAULT_WITH = ['characters'];

    public function paginate(int $pageSize, ?array $customWith = null): LengthAwarePaginator
    {
        return Planet::with($customWith ?? self::DEFAULT_WITH)->paginate($pageSize);
    }

    public function findById(int $id, ?array $customWith = null): ?Planet
    {
        return Planet::with($customWith ?? self::DEFAULT_WITH)->where('id', $id)->first();
    }
}
