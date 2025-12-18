<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{    
    // هتجيب الدكاترة الاقرب لموقع معين
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $doctors = Doctor::selectRaw("
        doctors.*,
        (
            6371 * acos(
                cos(radians(?)) 
                * cos(radians(JSON_EXTRACT(clinic_location, '$.lat')))
                * cos(
                    radians(JSON_EXTRACT(clinic_location, '$.lng')) 
                    - radians(?)
                )
                + sin(radians(?)) 
                * sin(radians(JSON_EXTRACT(clinic_location, '$.lat')))
            )
        ) AS distance
    ", [$lat, $lng, $lat])
            ->orderBy('distance', 'asc')
            ->get();

        return response()->json($doctors);
    }
}
