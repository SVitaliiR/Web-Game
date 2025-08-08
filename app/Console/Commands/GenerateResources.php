<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Player;
use App\Models\Resources;
use App\Models\Building;

class GenerateResources extends Command
{
    protected $signature = 'game:generate-resources';
    protected $description = 'Generate resources for all players based on their buildings';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $players = Player::with('buildings', 'resources')->get();

        foreach ($players as $player) {
            $this->info("Processing player: {$player->name}");

            dump($player->buildings);
            foreach ($player->buildings as $building) {
                $resourceType = $this->getResourceTypeForBuilding($building->type);

                if ($resourceType) {
                    $resource = $player->resources()->firstOrCreate(
                        ['type' => $resourceType],
                        ['quantity' => 0]
                    );

                    $amount = $building->level * 1; // 1 resource per level
                    $resource->quantity += $amount;
                    $resource->save();

                    $this->info("  - Added {$amount} {$resourceType} from {$building->type} (Level {$building->level})");
                }
            }
        }

        $this->info('Resource generation complete.');
    }

    private function getResourceTypeForBuilding($buildingType)
    {
        $map = [
            'Quarry' => 'rock',
            'Sawmill' => 'wood',
            'Farm' => 'food',
            'Mine' => 'gold',
        ];

        return $map[$buildingType] ?? null;
    }
}
