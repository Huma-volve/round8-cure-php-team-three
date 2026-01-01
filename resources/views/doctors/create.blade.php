@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-auto">  
              <h4 class="text-center">Create New Doctor <span class="btn btn-outline-primary"><a href={{route('doctors.index')}}>Back</a></span></h4>
              <form action={{route('doctors.store')}} method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                
                <input type="text" name="name" placeholder="Enter A Name" class="form-control mt-3">
                @error('name')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror

                <input type="email" name="email" placeholder="Enter An Email" class="form-control mt-3">
                @error('email')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror

                <input type="password" name="password" placeholder="Enter An Password" class="form-control mt-3">
                @error('password')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror 
                {{-- <input type="password" name="confirmation_password" placeholder="Confirm Password" class="form-control mt-3">
                @error('confirmation_password')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror  --}}

                <input type="number" name="mobile_number" placeholder="Enter A Mobile Phone" class="form-control mt-3">
                @error('mobile_number')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror 
    
                 <select name="specializations_id" id="specializations_id" class="form-control mt-3">
                    <option value="">Choose Specialization</option>
               
                    @foreach ($specializations as $item)
                    <option value={{$item->id}}>{{$item->name}}</option>
                    @endforeach
              
                </select>
                @error('specializations_id')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror
                
                <input type="text" placeholder="License Number" class="form-control mt-3" name='license_number'>
                @error('license_number')
                  <h4 class="alert alert-danger text-center">{{$message}}</h4>
              @enderror
              <input type="number" placeholder="Session Price" class="form-control mt-3" name='session_price'>
              @error('session_price')
                  <h4 class="alert alert-danger text-center">{{$message}}</h4>
              @enderror

              <div>
                  <input type="text" name="availability_slots[0][day]" placeholder="Day" class="mt-3">
                  <input type="time" name="availability_slots[0][from]">
                  <input type="time" name="availability_slots[0][to]" class="mb-3">        
                  @error('availability_slots')
                  <h4 class="alert alert-danger text-center">{{$message}}</h4>
                  @enderror
                </div>
                
                  <div>
                      <input type="text" name="clinic_location[city]" placeholder="City">
                      <input type="text" name="clinic_location[area]" placeholder="Area">
                      <input type="text" name="clinic_location[address]" placeholder="Address">     
                      @error('clinic_location')
                      <h4 class="alert alert-danger text-center">{{$message}}</h4>
                      @enderror
                    </div>
                
                <input type="submit" class="btn btn-success btn-block mt-3 mb-3" value="Create">
              </form>
            </div>
        </div>
    </div>
@endsection