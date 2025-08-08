<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name', 'player_id'
    ];

    /**
     * Get the user that owns the player.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'player_id');
    }

    /**
     * Get the resources for the player.
     */
    public function resources()
    {
        return $this->hasMany(Resources::class);    
    }

    /**
     * Get the buildings for the player.
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
}