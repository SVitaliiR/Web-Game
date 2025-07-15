<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resources extends Model
{
    protected $fillable = [
        'player_id', 'type', 'quantity', 'status'
    ];

    /**
     * Get the player that owns the resource.
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
