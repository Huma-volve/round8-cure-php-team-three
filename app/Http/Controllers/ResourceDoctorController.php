<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorCreateRequest;
use App\Http\Requests\DoctorUpdateRequest;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
class ResourceDoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('user')->get();
       
        return view ('doctors.index',['doctors' => $doctors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::doesntHave('doctor')->get();

        $specializations = Specialization::all();
        
        return view ('doctors.create',['users' => $users ,'specializations' => $specializations]);
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorCreateRequest $request)
    {
        $validation = $request->validated();

        Doctor::create($validation);

        User::find($request->user_id)->assignRole('doctor');

        return redirect()->route('doctors.index')->with('doctor_message','Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::findOrFail($id);

        $user = User::select('name','email','mobile_number')->where('id',$doctor->user_id)->first();
        
        $specializations = Specialization::select('id','name')->where('id',$doctor->specializations_id)->first();
        
        return view ('doctors.show',['doctor' => $doctor ,'user' => $user, 'specializations' => $specializations]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::findOrFail($id);

        $users = User::doesntHave('doctor')->orWhere('id', $doctor->user_id)->get();

        $specializations = Specialization::all();
        
        return view ('doctors.edit',['doctor' => $doctor,'users' => $users ,'specializations' => $specializations]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorUpdateRequest $request, string $id)
    {
         $doctor = Doctor::findOrFail($id);

        $validation = $request->validated();
        
        $doctor->update($validation);

        return redirect()->route('doctors.index')->with('doctor_message','Updated Successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->delete();
        
        return redirect()->route('doctors.index')->with('doctor_message','Deleted Successfully');
    }
}
