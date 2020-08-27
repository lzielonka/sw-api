<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    public $timestamps = false;
    protected $table = 'character';
    protected $fillable = ['name', 'planet_id'];
    protected $hidden = ['id', 'planet_id', 'pivot'];

    public function planet()
    {
        return $this->belongsTo(Planet::class, 'planet_id', 'id');
    }

    public function friends()
    {
        return $this->hasManyThrough(self::class, CharacterFriends::class, 'character_id', 'id', 'id', 'friend_id');
    }

    public function episodes()
    {
        return $this->belongsToMany(Episode::class);
    }
}
