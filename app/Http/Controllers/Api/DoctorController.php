<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        $doctors = Doctor::with(['user', 'specialization'])
            ->select('*')
            ->selectRaw(
                '(6371 * acos(
                cos(radians(?))
                * cos(radians(JSON_EXTRACT(clinic_location, "$.lat")))
                * cos(radians(JSON_EXTRACT(clinic_location, "$.lng")) - radians(?))
                + sin(radians(?))
                * sin(radians(JSON_EXTRACT(clinic_location, "$.lat")))
            )) AS distance',
                [$lat, $lng, $lat]
            )
            ->orderBy('distance', 'asc')
            ->paginate(9); // pagination

        $data = $doctors->getCollection()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'user' => [
                    'name' => $doctor->user->name,
                    'email' => $doctor->user->email,
                    'profile_photo' => $doctor->user->profile_photo,
                ],
                 'specialization' => $doctor->specialization ? [
                    'id'    => $doctor->specialization->id,
                    'name'  => $doctor->specialization->name,
                    'image' => $doctor->specialization->image, // nullable
                ] : null,
                'session_price' => $doctor->session_price,
                'availability_slots' => $doctor->availability_slots,
                'clinic_location' => $doctor->clinic_location,
                'about_me' => $doctor->about_me,
                'distance_km' => round($doctor->distance, 2),
                'reviews_count' => $doctor->reviews()->count(),
                'average_rating' => $doctor->averageRating(),
                'patients_count' => $doctor->bookings()->distinct('user_id')->count(),
            ];
        });

        return response()->json([
            'current_page' => $doctors->currentPage(),
            'total_pages' => $doctors->lastPage(),
            'total_items' => $doctors->total(),
            'data' => $data,
        ]);
    }


    // Show doctor by ID with user and specialization details
    public function showById($id)
    {
        $doctor = Doctor::with(['user', 'specialization', 'reviews'])->find($id);

        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor not found'
            ], 404);
        }

        $data = [
            'id' => $doctor->id,
            'user' => [
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
                'profile_photo' => $doctor->user->profile_photo,
            ],
             'specialization' => $doctor->specialization ? [
                    'id'    => $doctor->specialization->id,
                    'name'  => $doctor->specialization->name,
                    'image' => $doctor->specialization->image, // nullable
                ] : null,
            'session_price' => $doctor->session_price,
            'availability_slots' => $doctor->availability_slots,
            'clinic_location' => $doctor->clinic_location,
            'about_me' => $doctor->about_me,
            'reviews_count' => $doctor->reviews()->count(),
            'average_rating' => $doctor->averageRating(),
            'patients_count' => $doctor->bookings()->distinct('user_id')->count(),
            'experience_years' => $doctor->experience_years,
        ];

        return response()->json($data);
    }


    // List all doctors with user and specialization details
    public function allDoctors()
    {
        $doctors = Doctor::with(['user', 'specialization', 'reviews'])->paginate(9);

        $doctors->getCollection()->transform(function ($doctor) {
            return [
                'id' => $doctor->id,
                'user' => [
                    'name' => $doctor->user->name,
                    'email' => $doctor->user->email,
                    'profile_photo' => $doctor->user->profile_photo,
                ],
                'specialization' => $doctor->specialization ? [
                    'id'    => $doctor->specialization->id,
                    'name'  => $doctor->specialization->name,
                    'image' => $doctor->specialization->image, // nullable
                ] : null,
                'session_price' => $doctor->session_price,
                'availability_slots' => $doctor->availability_slots,
                'clinic_location' => $doctor->clinic_location,
                'about_me' => $doctor->about_me,
                'reviews_count' => $doctor->reviews()->count(),
                'average_rating' => $doctor->averageRating(),
                'patients_count' => $doctor->bookings()->distinct('user_id')->count(),
            ];
        });

        return response()->json($doctors);
    }
}
