<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Player;


class BuildingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'building_name' => 'required|string|max:255',
            'position' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $player = $user->player;
        if (!$player) {
            return response()->json(['error' => 'Player not found'], 404);
        }

        $building = Building::create(
            [
                'player_id' => $player->id,
                'building_name' => $request->input('building_name'),
                'position' => $request->input('position'),
            ],
        );
        return response()->json($building, 201);
    }

    // Display a listing of the player\'s buildings
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $player = $user->player;
        if (!$player) {
            return response()->json(['buildings' => []]);
        }
        
        $buildings = Building::where('player_id', $player->id)->get();
        return response()->json(['buildings' => $buildings]);
    }
}
    