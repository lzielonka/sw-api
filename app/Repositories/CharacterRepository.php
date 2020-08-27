<?php

namespace App\Repositories;

use App\Models\Character;
use App\Models\CharacterFriends;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CharacterRepository extends BaseRepository
{
    private const DEFAULT_WITH = ['friends', 'planet', 'episodes'];

    public function paginate(int $pageSize, ?array $customWith = null): LengthAwarePaginator
    {
        return Character::with($customWith ?? self::DEFAULT_WITH)->paginate($pageSize);
    }

    public function findById(int $id, ?array $customWith = null): ?Character
    {
        return Character::with($customWith ?? self::DEFAULT_WITH)->where('id', $id)->first();
    }

    public function fetchEpisodes(Character $character): Collection
    {
        return $character->episodes()->get();
    }

    public function addFriend(Character $character, Character $friend): void
    {
        CharacterFriends::firstOrCreate(['character_id' => $character->getKey(), 'friend_id' => $friend->getKey()]);
    }

    public function removeFriend(Character $character, Character $friend): void
    {
        $relation = CharacterFriends::where(['character_id' => $character->getKey(), 'friend_id' => $friend->getKey()])->first();
        if (null !== $relation) {
            $relation->delete();
        }
    }
}
