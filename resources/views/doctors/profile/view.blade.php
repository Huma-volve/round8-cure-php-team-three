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

                    @if(!empty($doctor->availability_slots))
                        <select class="form-control" type='none'>
                            <option value=""><strong>Availability Slots:</strong></option>
                            @foreach($doctor->availability_slots as $slot)
                                    <option>
                                     <strong>Day:</strong> {{ $slot['day'] ?? '' }} |
                                    <strong>From:</strong> {{ $slot['from'] ?? '' }} |
                                    <strong>To:</strong> {{ $slot['to'] ?? '' }}
                                    </option>
                            @endforeach
                        </select>
                        <a href={{ route('edit.slots') }} class="btn btn-primary mt-3">Edit Slots</a>
                    @else
                    <h5 class="alert alert-warning ">No slots available</h5>
                 
                    @endif
                    </p>
                </div>
                    
                    </div>
                    </div>
        </div>
  @endsection 
