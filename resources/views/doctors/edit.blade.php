@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-auto">  
              <h4 class="text-center">Edit The Doctor <span class="btn btn-outline-primary"><a href={{route('doctors.index')}}>Back</a></span></h4>
              <form action={{route('doctors.update',$doctor->id)}} method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                @method('put')
                
                 <select name="user_id" id="user_id" class="form-control mt-3" value={{$doctor->user_id}}>
                    <option value="">Choose User</option>
               
                    @foreach ($users as $item)
                    <option value={{$item->id}} selected>{{$item->id}}</option>
                    @endforeach
              
                </select>
                @error('user_id')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror    
                 <select name="specializations_id" id="specializations_id" class="form-control mt-3" value={{$doctor->specialization_id}}>
                    <option value="">Choose Specialization</option>
               
                    @foreach ($specializations as $item)
                    <option value={{$item->id}} selected>{{$item->name}}</option>
                    @endforeach
              
                </select>
                @error('specializations_id')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror
                
                <input type="text" placeholder="License Number" class="form-control mt-3" name='license_number' value={{$doctor->license_number}}>
                @error('license_number')
                  <h4 class="alert alert-danger text-center">{{$message}}</h4>
              @enderror
              <input type="number" placeholder="Session Price" class="form-control mt-3" name='session_price' value={{$doctor->session_price}}>
              @error('session_price')
                  <h4 class="alert alert-danger text-center">{{$message}}</h4>
              @enderror
              
                <input type="submit" class="btn btn-success btn-block mt-3 mb-3" value="Edit">
            </form>
            </div>
        </div>
    </div>
@endsection