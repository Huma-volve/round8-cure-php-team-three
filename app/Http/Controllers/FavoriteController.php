<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Favorite;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Show favorites
    public function index()
    {
        $user = Auth::user();

        // Fetch favorite with its type
        $favorites = Favorite::with(['favorable'])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->groupBy('type');

         return response()->json(['success' => true, 'favorites' => $favorites]);
    }


  // Add to favorites
    public function store(Request $request)
    {
        $user = User::first();
        $token = $user->createToken('test-token')->plainTextToken;
        $request->validate([
            'favorable_id' => 'required|integer',
            'favorable_type' => 'required|in:doctor,hospital',
        ]);

        // $user = Auth::user();

        $modelClass = $request->favorable_type === 'doctor' ? Doctor::class : Hospital::class;

        // Check if it exists in favorites before
        $exists = Favorite::where([
            'user_id' => $user->id,
            'favorable_id' => $request->favorable_id,
            'favorable_type' => $modelClass,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Item already in favorites'
            ]);
        }

        $favorite = Favorite::create([
            'user_id' => $user->id,
            'favorable_id' => $request->favorable_id,
            'favorable_type' => $modelClass,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to favorites',
            'favorite' => $favorite
        ]);
    }

    // Delete favorite
    public function destroy($id)
    {
        $favorite = Favorite::where('user_id', Auth::id())->findOrFail($id);
        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from favorites'
        ]);
    }

    // Clear all favorites
   public function clearAll()
{
    $user = Auth::user();
    $deleted = Favorite::where('user_id', $user->id)->delete();

    return response()->json([
        'success' => true,
        'message' => "$deleted favorites cleared"
    ]);
}


    // Check if item is favorited
    public function checkFavorite($type, $id)
    {
        $user = Auth::user();

        $modelClass = $type === 'doctor' ? Doctor::class : Hospital::class;

        $isFavorited = Favorite::where([
            'user_id' => $user->id,
            'favorable_id' => $id,
            'favorable_type' => $modelClass,
        ])->exists();

        return response()->json(['is_favorited' => $isFavorited]);
    }
}


