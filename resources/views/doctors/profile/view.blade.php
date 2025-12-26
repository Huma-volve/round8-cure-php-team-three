@extends('layouts.app')
@section('content')
    
   <div class="container">
        <div class="row">
            <div class="col-md-8 mt-5 mx-auto">
                
                @if (session('doctor_message'))
                    <h4 class="alert alert-danger text-center">{{session('doctor_message')}}</h4>
                @endif
                 
            <div class="card">
                <div class="card-header">
            <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <p class="form-control"><strong>Name:</strong> {{ $doctor->user->name }}</p>
                    <p class="form-control"><strong>Email:</strong> {{ $doctor->user->email }}</p>
                    <p class="form-control"><strong>License Number:</strong> {{ $doctor->license_number }}</p>
                    <p class="form-control"><strong>Specialization:</strong> {{ $doctor->specialization->name ?? 'Not Set' }}</p>
                    <p class="form-control"><strong>Clinic Location:</strong> {{ implode(', ', $doctor->clinic_location ?? []) }}</p>
                    <p class="form-control"><strong>Session Price:</strong> ${{ number_format($doctor->session_price, 2) }}</p>
                    <p class="form-control"><strong>Availability Slots:</strong></p>

                    @if(!empty($doctor->availability_slots))
                        <ul class="form-control" type='none'>
                            @foreach($doctor->availability_slots as $slot)
                                <li>
                                    <strong>Day:</strong> {{ $slot['day'] ?? '' }} |
                                    <strong>From:</strong> {{ $slot['from'] ?? '' }} |
                                    <strong>To:</strong> {{ $slot['to'] ?? '' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No slots available</p>
                    @endif
                    </p>
                    <a href={{ route('edit.slots') }} class="btn btn-primary mt-3">Edit Profile</a>
                </div>
                    
                    </div>
                    </div>
        </div>
  @endsection 
