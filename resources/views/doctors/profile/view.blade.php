@extends('layouts.app')
@section('content')
    
   <div class="container">
        <div class="row">
            <div class="col-md-8 mt-5 mx-auto">
                
                @if (session('doctor_message'))
                    <h4 class="alert alert-danger text-center">{{session('doctor_message')}}</h4>
                @endif
                
                    
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Personal Information</h5>
                    <p><strong>Name:</strong> {{ $doctor->user->name }}</p>
                    <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
                    <p><strong>License Number:</strong> {{ $doctor->license_number }}</p>
                    <p><strong>Specialization:</strong> {{ $doctor->specialization->name ?? 'Not Set' }}</p>
                    <p><strong>Clinic Location:</strong> {{ implode(', ', $doctor->clinic_location ?? []) }}</p>
                    <p><strong>Session Price:</strong> ${{ number_format($doctor->session_price, 2) }}</p>
                    <p><strong>Availability Slots:</strong>
                        @if(!empty($doctor->availability_slots))
                            <ul>
                                @foreach($doctor->availability_slots as $slot)
                                    <li>{{ $slot }}</li>
                                @endforeach
                            </ul>
                        @else
                            No slots available
                        @endif
                    </p>

                    <a href={{ route('edit.slots') }} class="btn btn-primary mt-3">Edit Profile</a>
                </div>
                    
                    </div>
                    </div>
        </div>
  @endsection 
