<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

        $player = $user->player;
        if (!$player) {
            return response()->json(['error' => 'Player not found'], 404);
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

        return redirect()->back();
    }

    public function getResources(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $player = $user->player;
        if (!$player) {
            return response()->json(['resources' => []]);
        }
        $resources = Resources::where('player_id', $player->id)->get(['type', 'quantity']);
        return response()->json(['resources' => $resources]);
    }

    public function leaderboard()
    {
        $leaders = \App\Models\Player::with('resources')
            ->get()
            ->map(function ($player) {
                return [
                    'name' => $player->name,
                    'total' => $player->resources->sum('quantity'),
                ];
            })
            ->sortByDesc('total')
            ->take(10)
            ->values();
        return response()->json(['leaders' => $leaders]);
    }
}