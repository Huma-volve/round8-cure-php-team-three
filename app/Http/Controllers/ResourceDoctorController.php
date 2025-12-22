<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Requests\DoctorRequest;
use App\Models\Doctor;
class ResourceDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return view ('doctors.index',['doctors' => $doctors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorRequest $request)
    {
        $validation = $request->validated();

        $doctor = $request::create([
            'id' => $request->id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'specialization_id' => $request->user_id,
            'mobile_number' => $request->mobile_number,
            'license_number' => $request->license_number,
            'session_price' => $request->session_price,
            'availability_slots' => $request->availability_slots,
            'clinic_location' => $request->clinic_location,
        ]);
        return redirect()->route('doctors.index')->with('message','Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        return view ('doctors.show',['doctor' => $doctor]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return redirect()->route('doctors.index')->with('message','Deleted Successfully');
    }
}
