<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name', 'player_id'
    ];

    /**
     * Get the resources for the player.
     */
    public function resources()
    {
        return $this->hasMany(Resources::class);    
    }
}