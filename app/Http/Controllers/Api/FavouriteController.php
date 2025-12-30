<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavouriteResource;
use Illuminate\Http\Request;
use App\Models\Doctor;

class FavouriteController extends Controller
{
    public function addOrRemoveFavourite($doctorId)
    {
        $user = auth()->user();

        $doctor = Doctor::findOrFail($doctorId);

        if ($user->is_exists($doctorId)) {
            $user->favourites()->where('doctor_id', $doctorId)->delete();
            return response()->json(['message' => 'Doctor removed from favourites.'], 200);
        } else {
            $user->favourites()->create(['doctor_id' => $doctorId]);
            return response()->json(['message' => 'Doctor added to favourites.'], 201);
        }
    }

    public function listFavourites()
    {
        $user = auth()->user();

        $favouriteDoctors = $user->favourites()
            ->with('doctor.specialization', 'doctor.user')
            ->get();

        return response()->json([
            'favourites' => FavouriteResource::collection($favouriteDoctors)
        ], 200);
    }

    public function showFavourite($id)
    {
        $user = auth()->user();

        $favouriteDoctor = $user->favourites()
            ->where('id', $id)
            ->with('user', 'doctor.specialization')
            ->first();

        return response()->json(['favourite' => $favouriteDoctor], 200);
    }
}
