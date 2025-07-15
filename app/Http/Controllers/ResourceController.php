<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Resources;
use App\Models\Player;

class ResourceController extends Controller
{
    public function gatherResource(Request $request)
    {
        $request->validate([
            'resource' => 'required|string|in:rock,wood,food,gold',
        ]);

        // Get the current user and their player record
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $player = Player::where('name', $user->name)->first();
        if (!$player) {
            // Optionally, create a player record for the user
            $player = Player::create(['name' => $user->name, 'player_id' => auth()->user()->id]);
        }        

        // Find or create the resource row for this player and type
        $resource = Resources::firstOrCreate(
            [
                'player_id' => $player->id,
                'type' => $request->input('resource'),
            ],
            [
                'quantity' => 0,
                'status' => 'available',
            ]
        );

        // Increment the resource quantity
        $resource->quantity++;
        $resource->save();

        Log::info('Resource gathered: ' . $request->input('resource'));

        return response()->json([
            'message' => 'Resource gathered successfully!',
            'resource' => $resource->type,
            'quantity' => $resource->quantity,
        ]);
    }

    public function getResources(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $player = Player::where('name', $user->name)->first();
        if (!$player) {
            return response()->json(['resources' => []]);
        }
        $resources = Resources::where('player_id', $player->id)->get(['type', 'quantity']);
        return response()->json(['resources' => $resources]);
    }
}