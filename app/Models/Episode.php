<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    public $timestamps = false;
    protected $table = 'episode';
    protected $fillable = ['name'];
    protected $hidden = ['id', 'pivot'];

    public function characters()
    {
        return $this->belongsToMany(Character::class);
    }
}
