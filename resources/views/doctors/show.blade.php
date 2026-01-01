@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="float-left">
                            Doctor
                            <span class="badge badge-info">1</span>
                        </div>
                        <div class="float-right">
                            <a href={{route('doctors.index')}} class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark text-center">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Specializations Name</th>
                                <th>Mobile Number</th>
                                <th>License Number</th>
                                <th>Session Price</th>
                                <th>Availability Slots</th>
                                <th>Clinic Location</th>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td>{{$doctor->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$specializations->name}}</td>
                                    <td>{{$user->mobile_number}}</td>
                                    <td>{{$doctor->license_number}}</td>
                                    <td>{{$doctor->session_price}}</td>
                                    <td>
                                    <select name="availability_slots" id="availability_slots">
                                        @foreach ($doctor->availability_slots as $slot)
                                        <option value={{ $slot['from'] ?? '-' }} - {{ $slot['to'] ?? '-' }}>{{ $slot['from'] ?? '-' }} - {{ $slot['to'] ?? '-' }}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                    <td>
                                        {{ $doctor->clinic_location['city'] ?? '' }} 
                                        {{ $doctor->clinic_location['area'] ?? '' }} 
                                        {{ $doctor->clinic_location['address'] ?? '' }}
                                    </td>
                                   
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection