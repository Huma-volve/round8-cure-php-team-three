<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function show($id)
    {
        $doctor = Doctor::with('specialization')->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        $data = [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'email' => $doctor->email,
            'mobile_number' => $doctor->mobile_number,
            'license_number' => $doctor->license_number,
            'session_price' => $doctor->session_price,
            'specialization' => $doctor->specialization ? $doctor->specialization->name : null,
            'availability_slots' => $doctor->availability_slots,
            'clinic_location' => $doctor->clinic_location,
        ];
        return response()->json($data);
    }

    // هتجيب الدكاترة الاقرب لموقع معين
    public function nearby(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;

        $doctors = Doctor::with(['user', 'specialization'])
            ->select('*')
            ->selectRaw('
            (6371 * acos(
                cos(radians(?)) 
                * cos(radians(JSON_EXTRACT(clinic_location, "$.lat"))) 
                * cos(radians(JSON_EXTRACT(clinic_location, "$.lng")) - radians(?)) 
                + sin(radians(?)) 
                * sin(radians(JSON_EXTRACT(clinic_location, "$.lat")))
            )) AS distance
        ', [$lat, $lng, $lat])
            ->orderBy('distance', 'asc')
            ->get();


        // Format response
        $data = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'user' => [
                    'name' => $doctor->user->name,
                    'email' => $doctor->user->email,
                ],
                'specialization' => $doctor->specialization ? $doctor->specialization->name : null,
                'session_price' => $doctor->session_price,
                'availability_slots' => $doctor->availability_slots,
                'clinic_location' => $doctor->clinic_location,
            ];
        });

        return response()->json($data);
    }


    // Show doctor by ID with user and specialization details
    public function showById($id)
{
    $doctor = Doctor::with(['user', 'specialization'])->find($id);

    if (!$doctor) {
        return response()->json(['message' => 'Doctor not found'], 404);
    }

    $data = [
        'id' => $doctor->id,
        'user' => [
            'name' => $doctor->user->name,
            'email' => $doctor->user->email,
        ],
        'specialization' => $doctor->specialization ? $doctor->specialization->name : null,
        'session_price' => $doctor->session_price,
        'availability_slots' => $doctor->availability_slots,
        'clinic_location' => $doctor->clinic_location,
    ];

    return response()->json($data);
}

    // List all doctors with user and specialization details
public function allDoctors()
{
    $doctors = Doctor::with(['user', 'specialization'])->get();

    $data = $doctors->map(function($doctor) {
        return [
            'id' => $doctor->id,
            'user' => [
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
            ],
            'specialization' => $doctor->specialization ? $doctor->specialization->name : null,
            'session_price' => $doctor->session_price,
            'availability_slots' => $doctor->availability_slots,
            'clinic_location' => $doctor->clinic_location,
        ];
    });

    return response()->json($data);
}


}
