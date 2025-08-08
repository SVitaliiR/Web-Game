<?php

namespace App\Listeners;

use App\Models\Player;
use App\Models\Resources;
use Illuminate\Auth\Events\Registered;

class CreatePlayerForNewUser
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        $player = Player::firstOrCreate(
            ['name' => $user->name],
            ['player_id' => $user->id]
        );

        if ($player->player_id !== $user->id) {
            $player->player_id = $user->id;
            $player->save();
        }

        $this->createInitialResources($player);
    }

    private function createInitialResources(Player $player): void
    {
        $resources = ['gold', 'wood', 'rock', 'food'];

        foreach ($resources as $resource) {
            Resources::firstOrCreate(
                [
                    'player_id' => $player->id,
                    'type' => $resource,
                ],
                [
                    'quantity' => 0,
                ]
            );
        }
    }
}
