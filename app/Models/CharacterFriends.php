<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CharacterFriends extends Pivot {

    public $timestamps = false;
    protected $table = 'character_friends';

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function friends()
    {
        return $this->hasManyThrough('App\AudioFiles', 'App\Podcast');
    }

}
