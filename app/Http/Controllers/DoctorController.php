<?php

namespace App\Http\Controllers;


use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function store(DoctorRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $doctor = Doctor::create($data);

        return response()->json([
            'message' => 'Doctor created successfully',
            'doctor' => $doctor
        ], 201);
    }
}
