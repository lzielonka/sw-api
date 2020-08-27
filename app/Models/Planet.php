<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    public $timestamps = false;
    protected $table = 'planet';
    protected $fillable = ['name'];
    protected $hidden = ['pivot'];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
