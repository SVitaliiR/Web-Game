<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

class Building extends Model
{
    protected $fillable = [
        'player_id', 'building_name', 'position', 'level', 'status', 'cost', 'production_rate', 'income', 'max_level', 'upgrade_cost', 'upgrade_time'
    ];

    /**
     * Get the player that owns the building.
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public $timestamps = false;
}