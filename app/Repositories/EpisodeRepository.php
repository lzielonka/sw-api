<?php

namespace App\Repositories;

use App\Models\Character;
use App\Models\Episode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EpisodeRepository extends BaseRepository
{
    private const DEFAULT_WITH = ['characters'];

    public function paginate(int $pageSize, ?array $customWith = null): LengthAwarePaginator
    {
        return Episode::with($customWith ?? self::DEFAULT_WITH)->paginate($pageSize);
    }

    public function findById(int $id, ?array $customWith = null): ?Episode
    {
        return Episode::with($customWith ?? self::DEFAULT_WITH)->where('id', $id)->first();
    }

    public function fetchCharacters(Episode $episode): Collection
    {
        return $episode->characters()->get();
    }

    public function addCharacter(Episode $episode, Character $character): void
    {
        $currentIds = $episode->characters()->get()->pluck('id')->toArray();
        $episode->characters()->sync(array_merge($currentIds, [$character->getKey()]));
    }

    public function removeCharacter(Episode $episode, Character $character): void
    {
        $episode->characters()->detach($character);
    }
}
